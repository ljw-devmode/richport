<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\RipsSales;

/**
 * RIPS 상품 재고 예측 (임시 단순 계산, AI 기능 오픈 전까지 사용)
 *
 * 1) 일평균 판매량  = 최근 판매 데이터 기준 최대 28일간 판매수량 합계 ÷ 기간 일수
 *                    (기간 = 마지막 판매일 포함 이전 28일, 판매 데이터가 그보다 짧으면 첫 판매일부터)
 * 2) 잔여소진 예상일 = 잔여수량 기준일 + 내림(잔여수량 ÷ 일평균 판매량)
 *                    (기준일 = 잔여수량이 입력된 마지막 판매일자, 없으면 오늘)
 * 3) 추천 발주일    = 기준일 + 내림((잔여수량 + 입고 대기수량) ÷ 일평균) − 입고 소요기간 − 안전재고일수
 *                    (오늘보다 이전이면 오늘 = 즉시 발주)
 * 4) 추천수량      = 올림(일평균 × (입고 소요기간 + 안전재고일수 + 발주 주기)) − 잔여수량 − 입고 대기수량
 *                    (0 이하이면 0 = 발주 불필요)
 *
 * - 입고 대기수량 : 발주접수~통관진행중(입고완료/취소 제외) 발주의 수량 합계
 * - 입고 소요기간 : 상품의 '예약 입고 소요기간', 미입력 시 DEFAULT_LEAD_DAYS
 * - 판매 데이터가 없거나 최근 RECENT_DAYS 일 내 판매가 없으면 계산하지 않음 ('-' 표시)
 * - AI 예측값 : AI 서버가 POST /ai-api/v1/forecasts 로 보낸 값(rips_forecast)을 관리자 > AI 연동 의 사용 방식에 따라 사용
 *     auto : 유효기간(valid_days) 안의 가장 최근 기준일 AI 값, 없으면 위 계산식 / ai : AI 값만 / calc : 계산식만
 */
class RipsForecast
{
	const WINDOW_DAYS       = 28;	// 일평균 산출 기간 (4주)
	const SAFETY_DAYS       = 3;	// 안전재고일수
	const CYCLE_DAYS        = 14;	// 발주 주기 (1회 발주로 확보할 판매일수)
	const DEFAULT_LEAD_DAYS = 7;	// 입고 소요기간 미입력 시 기본값
	const RECENT_DAYS       = 90;	// 이 기간 안의 판매 데이터만 사용

