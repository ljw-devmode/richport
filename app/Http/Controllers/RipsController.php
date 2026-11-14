<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use App\Exports\ripsSalesTemplateExport;
use App\Models\CustomsIssue;
use App\Models\FormTypes;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductFiles;
use App\Models\RipsCategory;
use App\Models\RipsOrder;
use App\Models\RipsOrderItem;
use App\Models\RipsProduct;
use App\Models\RipsSales;
use App\Models\RipsSalesSetting;
use App\Models\Testbedinquire;
use App\Services\RipsForecast;
use App\Services\SurveyResult;
use App\Models\CheckList;

class RipsController extends Controller
{
	// 로그인한 RIPS 업체(파트너)
	private function partner()
	{
		return Auth::guard('rips')->user();
	}


	//================================== 로그인 ==================================
	// /rips 접속 시 로그인 여부에 따라 이동
	public function index()
	{
		$partner = $this->partner();

		if ($partner && (int) $partner->rips_use === 1) {
			return redirect()->route('rips.dashboard', ['rid' => $partner->rips_id]);
		}
		return redirect()->route('rips.login');
	}

	public function loginGet()
	{
		$partner = $this->partner();

		if ($partner && (int) $partner->rips_use === 1) {
			return redirect()->route('rips.dashboard', ['rid' => $partner->rips_id]);
		}
		return view('rips.login');
	}

	public function loginPost(Request $request)
	{
		$credentials = $request->validate(['id' => 'required|string', 'pw' => 'required|string']);

		if (Auth::guard('rips')->attempt(['rips_id' => $credentials['id'], 'rips_use' => 1, 'password' => $credentials['pw']])) {
			$request->session()->regenerate();

			return redirect()->route('rips.dashboard', ['rid' => $this->partner()->rips_id]);
		}

		return back()->withInput($request->only('id'))->withErrors(['msg' => '아이디 또는 비밀번호가 일치하지 않습니다.']);
	}

	public function logout(Request $request)
	{
		Auth::guard('rips')->logout();
		$request->session()->regenerateToken();

		return redirect()->route('rips.login');
	}


	//================================== 대시보드 ==================================
	public function getDashboard(Request $request, $rid)
	{
		$partner = $this->partner();
		$today   = Carbon::today();

		$productCount = RipsProduct::where('partner_idx', $partner->idx)->count();
		$soldOutCount = RipsProduct::where('partner_idx', $partner->idx)->where('stock_qty', '<=', 0)->count();

		// 최근 30일 판매
		$sales30 = RipsSales::where('partner_idx', $partner->idx)
		->whereBetween('sales_date', [$today->copy()->subDays(29)->toDateString(), $today->toDateString()])
		->selectRaw('COALESCE(SUM(qty),0) AS qty, COALESCE(SUM(total_amount),0) AS amount')->first();

		// 최근 14일 일별 판매금액
		$dailyRows = RipsSales::where('partner_idx', $partner->idx)
		->whereBetween('sales_date', [$today->copy()->subDays(13)->toDateString(), $today->toDateString()])
		->groupBy('sales_date')->selectRaw('sales_date, SUM(qty) AS qty, SUM(total_amount) AS amount')
		->get()->keyBy(fn ($row) => $row->sales_date->toDateString());

		$daily = [];
		for ($i = 13; $i >= 0; $i--) {
			$date = $today->copy()->subDays($i)->toDateString();
			$daily[] = [
				'date'   => $date,
				'qty'    => (int) ($dailyRows[$date]->qty ?? 0),
				'amount' => (int) ($dailyRows[$date]->amount ?? 0),
			];
		}

		// 최근 30일 판매 상위 상품
		$topProducts = RipsSales::with('product')->where('partner_idx', $partner->idx)
		->whereBetween('sales_date', [$today->copy()->subDays(29)->toDateString(), $today->toDateString()])
		->groupBy('product_idx')->selectRaw('product_idx, SUM(qty) AS qty, SUM(total_amount) AS amount')
		->orderByDesc('qty')->limit(5)->get();

		// 진행중 발주 / 최근 발주
		$activeOrderCount = RipsOrder::where('partner_idx', $partner->idx)->whereNotIn('status', ['received', 'canceled'])->count();
		$recentOrders     = RipsOrder::where('partner_idx', $partner->idx)->orderByDesc('order_date')->limit(5)->get();

		// 재고소진 임박 : 잔여소진 예상일이 가장 가까운 5개 (예상일 → 추천수량 많은 순)
		$products = RipsProduct::where('partner_idx', $partner->idx)->get();
		RipsForecast::apply($products);
		$urgentProducts = $products->filter(fn ($p) => $p->expect_out_date)
		->sortBy([fn ($a, $b) => $a->expect_out_date <=> $b->expect_out_date, fn ($a, $b) => (int) $b->recommend_qty <=> (int) $a->recommend_qty])
		->take(5)->values();

		// 리치포트 신상품 3건
		$newProducts = Product::with(['forms', 'partners'])->where('new', '1')->orderByDesc('created_at')->limit(3)->get();

		// 통관 이슈 3건
		$customsIssues = CustomsIssue::orderByDesc('created_at')->orderByDesc('idx')->limit(3)->get(['idx', 'title', 'ori_name', 'created_at']);

		return view('rips.dashboard', compact('partner', 'productCount', 'soldOutCount', 'sales30', 'daily', 'topProducts', 'activeOrderCount', 'recentOrders', 'urgentProducts', 'newProducts', 'customsIssues'));
	}


	//================================== 카테고리 ==================================
	public function getCategory(Request $request, $rid)
	{
		$partner = $this->partner();

		return view('rips.category', compact('partner'));
	}

	// 카테고리 전체 목록 (하위/상품 개수 포함)
	public function getCategoryList(Request $request, $rid)
	{
		$partner = $this->partner();

		$data = RipsCategory::where('partner_idx', $partner->idx)
		->withCount(['children', 'products'])
		->orderBy('depth')->orderBy('orderby')->orderBy('idx')->get();

		return response()->json(['status' => 'success', 'data' => $data]);
	}

	public function setCategoryRegist(Request $request, $rid)
	{
		$partner = $this->partner();

		$name      = trim((string) $request->input('name'));
		$parentIdx = (int) $request->input('parent_idx', 0);

		if ($name === '') return response()->json(['status' => 'error', 'message' => '카테고리명을 입력해주세요.']);

		$depth = 1;
		if ($parentIdx > 0) {
			$parent = RipsCategory::where('partner_idx', $partner->idx)->find($parentIdx);
			if (!$parent) return response()->json(['status' => 'error', 'message' => '상위 카테고리를 찾을 수 없습니다.']);

			$depth = $parent->depth + 1;
			if ($depth > RipsCategory::MAX_DEPTH) {
				return response()->json(['status' => 'error', 'message' => '카테고리는 최대 '.RipsCategory::MAX_DEPTH.'단계까지 등록할 수 있습니다.']);
			}
		}

		$orderby = RipsCategory::where('partner_idx', $partner->idx)->where('parent_idx', $parentIdx)->max('orderby');

		$category = RipsCategory::create([
			'partner_idx' => $partner->idx,
			'parent_idx'  => $parentIdx,
			'depth'       => $depth,
			'name'        => $name,
			'orderby'     => (int) $orderby + 1,
		]);

		return response()->json(['status' => 'success', 'data' => $category]);
	}

