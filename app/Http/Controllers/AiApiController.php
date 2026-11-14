<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AiApiSetting;
use App\Models\Partner;
use App\Models\RipsCategory;
use App\Models\RipsForecastResult;
use App\Models\RipsProduct;

/**
 * AI 재고 예측 연동 API (v1) - 인증 : App\Http\Middleware\AiApiAuth
 *
 *  GET  /ai-api/v1/ping              연결 · 인증 확인
 *  GET  /ai-api/v1/partners          RIPS 업체 목록
 *  GET  /ai-api/v1/products          업체 상품 (잔여수량, 입고 소요기간 등)
 *  GET  /ai-api/v1/sales             일별 판매 (상품 · 일자별 합계)
 *  GET  /ai-api/v1/pending-orders    입고 대기 발주
 *  POST /ai-api/v1/forecasts         예측 결과 저장 (여러 상품 · 여러 기준일 일괄)
 *  POST /ai-api/v1/forecasts/query   예측 결과 조회 (상품 · 기준일 목록)
 *
 * 응답 : {"status":"success","data":...,"meta":{...}} / {"status":"error","code":"...","message":"...","errors":[...]}
 */
class AiApiController extends Controller
{
	const MAX_ITEMS     = 5000;		// 예측 결과 1회 최대 건수
	const MAX_DATES     = 62;		// 조회 1회 최대 기준일 수
	const MAX_SALES_DAY = 366;		// 판매 조회 최대 기간 (일)
	const PER_PAGE      = 1000;		// 판매 조회 기본 페이지 크기 (최대 5000)

	private function ok($data, array $meta = []): JsonResponse
	{
		return response()->json(['status' => 'success', 'data' => $data, 'meta' => $meta + ['count' => is_countable($data) ? count($data) : null]]);
	}

	private function fail(int $status, string $code, string $message, array $errors = []): JsonResponse
	{
		return response()->json(array_filter(['status' => 'error', 'code' => $code, 'message' => $message, 'errors' => $errors ?: null], fn ($v) => $v !== null), $status);
	}

	// 날짜 (Y-m-d) 검사 : 올바르면 문자열, 아니면 false, 비었으면 null
	private function date($value)
	{
		if ($value === null || $value === '') return null;
		if (!is_string($value)) return false;
		$d = \DateTime::createFromFormat('!Y-m-d', $value);
		return $d && $d->format('Y-m-d') === $value ? $value : false;
	}

	private function partnerIds(Request $request): ?array
	{
		if (!$request->filled('partner_id')) return null;
		return array_values(array_filter(array_map('intval', explode(',', (string) $request->partner_id))));
	}

	// ---------------- 연결 확인 ----------------
	public function ping(): JsonResponse
	{
		$s = AiApiSetting::current();
		return $this->ok(['server_time' => now()->toIso8601String(), 'forecast_mode' => $s->forecast_mode, 'valid_days' => (int) $s->valid_days]);
	}

	// ---------------- RIPS 업체 ----------------
	public function partners(): JsonResponse
	{
		$rows = Partner::ripsAccounts()->where('rips_use', 1)->orderBy('idx')
		->get(['idx', 'rips_id', 'partner_name', 'store_name', 'country'])
		->map(fn ($p) => ['partner_id' => $p->idx, 'rips_id' => $p->rips_id, 'name' => $p->partner_name, 'store_name' => $p->store_name, 'country' => $p->country])->values();

		return $this->ok($rows);
	}

	// ---------------- 업체 상품 ----------------
	public function products(Request $request): JsonResponse
	{
		$partnerIds = $this->partnerIds($request);

		$products = RipsProduct::with('richportProduct:idx,order_price')
		->whereIn('partner_idx', Partner::ripsAccounts()->where('rips_use', 1)->select('idx'))
		->when($partnerIds, fn ($q) => $q->whereIn('partner_idx', $partnerIds))
		->orderBy('idx')->get();

		// 잔여수량 기준일 (잔여수량이 입력된 마지막 판매일자)
		$stockDates = DB::table('rips_sales')->whereIn('product_idx', $products->pluck('idx'))->whereNotNull('stock_qty')
		->groupBy('product_idx')->selectRaw('product_idx, MAX(sales_date) AS d')->pluck('d', 'product_idx');

		$paths = [];
		$rows  = $products->map(function ($p) use (&$paths, $stockDates) {
			$paths[$p->partner_idx] ??= RipsCategory::pathMap($p->partner_idx);
			return [
				'product_id'          => $p->idx,
				'partner_id'          => $p->partner_idx,
				'category_id'         => $p->category_idx,
				'category_path'       => $paths[$p->partner_idx][$p->category_idx] ?? null,
				'name'                => $p->product_name,
				'company_name'        => $p->company_name,
				'barcode'             => $p->barcode,
				'richport_product_id' => $p->product_idx,
				'stock_qty'           => (int) $p->stock_qty,
				'stock_date'          => $stockDates[$p->idx] ?? null,
				'lead_days'           => $p->lead_days !== null ? (int) $p->lead_days : null,
				'order_price'         => $p->orderPrice()[0],
			];
		})->values();

		return $this->ok($rows);
	}