	/**
	 * 상품 목록에 예측값을 채움 ($excludeOrderIdx : 입고 대기수량에서 뺄 발주, 발주 수정 화면용)
	 * 각 상품에 expect_out_date, recommend_order_date, recommend_qty, daily_avg, pending_qty, forecast_type(ai|calc|null) 설정
	 * AI 값이면 forecast_base_date(기준일), forecast_model(모델 버전) 도 설정
	 */
	public static function apply(iterable $products, ?int $excludeOrderIdx = null): void
	{
		$products = collect($products instanceof \Illuminate\Pagination\AbstractPaginator ? $products->items() : $products);
		if ($products->isEmpty()) return;

		$ids   = $products->pluck('idx')->all();
		$today = Carbon::today();
		$from  = $today->copy()->subDays(self::RECENT_DAYS)->toDateString();

		// 상품별 일자별 판매수량 (최근 RECENT_DAYS 일)
		$daily = RipsSales::whereIn('product_idx', $ids)->where('sales_date', '>=', $from)
		->where('input_type', '!=', 'stock')	// 재고등록(판매 없음)은 판매량 계산에서 제외
		->groupBy('product_idx', 'sales_date')
		->selectRaw('product_idx, sales_date, SUM(qty) AS qty')
		->get()->groupBy('product_idx');

		// 상품별 잔여수량이 입력된 마지막 판매일자 (잔여수량 기준일, 재고등록 포함)
		$stockDates = RipsSales::whereIn('product_idx', $ids)->whereNotNull('stock_qty')
		->groupBy('product_idx')->selectRaw('product_idx, MAX(sales_date) AS last_date')
		->pluck('last_date', 'product_idx');

		// 상품별 입고 대기수량 (입고완료/취소 제외 발주)
		$pending = DB::table('rips_order_item AS i')
		->join('rips_order AS o', 'o.idx', '=', 'i.order_idx')
		->whereIn('i.product_idx', $ids)->whereNotIn('o.status', ['received', 'canceled'])
		->when($excludeOrderIdx, fn ($q) => $q->where('o.idx', '!=', $excludeOrderIdx))	// 수정 중인 발주는 제외
		->groupBy('i.product_idx')->selectRaw('i.product_idx, SUM(i.qty) AS qty')
		->pluck('qty', 'product_idx');

		// AI 예측값 (상품별 유효기간 안의 가장 최근 기준일)
		$setting = \App\Models\AiApiSetting::current();
		$mode    = array_key_exists($setting->forecast_mode, \App\Models\AiApiSetting::MODES) ? $setting->forecast_mode : 'auto';
		$ai      = collect();
		if ($mode !== 'calc') {
			try {
				$ai = \App\Models\RipsForecastResult::whereIn('product_idx', $ids)
				->whereBetween('base_date', [$today->copy()->subDays(max(0, (int) $setting->valid_days))->toDateString(), $today->toDateString()])
				->orderBy('base_date')->get()->keyBy('product_idx');	// 같은 상품은 마지막(가장 최근 기준일) 값
			} catch (\Throwable $e) {
				$ai = collect();	// 예측 테이블이 없는 환경 : 계산식 사용
			}
		}

		foreach ($products as $product) {
			$pendingQty = (int) ($pending[$product->idx] ?? 0);
			$product->setAttribute('pending_qty', $pendingQty);

			if ($row = $ai->get($product->idx)) {
				$product->setAttribute('forecast_type', 'ai');
				$product->setAttribute('forecast_base_date', $row->base_date);
				$product->setAttribute('forecast_model', $row->model_version);
				$product->setAttribute('daily_avg', $row->daily_avg === null ? null : (float) $row->daily_avg);
				$product->setAttribute('expect_out_date', $row->expect_out_date);
				$product->setAttribute('recommend_order_date', $row->recommend_order_date);
				$product->setAttribute('recommend_qty', $row->recommend_qty);
				continue;
			}
			if ($mode === 'ai') {	// AI 값만 사용 : 없으면 표시하지 않음
				$product->setAttribute('forecast_type', null);
				foreach (['daily_avg', 'expect_out_date', 'recommend_order_date', 'recommend_qty'] as $key) $product->setAttribute($key, null);
				continue;
			}

			$result = self::calc(
				$daily->get($product->idx, collect()),
				(int) $product->stock_qty,
				$pendingQty,
				$product->lead_days !== null ? (int) $product->lead_days : self::DEFAULT_LEAD_DAYS,
				isset($stockDates[$product->idx]) ? Carbon::parse($stockDates[$product->idx]) : $today,
				$today
			);

			$product->setAttribute('daily_avg', $result['daily_avg']);
			$product->setAttribute('forecast_type', $result['daily_avg'] !== null ? 'calc' : null);
			$product->setAttribute('expect_out_date', $result['expect_out_date']);
			$product->setAttribute('recommend_order_date', $result['recommend_order_date']);
			$product->setAttribute('recommend_qty', $result['recommend_qty']);
		}
	}

	const ALERT_DAYS = 15;	// 잔여소진 예상일이 이 기간 이내면 발주 필요/추천

	// 발주 상태 구분 (화면 탭 / 건수 표시용)
	const ALERT_TYPES = [
		'soldout' => '재고소진',
		'urgent'  => '즉시발주',
		'soon'    => '발주필요',
	];

	/**
	 * 발주 상태 (apply() 후 사용), 하나의 상품은 한 가지 상태만 (재고소진 → 즉시발주 → 발주필요 순)
	 * @return array|null ['type' => soldout|urgent|soon, 'label' => 표시문구, 'days' => 소진까지 남은 일수]
	 *  - soldout : 잔여수량 0개 이하
	 *  - urgent  : 추천 발주일이 오늘 이전 (즉시 발주, 추천수량 있음)
	 *  - soon    : 소진 예상일이 오늘부터 ALERT_DAYS 일 이내 (추천수량 있음, 진행중 발주로 충분하면 제외)
	 */
	public static function alert($product): ?array
	{
		$today = Carbon::today();
		$out   = $product->expect_out_date;
		$days  = $out ? max(0, (int) $today->diffInDays($out, false)) : null;

		if ((int) $product->stock_qty <= 0) {
			return ['type' => 'soldout', 'label' => '재고소진', 'days' => 0];
		}
		if ($product->recommend_qty > 0 && $product->recommend_order_date && $product->recommend_order_date->lte($today)) {
			return ['type' => 'urgent', 'label' => '즉시 발주', 'days' => $days];
		}
		if ($product->recommend_qty > 0 && $out && $days <= self::ALERT_DAYS) {
			return ['type' => 'soon', 'label' => '발주필요 D-'.$days, 'days' => $days];
		}
		return null;
	}