	public function setCategoryModify(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$name = trim((string) $request->input('name'));
		if ($name === '') return response()->json(['status' => 'error', 'message' => '카테고리명을 입력해주세요.']);

		$category = RipsCategory::where('partner_idx', $partner->idx)->findOrFail($idx);
		$category->update(['name' => $name]);

		return response()->json(['status' => 'success']);
	}

	// 같은 단계 안에서 위/아래로 이동
	public function setCategorySort(Request $request, $rid)
	{
		$partner = $this->partner();

		$category = RipsCategory::where('partner_idx', $partner->idx)->findOrFail($request->input('idx'));

		$siblings = RipsCategory::where('partner_idx', $partner->idx)->where('parent_idx', $category->parent_idx)
		->orderBy('orderby')->orderBy('idx')->get()->values();

		$pos    = $siblings->search(fn ($row) => $row->idx === $category->idx);
		$target = $request->input('direction') === 'up' ? $pos - 1 : $pos + 1;

		if ($target < 0 || $target >= $siblings->count()) return response()->json(['status' => 'success']);

		DB::transaction(function () use ($siblings, $pos, $target) {
			$ordered = $siblings->all();
			[$ordered[$pos], $ordered[$target]] = [$ordered[$target], $ordered[$pos]];
			foreach ($ordered as $i => $row) {
				$row->update(['orderby' => $i + 1]);
			}
		});

		return response()->json(['status' => 'success']);
	}

	public function setCategoryDelete(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$category = RipsCategory::where('partner_idx', $partner->idx)->withCount(['children', 'products'])->findOrFail($idx);

		if ($category->children_count > 0) return response()->json(['status' => 'error', 'message' => '하위 카테고리가 있는 카테고리는 삭제할 수 없습니다.']);
		if ($category->products_count > 0) return response()->json(['status' => 'error', 'message' => '등록된 상품이 있는 카테고리는 삭제할 수 없습니다.']);

		$category->delete();

		return response()->json(['status' => 'success']);
	}


	//================================== 상품 ==================================
	public function getProductList(Request $request, $rid)
	{
		$partner = $this->partner();
		$perPage = 20;

		$categoryMap = RipsCategory::pathMap($partner->idx);

		// 잔여소진 예상일 / 추천 발주일 / 추천수량 (임시 단순 계산) + 발주 상태 탭 (재고소진 / 즉시발주 / 발주필요)
		[$data, $counts, $filtered] = RipsForecast::productList($partner->idx, $request->only(['category_idx', 'keyword', 'status']), $perPage);

		// 상태 탭의 상품을 한 번에 발주 (추천수량 있는 상품만)
		$orderIdxs = $request->filled('status') ? $filtered->filter(fn ($p) => $p->recommend_qty > 0 || $p->stock_qty <= 0)->pluck('idx')->values()->all() : [];

		// 등록/수정 모달의 1~4차 단계별 선택용
		$categories = RipsCategory::where('partner_idx', $partner->idx)->orderBy('orderby')->orderBy('idx')->get(['idx', 'parent_idx', 'depth', 'name']);

		return view('rips.product', compact('partner', 'data', 'categoryMap', 'categories', 'counts', 'orderIdxs'));
	}

	// $productIdx : 수정할 상품 (null 이면 등록 → 바코드 필수)
	private function productInput(Request $request, $partner, $productIdx = null)
	{
		$categoryIdx = (int) $request->input('category_idx');

		if (!RipsCategory::where('partner_idx', $partner->idx)->whereKey($categoryIdx)->exists()) return [null, '카테고리를 선택해주세요.'];
		if (trim((string) $request->input('company_name')) === '') return [null, '업체명을 입력해주세요.'];
		if (trim((string) $request->input('product_name')) === '') return [null, '상품명을 입력해주세요.'];

		// 바코드 : 등록 시 필수, 같은 업체 안에서 중복 불가
		[$barcode, $barcodeError] = \App\Support\Barcode::check($request->input('barcode'), $productIdx === null, RipsProduct::where('partner_idx', $partner->idx), $productIdx);
		if ($barcodeError) return [null, $barcodeError];

		return [[
			'category_idx' => $categoryIdx,
			'company_name' => trim($request->input('company_name')),
			'product_name' => trim($request->input('product_name')),
			'barcode'      => $barcode,
			'unit_price'   => max(0, (int) $request->input('unit_price')),
			'stock_qty'    => (int) $request->input('stock_qty'),
			'lead_days'    => $request->filled('lead_days') ? max(0, (int) $request->input('lead_days')) : null,
		], null];
	}

	public function setProductRegist(Request $request, $rid)
	{
		$partner = $this->partner();

		[$data, $error] = $this->productInput($request, $partner);
		if ($error) return response()->json(['status' => 'error', 'message' => $error]);

		RipsProduct::create($data + ['partner_idx' => $partner->idx, 'created_by' => $partner->rips_id]);

		return response()->json(['status' => 'success']);
	}

	public function getProductDetailForm(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$data = RipsProduct::where('partner_idx', $partner->idx)->findOrFail($idx);

		return response()->json(['status' => 'success', 'data' => $data]);
	}

	public function setProductModify(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$product = RipsProduct::where('partner_idx', $partner->idx)->findOrFail($idx);

		[$data, $error] = $this->productInput($request, $partner, $product->idx);
		if ($error) return response()->json(['status' => 'error', 'message' => $error]);

		$product->update($data + ['updated_by' => $partner->rips_id]);

		return response()->json(['status' => 'success']);
	}

	public function setProductDelete(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$product = RipsProduct::where('partner_idx', $partner->idx)->findOrFail($idx);

		if (RipsSales::where('product_idx', $product->idx)->exists() || RipsOrderItem::where('product_idx', $product->idx)->exists()) {
			return response()->json(['status' => 'error', 'message' => '판매 또는 발주 이력이 있는 상품은 삭제할 수 없습니다.']);
		}

		$product->delete();

		return response()->json(['status' => 'success']);
	}