	// ---------------- 일별 판매 ----------------
	public function sales(Request $request): JsonResponse
	{
		$from = $this->date($request->input('from'));
		$to   = $this->date($request->input('to'));
		if (!$from || !$to) return $this->fail(422, 'INVALID_PARAMETER', 'from, to 는 YYYY-MM-DD 형식으로 필수입니다.');
		if ($from > $to) return $this->fail(422, 'INVALID_PARAMETER', 'from 은 to 보다 이전이어야 합니다.');
		if (Carbon::parse($from)->diffInDays(Carbon::parse($to)) >= self::MAX_SALES_DAY) return $this->fail(422, 'INVALID_PARAMETER', '조회 기간은 최대 '.self::MAX_SALES_DAY.'일입니다.');

		$perPage    = max(1, min(5000, (int) ($request->input('per_page') ?: self::PER_PAGE)));
		$partnerIds = $this->partnerIds($request);
		$productIds = $request->filled('product_ids') ? array_values(array_filter(array_map('intval', explode(',', (string) $request->product_ids)))) : null;

		$query = DB::table('rips_sales AS s')
		->whereIn('s.partner_idx', Partner::ripsAccounts()->where('rips_use', 1)->select('idx'))
		->whereBetween('s.sales_date', [$from, $to])
		->when($partnerIds, fn ($q) => $q->whereIn('s.partner_idx', $partnerIds))
		->when($productIds, fn ($q) => $q->whereIn('s.product_idx', $productIds))
		->groupBy('s.sales_date', 's.product_idx')
		->selectRaw("s.sales_date AS date, s.product_idx AS product_id, MAX(s.partner_idx) AS partner_id,
			SUM(CASE WHEN s.input_type <> 'stock' THEN s.qty ELSE 0 END) AS qty,
			SUM(s.total_amount) AS amount,
			SUBSTRING_INDEX(GROUP_CONCAT(s.stock_qty ORDER BY s.sales_time DESC, s.idx DESC SEPARATOR ','), ',', 1) AS stock_qty,
			MAX(s.promotion <> 'N') AS promotion,
			GROUP_CONCAT(DISTINCT NULLIF(s.promotion, 'N')) AS promotion_types,
			MAX(s.discount_rate) AS discount_rate,
			GROUP_CONCAT(DISTINCT NULLIF(s.display_place, '')) AS display_places,
			SUM(s.input_type <> 'stock') AS sales_rows")
		->orderBy('s.sales_date')->orderBy('s.product_idx');

		$page = $query->paginate($perPage);
		$rows = collect($page->items())->map(fn ($r) => [
			'date'            => $r->date,
			'partner_id'      => (int) $r->partner_id,
			'product_id'      => (int) $r->product_id,
			'qty'             => (int) $r->qty,
			'amount'          => (int) $r->amount,
			'stock_qty'       => $r->stock_qty === null ? null : (int) $r->stock_qty,
			'promotion'       => (bool) $r->promotion,
			'promotion_types' => $r->promotion_types ? explode(',', $r->promotion_types) : [],
			'discount_rate'   => $r->discount_rate === null ? null : (float) $r->discount_rate,
			'display_places'  => $r->display_places ? explode(',', $r->display_places) : [],
			'stock_only'      => (int) $r->sales_rows === 0,
		])->values();

		return $this->ok($rows, ['page' => $page->currentPage(), 'per_page' => $perPage, 'total' => $page->total(), 'last_page' => $page->lastPage()]);
	}

	// ---------------- 입고 대기 발주 ----------------
	public function pendingOrders(Request $request): JsonResponse
	{
		$partnerIds = $this->partnerIds($request);

		$rows = DB::table('rips_order_item AS i')->join('rips_order AS o', 'o.idx', '=', 'i.order_idx')
		->whereNotIn('o.status', ['received', 'canceled'])
		->when($partnerIds, fn ($q) => $q->whereIn('o.partner_idx', $partnerIds))
		->orderBy('o.order_date')->orderBy('i.idx')
		->get(['o.idx AS order_id', 'o.order_no', 'o.partner_idx AS partner_id', 'i.product_idx AS product_id', 'i.qty', 'o.status', 'o.order_date', 'o.expected_date'])
		->map(fn ($r) => [
			'order_id'      => (int) $r->order_id,
			'order_no'      => $r->order_no,
			'partner_id'    => (int) $r->partner_id,
			'product_id'    => (int) $r->product_id,
			'qty'           => (int) $r->qty,
			'status'        => $r->status,
			'order_date'    => $r->order_date ? substr($r->order_date, 0, 10) : null,
			'expected_date' => $r->expected_date ? substr($r->expected_date, 0, 10) : null,
		])->values();

		return $this->ok($rows);
	}

	// ---------------- 예측 결과 저장 ----------------
	/**
	 * {"model_version":"v1.0","items":[{"product_id":12,"base_date":"2026-11-07","expect_out_date":"2026-11-20","recommend_order_date":"2026-11-10","recommend_qty":120,"daily_avg":8.5,"confidence":0.87}, ...]}
	 * 모든 항목을 검사해 오류가 없을 때만 저장 (같은 상품 · 기준일은 덮어씀)
	 */
	public function storeForecasts(Request $request): JsonResponse
	{
		$model = trim((string) $request->input('model_version'));
		$items = $request->input('items');

		if ($model === '' || mb_strlen($model) > 50) return $this->fail(422, 'INVALID_PARAMETER', 'model_version 은 1~50자로 필수입니다.');
		if (!is_array($items) || !$items) return $this->fail(422, 'INVALID_PARAMETER', 'items 가 비어 있습니다.');
		if (count($items) > self::MAX_ITEMS) return $this->fail(422, 'TOO_MANY_ITEMS', 'items 는 한 번에 최대 '.self::MAX_ITEMS.'건입니다.');

		$productIds = array_unique(array_map(fn ($it) => is_array($it) ? (int) ($it['product_id'] ?? 0) : 0, $items));
		$partnerOf  = RipsProduct::whereIn('idx', $productIds)->pluck('partner_idx', 'idx');

		$rows = []; $errors = []; $seen = [];
		foreach (array_values($items) as $i => $it) {
			$err = function ($field, $msg) use (&$errors, $i) { $errors[] = ['index' => $i, 'field' => $field, 'message' => $msg]; };
			if (!is_array($it)) { $err(null, '객체 형식이어야 합니다.'); continue; }

			$pid  = (int) ($it['product_id'] ?? 0);
			$base = $this->date($it['base_date'] ?? null);
			$out  = $this->date($it['expect_out_date'] ?? null);
			$ord  = $this->date($it['recommend_order_date'] ?? null);
			$qty  = $it['recommend_qty'] ?? null;
			$avg  = $it['daily_avg'] ?? null;
			$conf = $it['confidence'] ?? null;
			$before = count($errors);

			if (!isset($partnerOf[$pid]))                       $err('product_id', '존재하지 않는 상품입니다.');
			if (!$base)                                         $err('base_date', 'YYYY-MM-DD 형식으로 필수입니다.');
			if ($out === false)                                 $err('expect_out_date', 'YYYY-MM-DD 형식이어야 합니다.');
			if ($ord === false)                                 $err('recommend_order_date', 'YYYY-MM-DD 형식이어야 합니다.');
			if ($qty !== null && (!is_numeric($qty) || (int) $qty != $qty || $qty < 0)) $err('recommend_qty', '0 이상의 정수여야 합니다.');
			if ($avg !== null && (!is_numeric($avg) || $avg < 0))                     $err('daily_avg', '0 이상의 숫자여야 합니다.');
			if ($conf !== null && (!is_numeric($conf) || $conf < 0 || $conf > 1))      $err('confidence', '0~1 사이 숫자여야 합니다.');
			if ($out === null && $ord === null && $qty === null) $err(null, 'expect_out_date, recommend_order_date, recommend_qty 중 하나 이상 필요합니다.');
			if ($base && isset($seen["$pid|$base"]))            $err('base_date', '같은 상품 · 기준일이 중복되었습니다. (index '.$seen["$pid|$base"].')');
			if (count($errors) > $before) continue;

			$seen["$pid|$base"] = $i;
			$rows[] = [
				'partner_idx' => $partnerOf[$pid], 'product_idx' => $pid, 'base_date' => $base,
				'expect_out_date' => $out, 'recommend_order_date' => $ord,
				'recommend_qty' => $qty === null ? null : (int) $qty,
				'daily_avg' => $avg === null ? null : round((float) $avg, 2),
				'confidence' => $conf === null ? null : round((float) $conf, 4),
				'model_version' => $model,
			];
		}

		if ($errors) return $this->fail(422, 'VALIDATION_FAILED', '오류가 있어 저장하지 않았습니다. ('.count($errors).'건)', array_slice($errors, 0, 100));

		$existing = RipsForecastResult::whereIn('product_idx', array_column($rows, 'product_idx'))
		->whereIn('base_date', array_unique(array_column($rows, 'base_date')))->get(['product_idx', 'base_date'])
		->map(fn ($r) => $r->product_idx.'|'.$r->base_date->toDateString())->flip();

		DB::transaction(function () use ($rows) {
			$now = now();
			foreach (array_chunk($rows, 500) as $chunk) {
				RipsForecastResult::upsert(
					array_map(fn ($r) => $r + ['created_at' => $now, 'updated_at' => $now], $chunk),
					['product_idx', 'base_date'],
					['partner_idx', 'expect_out_date', 'recommend_order_date', 'recommend_qty', 'daily_avg', 'confidence', 'model_version', 'updated_at']
				);
			}
		});
		AiApiSetting::current()->forceFill(['last_push_at' => now()])->saveQuietly();

		$updated = collect($rows)->filter(fn ($r) => isset($existing[$r['product_idx'].'|'.$r['base_date']]))->count();
		return $this->ok(['inserted' => count($rows) - $updated, 'updated' => $updated], ['count' => count($rows)]);
	}

	// ---------------- 예측 결과 조회 ----------------
	/**
	 * {"product_ids":[12,13], "dates":["2026-11-06","2026-11-07"]}  또는  {"partner_id":7, "latest":true}
	 * dates : 기준일 목록 (각 기준일의 저장값) / latest : 상품별 가장 최근 기준일 값
	 */
	public function queryForecasts(Request $request): JsonResponse
	{
		$productIds = $request->input('product_ids');
		$partnerId  = (int) $request->input('partner_id');
		$dates      = $request->input('dates');
		$latest     = filter_var($request->input('latest'), FILTER_VALIDATE_BOOLEAN);

		if ($productIds !== null && (!is_array($productIds) || count($productIds) > self::MAX_ITEMS)) return $this->fail(422, 'INVALID_PARAMETER', 'product_ids 는 최대 '.self::MAX_ITEMS.'개 배열입니다.');
		if (!$productIds && !$partnerId) return $this->fail(422, 'INVALID_PARAMETER', 'product_ids 또는 partner_id 가 필요합니다.');
		if (!$latest) {
			if (!is_array($dates) || !$dates || count($dates) > self::MAX_DATES) return $this->fail(422, 'INVALID_PARAMETER', 'dates 는 1~'.self::MAX_DATES.'개 배열이거나 latest:true 를 사용합니다.');
			foreach ($dates as $d) if (!$this->date($d)) return $this->fail(422, 'INVALID_PARAMETER', 'dates 는 YYYY-MM-DD 형식입니다. ('.json_encode($d).')');
		}

		$query = RipsForecastResult::query()
		->when($productIds, fn ($q) => $q->whereIn('product_idx', array_map('intval', $productIds)))
		->when($partnerId, fn ($q) => $q->where('partner_idx', $partnerId));

		if ($latest) {
			$query->whereIn('idx', RipsForecastResult::selectRaw('MAX(idx)')->whereRaw('(product_idx, base_date) IN (SELECT product_idx, MAX(base_date) FROM rips_forecast GROUP BY product_idx)')->groupBy('product_idx'));
		} else {
			$query->whereIn('base_date', $dates);
		}

		$rows = $query->orderBy('product_idx')->orderBy('base_date')->get()->map(fn ($r) => [
			'product_id'           => $r->product_idx,
			'partner_id'           => $r->partner_idx,
			'base_date'            => $r->base_date->toDateString(),
			'expect_out_date'      => $r->expect_out_date?->toDateString(),
			'recommend_order_date' => $r->recommend_order_date?->toDateString(),
			'recommend_qty'        => $r->recommend_qty,
			'daily_avg'            => $r->daily_avg === null ? null : (float) $r->daily_avg,
			'confidence'           => $r->confidence === null ? null : (float) $r->confidence,
			'model_version'        => $r->model_version,
			'received_at'          => ($r->updated_at ?? $r->created_at)?->toIso8601String(),
		])->values();

		return $this->ok($rows);
	}
}