	/**
	 * 업체 상품 목록 + 발주 상태 탭 (RIPS > 상품, 관리자 > RIPS 업체 상세 > 상품 현황 공통)
	 * 발주 상태는 계산값이라 전체 상품을 계산한 뒤 걸러서 페이지를 나눔
	 * @param array $filters category_idx, keyword, status(soldout|urgent|soon)
	 * @return array [paginator, 상태별 건수(all 포함), 현재 탭의 전체 상품]
	 */
	public static function productList(int $partnerIdx, array $filters, int $perPage = 20): array
	{
		$products = \App\Models\RipsProduct::where('partner_idx', $partnerIdx)
		->when(!empty($filters['category_idx']), fn ($q) => $q->whereIn('category_idx', \App\Models\RipsCategory::descendantIds($partnerIdx, $filters['category_idx'])))
		->when(!empty($filters['keyword']), function ($q) use ($filters) {
			$q->where(fn ($w) => $w->where('product_name', 'like', '%'.$filters['keyword'].'%')->orWhere('company_name', 'like', '%'.$filters['keyword'].'%')->orWhere('barcode', 'like', '%'.$filters['keyword'].'%'));
		})->orderByDesc('idx')->get();

		self::apply($products);
		$products->each(fn ($p) => $p->setAttribute('alert', self::alert($p)));

		$counts = ['all' => $products->count()];
		foreach (array_keys(self::ALERT_TYPES) as $type) {
			$counts[$type] = $products->filter(fn ($p) => ($p->alert['type'] ?? null) === $type)->count();
		}

		$status   = array_key_exists($filters['status'] ?? '', self::ALERT_TYPES) ? $filters['status'] : null;
		$filtered = $status ? $products->filter(fn ($p) => ($p->alert['type'] ?? null) === $status)->values() : $products;

		$page      = \Illuminate\Pagination\Paginator::resolveCurrentPage();
		$paginator = new \Illuminate\Pagination\LengthAwarePaginator(
			$filtered->forPage($page, $perPage)->values(), $filtered->count(), $perPage, $page,
			['path' => request()->url(), 'query' => request()->query()]
		);

		return [$paginator, $counts, $filtered];
	}

	/**
	 * @param Collection $daily  일자별 판매수량 (sales_date, qty)
	 */
	public static function calc(Collection $daily, int $stock, int $pendingQty, int $leadDays, Carbon $baseDate, Carbon $today): array
	{
		$empty = ['daily_avg' => null, 'expect_out_date' => null, 'recommend_order_date' => null, 'recommend_qty' => null];
		if ($daily->isEmpty()) return $empty;

		$dates     = $daily->map(fn ($row) => Carbon::parse($row->sales_date)->startOfDay());
		$lastDate  = $dates->max();
		$firstDate = $dates->min();

		$windowStart = $lastDate->copy()->subDays(self::WINDOW_DAYS - 1);
		if ($firstDate->gt($windowStart)) $windowStart = $firstDate->copy();

		$days = $windowStart->diffInDays($lastDate) + 1;
		$qty  = $daily->filter(fn ($row) => Carbon::parse($row->sales_date)->gte($windowStart))->sum('qty');

		$avg = $days > 0 ? $qty / $days : 0;
		if ($avg <= 0) return $empty;

		$stock     = max(0, $stock);
		$baseDate  = $baseDate->copy()->startOfDay();

		// 잔여소진 예상일
		$expectOut = $baseDate->copy()->addDays((int) floor($stock / $avg));

		// 추천 발주일 (입고 대기수량까지 소진되는 날 − 입고 소요기간 − 안전재고일수)
		$coverEnd   = $baseDate->copy()->addDays((int) floor(($stock + $pendingQty) / $avg));
		$orderDate  = $coverEnd->copy()->subDays($leadDays + self::SAFETY_DAYS);
		if ($orderDate->lt($today)) $orderDate = $today->copy();

		// 추천수량
		$needQty = (int) ceil($avg * ($leadDays + self::SAFETY_DAYS + self::CYCLE_DAYS)) - $stock - $pendingQty;

		return [
			'daily_avg'            => round($avg, 1),
			'expect_out_date'      => $expectOut,
			'recommend_order_date' => $orderDate,
			'recommend_qty'        => max(0, $needQty),
		];
	}
}