	//================================== 판매 ==================================
	public function getSalesList(Request $request, $rid)
	{
		$partner = $this->partner();
		$perPage = 20;

		$categoryMap = RipsCategory::pathMap($partner->idx);
		$products    = RipsProduct::where('partner_idx', $partner->idx)->orderBy('product_name')->get(['idx', 'category_idx', 'company_name', 'product_name', 'stock_qty']);

		[$data, $summary] = RipsSales::listFor($partner->idx, $request->all(), $perPage);

		$setting = RipsSalesSetting::firstOrCreate(['partner_idx' => $partner->idx], ['api_use' => 0]);

		// 조회/등록의 1~4차 단계별 선택용
		$categories = RipsCategory::where('partner_idx', $partner->idx)->orderBy('orderby')->orderBy('idx')->get(['idx', 'parent_idx', 'depth', 'name']);

		return view('rips.sales', compact('partner', 'data', 'summary', 'categoryMap', 'products', 'setting', 'categories'));
	}

	/**
	 * 판매 데이터 1건 정규화 (직접입력 / 엑셀 / API 공통)
	 * @return array [저장할 데이터, 오류메시지]
	 */
	private function salesInput(array $row, $partner, $productsByName = null)
	{
		// 구분
		$inputType = match (trim((string) ($row['input_type'] ?? ''))) {
			'case', '건별'                   => 'case',
			'daily', '일별', '일별합계', ''   => 'daily',
			'stock', '재고', '재고등록'       => 'stock',
			default                          => null,
		};
		if (!$inputType) return [null, '구분은 건별/일별합계/재고 중 하나여야 합니다.'];

		// 판매일자
		$salesDate = $row['sales_date'] ?? '';
		if (!$salesDate instanceof \DateTimeInterface) {
			if (trim((string) $salesDate) === '') return [null, '판매일자를 입력해주세요.'];
			try {
				$salesDate = Carbon::parse(trim((string) $salesDate));
			} catch (\Exception $e) {
				return [null, '판매일자 형식이 올바르지 않습니다. (예: 2026-10-25)'];
			}
		}
		$salesDate = Carbon::instance($salesDate);

		// 판매시각 (건별만)
		$salesTime = null;
		if ($inputType === 'case' && trim((string) ($row['sales_time'] ?? '')) !== '') {
			try {
				$salesTime = Carbon::parse(trim((string) $row['sales_time']))->format('H:i:s');
			} catch (\Exception $e) {
				return [null, '판매시각 형식이 올바르지 않습니다. (예: 13:30)'];
			}
		}

		// 상품 (idx 또는 상품명)
		$product = null;
		if (!empty($row['product_idx'])) {
			$product = RipsProduct::where('partner_idx', $partner->idx)->find($row['product_idx']);
		} elseif (trim((string) ($row['product_name'] ?? '')) !== '') {
			$matches = ($productsByName ?? RipsProduct::where('partner_idx', $partner->idx)->get()->groupBy('product_name'))[trim($row['product_name'])] ?? collect();
			if ($matches->count() > 1) return [null, '같은 이름의 상품이 여러 개 있습니다. ('.trim($row['product_name']).')'];
			$product = $matches->first();
		}
		if (!$product) return [null, '등록된 상품을 찾을 수 없습니다.'];

		// 재고등록 : 판매 없이 잔여수량만 (판매수량/금액/프로모션/매대 없음)
		if ($inputType === 'stock') {
			$stockQty = $row['stock_qty'] ?? '';
			if ($stockQty === '' || $stockQty === null || !is_numeric($stockQty)) return [null, '재고등록은 잔여수량(숫자)을 입력해야 합니다.'];
			if ((int) $stockQty < 0) return [null, '잔여수량은 0 이상이어야 합니다.'];

			return [[
				'product_idx'   => $product->idx,
				'category_idx'  => $product->category_idx,
				'input_type'    => 'stock',
				'sales_date'    => $salesDate->toDateString(),
				'sales_time'    => null,
				'qty'           => 0,
				'total_amount'  => 0,
				'unit_price'    => 0,
				'stock_qty'     => (int) $stockQty,
				'promotion'     => 'N',
				'discount_rate' => null,
				'display_place' => '',
			], null];
		}

		// 수량 / 금액 / 잔여수량
		$qty         = $row['qty'] ?? '';
		$totalAmount = $row['total_amount'] ?? '';
		$stockQty    = $row['stock_qty'] ?? '';

		if (!is_numeric($qty) || (int) $qty <= 0) return [null, '총 판매수량은 1 이상의 숫자여야 합니다.'];
		if (!is_numeric($totalAmount) || $totalAmount < 0) return [null, '총 판매가는 0 이상의 숫자여야 합니다.'];
		if ($stockQty !== '' && $stockQty !== null && !is_numeric($stockQty)) return [null, '잔여수량은 숫자여야 합니다.'];

		// 프로모션
		$promotion = match (trim((string) ($row['promotion'] ?? ''))) {
			'', 'N', 'n', '없음'      => 'N',
			'discount', '할인'        => 'discount',
			'1+1'                     => '1+1',
			'2+1'                     => '2+1',
			default                   => null,
		};
		if (!$promotion) return [null, '프로모션은 N/할인/1+1/2+1 중 하나여야 합니다.'];

		// 할인율 (할인일 때만, "10" 또는 "10%")
		$discountRate = null;
		if ($promotion === 'discount') {
			$rate = rtrim(trim((string) ($row['discount_rate'] ?? '')), '%');
			if ($rate !== '') {
				if (!is_numeric($rate) || $rate <= 0 || $rate > 100) return [null, '할인율은 0보다 크고 100 이하인 숫자여야 합니다.'];
				$discountRate = round((float) $rate, 2);
			}
		}

		// 판매매대
		$displayPlace = match (trim((string) ($row['display_place'] ?? ''))) {
			'', '미입력'                                       => '',
			'basic', '기본상품코너'                             => 'basic',
			'counter', '계산대앞'                               => 'counter',
			'event', '할인/이벤트코너', '이벤트코너', '할인코너'  => 'event',
			default                                            => null,
		};
		if ($displayPlace === null) return [null, '판매매대는 미입력/기본상품코너/계산대앞/할인/이벤트코너 중 하나여야 합니다.'];

		return [[
			'product_idx'   => $product->idx,
			'category_idx'  => $product->category_idx,
			'input_type'    => $inputType,
			'sales_date'    => $salesDate->toDateString(),
			'sales_time'    => $salesTime,
			'qty'           => (int) $qty,
			'total_amount'  => (int) round($totalAmount),
			'unit_price'    => RipsSales::calcUnitPrice($totalAmount, (int) $qty),
			'stock_qty'     => ($stockQty === '' || $stockQty === null) ? null : (int) $stockQty,
			'promotion'     => $promotion,
			'discount_rate' => $discountRate,
			'display_place' => $displayPlace,
		], null];
	}

	// 상품 잔여수량 = 가장 최근 판매 데이터의 잔여수량
	private function syncProductStock(array $productIdxs)
	{
		foreach (array_unique($productIdxs) as $productIdx) {
			$latest = RipsSales::where('product_idx', $productIdx)->whereNotNull('stock_qty')
			->orderByDesc('sales_date')->orderByDesc('sales_time')->orderByDesc('idx')->first();

			if ($latest) {
				RipsProduct::whereKey($productIdx)->update(['stock_qty' => $latest->stock_qty]);
			}
		}
	}

	public function setSalesRegist(Request $request, $rid)
	{
		$partner = $this->partner();

		[$data, $error] = $this->salesInput($request->all(), $partner);
		if ($error) return response()->json(['status' => 'error', 'message' => $error]);

		RipsSales::create($data + ['partner_idx' => $partner->idx, 'source' => 'manual', 'created_by' => $partner->rips_id]);
		$this->syncProductStock([$data['product_idx']]);

		return response()->json(['status' => 'success']);
	}

	public function getSalesDetailForm(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$data = RipsSales::where('partner_idx', $partner->idx)->findOrFail($idx);

		return response()->json(['status' => 'success', 'data' => $data->toArray() + [
			'sales_date_text' => $data->sales_date->toDateString(),
			'sales_time_text' => $data->sales_time ? substr($data->sales_time, 0, 5) : '',
		]]);
	}

	public function setSalesModify(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		[$data, $error] = $this->salesInput($request->all(), $partner);
		if ($error) return response()->json(['status' => 'error', 'message' => $error]);

		$sales      = RipsSales::where('partner_idx', $partner->idx)->findOrFail($idx);
		$oldProduct = $sales->product_idx;

		$sales->update($data + ['updated_by' => $partner->rips_id]);
		$this->syncProductStock([$oldProduct, $data['product_idx']]);

		return response()->json(['status' => 'success']);
	}

	public function setSalesDelete(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$sales = RipsSales::where('partner_idx', $partner->idx)->findOrFail($idx);
		$sales->delete();
		$this->syncProductStock([$sales->product_idx]);

		return response()->json(['status' => 'success']);
	}

	// 엑셀 업로드 양식
	public function getSalesTemplate(Request $request, $rid)
	{
		$partner  = $this->partner();
		$products = RipsProduct::where('partner_idx', $partner->idx)->orderBy('product_name')->limit(3)->pluck('product_name');

		return Excel::download(new ripsSalesTemplateExport($products), 'RIPS_판매데이터_양식.xlsx');
	}

	// 엑셀 업로드 (한 행이라도 오류가 있으면 전체 미등록)
	public function setSalesUpload(Request $request, $rid)
	{
		$partner = $this->partner();

		if (!$request->hasFile('file')) return response()->json(['status' => 'error', 'message' => '엑셀 파일을 선택해주세요.']);

		$file = $request->file('file');
		if (!in_array(strtolower($file->getClientOriginalExtension()), ['xlsx', 'xls', 'csv'])) {
			return response()->json(['status' => 'error', 'message' => 'xlsx, xls, csv 파일만 업로드할 수 있습니다.']);
		}

		try {
			$sheet = IOFactory::load($file->getRealPath())->getActiveSheet()->toArray(null, true, false, false);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => '엑셀 파일을 읽을 수 없습니다.']);
		}

		$productsByName = RipsProduct::where('partner_idx', $partner->idx)->get()->groupBy('product_name');

		$rows   = [];
		$errors = [];
		foreach (array_slice($sheet, 1) as $i => $cols) {
			$cols = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $cols);
			if (count(array_filter($cols, fn ($v) => $v !== null && $v !== '')) === 0) continue;

			// 엑셀 날짜/시각(숫자) 변환
			$date = $cols[1] ?? '';
			if (is_numeric($date)) $date = ExcelDate::excelToDateTimeObject($date);
			$time = $cols[2] ?? '';
			if (is_numeric($time)) $time = ExcelDate::excelToDateTimeObject($time)->format('H:i');

			[$data, $error] = $this->salesInput([
				'input_type'    => $cols[0] ?? '',
				'sales_date'    => $date,
				'sales_time'    => $time,
				'product_name'  => $cols[3] ?? '',
				'qty'           => $cols[4] ?? '',
				'total_amount'  => $cols[5] ?? '',
				'stock_qty'     => $cols[6] ?? '',
				'promotion'     => $cols[7] ?? '',
				'discount_rate' => $cols[8] ?? '',
				'display_place' => $cols[9] ?? '',
			], $partner, $productsByName);

			if ($error) {
				$errors[] = ($i + 2).'행: '.$error;
			} else {
				$rows[] = $data;
			}
		}

		if ($errors) return response()->json(['status' => 'error', 'message' => '오류가 있어 등록하지 않았습니다.', 'errors' => array_slice($errors, 0, 20)]);
		if (!$rows) return response()->json(['status' => 'error', 'message' => '등록할 데이터가 없습니다.']);

		DB::transaction(function () use ($rows, $partner) {
			foreach ($rows as $data) {
				RipsSales::create($data + ['partner_idx' => $partner->idx, 'source' => 'excel', 'created_by' => $partner->rips_id]);
			}
			$this->syncProductStock(array_column($rows, 'product_idx'));
		});

		return response()->json(['status' => 'success', 'count' => count($rows)]);
	}

	// API 연동 설정 (사용여부 / 인증키 재발급)
	public function setSalesSetting(Request $request, $rid)
	{
		$partner = $this->partner();

		$setting = RipsSalesSetting::firstOrCreate(['partner_idx' => $partner->idx], ['api_use' => 0]);

		$data = ['api_use' => $request->input('api_use') == 1 ? 1 : 0];
		if ($request->input('regenerate') == 1 || ($data['api_use'] && !$setting->api_key)) {
			$data['api_key'] = Str::random(40);
		}
		$setting->update($data);

		return response()->json(['status' => 'success', 'data' => $setting->only(['api_use', 'api_key'])]);
	}

	/**
	 * 판매 데이터 API 수신
	 * POST /rips-api/sales  (Header: X-RIPS-KEY)
	 * { "rows": [ { "input_type": "daily", "sales_date": "2026-10-25", "product_name": "...", "qty": 10, "total_amount": 120000, ... } ] }
	 */
	public function setSalesApi(Request $request)
	{
		$key = (string) $request->header('X-RIPS-KEY');

		$setting = $key !== '' ? RipsSalesSetting::where('api_key', $key)->where('api_use', 1)->first() : null;
		$partner = $setting ? Partner::where('rips_use', 1)->find($setting->partner_idx) : null;

		if (!$partner) return response()->json(['status' => 'error', 'message' => '인증키가 올바르지 않거나 API 연동이 꺼져 있습니다.'], 401);

		$rows = $request->input('rows');
		if (!is_array($rows) || !$rows) return response()->json(['status' => 'error', 'message' => 'rows 가 비어 있습니다.'], 422);
		if (count($rows) > 1000) return response()->json(['status' => 'error', 'message' => '한 번에 최대 1000건까지 전송할 수 있습니다.'], 422);

		$productsByName = RipsProduct::where('partner_idx', $partner->idx)->get()->groupBy('product_name');

		$valid  = [];
		$errors = [];
		foreach ($rows as $i => $row) {
			[$data, $error] = $this->salesInput(is_array($row) ? $row : [], $partner, $productsByName);
			if ($error) {
				$errors[] = ['row' => $i, 'message' => $error];
			} else {
				$valid[] = $data;
			}
		}

		if ($errors) return response()->json(['status' => 'error', 'message' => '오류가 있어 등록하지 않았습니다.', 'errors' => $errors], 422);

		DB::transaction(function () use ($valid, $partner, $setting) {
			foreach ($valid as $data) {
				RipsSales::create($data + ['partner_idx' => $partner->idx, 'source' => 'api', 'created_by' => 'api']);
			}
			$this->syncProductStock(array_column($valid, 'product_idx'));
			$setting->update(['last_received_at' => now()]);
		});

		return response()->json(['status' => 'success', 'count' => count($valid)]);
	}


	//================================== 발주 ==================================
	public function getOrderList(Request $request, $rid)
	{
		$partner = $this->partner();
		$perPage = 10;

		$categoryMap = RipsCategory::pathMap($partner->idx);

		$data = RipsOrder::listFor($partner->idx, $request->all(), $perPage);

		return view('rips.order', compact('partner', 'data', 'categoryMap'));
	}

	// 발주 등록 / 수정 폼
	public function getOrderForm(Request $request, $rid, $idx = null)
	{
		$partner = $this->partner()->load(['cities', 'districts', 'head']);

		$order = null;
		if ($idx) {
			$order = RipsOrder::with('items')->where('partner_idx', $partner->idx)->findOrFail($idx);
			if (!$order->isEditable()) {
				return redirect()->route('rips.order.detail', ['rid' => $rid, 'idx' => $idx])->with('alert', '발주접수 상태에서만 수정할 수 있습니다.');
			}
		}

		// 상품 추가 시 카테고리를 1~4단계 한 단계씩 선택
		$categories = RipsCategory::where('partner_idx', $partner->idx)->orderBy('depth')->orderBy('orderby')->orderBy('idx')->get(['idx', 'parent_idx', 'depth', 'name']);
		$products = RipsProduct::with('richportProduct')->where('partner_idx', $partner->idx)->orderBy('product_name')->get();
		RipsForecast::apply($products, $order?->idx);

		// 직전 발주자 정보를 기본값으로
		$lastOrder = $order ?? RipsOrder::where('partner_idx', $partner->idx)->orderByDesc('idx')->first();

		// 상품 목록에서 [발주하기]로 들어온 경우
		$initItems = $order
			? $order->items->map(fn ($item) => ['product_idx' => $item->product_idx, 'category_idx' => $item->category_idx, 'qty' => $item->qty])->values()
			: collect();
		if (!$order && $request->filled('product')) {
			$product = $products->firstWhere('idx', (int) $request->product);
			if ($product) $initItems->push(['product_idx' => $product->idx, 'category_idx' => $product->category_idx, 'qty' => '']);
		}

		// 상품 > 재고소진 / 즉시발주 / 발주필요 탭에서 [한 번에 발주] : 추천수량을 기본 수량으로
		if (!$order && is_array($request->products)) {
			foreach (array_unique(array_map('intval', $request->products)) as $productIdx) {
				$product = $products->firstWhere('idx', $productIdx);
				if ($product) $initItems->push(['product_idx' => $product->idx, 'category_idx' => $product->category_idx, 'qty' => $product->recommend_qty > 0 ? $product->recommend_qty : '']);
			}
		}

		return view('rips.order_form', compact('partner', 'order', 'lastOrder', 'categories', 'products', 'initItems'));
	}

	/**
	 * 발주 입력값 검증 / 계산
	 * @return array [발주 데이터, 발주 상품 목록, 오류메시지]
	 */
	private function orderInput(Request $request, $partner)
	{
		$name  = trim((string) $request->input('orderer_name'));
		$tel   = trim((string) $request->input('orderer_tel'));
		$email = trim((string) $request->input('orderer_email'));

		if ($name === '') return [null, null, '발주자명을 입력해주세요.'];
		if ($tel === '') return [null, null, '전화번호를 입력해주세요.'];
		if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) return [null, null, '이메일 형식이 올바르지 않습니다.'];

		$inputItems = $request->input('items');
		if (!is_array($inputItems) || !$inputItems) return [null, null, '발주할 상품을 추가해주세요.'];

		$products = RipsProduct::with('richportProduct')->where('partner_idx', $partner->idx)->whereIn('idx', array_column($inputItems, 'product_idx'))->get()->keyBy('idx');

		$items    = [];
		$leadDays = [];
		foreach ($inputItems as $row) {
			$product = $products[(int) ($row['product_idx'] ?? 0)] ?? null;
			$qty     = (int) ($row['qty'] ?? 0);

			if (!$product) return [null, null, '상품을 선택해주세요.'];
			if (isset($items[$product->idx])) return [null, null, '같은 상품이 중복으로 추가되었습니다. ('.$product->product_name.')'];
			if ($qty <= 0) return [null, null, '수량을 입력해주세요. ('.$product->product_name.')'];

			[$unitPrice] = $product->orderPrice();

			$items[$product->idx] = [
				'product_idx'  => $product->idx,
				'category_idx' => $product->category_idx,
				'company_name' => $product->company_name,
				'product_name' => $product->product_name,
				'unit_price'   => $unitPrice,
				'qty'          => $qty,
				'amount'       => $unitPrice * $qty,
			];
			if ($product->lead_days !== null) $leadDays[] = $product->lead_days;
		}

		$order = [
			'orderer_dept'  => trim((string) $request->input('orderer_dept')),
			'orderer_name'  => $name,
			'orderer_tel'   => $tel,
			'orderer_email' => $email,
			'total_items'   => count($items),
			'total_qty'     => array_sum(array_column($items, 'qty')),
			'total_amount'  => array_sum(array_column($items, 'amount')),
			// 예상입고일 = 발주일 + 상품 중 가장 긴 예약 입고 소요기간
			'expected_date' => $leadDays ? Carbon::today()->addDays(max($leadDays))->toDateString() : null,
		];

		return [$order, array_values($items), null];
	}

	// 캔버스 서명(data:image/png;base64,...) 저장
	private function saveSign($dataUrl, $partner)
	{
		if (!preg_match('/^data:image\/png;base64,([A-Za-z0-9+\/=]+)$/', (string) $dataUrl, $m)) return null;

		$binary = base64_decode($m[1], true);
		if ($binary === false || strlen($binary) > 2 * 1024 * 1024) return null;

		$path = 'ripsSign/'.$partner->idx.'/'.now()->format('YmdHis').'_'.Str::random(8).'.png';
		Storage::disk('public')->put($path, $binary);

		return $path;
	}

	public function setOrderRegist(Request $request, $rid)
	{
		$partner = $this->partner();

		[$data, $items, $error] = $this->orderInput($request, $partner);
		if ($error) return response()->json(['status' => 'error', 'message' => $error]);

		$signPath = $this->saveSign($request->input('sign'), $partner);
		if (!$signPath) return response()->json(['status' => 'error', 'message' => '서명을 해주세요.']);

		try {
			$order = DB::transaction(function () use ($partner, $data, $items, $signPath) {
				$order = RipsOrder::create($data + [
					'partner_idx' => $partner->idx,
					'order_date'  => now(),
					'status'      => 'ordered',
					'sign_path'   => $signPath,
					'created_by'  => $data['orderer_name'],
				]);
				$order->update(['order_no' => 'RO'.now()->format('ymd').'-'.str_pad($order->idx, 5, '0', STR_PAD_LEFT)]);

				foreach ($items as $item) {
					RipsOrderItem::create($item + ['order_idx' => $order->idx]);
				}
				return $order;
			});
		} catch (\Exception $e) {
			Storage::disk('public')->delete($signPath);
			return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
		}

		return response()->json(['status' => 'success', 'idx' => $order->idx]);
	}

	public function getOrderDetail(Request $request, $rid, $idx)
	{
		$partner = $this->partner()->load(['cities', 'districts', 'head']);

		$order       = RipsOrder::with('items')->where('partner_idx', $partner->idx)->findOrFail($idx);
		$categoryMap = RipsCategory::pathMap($partner->idx);

		return view('rips.order_detail', compact('partner', 'order', 'categoryMap'));
	}

	public function setOrderModify(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$order = RipsOrder::where('partner_idx', $partner->idx)->findOrFail($idx);
		if (!$order->isEditable()) return response()->json(['status' => 'error', 'message' => '발주접수 상태에서만 수정할 수 있습니다.']);

		[$data, $items, $error] = $this->orderInput($request, $partner);
		if ($error) return response()->json(['status' => 'error', 'message' => $error]);

		$signPath = $this->saveSign($request->input('sign'), $partner);
		if (!$signPath) return response()->json(['status' => 'error', 'message' => '서명을 해주세요.']);

		$oldSign = $order->sign_path;

		DB::transaction(function () use ($order, $data, $items, $signPath) {
			$order->update($data + ['sign_path' => $signPath, 'updated_by' => $data['orderer_name']]);

			RipsOrderItem::where('order_idx', $order->idx)->delete();
			foreach ($items as $item) {
				RipsOrderItem::create($item + ['order_idx' => $order->idx]);
			}
		});

		if ($oldSign) Storage::disk('public')->delete($oldSign);

		return response()->json(['status' => 'success', 'idx' => $order->idx]);
	}

	public function setOrderCancel(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$order = RipsOrder::where('partner_idx', $partner->idx)->findOrFail($idx);
		if (!$order->isEditable()) return response()->json(['status' => 'error', 'message' => '발주접수 상태에서만 취소할 수 있습니다.']);

		$order->update(['status' => 'canceled', 'canceled_at' => now(), 'updated_by' => $partner->rips_id]);

		return response()->json(['status' => 'success']);
	}


	//================================== 리치포트 상품 ==================================
	// 리치포트가 등록한 상품 목록 (신상품은 테스트베드 신청 가능)
	public function getRichportProductList(Request $request, $rid)
	{
		$partner  = $this->partner();
		$formTree = FormTypes::tree();

		$data = Product::with(['forms', 'partners'])
		->when($request->filled('forms_idx'), function ($q) use ($request, $formTree) {
			$q->whereIn('forms_idx', FormTypes::descendantIds($request->forms_idx, $formTree));
		})
		->when($request->input('new') === '1', fn ($q) => $q->where('new', '1'))
		->when($request->filled('product_name'), function ($q) use ($request) {
			$keyword = '%'.$request->product_name.'%';
			$q->where(function ($w) use ($keyword) {
				$w->where('product_name', 'like', $keyword)->orWhere('product_name_en', 'like', $keyword)->orWhere('product_name_vn', 'like', $keyword);
			});
		})
		->orderByDesc('new')->orderByDesc('created_at')->paginate(10)->withQueryString();

		// 이미 신청한 상품 (처리 진행중/완료 여부와 무관하게 표시용)
		$appliedIdxs = Testbedinquire::where('partner_idx', $partner->idx)
		->whereIn('types_idx', $data->pluck('idx'))->pluck('types_idx')->unique()->values()->all();

		$categories = $formTree->where('depth', 1)->values();

		return view('rips.richport_product', compact('partner', 'data', 'appliedIdxs', 'categories'));
	}

	// 리치포트 상품 상세
	public function getRichportProductDetail(Request $request, $rid, $idx)
	{
		$product = Product::with(['forms', 'partners'])->findOrFail($idx);

		$files = ProductFiles::where('product_idx', $product->idx)->orderBy('orderby')->get(['idx', 'up_name']);

		return response()->json(['status' => 'success', 'data' => [
			'idx'          => $product->idx,
			'product_name' => $product->product_name,
			'category'     => $product->forms?->subject ?? '',
			'partner_name' => $product->partners?->partner_name ?? '',
			'contents'     => $product->contents ?? '',
			'is_new'       => (string) $product->new === '1',
			'thumbnail'    => $product->pc_up_name ? Storage::disk('public')->url($product->pc_up_name) : '',
			'files'        => $files->map(fn ($f) => Storage::disk('public')->url($f->up_name))->values(),
		]]);
	}

	// 테스트베드 신청 (신상품만, 신청 업체 자동 저장)
	public function setRichportTestbedRegist(Request $request, $rid)
	{
		$partner = $this->partner();

		$company  = trim((string) $request->input('inquire_company'));
		$hp       = trim((string) $request->input('inquire_hp'));
		$address  = trim((string) $request->input('inquire_address'));
		$contents = trim((string) $request->input('inquire_contents'));

		$product = Product::find($request->input('product_idx'));
		if (!$product)                     return response()->json(['status' => 'error', 'message' => '상품을 찾을 수 없습니다.']);
		if ((string) $product->new !== '1') return response()->json(['status' => 'error', 'message' => '테스트베드는 신상품만 신청할 수 있습니다.']);
		if ($company === '')               return response()->json(['status' => 'error', 'message' => '회사명을 입력해주세요.']);
		if ($hp === '')                    return response()->json(['status' => 'error', 'message' => '연락처를 입력해주세요.']);
		if ($address === '')               return response()->json(['status' => 'error', 'message' => '주소를 입력해주세요.']);
		if ($contents === '')              return response()->json(['status' => 'error', 'message' => '요청 내용을 입력해주세요.']);

		Testbedinquire::create([
			'status'           => 1,
			'types_idx'        => $product->idx,
			'partner_idx'      => $partner->idx,
			'inquire_date'     => Carbon::now(),
			'inquire_company'  => mb_substr($company, 0, 200),
			'inquire_hp'       => mb_substr($hp, 0, 50),
			'inquire_address'  => mb_substr($address, 0, 255),
			'inquire_contents' => $contents,
		]);

		return response()->json(['status' => 'success', 'redirect' => route('rips.testbed', ['rid' => $partner->rips_id])]);
	}

	// 테스트베드 신청 내역 (본 업체 신청 건만)
	public function getTestbedList(Request $request, $rid)
	{
		$partner = $this->partner();

		$data = Testbedinquire::with('product')->where('partner_idx', $partner->idx)
		->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
		->when($request->filled('product_name'), function ($q) use ($request) {
			$q->whereHas('product', fn ($p) => $p->where('product_name', 'like', '%'.$request->product_name.'%'));
		})
		->orderByDesc('idx')->paginate(10)->withQueryString();

		return view('rips.testbed', compact('partner', 'data'));
	}

	// 테스트베드 신청 상세 (본 업체 신청 건만)
	public function getTestbedDetail(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		$item = Testbedinquire::with('product')->where('partner_idx', $partner->idx)->findOrFail($idx);

		return response()->json(['status' => 'success', 'data' => [
			'idx'              => $item->idx,
			'product_name'     => $item->product?->product_name ?? '-',
			'inquire_date'     => $item->inquire_date,
			'inquire_company'  => $item->inquire_company,
			'inquire_hp'       => $item->inquire_hp,
			'inquire_address'  => $item->inquire_address,
			'inquire_contents' => $item->inquire_contents,
			'status'           => (int) $item->status,
			'status_kr'        => $item->status_kr,
			'response_at'      => $item->response_at,
			'complete_at'      => $item->complete_at,
			'feedback'         => $item->feedback,
			'feedback_at'      => $item->feedback_at,
		]]);
	}


	//================================== 통관 이슈 (리치포트 등록, 조회만) ==================================
	public function getCustomsIssueDetail(Request $request, $rid, $idx)
	{
		$issue = CustomsIssue::findOrFail($idx);

		return response()->json(['status' => 'success', 'data' => [
			'idx'        => $issue->idx,
			'title'      => $issue->title,
			'contents'   => $issue->contents,
			'ori_name'   => $issue->ori_name,
			'created_at' => $issue->created_at?->format('Y-m-d'),
			'download'   => $issue->ori_name ? route('rips.customs.download', ['rid' => $rid, 'idx' => $issue->idx]) : null,
		]]);
	}

	public function getCustomsIssueDownload(Request $request, $rid, $idx)
	{
		$issue = CustomsIssue::findOrFail($idx);

		if (!$issue->up_name || !Storage::disk('local')->exists($issue->up_name)) {
			abort(404, '파일이 존재하지 않습니다.');
		}

		return Storage::disk('local')->download($issue->up_name, $issue->ori_name);
	}

	//================================== 설문조사 결과 ==================================
	// 테스트베드 진행완료된 상품 idx (본 업체 신청 건)
	private function surveyProductIdxs($partner)
	{
		return Testbedinquire::where('partner_idx', $partner->idx)->where('status', Testbedinquire::STATUS_DONE)
		->whereNotNull('types_idx')->distinct()->pluck('types_idx');
	}

	// 테스트베드 진행완료 상품 목록 (설문조사 결과)
	public function getSurveyList(Request $request, $rid)
	{
		$partner = $this->partner();
		$active  = fn ($q) => $q->where('check_execution.use_yn', 'Y');

		$data = Product::with('partners:idx,partner_name')->whereIn('idx', $this->surveyProductIdxs($partner))
		->withCount(['surveys', 'surveyResponses as response_count' => $active])
		->withMax(['surveyResponses as last_response_at' => $active], 'created_at')
		->when($request->filled('product_name'), fn ($q) => $q->where('product_name', 'like', '%'.$request->product_name.'%'))
		->orderByDesc('last_response_at')->orderByDesc('idx')->paginate(10)->withQueryString();

		// 상품별 테스트베드 진행완료일 (가장 최근)
		$completeDates = Testbedinquire::where('partner_idx', $partner->idx)->where('status', Testbedinquire::STATUS_DONE)
		->whereIn('types_idx', $data->pluck('idx'))->groupBy('types_idx')
		->selectRaw('types_idx, MAX(complete_at) AS complete_at')->pluck('complete_at', 'types_idx');

		// 상품별 판매 (업체 상품 ↔ 리치포트 상품 매핑 기준)
		$salesMap = $this->testbedSalesQuery($partner, $data->pluck('idx'))
		->groupBy('rp.product_idx')
		->selectRaw('rp.product_idx, SUM(s.qty) AS qty, SUM(s.total_amount) AS amount')
		->get()->keyBy('product_idx');

		return view('rips.survey', compact('partner', 'data', 'completeDates', 'salesMap'));
	}

	// 설문조사 결과 상세 (요약 / 개별 설문)
	public function getSurveyDetail(Request $request, $rid, $idx)
	{
		$partner = $this->partner();

		if (!$this->surveyProductIdxs($partner)->contains((int) $idx)) {
			return redirect()->route('rips.survey', ['rid' => $rid])->with('alert', '테스트베드 진행이 완료된 상품만 설문조사 결과를 볼 수 있습니다.');
		}

		$product = Product::with('partners:idx,partner_name')->findOrFail($idx);

		return view('rips.survey_detail', ['partner' => $partner, 'product' => $product, 'sales' => $this->testbedSales($partner, $product)]
			+ SurveyResult::summary($product, $request->list_idx));
	}

	// 업체 판매 데이터 중 리치포트 상품에 매핑된 업체 상품의 판매 (재고등록 제외)
	private function testbedSalesQuery($partner, $productIdxs)
	{
		return DB::table('rips_sales AS s')
		->join('rips_product AS rp', 'rp.idx', '=', 's.product_idx')
		->where('s.partner_idx', $partner->idx)->where('rp.partner_idx', $partner->idx)
		->whereIn('rp.product_idx', collect($productIdxs)->all())
		->where('s.input_type', '!=', 'stock');
	}

	/**
	 * 테스트베드 상품 판매 요약 (설문조사 결과 상세)
	 * - 매핑된 업체 상품, 누적 판매, 테스트베드 완료 이후 판매, 최근 30일, 최근 12주 주별 판매수량
	 */
	private function testbedSales($partner, $product)
	{
		$today      = Carbon::today();
		$completeAt = Testbedinquire::where('partner_idx', $partner->idx)->where('types_idx', $product->idx)
			->where('status', Testbedinquire::STATUS_DONE)->max('complete_at');
		$completeAt = $completeAt ? Carbon::parse($completeAt)->startOfDay() : null;

		$mapped = RipsProduct::where('partner_idx', $partner->idx)->where('product_idx', $product->idx)
			->orderBy('product_name')->get(['idx', 'product_name', 'company_name', 'stock_qty']);

		$query = fn () => $this->testbedSalesQuery($partner, [$product->idx]);
		$sum   = fn ($q) => $q->selectRaw('COALESCE(SUM(s.qty),0) AS qty, COALESCE(SUM(s.total_amount),0) AS amount, COUNT(DISTINCT s.sales_date) AS days, MIN(s.sales_date) AS first_date, MAX(s.sales_date) AS last_date')->first();

		$total  = $sum($query());
		$after  = $completeAt ? $sum($query()->where('s.sales_date', '>=', $completeAt->toDateString())) : null;
		$recent = $sum($query()->where('s.sales_date', '>=', $today->copy()->subDays(29)->toDateString()));

		// 최근 12주 주별 판매수량 (월요일 시작)
		$weekStart = $today->copy()->startOfWeek()->subWeeks(11);
		$daily     = $query()->where('s.sales_date', '>=', $weekStart->toDateString())
			->groupBy('s.sales_date')->selectRaw('s.sales_date, SUM(s.qty) AS qty')->pluck('qty', 'sales_date');

		$weeks = [];
		for ($w = 0; $w < 12; $w++) {
			$from = $weekStart->copy()->addWeeks($w);
			$to   = $from->copy()->addDays(6);
			$qty  = $daily->filter(fn ($q, $date) => $date >= $from->toDateString() && $date <= $to->toDateString())->sum();
			$weeks[] = [
				'from'     => $from->toDateString(),
				'to'       => $to->toDateString(),
				'qty'      => (int) $qty,
				'testbed'  => $completeAt && $completeAt->between($from, $to->copy()->endOfDay()),	// 테스트베드 완료 주
			];
		}

		return compact('mapped', 'completeAt', 'total', 'after', 'recent', 'weeks');
	}

	// 개별 설문 JSON
	public function getSurveyResponse(Request $request, $rid, $idx, $listIdx)
	{
		$partner = $this->partner();

		$allowed = $this->surveyProductIdxs($partner)->contains((int) $idx)
			&& CheckList::where('product_idx', $idx)->whereKey($listIdx)->exists();
		if (!$allowed) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		return response()->json(SurveyResult::response($listIdx, $request->no));
	}

	//================================== 설정 > 내 정보 수정 ==================================
	public function getProfile(Request $request, $rid)
	{
		$partner = $this->partner()->load('ripsType');

		return view('rips.profile', compact('partner'));
	}

	// 회사정보 · 담당자정보 저장 (업체구분 · 회사명 · 아이디는 수정 불가, 통관고유번호 · 사업자번호는 최초 1회만)
	public function setProfile(Request $request, $rid)
	{
		$partner = $this->partner();
		$input   = fn ($key, $max) => mb_substr(trim((string) $request->input($key)), 0, $max);

		$data = [
			'company_address' => $input('company_address', 255),
			'company_tel'     => $input('company_tel', 50),
			'ceo_name'        => $input('ceo_name', 100),
			'manager_name'    => $input('manager_name', 100),
			'manager_hp'      => $input('manager_hp', 50),
			'manager_email'   => $input('manager_email', 200),
			'manager_task'    => $input('manager_task', 200),
		];

		if ($data['manager_name'] === '') return response()->json(['status' => 'error', 'message' => '담당자명을 입력해주세요.']);
		if ($data['manager_hp'] === '')   return response()->json(['status' => 'error', 'message' => '담당자 연락처를 입력해주세요.']);
		foreach (['company_tel' => '회사 대표전화', 'manager_hp' => '담당자 연락처'] as $key => $label) {
			if ($data[$key] !== '' && !preg_match('/^[0-9+\-\s()]{6,50}$/', $data[$key])) return response()->json(['status' => 'error', 'message' => $label.'는 숫자와 +, -, 괄호만 입력할 수 있습니다.']);
		}
		if ($data['manager_email'] !== '' && !filter_var($data['manager_email'], FILTER_VALIDATE_EMAIL)) return response()->json(['status' => 'error', 'message' => '담당자 이메일 형식이 올바르지 않습니다.']);

		// 통관고유번호 / 사업자번호 : 비어 있을 때만 입력 (이미 있으면 보낸 값은 무시)
		foreach (['customs_code' => '통관고유번호', 'biz_no' => '사업자번호'] as $key => $label) {
			if (!empty($partner->{$key})) continue;
			$value = $input($key, 50);
			if ($value === '') continue;
			if (!preg_match('/^[0-9A-Za-z\-]{4,50}$/', $value)) return response()->json(['status' => 'error', 'message' => $label.'는 영문, 숫자, 하이픈(-) 4~50자로 입력해주세요.']);
			$data[$key] = $value;
		}

		$data = array_map(fn ($v) => $v === '' ? null : $v, $data);

		// 업체 사진 (jpg, png, webp, 5MB 이하)
		$oldPhoto = null;
		if ($request->hasFile('photo')) {
			$file = $request->file('photo');
			if (!$file->isValid() || !in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp']) || !str_starts_with((string) $file->getMimeType(), 'image/')) {
				return response()->json(['status' => 'error', 'message' => '업체 사진은 jpg, png, webp 이미지만 올릴 수 있습니다.']);
			}
			if ($file->getSize() > 5 * 1024 * 1024) return response()->json(['status' => 'error', 'message' => '업체 사진은 5MB 이하로 올려주세요.']);

			$oldPhoto = $partner->photo_up_name;
			$data['photo_ori_name'] = mb_substr($file->getClientOriginalName(), 0, 255);
			$data['photo_up_name']  = $file->store('partnerPhoto', 'public');
		} elseif ($request->input('remove_photo') == 1) {
			$oldPhoto = $partner->photo_up_name;
			$data['photo_ori_name'] = $data['photo_up_name'] = null;
		}

		$partner->update($data + ['updated_by' => $partner->rips_id]);
		if ($oldPhoto) Storage::disk('public')->delete($oldPhoto);

		return response()->json(['status' => 'success']);
	}

	// 비밀번호 변경 (현재 비밀번호 확인)
	public function setProfilePassword(Request $request, $rid)
	{
		$partner = $this->partner();
		$current = (string) $request->input('current_password');
		$new     = (string) $request->input('new_password');
		$confirm = (string) $request->input('new_password_confirmation');

		if ($current === '' || !Hash::check($current, $partner->rips_pw)) return response()->json(['status' => 'error', 'message' => '현재 비밀번호가 일치하지 않습니다.']);
		if (strlen($new) < 6)                 return response()->json(['status' => 'error', 'message' => '새 비밀번호는 6자 이상 입력해주세요.']);
		if ($new !== $confirm)                return response()->json(['status' => 'error', 'message' => '새 비밀번호와 비밀번호 확인이 일치하지 않습니다.']);
		if (Hash::check($new, $partner->rips_pw)) return response()->json(['status' => 'error', 'message' => '현재 비밀번호와 다른 비밀번호를 입력해주세요.']);

		$partner->update(['rips_pw' => Hash::make($new), 'updated_by' => $partner->rips_id]);
		$request->session()->regenerate();

		return response()->json(['status' => 'success']);
	}
}
