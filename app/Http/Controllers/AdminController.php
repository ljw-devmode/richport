<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use function Illuminate\Log\log;
use Carbon\Carbon;
use DateException;
use App\Models\Admin;
use App\Models\AdminPermissions;
use App\Models\AdminLog;
use App\Models\AiApiLog;
use App\Models\AiApiSetting;
use App\Models\Cities;
use App\Models\CustomsIssue;
use App\Models\CheckExecution;
use App\Models\CheckItem;
use App\Models\CheckList;
use App\Models\CheckOption;
use App\Models\CheckResult;
use App\Models\CheckSection;
use App\Models\Department;
use App\Models\Districts;
use App\Models\Faq;
use App\Models\FormTypes;
use App\Models\History;
use App\Models\Inquire;
use App\Models\InquireTypes;
use App\Models\Notice;
use App\Models\NoticeFiles;
use App\Models\OutCompany;
use App\Models\OutUser;
use App\Models\Partner;
use App\Models\PartnerFiles;
use App\Models\Portfolio;
use App\Models\PortfolioFiles;
use App\Models\Product;
use App\Models\ProductFiles;
use App\Models\RipsCategory;
use App\Models\Ripsinquire;
use App\Models\RipsInquireTypes;
use App\Models\RipsOrder;
use App\Models\RipsOrderItem;
use App\Models\RipsOrderLog;
use App\Models\RipsProduct;
use App\Models\RipsSales;
use App\Models\Testbedinquire;
use App\Models\TestbedInquireTypes;
use App\Models\Types;
use App\Exports\historyExport;
use App\Exports\inquireExport;
use App\Exports\inquireRipsExport;
use App\Exports\inquireTestbedExport;
use App\Services\SurveyResult;
use App\Services\RipsForecast;
use App\Exports\noticeExport;
use App\Exports\partnerExport;
use App\Exports\portfolioExport;
use App\Exports\productExport;

class AdminController extends Controller 
{

	//================================== 로그인 ==================================		
	// 라우트 /download/{ori_name}/{path} : 라우트 값은 순서대로 전달되므로 인자 순서를 라우트와 같게 유지
	public function downloadFile($ori_name, $path) {
		// 1. NAS에 파일이 있는지 바로 확인
		if (Storage::disk('synology')->exists($path)) {
			$mimeType = Storage::disk('synology')->mimeType($path);
			return Storage::disk('synology')->download($path, $ori_name);
		}
		abort(404, '파일이 NAS에 존재하지 않습니다.');
	}

	public function viewFile($path) {
		// 1. NAS에 파일이 있는지 바로 확인
		if (Storage::disk('synology')->exists($path)) {
			$mimeType = Storage::disk('synology')->mimeType($path);
			return Storage::disk('synology')->response($path);
		}
		abort(404, '파일이 NAS에 존재하지 않습니다.');
	}

	public function formatDate($date) {
		if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') return '-';
		try {
			return Carbon::parse($date)->format('y-m-d');
		} catch (\Exception $e) {
			return '-';
    	}
	}


	//================================== 로그인 ==================================		
    public function loginGet() 
	{
        return view('admin.login');
    }
    
    public function loginPost(Request $request)
	{
    	$credentials = $request->validate(['id' => 'required|string','pw' => 'required|string',]);
    	
    	if (Auth::guard('admin')->attempt(['id'  => $credentials['id'],'password' => $credentials['pw'],])) {
    		$request->session()->regenerate();
    		$admin = Auth::guard('admin')->user();
    		
    		$sessionData = [
    				'idx'      => $admin->idx,
    				'id'       => $admin->account,
    				'name'     => $admin->name,
    				'level'    => $admin->level ?? null,
    				'ip'       => $request->ip(),
    				'login_at' => now()->format('Y-m-d H:i:s'),
    		];
    		Session::put('admin', $sessionData);
    		
    		return redirect()->route('admin.dashboard');
    	} else {
    		return back()->withErrors(['login' => '아이디 또는 비밀번호가 일치하지 않습니다.']);
    	}
    }
    

	//================================== 로그아웃 ==================================
    //로그아웃
    public function logout(Request $request)
    {
    	Session::forget('admin');
    	Auth::guard('admin')->logout();          // 1) 가드 로그아웃
    	$request->session()->invalidate();       // 2) 세션 무효화
    	$request->session()->regenerateToken();  // 3) CSRF 토큰 재발급
    	
    	return redirect()->route('admin.login');
    }


	//================================== 공통 ==================================
    // 도시목록
	public function getDistricts($cities_idx)
    {
    	$districts = Districts::where('cities_idx', $cities_idx)->orderBy('name_kr')->get(['idx', 'name_kr']);
    	
    	return response()->json($districts);
    }


	//================================== 대시보드 ==================================
    // 대시보드
	public function getDashboard(Request $request)
    {
    	$admin = auth()->user();
    	$monthRange = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];

    	// 권한이 있는 항목만 조회 (null 이면 화면에서 숨김)
    	$partners = $products = $orders = $recentOrders = $customsIssues = $ripsInquires = $testbedInquires = null;
    	$topSales = $orderAlerts = null;

    	// 이번달 판매 상위 리치포트 상품 (판매 관리 > 월별 과 같은 기준)
    	if ($admin->hasPerm('ripsSales.view')) {
    		$formPaths = FormTypes::pathMap();
    		$topSales  = DB::table('rips_sales AS s')->join('rips_product AS rp', 'rp.idx', '=', 's.product_idx')->join('product AS p', 'p.idx', '=', 'rp.product_idx')
    		->whereBetween('s.sales_date', [$monthRange[0]->toDateString(), $monthRange[1]->toDateString()])
    		->groupBy('p.idx')->selectRaw('p.idx, MAX(p.product_name) AS product_name, MAX(p.forms_idx) AS forms_idx, SUM(s.qty) AS qty, SUM(s.total_amount) AS amount')
    		->orderByDesc('qty')->limit(7)->get()
    		->each(fn ($row) => $row->category = $formPaths[$row->forms_idx] ?? '-');
    	}

    	// 발주가 필요한 상품이 있는 RIPS 업체 (재고소진 / 즉시발주 / 발주필요)
    	if ($admin->hasPerm('rips.view')) {
    		$ripsProducts = RipsProduct::whereIn('partner_idx', Partner::where('rips_use', 1)->select('idx'))->get();
    		RipsForecast::apply($ripsProducts);
    		$alertPartners = Partner::whereIn('idx', $ripsProducts->pluck('partner_idx')->unique())->get(['idx', 'partner_name', 'store_name'])->keyBy('idx');

    		$orderAlerts = $ripsProducts->groupBy('partner_idx')->map(function ($rows, $partnerIdx) use ($alertPartners) {
    			$types = $rows->map(fn ($p) => RipsForecast::alert($p)['type'] ?? null)->filter()->countBy();
    			return (object) ['partner' => $alertPartners[$partnerIdx] ?? null, 'counts' => $types, 'total' => $types->sum()];
    		})->filter(fn ($row) => $row->partner && $row->total > 0)->sortByDesc('total')->values();
    	}

    	if ($admin->hasPerm('partner.view')) {
    		$partners = Partner::partnersOnly()->whereIn('country', ['korea', 'vietnam'])
    		->selectRaw('country, COUNT(*) total, COALESCE(SUM(created_at BETWEEN ? AND ?), 0) month_count', $monthRange)
    		->groupBy('country')->get()->keyBy('country');
    	}

    	if ($admin->hasPerm('product.view')) {
    		$products = Product::selectRaw(
    			'COUNT(*) total, COALESCE(SUM(created_at BETWEEN ? AND ?), 0) month_count,
    			COALESCE(SUM(congbo = 1), 0) congbo_total, COALESCE(SUM(congbo = 1 AND created_at BETWEEN ? AND ?), 0) congbo_month_count',
    			[...$monthRange, ...$monthRange]
    		)->first();
    	}

    	if ($admin->hasPerm('ripsOrder.view')) {
    		// 이번달 발주 (취소 제외)
    		$monthOrders = RipsOrder::whereBetween('order_date', $monthRange)->where('status', '!=', 'canceled');

    		$orders = [
    				'count'    => (clone $monthOrders)->count(),
    				'amount'   => (clone $monthOrders)->sum('total_amount'),
    				'partners' => (clone $monthOrders)->distinct()->count('partner_idx'),
    				'products' => RipsOrderItem::whereIn('order_idx', (clone $monthOrders)->select('idx'))->distinct()->count('product_idx'),
    		];

    		$recentOrders = RipsOrder::with('partner:idx,partner_name,store_name')->orderByDesc('order_date')->orderByDesc('idx')->limit(5)->get();
    	}

    	if ($admin->hasPerm('customsIssue.view')) {
    		$customsIssues = CustomsIssue::orderByDesc('idx')->limit(5)->get(['idx', 'title', 'ori_name']);
    	}

    	if ($admin->hasPerm('inquireRips.view')) {
    		$ripsInquires = Ripsinquire::orderByDesc('idx')->limit(5)->get(['idx', 'status', 'inquire_company']);
    	}

    	if ($admin->hasPerm('inquireTestbed.view')) {
    		$testbedInquires = Testbedinquire::with('product:idx,product_name')->orderByDesc('idx')->limit(5)->get(['idx', 'status', 'types_idx', 'inquire_company']);
    	}

        return view('admin.dashboard', compact('partners', 'products', 'orders', 'recentOrders', 'customsIssues', 'ripsInquires', 'testbedInquires', 'topSales', 'orderAlerts'));
    }

    
    
    //================================== 연혁 ==================================
    // 연혁 목록
    public function getHistoryList(Request $request)
    {
	    if (!auth()->user()->hasPerm('history.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = History::query();
    	$year = History::query()->select('year')->whereNotNull('year')->groupBy('year')->orderByDesc('year')->pluck('year');
    	
    	// 검색 조건 처리
    	if ($request->filled('year')) {
    		$query->where('year', $request->year);
    	}
    	
    	if ($request->filled('subject')) {
			$query->where(function ($q) use ($request) {
    			$q->where('subject', 'like', '%' . $request->subject . '%')
      				->orWhere('subject_en', 'like', '%' . $request->subject . '%')
      				->orWhere('subject_vn', 'like', '%' . $request->subject . '%');
    		});
    	}
    	
    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	return view('admin.history', compact('data','year'));
    }
    
	// 연혁 엑셀 다운로드
    public function getHistoryExport(Request $request)
    {
	    if (!auth()->user()->hasPerm('history.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$filters = $request->only(['year', 'subject']);
    	
    	$timestamp = Carbon::now()->format('Ymd_His');
    	$filename = '연혁목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new historyExport($filters), $filename);
    }
    
    // 연혁 등록
    public function setHistoryRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('history.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		History::create([
    				'subject'		=> $request->input('subject'),
					'subject_en'		=> $request->input('subject_en'),
					'subject_vn'		=> $request->input('subject_vn'),
    				'year'			=> $request->input('year'),
    				'month'			=> $request->input('month'),
    				'created_by'	=> $admin['name'] ?? ''
    		]);
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // 연혁 수정 폼
    public function getHistoryDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('history.update') && auth()->user()->hasPerm('history.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$data = $idx ? History::findOrFail($idx) : null;
    	
    	return response()->json(['status' => 'success', 'data' => $data]);
    }
    
    // 연혁 수정
    public function setHistoryModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('history.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		try {
    		$admin = Session::get('admin');
    		
    		DB::beginTransaction();
    		
    		$history = History::findOrFail($idx);
    		
    		$result = $history->update([
    				'subject'		=> $request->input('subject'),
					'subject_en'		=> $request->input('subject_en'),
					'subject_vn'		=> $request->input('subject_vn'),
    				'year'			=> $request->input('year'),
    				'month'			=> $request->input('month'),
    				'updated_by'	=> $admin['name'] ?? ''
    		]);
    		
    		DB::commit();
    		
    		return response()->json(['status' => $result]);
    	} catch (\Exception $e) {
    		DB::rollBack();
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }
    
    // 연혁 삭제
    public function setHistoryDelete(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('history.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$history = History::findOrFail($idx);
    		
    		DB::beginTransaction();
    		$history->delete();
    		DB::commit();
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		if (DB::transactionLevel() > 0) {
    			DB::rollBack();
    		}
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }
    




    //================================== RIPS ==================================
    // 도입현황 목록
    public function getPortfolioList(Request $request)
    {
		if (!auth()->user()->hasPerm('portfolio.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Portfolio::with('types','cities','districts');
    	
    	// 검색 조건 처리
    	if ($request->filled('types_idx')) {
    		$query->where('portfolio.types_idx', $request->types_idx);
    	}
    	
    	if ($request->filled('home_recommend')) {
    		$query->where('portfolio.home_recommend', $request->home_recommend);
    	}
    	
    	if ($request->filled('portfolio_recommend')) {
    		$query->where('portfolio.portfolio_recommend', $request->portfolio_recommend);
    	}
    	
    	if ($request->filled('cities_idx')) {
    		$query->where('portfolio.cities_idx', $request->cities_idx);
    	}

    	if ($request->filled('districts_idx')) {
    		$query->where('portfolio.districts_idx', $request->districts_idx);
    	}

    	if ($request->filled('station_name')) {
    		$query->where('portfolio.station_name', 'like', '%' . $request->station_name . '%');
    	}
    	
    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	$data->getCollection()->transform(function ($item) {
    		$item->volume_formatted = number_format((int)$item->volume);
    		
    		// 추천 플래그 1/0 → Y / N
    		$toYN = function ($v) {
    			return ((string)$v === '1') ? 'Y' : 'N';
    		};
    		
    		$item->home_recommend_text       = $toYN($item->home_recommend);
    		$item->portfolio_recommend_text  = $toYN($item->portfolio_recommend);
    		
    		$item->created_date = optional($item->created_at)->format('Y-m-d');
    		
    		return $item;
    	});
    	
    	$types = Types::orderBy('idx')->get();
    	$cities = Cities::orderBy('idx')->get();
    	
    	return view('admin.portfolio', compact('data','cities','types'));
    }
    
	// 포트폴리오 엑셀 다운로드
    public function getPortfolioExport(Request $request)
    {
		if (!auth()->user()->hasPerm('portfolio.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$filters = $request->only(['types_idx', 'home_recommend', 'portfolio_recommend', 'cities_idx', 'districts_idx', 'station_name']);
    	
    	$timestamp = Carbon::now()->format('Ymd_His');
    	$filename = 'RIPS도입목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new portfolioExport($filters), $filename);
    }
    
    // 포트폴리오 등록
    public function setPortfolioRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('portfolio.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		$portfolio = Portfolio::create([
    				'types_idx'				=> $request->input('types_idx'),
    				'cities_idx'			=> $request->input('cities_idx'),
    				'districts_idx'			=> $request->input('districts_idx'),
    				'station_name'			=> $request->input('station_name'),
    				'station_name_en'		=> $request->input('station_name_en'),
    				'station_name_vn'		=> $request->input('station_name_vn'),
					'contents'				=> $request->input('contents'),
					'contents_en'			=> $request->input('contents_en'),
					'contents_vn'			=> $request->input('contents_vn'),
    				'home_recommend'		=> $request->input('home_recommend'),
    				'portfolio_recommend'	=> $request->input('portfolio_recommend'),
    				'created_by'			=> $admin['name'] ?? ''
    		]);
    		
    		$portfolio = Portfolio::findOrFail($portfolio->idx);
    		
    		if ($request->hasFile('pcThumb')) {
    			$file = $request->file('pcThumb');
    			$up_name = $file->store('portfolioThumb', 'public');
    			$portfolio->update([
    					'pc_ori_name'    	=> $file->getClientOriginalName(),
    					'pc_up_name'     	=> $up_name,
    					'pc_file_ext'    	=> $file->getClientOriginalExtension(),
    					'pc_file_size'   	=> $file->getSize(),
    			]);
    		}
    		
    		if ($request->hasFile('foThumb')) {
    			$file = $request->file('foThumb');
    			$up_name = $file->store('portfolioThumb', 'public');
    			$portfolio->update([
    					'fo_ori_name'    	=> $file->getClientOriginalName(),
    					'fo_up_name'     	=> $up_name,
    					'fo_file_ext'    	=> $file->getClientOriginalExtension(),
    					'fo_file_size'   	=> $file->getSize(),
    			]);
    		}
    		
    		if ($request->hasFile('moThumb')) {
    			$file = $request->file('moThumb');
    			$up_name = $file->store('portfolioThumb', 'public');
    			$portfolio->update([
    					'mo_ori_name'    	=> $file->getClientOriginalName(),
    					'mo_up_name'     	=> $up_name,
    					'mo_file_ext'    	=> $file->getClientOriginalExtension(),
    					'mo_file_size'   	=> $file->getSize(),
    			]);
    		}
    		
    		if ($request->hasFile('portfolioFiles')) {
    			$orderby = 1;
    			foreach ($request->file('portfolioFiles') as $file) {
    				// 파일 저장
    				$up_name = $file->store('portfolio', 'public');
    				PortfolioFiles::create([
    						'portfolio_idx' => $portfolio->idx,
    						'ori_name'    	=> $file->getClientOriginalName(),
    						'up_name'     	=> $up_name,
    						'file_ext'    	=> $file->getClientOriginalExtension(),
    						'file_size'   	=> $file->getSize(),
    						'orderby' 		=> $orderby++,
    				]);
    			}
    		}
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }

    // 포트폴리오 수정 폼
    public function getPortfolioDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('portfolio.update') && !auth()->user()->hasPerm('portfolio.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data = $idx ? Portfolio::findOrFail($idx) : null;
    	
    	$files = collect();
    	if ($files && $data->idx) {
    		$files = PortfolioFiles::where('portfolio_idx', $data->idx)->orderByRaw('orderby')->get();
    	}
    	
    	return response()->json(['status' => 'success', 'data' => $data, 'files' => $files]);
    }
    
    // 포트폴리오 수정
    public function setPortfolioModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('portfolio.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		DB::beginTransaction();
    		
    		$portfolio = Portfolio::findOrFail($idx);
    		
    		$portfolio->update([
    				'types_idx'				=> $request->input('types_idx'),
    				'cities_idx'			=> $request->input('cities_idx'),
    				'districts_idx'			=> $request->input('districts_idx'),
    				'station_name'			=> $request->input('station_name'),
    				'station_name_en'		=> $request->input('station_name_en'),
    				'station_name_vn'		=> $request->input('station_name_vn'),
					'contents'				=> $request->input('contents'),
					'contents_en'			=> $request->input('contents_en'),
					'contents_vn'			=> $request->input('contents_vn'),
    				'home_recommend'		=> $request->input('home_recommend'),
    				'portfolio_recommend'	=> $request->input('portfolio_recommend'),
    				'updated_by'            => $admin['name'] ?? '',
    		]);
    		
    		// =================================== Thumb ===================================
    		if ($request->hasFile('pcThumb')) {
    			$oldPath = $portfolio->pc_up_name;
    			$file = $request->file('pcThumb');
    			$up_name = $file->store('portfolioThumb', 'public');
    			
    			$portfolio->update([
    					'pc_ori_name'    => $file->getClientOriginalName(),
    					'pc_up_name'     => $up_name,
    					'pc_file_ext'    => $file->getClientOriginalExtension(),
    					'pc_file_size'   => $file->getSize()
    			]);
    			
    			if ($oldPath) {
    				Storage::disk('public')->delete($oldPath);
    			}
    		}
    		
    		if ($request->hasFile('foThumb')) {
    			$oldPath = $portfolio->fo_up_name;
    			$file = $request->file('foThumb');
    			$up_name = $file->store('portfolioThumb', 'public');
    			
    			$portfolio->update([
    					'fo_ori_name'    => $file->getClientOriginalName(),
    					'fo_up_name'     => $up_name,
    					'fo_file_ext'    => $file->getClientOriginalExtension(),
    					'fo_file_size'   => $file->getSize()
    			]);
    			
    			if ($oldPath) {
    				Storage::disk('public')->delete($oldPath);
    			}
    		}
    		
    		if ($request->hasFile('moThumb')) {
    			$oldPath = $portfolio->mo_up_name;
    			$file = $request->file('moThumb');
    			$up_name = $file->store('portfolioThumb', 'public');
    			
    			$portfolio->update([
    					'mo_ori_name'    => $file->getClientOriginalName(),
    					'mo_up_name'     => $up_name,
    					'mo_file_ext'    => $file->getClientOriginalExtension(),
    					'mo_file_size'   => $file->getSize()
    			]);
    			
    			if ($oldPath) {
    				Storage::disk('public')->delete($oldPath);
    			}
    		}
    		
    		$removedFileIdxs   = json_decode($request->input('removedFileIdxs', '[]'), true) ?: [];
    		$existingFileOrder = json_decode($request->input('existingFileOrder', '[]'), true) ?: [];
    		
    		if (!empty($removedFileIdxs)) {
    			$filesToDelete = PortfolioFiles::whereIn('idx', $removedFileIdxs)->get(['up_name']);
    			
    			foreach ($filesToDelete as $f) {
    				Storage::disk('public')->delete($f->up_name);
    			}
    			PortfolioFiles::whereIn('idx', $removedFileIdxs)->delete();
    		}
    		
    		// 순서 업데이트
    		foreach ($existingFileOrder as $item) {
    			PortfolioFiles::where('idx', $item['idx'])
    			->where('portfolio_idx', $idx)
    			->update([
    					'orderby' => $item['orderby'],
    			]);
    		}
    		
    		// 새 파일 순서
    		$newFileOrders = $request->input('new_file_order', []); 
    		
    		if ($request->hasFile('new_file_upload')) {
    			foreach ($request->file('new_file_upload') as $i => $file) {
    				$up_name = $file->store('portfolio', 'public');
    				PortfolioFiles::create([
    						'portfolio_idx' => $idx,
    						'ori_name'      => $file->getClientOriginalName(),
    						'up_name'       => $up_name,
    						'file_ext'      => $file->getClientOriginalExtension(),
    						'file_size'     => $file->getSize(),
    						'orderby'       => $newFileOrders[$i] ?? 0,
    				]);
    			}
    		}
    		
    		DB::commit();
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		DB::rollBack();
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }
    
    // 포트폴리오 삭제
    public function setPortfolioDelete(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('portfolio.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$portfolio = Portfolio::findOrFail($idx);
    		$portfolioFilePaths = PortfolioFiles::where('portfolio_idx', $idx)->pluck('up_name')->filter()->all();
    		$pcThumbPath = $portfolio->pc_up_name;
    		$foThumbPath = $portfolio->fo_up_name;
    		$moThumbPath = $portfolio->mo_up_name;
    		
    		DB::beginTransaction();
    		
    		PortfolioFiles::where('portfolio_idx', $idx)->delete();
    		$portfolio->delete();
    		
    		DB::commit();
    		
    		if (!empty($pcThumbPath)) {
    			try {
    				Storage::disk('public')->delete($pcThumbPath);
    			} catch (\Exception $fe) {
    				\Log::warning('Portfolio pcThumb file delete failed', [
    						'portfolio_idx' => $idx,
    						'paths'         => $pcThumbPath,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		if (!empty($foThumbPath)) {
    			try {
    				Storage::disk('public')->delete($foThumbPath);
    			} catch (\Exception $fe) {
    				\Log::warning('Portfolio foThumb file delete failed', [
    						'portfolio_idx' => $idx,
    						'paths'         => $foThumbPath,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		if (!empty($moThumbPath)) {
    			try {
    				Storage::disk('public')->delete($moThumbPath);
    			} catch (\Exception $fe) {
    				\Log::warning('Portfolio moThumb file delete failed', [
    						'portfolio_idx' => $idx,
    						'paths'         => $moThumbPath,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		if (!empty($portfolioFilePaths)) {
    			try {
    				Storage::disk('public')->delete($portfolioFilePaths);
    			} catch (\Exception $fe) {
    				\Log::warning('Portfolio file delete failed', [
    						'portfolio_idx' => $idx,
    						'paths'         => $portfolioFilePaths,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		if (DB::transactionLevel() > 0) {
    			DB::rollBack();
    		}
    		return response()->json(['status'  => 'error','message' => $e->getMessage(),], 500);
    	}
    }


	

    //================================== 파트너 ==================================
    // 파트너 목록
    public function getPartnerList(Request $request)
    {
		if (!auth()->user()->hasPerm('partner.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Partner::with('types','forms','cities','districts','head','ripsPartner:idx,rips_id')->partnersOnly();

    	// 검색 조건 처리
    	if ($request->filled('country')) {
    		$query->where('partner.country', $request->country);
    	}

    	if ($request->filled('branch_type')) {
    		$query->where('partner.branch_type', $request->branch_type);
    	}
    	
    	if ($request->filled('types_idx')) {
    		$query->where('partner.types_idx', $request->types_idx);
    	}
    	
    	if ($request->filled('forms_idx')) {
    		$query->where('partner.forms_idx', $request->forms_idx);
    	}
    	
    	if ($request->filled('home_recommend')) {
    		$query->where('partner.home_recommend', $request->home_recommend);
    	}
    	
    	if ($request->filled('partner_recommend')) {
    		$query->where('partner.partner_recommend', $request->partner_recommend);
    	}
    	
    	if ($request->filled('cities_idx')) {
    		$query->where('partner.cities_idx', $request->cities_idx);
    	}

    	if ($request->filled('districts_idx')) {
    		$query->where('partner.districts_idx', $request->districts_idx);
    	}

    	if ($request->filled('partner_name')) {
    		$query->where('partner.partner_name', 'like', '%' . $request->partner_name . '%');
    	}
    	
    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	$data->getCollection()->transform(function ($item) {
    		// 추천 플래그 1/0 → Y / N
    		$toYN = function ($v) {
    			return ((string)$v === '1') ? 'Y' : 'N';
    		};
    		
    		$item->home_recommend_text       = $toYN($item->home_recommend);
    		$item->partner_recommend_text  = $toYN($item->partner_recommend);
    		
    		$item->created_date = optional($item->created_at)->format('Y-m-d');
    		
    		return $item;
    	});
    	
    	$types = Types::orderBy('idx')->get();
		$forms = FormTypes::top()->get();
    	$cities = Cities::orderBy('idx')->get();
		$districts = Districts::orderBy('idx')->get();
		// 지점 등록 시 선택할 본점 목록
		$heads = Partner::partnersOnly()->where('branch_type', 'head')->where('country', 'vietnam')->orderBy('partner_name')->get(['idx', 'partner_name', 'country']);

    	$ripsAccounts = $this->ripsAccountOptions();

    	return view('admin.partner', compact('data','types','forms','cities','districts','heads','ripsAccounts'));
    }
    
	// 파트너 엑셀 다운로드
    public function getPartnerExport(Request $request)
    {
		if (!auth()->user()->hasPerm('partner.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$filters = $request->only(['country', 'branch_type', 'types_idx', 'forms_idx', 'home_recommend', 'partner_recommend', 'cities_idx', 'districts_idx', 'partner_name']);
    	
    	$timestamp = Carbon::now()->format('Ymd_His');
    	$filename = '파트너목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new partnerExport($filters), $filename);
    }
    
    // 파트너 등록
    public function setPartnerRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('partner.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	[$branch, $branchError] = $this->partnerBranchData($request);
    	if ($branchError) return response()->json(['status' => 'error', 'message' => $branchError]);

    	[$rips, $ripsError] = $this->partnerRipsData($request);
    	if ($ripsError) return response()->json(['status' => 'error', 'message' => $ripsError]);
    	$rips += $branch;

    	try {
    		$admin = Session::get('admin');

    		$Partner = Partner::create($rips + [
    				'country'				=> $request->input('country'),
    				'types_idx'				=> $request->input('types_idx'),
					'forms_idx'				=> $request->input('forms_idx'),
    				'cities_idx'			=> $request->input('cities_idx'),
    				'districts_idx'			=> $request->input('districts_idx'),
					'channel'				=> $request->input('channel'),
					'channel_en'			=> $request->input('channel_en'),
					'channel_vn'			=> $request->input('channel_vn'),
					'logistic'				=> $request->input('logistic'),
    				'partner_name'			=> $request->input('partner_name'),
    				'partner_name_en'		=> $request->input('partner_name_en'),
    				'partner_name_vn'		=> $request->input('partner_name_vn'),
					'contents'				=> $request->input('contents'),
					'contents_en'			=> $request->input('contents_en'),
					'contents_vn'			=> $request->input('contents_vn'),
    				'home_recommend'		=> $request->input('home_recommend'),
    				'partner_recommend'		=> $request->input('partner_recommend'),
    				'created_by'			=> $admin['name'] ?? ''
    		]);
    		
    		$Partner = Partner::findOrFail($Partner->idx);
    		
    		if ($request->hasFile('pcThumb')) {
    			$file = $request->file('pcThumb');
    			$up_name = $file->store('partnerThumb', 'public');
    			$Partner->update([
    					'pc_ori_name'    	=> $file->getClientOriginalName(),
    					'pc_up_name'     	=> $up_name,
    					'pc_file_ext'    	=> $file->getClientOriginalExtension(),
    					'pc_file_size'   	=> $file->getSize(),
    			]);
    		}
    		
    		if ($request->hasFile('foThumb')) {
    			$file = $request->file('foThumb');
    			$up_name = $file->store('partnerThumb', 'public');
    			$Partner->update([
    					'fo_ori_name'    	=> $file->getClientOriginalName(),
    					'fo_up_name'     	=> $up_name,
    					'fo_file_ext'    	=> $file->getClientOriginalExtension(),
    					'fo_file_size'   	=> $file->getSize(),
    			]);
    		}
    		
    		if ($request->hasFile('moThumb')) {
    			$file = $request->file('moThumb');
    			$up_name = $file->store('partnerThumb', 'public');
    			$Partner->update([
    					'mo_ori_name'    	=> $file->getClientOriginalName(),
    					'mo_up_name'     	=> $up_name,
    					'mo_file_ext'    	=> $file->getClientOriginalExtension(),
    					'mo_file_size'   	=> $file->getSize(),
    			]);
    		}
    		
    		if ($request->hasFile('partnerFiles')) {
    			$orderby = 1;
    			foreach ($request->file('partnerFiles') as $file) {
    				// 파일 저장
    				$up_name = $file->store('partner', 'public');
    				PartnerFiles::create([
    						'partner_idx' => $Partner->idx,
    						'ori_name'    	=> $file->getClientOriginalName(),
    						'up_name'     	=> $up_name,
    						'file_ext'    	=> $file->getClientOriginalExtension(),
    						'file_size'   	=> $file->getSize(),
    						'orderby' 		=> $orderby++,
    				]);
    			}
    		}
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }

    // 파트너 수정 폼
    public function getPartnerDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('partner.update') && !auth()->user()->hasPerm('partner.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data = $idx ? Partner::findOrFail($idx) : null;

    	$files = collect();
    	if ($files && $data->idx) {
    		$files = PartnerFiles::where('partner_idx', $data->idx)->orderByRaw('orderby')->get();
    	}

    	return response()->json(['status' => 'success', 'data' => $data->toArray() + ['has_rips_pw' => !empty($data->rips_pw)], 'files' => $files]);
    }

    // 파트너 본점/지점 입력값 (지점은 등록된 본점을 선택)
    private function partnerBranchData(Request $request, $partner = null)
    {
    	// 본점/지점은 vietnam 만 사용 (그 외 국가는 본점)
    	$isVietnam  = strtolower((string) $request->input('country')) === 'vietnam';
    	$branchType = $isVietnam && $request->input('branch_type') === 'branch' ? 'branch' : 'head';

    	if ($branchType === 'head') {
    		return [['branch_type' => 'head', 'head_idx' => null], null];
    	}

    	if ($partner && Partner::where('head_idx', $partner->idx)->exists()) {
    		return [null, '소속 지점이 있는 본점은 지점으로 변경할 수 없습니다.'];
    	}

    	$headIdx = (int) $request->input('head_idx');
    	if (!$headIdx) return [null, '본점을 선택해주세요.'];
    	if ($partner && $headIdx === (int) $partner->idx) return [null, '자기 자신을 본점으로 선택할 수 없습니다.'];
    	if (!Partner::where('branch_type', 'head')->where('country', 'vietnam')->whereKey($headIdx)->exists()) return [null, '선택한 본점을 찾을 수 없습니다.'];

    	return [['branch_type' => 'branch', 'head_idx' => $headIdx], null];
    }

    //================================== RIPS 관리 ==================================
    // RIPS 업체 목록 (RIPS 계정이 있는 파트너, 카테고리/상품 매핑 현황)
    public function getRipsPartnerList(Request $request)
    {
		if (!auth()->user()->hasPerm('rips.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Partner::with('head')
    	->whereNotNull('rips_id')->where('rips_id', '!=', '')
    	->withCount([
    			'ripsCategories',
    			'ripsCategories as rips_categories_mapped_count' => fn ($q) => $q->whereNotNull('forms_idx'),
    			'ripsProducts',
    			'ripsProducts as rips_products_mapped_count'     => fn ($q) => $q->whereNotNull('product_idx'),
    			'ripsProducts as rips_products_custom_count'     => fn ($q) => $q->where('price_type', 'custom')->whereNotNull('custom_price'),
    	]);

    	if ($request->filled('rips_use')) {
    		$query->where('rips_use', $request->rips_use);
    	}

    	if ($request->filled('keyword')) {
    		$query->where(function ($q) use ($request) {
    			$q->where('partner_name', 'like', '%' . $request->keyword . '%')
    			  ->orWhere('rips_id', 'like', '%' . $request->keyword . '%');
    		});
    	}

    	$data = $query->orderByDesc('rips_use')->orderBy('partner_name')->paginate(20)->withQueryString();
    	$ids  = collect($data->items())->pluck('idx');

    	// 이 RIPS 업체를 연결한 파트너 (사업관리 > 파트너 > RIPS 업체 설정)
    	$mappedPartners = Partner::whereIn('rips_partner_idx', $ids)->get(['idx', 'partner_name', 'rips_partner_idx'])->keyBy('rips_partner_idx');

    	// 상품 관리 : 업체별 재고소진 / 즉시발주 / 발주필요 상품 수
    	$products = RipsProduct::whereIn('partner_idx', $ids)->get();
    	RipsForecast::apply($products);
    	$stockCounts = $products->groupBy('partner_idx')->map(fn ($rows) => $rows->map(fn ($p) => RipsForecast::alert($p)['type'] ?? null)->filter()->countBy());

    	// 발주 관리 : 업체별 진행 상태 건수 (취소 제외)
    	$orderCounts = RipsOrder::whereIn('partner_idx', $ids)->where('status', '!=', 'canceled')
    	->groupBy('partner_idx', 'status')->selectRaw('partner_idx, status, COUNT(*) AS cnt')->get()
    	->groupBy('partner_idx')->map(fn ($rows) => $rows->pluck('cnt', 'status'));

    	return view('admin.ripsPartner', compact('data', 'stockCounts', 'orderCounts', 'mappedPartners'));
    }

    /**
     * RIPS 업체 상세
     * tab - category : 업체 카테고리 ↔ 리치포트 카테고리, product : 업체 상품 ↔ 리치포트 상품 + 발주금액
     *       stock : 상품 현황 (RIPS > 상품), sales : 판매 현황 (RIPS > 판매관리), orders : 발주 현황 (RIPS > 발주관리) - 조회만
     */
    public function getPartnerRips(Request $request, $idx, $tab = 'category')
    {
    	$partner = Partner::with(['head', 'cities', 'districts'])->findOrFail($idx);
    	$tab     = in_array($tab, ['info', 'category', 'product', 'stock', 'sales', 'orders']) ? $tab : 'category';
    	$view    = compact('partner', 'tab');

    	// 업체 정보 (RIPS 업체 등록과 같은 항목)
    	if ($tab === 'info') {
    		$view['ripsTypes'] = RipsInquireTypes::orderBy('idx')->get(['idx', 'subject']);
    		$view['cities']    = Cities::orderBy('idx')->get(['idx', 'name_kr']);
    		return view('admin.partnerRips', $view);
    	}

    	if (in_array($tab, ['category', 'product'])) {
    		// 리치포트 카테고리 1~4단계 전체 (업체 카테고리는 단계와 무관하게 어느 단계에든 매핑)
    		$view['forms'] = FormTypes::orderBy('depth')->orderBy('orderby')->orderBy('idx')->get(['idx', 'parent_idx', 'depth', 'subject']);
    		return view('admin.partnerRips', $view);
    	}

    	$view['categoryMap'] = RipsCategory::pathMap($partner->idx);

    	if ($tab === 'stock') {
    		[$view['data'], $view['counts']] = RipsForecast::productList($partner->idx, $request->only(['category_idx', 'keyword', 'status']), 20);
    	} elseif ($tab === 'sales') {
    		[$view['data'], $view['summary']] = RipsSales::listFor($partner->idx, $request->all(), 20);
    	} else {
    		$view['data'] = RipsOrder::listFor($partner->idx, $request->all(), 10);
    	}

    	return view('admin.partnerRips', $view);
    }

    //================================== RIPS 업체 등록 / 업체 정보 ==================================
    // RIPS 업체 등록 화면 (RIPS 이용신청 내용을 참고해 관리자가 직접 등록)
    public function getRipsPartnerCreate(Request $request)
    {
		if (!auth()->user()->hasPerm('rips.create')) return redirect('/admin/ripsPartner')->with('error', '권한이 없습니다.');

    	$ripsTypes = RipsInquireTypes::orderBy('idx')->get(['idx', 'subject']);
    	$cities    = Cities::orderBy('idx')->get(['idx', 'name_kr']);

    	return view('admin.ripsPartnerCreate', compact('ripsTypes', 'cities'));
    }

    /**
     * RIPS 업체 입력값 (등록 / 수정 공통, RIPS > 설정 > 내 정보 수정 과 같은 항목)
     * - 회사정보 : 업체구분*, 국가*, 회사명*, 매장명, 지역(베트남 업체 필수), 주소, 대표전화, 대표자명, 통관고유번호, 사업자번호
     * - 담당자정보 : 담당자명*, 담당자 연락처*, 담당자 이메일*, 담당업무
     * - 계정정보 : RIPS 사용, 아이디*, 비밀번호*(등록 시) + 비밀번호 확인
     * @return array [저장할 값, 오류메시지]
     */
    private function ripsPartnerInput(Request $request, $partner = null)
    {
    	$str = fn ($key, $max) => mb_substr(trim((string) $request->input($key)), 0, $max);

    	$ripsTypeIdx = (int) $request->input('rips_type_idx');
    	$country     = $request->input('country');
    	$cityIdx     = (int) $request->input('cities_idx');
    	$districtIdx = (int) $request->input('districts_idx');
    	$ripsId      = $str('rips_id', 50);
    	$ripsPw      = (string) $request->input('rips_pw');

    	$data = [
    			'rips_type_idx'   => $ripsTypeIdx,
    			'country'         => $country,
    			'partner_name'    => $str('partner_name', 200),
    			'store_name'      => $str('store_name', 200),
    			'company_address' => $str('company_address', 255),
    			'company_tel'     => $str('company_tel', 50),
    			'ceo_name'        => $str('ceo_name', 100),
    			'customs_code'    => $str('customs_code', 50),
    			'biz_no'          => $str('biz_no', 50),
    			'manager_name'    => $str('manager_name', 100),
    			'manager_hp'      => $str('manager_hp', 50),
    			'manager_email'   => $str('manager_email', 200),
    			'manager_task'    => $str('manager_task', 200),
    			'rips_use'        => $request->input('rips_use') == 1 ? 1 : 0,
    			'rips_id'         => $ripsId,
    	];

    	if (!RipsInquireTypes::whereKey($ripsTypeIdx)->exists()) return [null, '업체구분을 선택해주세요.'];
    	if (!in_array($country, ['korea', 'vietnam'])) return [null, '국가를 선택해주세요.'];
    	if ($data['partner_name'] === '') return [null, '회사명을 입력해주세요.'];

    	// 지역 : 베트남 업체는 필수 (발주서 표시), 시에 속한 구인지 확인
    	if ($cityIdx && !Cities::whereKey($cityIdx)->exists()) $cityIdx = 0;
    	if ($districtIdx && !Districts::where('cities_idx', $cityIdx)->whereKey($districtIdx)->exists()) $districtIdx = 0;
    	if ($country === 'vietnam' && (!$cityIdx || !$districtIdx)) return [null, '지역(시 / 구)을 선택해주세요.'];
    	$data['cities_idx']    = $cityIdx ?: null;
    	$data['districts_idx'] = $districtIdx ?: null;

    	foreach (['company_tel' => '회사 대표전화', 'manager_hp' => '담당자 연락처'] as $key => $label) {
    		if ($data[$key] !== '' && !preg_match('/^[0-9+\-\s()]{6,50}$/', $data[$key])) return [null, $label.'는 숫자와 +, -, 괄호만 입력할 수 있습니다.'];
    	}
    	foreach (['customs_code' => '통관고유번호', 'biz_no' => '사업자번호'] as $key => $label) {
    		if ($data[$key] !== '' && !preg_match('/^[0-9A-Za-z\-]{4,50}$/', $data[$key])) return [null, $label.'는 영문, 숫자, 하이픈(-) 4~50자로 입력해주세요.'];
    	}

    	if ($data['manager_name'] === '')  return [null, '담당자명을 입력해주세요.'];
    	if ($data['manager_hp'] === '')    return [null, '담당자 연락처를 입력해주세요.'];
    	if ($data['manager_email'] === '' || !filter_var($data['manager_email'], FILTER_VALIDATE_EMAIL)) return [null, '담당자 이메일을 올바르게 입력해주세요.'];

    	// 계정
    	if (!preg_match('/^[A-Za-z0-9_\-]{4,50}$/', $ripsId)) return [null, 'RIPS 아이디는 영문, 숫자, -, _ 4~50자로 입력해주세요.'];
    	if (in_array(strtolower($ripsId), ['login', 'logout'])) return [null, '사용할 수 없는 RIPS 아이디입니다.'];
    	if (Partner::where('rips_id', $ripsId)->when($partner, fn ($q) => $q->where('idx', '!=', $partner->idx))->exists()) return [null, '이미 사용 중인 RIPS 아이디입니다.'];

    	if (!$partner || $ripsPw !== '') {
    		if (strlen($ripsPw) < 6) return [null, 'RIPS 비밀번호는 6자 이상 입력해주세요.'];
    		if ($ripsPw !== (string) $request->input('rips_pw_confirmation')) return [null, '비밀번호와 비밀번호 확인이 일치하지 않습니다.'];
    		$data['rips_pw'] = Hash::make($ripsPw);
    	}

    	return [array_map(fn ($v) => $v === '' ? null : $v, $data), null];
    }

    // 업체 사진 (jpg, png, webp, 5MB 이하) - [저장할 값, 오류메시지, 지울 이전 파일]
    private function ripsPartnerPhoto(Request $request, $partner = null)
    {
    	if ($request->hasFile('photo')) {
    		$file = $request->file('photo');
    		if (!$file->isValid() || !in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp']) || !str_starts_with((string) $file->getMimeType(), 'image/')) {
    			return [[], '업체 사진은 jpg, png, webp 이미지만 올릴 수 있습니다.', null];
    		}
    		if ($file->getSize() > 5 * 1024 * 1024) return [[], '업체 사진은 5MB 이하로 올려주세요.', null];

    		return [[
    				'photo_ori_name' => mb_substr($file->getClientOriginalName(), 0, 255),
    				'photo_up_name'  => $file->store('partnerPhoto', 'public'),
    		], null, $partner?->photo_up_name];
    	}
    	if ($partner && $request->input('remove_photo') == 1) {
    		return [['photo_ori_name' => null, 'photo_up_name' => null], null, $partner->photo_up_name];
    	}
    	return [[], null, null];
    }

    // RIPS 업체 등록
    public function setRipsPartnerRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('rips.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	[$data, $error] = $this->ripsPartnerInput($request);
    	if ($error) return response()->json(['status' => 'error', 'message' => $error]);

    	[$photo, $photoError] = $this->ripsPartnerPhoto($request);
    	if ($photoError) return response()->json(['status' => 'error', 'message' => $photoError]);

    	$admin   = Session::get('admin');
    	$partner = Partner::create($data + $photo + [
    			'partner_name_en'   => $data['partner_name'],
    			'partner_name_vn'   => $data['partner_name'],
    			'branch_type'       => 'head',
    			'rips_only'         => 1,		// 사업관리 > 파트너 목록 · 선택에서 제외
    			'home_recommend'    => 0,		// 홈페이지 미노출
    			'partner_recommend' => 0,
    			'created_at'        => now(),
    			'created_by'        => $admin['name'] ?? auth()->user()?->name,
    	]);

    	return response()->json(['status' => 'success', 'idx' => $partner->idx, 'redirect' => route('admin.ripsPartner.info', ['idx' => $partner->idx])]);
    }

    // RIPS 업체 정보 수정 (비밀번호는 입력했을 때만 변경)
    public function setRipsPartnerModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('rips.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$partner = Partner::findOrFail($idx);

    	[$data, $error] = $this->ripsPartnerInput($request, $partner);
    	if ($error) return response()->json(['status' => 'error', 'message' => $error]);

    	[$photo, $photoError, $oldPhoto] = $this->ripsPartnerPhoto($request, $partner);
    	if ($photoError) return response()->json(['status' => 'error', 'message' => $photoError]);

    	$admin = Session::get('admin');
    	$partner->update($data + $photo + ['updated_at' => now(), 'updated_by' => $admin['name'] ?? auth()->user()?->name]);
    	if ($oldPhoto) Storage::disk('public')->delete($oldPhoto);

    	return response()->json(['status' => 'success']);
    }

    // 매핑 화면 데이터 (업체 카테고리/상품, 리치포트 상품)
    public function getPartnerRipsData(Request $request, $idx)
    {
    	$partner = Partner::findOrFail($idx);

    	$categoryMap = RipsCategory::pathMap($partner->idx);
    	$categories  = RipsCategory::where('partner_idx', $partner->idx)->get()->keyBy('idx');
    	$categories  = collect($categoryMap)->map(fn ($path, $cidx) => [
    			'idx'        => $cidx,
    			'parent_idx' => $categories[$cidx]->parent_idx,
    			'depth'      => $categories[$cidx]->depth,
    			'name'       => $categories[$cidx]->name,
    			'path'       => $path,
    			'forms_idx'  => $categories[$cidx]->forms_idx,
    	])->values();

    	$products = RipsProduct::with('richportProduct')->where('partner_idx', $partner->idx)->orderBy('product_name')->get()
    	->map(function ($item) {
    		[$price, $priceFrom] = $item->orderPrice();
    		return [
    				'idx'           => $item->idx,
    				'category_idx'  => $item->category_idx,
    				'company_name'  => $item->company_name,
    				'product_name'  => $item->product_name,
    				'unit_price'    => (int) $item->unit_price,
    				'product_idx'   => $item->product_idx,
    				'price_type'    => $item->price_type,
    				'custom_price'  => $item->custom_price,
    				'order_price'   => $price,
    				'price_from'    => $priceFrom,
    		];
    	});

    	$richportProducts = Product::with('partners:idx,partner_name')->orderBy('product_name')
    	->get(['idx', 'partner_idx', 'forms_idx', 'product_name', 'order_price'])
    	->map(fn ($item) => [
    			'idx'          => $item->idx,
    			'forms_idx'    => $item->forms_idx,
    			'product_name' => $item->product_name,
    			'partner_name' => $item->partners->partner_name ?? '',
    			'order_price'  => $item->order_price,
    	]);

    	return response()->json(['status' => 'success', 'categories' => $categories, 'products' => $products, 'richportProducts' => $richportProducts]);
    }

    // 업체 카테고리 → 리치포트 카테고리 매핑
    public function setPartnerRipsCategory(Request $request, $idx, $cidx)
    {
		if (!auth()->user()->hasPerm('rips.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$category = RipsCategory::where('partner_idx', $idx)->findOrFail($cidx);

    	$formsIdx = $request->filled('forms_idx') ? (int) $request->input('forms_idx') : null;
    	if ($formsIdx && !FormTypes::whereKey($formsIdx)->exists()) return response()->json(['status' => 'error', 'message' => '리치포트 카테고리를 찾을 수 없습니다.']);

    	$category->update(['forms_idx' => $formsIdx]);

    	return response()->json(['status' => 'success']);
    }

    // 업체 상품 → 리치포트 상품 매핑 / 발주금액 (기본 또는 개별)
    public function setPartnerRipsProduct(Request $request, $idx, $pidx)
    {
		if (!auth()->user()->hasPerm('rips.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$product = RipsProduct::where('partner_idx', $idx)->findOrFail($pidx);

    	$productIdx = $request->filled('product_idx') ? (int) $request->input('product_idx') : null;
    	if ($productIdx && !Product::whereKey($productIdx)->exists()) return response()->json(['status' => 'error', 'message' => '리치포트 상품을 찾을 수 없습니다.']);

    	// 개별금액을 선택했더라도 금액을 비우면 기본금액 적용
    	$priceType   = $request->input('price_type') === 'custom' ? 'custom' : 'default';
    	$customPrice = $priceType === 'custom' && $request->filled('custom_price') ? max(0, (int) $request->input('custom_price')) : null;
    	if ($customPrice === null) $priceType = 'default';

    	$admin = Session::get('admin');
    	$product->update([
    			'product_idx'  => $productIdx,
    			'price_type'   => $priceType,
    			'custom_price' => $customPrice,
    			'updated_by'   => $admin['name'] ?? '',
    	]);

    	return response()->json(['status' => 'success']);
    }

    //================================== RIPS 발주 관리 ==================================
    // 진행상태 : 발주접수 → 상품준비중 → 물류진행중 → 통관진행중 → 입고완료 (발주취소는 RIPS 업체만)
    // 리치포트 발주 정보 : 발주 상품별 공급 업체 / 발주일 / 수량 / 금액 (rips_order_item.supplier_idx ...)

    // 리치포트 발주 업체 : 저장값, 없으면 매핑된 리치포트 상품의 파트너
    private function ripsOrderSupplierSql()
    {
    	return 'EXISTS (SELECT 1 FROM rips_order_item oi
    			LEFT JOIN rips_product rp ON rp.idx = oi.product_idx
    			LEFT JOIN product p ON p.idx = rp.product_idx
    			WHERE oi.order_idx = rips_order.idx AND COALESCE(oi.supplier_idx, p.partner_idx) = ?)';
    }

    // 발주 목록
    public function getRipsOrderList(Request $request)
    {
		if (!auth()->user()->hasPerm('ripsOrder.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = RipsOrder::with(['partner:idx,partner_name,store_name', 'items.supplier:idx,partner_name', 'items.product.richportProduct.partners:idx,partner_name']);

    	if ($request->filled('partner_idx'))  $query->where('partner_idx', $request->partner_idx);
    	if ($request->filled('order_from'))   $query->whereDate('order_date', '>=', $request->order_from);
    	if ($request->filled('order_to'))     $query->whereDate('order_date', '<=', $request->order_to);
    	if ($request->filled('recv_from'))    $query->where('received_date', '>=', $request->recv_from);
    	if ($request->filled('recv_to'))      $query->where('received_date', '<=', $request->recv_to);
    	if ($request->filled('supplier_idx')) $query->whereRaw($this->ripsOrderSupplierSql(), [(int) $request->supplier_idx]);
    	if ($request->filled('keyword')) {
    		$keyword = '%' . $request->keyword . '%';
    		$query->where(function ($q) use ($keyword) {
    			$q->where('order_no', 'like', $keyword)
    			  ->orWhereHas('items', fn ($i) => $i->where('product_name', 'like', $keyword));
    		});
    	}

    	// 상태별 건수 (상태 조건 제외한 나머지 조건 기준)
    	$statusCounts = (clone $query)->setEagerLoads([])->reorder()->selectRaw('status, COUNT(*) cnt')->groupBy('status')->pluck('cnt', 'status');

    	if ($request->filled('status')) $query->where('status', $request->status);

    	$data = $query->orderByDesc('order_date')->orderByDesc('idx')->paginate(20)->withQueryString();

    	$data->getCollection()->transform(function ($order) {
    		$first = $order->items->first();
    		$order->product_summary = $first ? $first->product_name . ($order->items->count() > 1 ? ' 외 ' . ($order->items->count() - 1) . '건' : '') : '-';

    		// 실제 발주금액 : 리치포트 발주 금액이 입력된 상품만 합계
    		$entered = $order->items->whereNotNull('purchase_amount');
    		$order->purchase_total   = $entered->count() ? $entered->sum('purchase_amount') : null;
    		$order->purchase_entered = $entered->count();

    		// 리치포트 발주 업체 (저장값 / 저장 전이면 매핑 상품 기준 예정)
    		$order->suppliers = $order->items->map(function ($item) {
    			if ($item->supplier) return ['name' => $item->supplier->partner_name, 'planned' => false];
    			$name = $item->product?->richportProduct?->partners?->partner_name;
    			return $name ? ['name' => $name, 'planned' => true] : null;
    		})->filter()->unique('name')->values();

    		return $order;
    	});

    	$ripsPartners = Partner::whereNotNull('rips_id')->where('rips_id', '!=', '')->orderBy('partner_name')->get(['idx', 'partner_name', 'store_name']);
    	$suppliers    = $this->ripsSuppliers();

    	return view('admin.ripsOrder', compact('data', 'statusCounts', 'ripsPartners', 'suppliers'));
    }

    //================================== RIPS 관리 > AI 연동 ==================================
    // AI 연동 설정 · 현황 (인증키, 예측값 사용 방식, 업체별 예측 수신 현황, 최근 요청 로그)
    public function getAiApi(Request $request)
    {
    	$setting = AiApiSetting::query()->orderBy('idx')->first() ?? AiApiSetting::create(['api_use' => 0, 'forecast_mode' => 'auto', 'valid_days' => 3]);

    	// 업체별 최신 예측 (기준일, 상품 수, 모델)
    	$forecasts = DB::table('rips_forecast AS f')->join('partner AS p', 'p.idx', '=', 'f.partner_idx')
    	->whereRaw('f.base_date = (SELECT MAX(x.base_date) FROM rips_forecast x WHERE x.partner_idx = f.partner_idx)')
    	->groupBy('f.partner_idx')
    	->selectRaw('f.partner_idx, MAX(p.partner_name) AS partner_name, MAX(p.store_name) AS store_name, MAX(f.base_date) AS base_date, COUNT(*) AS products, MAX(f.model_version) AS model_version, MAX(COALESCE(f.updated_at, f.created_at)) AS received_at')
    	->orderBy('partner_name')->get();
    	$totalProducts = RipsProduct::whereIn('partner_idx', Partner::ripsAccounts()->where('rips_use', 1)->select('idx'))->count();

    	$logs = AiApiLog::orderByDesc('idx')->limit(30)->get();

    	return view('admin.aiApi', compact('setting', 'forecasts', 'totalProducts', 'logs'));
    }

    // 설정 저장 (사용 시 인증키가 없으면 발급)
    public function setAiApiSave(Request $request)
    {
		if (!auth()->user()->hasPerm('aiApi.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$mode = (string) $request->input('forecast_mode');
    	if (!array_key_exists($mode, AiApiSetting::MODES)) return response()->json(['status' => 'error', 'message' => '예측값 사용 방식을 선택해주세요.']);

    	$days = (int) $request->input('valid_days');
    	if ($days < 0 || $days > 30) return response()->json(['status' => 'error', 'message' => '유효기간은 0~30일로 입력해주세요.']);

    	$ips = collect(preg_split('/[\s,]+/', (string) $request->input('allowed_ips')))->filter()->values();
    	foreach ($ips as $ip) {
    		if (!filter_var($ip, FILTER_VALIDATE_IP)) return response()->json(['status' => 'error', 'message' => '허용 IP 형식이 올바르지 않습니다. (' . $ip . ')']);
    	}

    	$setting = AiApiSetting::query()->orderBy('idx')->firstOrFail();
    	$data = [
    			'api_use'       => $request->input('api_use') == 1 ? 1 : 0,
    			'forecast_mode' => $mode,
    			'valid_days'    => $days,
    			'allowed_ips'   => $ips->implode(',') ?: null,
    			'updated_by'    => Session::get('admin')['name'] ?? auth()->user()?->name,
    	];
    	if ($data['api_use'] && !$setting->api_key) $data['api_key'] = Str::random(48);
    	$setting->update($data);
    	AiApiSetting::forget();

    	return response()->json(['status' => 'success']);
    }

    // 인증키 재발급 (이전 키는 바로 사용 불가)
    public function setAiApiRegenerate(Request $request)
    {
		if (!auth()->user()->hasPerm('aiApi.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$setting = AiApiSetting::query()->orderBy('idx')->firstOrFail();
    	$setting->update(['api_key' => Str::random(48), 'updated_by' => Session::get('admin')['name'] ?? auth()->user()?->name]);
    	AiApiSetting::forget();

    	return response()->json(['status' => 'success']);
    }

    //================================== RIPS 판매 관리 ==================================
    // 판매 데이터를 볼 수 있는 업체 : 베트남 파트너
    private function ripsSalesPartners()
    {
    	return Partner::ripsAccounts()->where('country', 'vietnam')->orderBy('partner_name')->orderBy('store_name')->get(['idx', 'partner_name', 'store_name']);
    }

    // 판매 데이터 기본 쿼리 (업체 상품 조인)
    private function ripsSalesBase()
    {
    	return DB::table('rips_sales AS s')->join('rips_product AS rp', 'rp.idx', '=', 's.product_idx');
    }

    /**
     * 일자 · 업체 · 업체 상품별 합계 (같은 날 건별 데이터가 여러 건이어도 1행)
     * 잔여수량은 그날 마지막으로 입력된 값, 프로모션 / 판매매대는 그날 입력된 값 모두
     */
    private function ripsSalesDayRows($query)
    {
    	return $query->join('partner AS pt', 'pt.idx', '=', 's.partner_idx')
    	->groupBy('s.sales_date', 's.partner_idx', 's.product_idx')
    	->selectRaw("s.sales_date, s.partner_idx, s.product_idx,
    		MAX(pt.partner_name) AS partner_name, MAX(pt.store_name) AS store_name,
    		MAX(rp.category_idx) AS category_idx, MAX(rp.product_name) AS product_name, MAX(rp.company_name) AS company_name,
    		SUM(s.qty) AS qty, SUM(s.total_amount) AS amount,
    		SUBSTRING_INDEX(GROUP_CONCAT(s.stock_qty ORDER BY s.sales_time DESC, s.idx DESC SEPARATOR ','), ',', 1) AS stock_qty,
    		GROUP_CONCAT(DISTINCT CASE WHEN s.promotion <> 'N' THEN CONCAT(s.promotion, '|', IFNULL(s.discount_rate, '')) END SEPARATOR ',') AS promos,
    		GROUP_CONCAT(DISTINCT NULLIF(s.display_place, '') SEPARATOR ',') AS places");
    }

    // 목록 행 표시값 (프로모션 / 판매매대 / 발주 필요) 정리
    private function ripsSalesDecorate($rows)
    {
    	$products = RipsProduct::whereIn('idx', collect($rows->items())->pluck('product_idx')->unique())->get()->keyBy('idx');
    	RipsForecast::apply($products->values());

    	$rows->getCollection()->transform(function ($row) use ($products) {
    		$promos = collect(explode(',', (string) $row->promos))->filter()->map(function ($promo) {
    			[$type, $rate] = array_pad(explode('|', $promo), 2, '');
    			return $type === 'discount' ? '할인' . ($rate !== '' ? ' ' . rtrim(rtrim($rate, '0'), '.') . '%' : '') : $type;
    		})->values();

    		$row->promotion_yn   = $promos->isNotEmpty() ? 'Y' : 'N';
    		$row->promotion_text = $promos->implode(', ');
    		$row->display_text   = collect(explode(',', (string) $row->places))->filter()->map(fn ($p) => RipsSales::DISPLAY_PLACES[$p] ?? $p)->implode(', ');
    		$row->stock_qty      = $row->stock_qty === null ? null : (int) $row->stock_qty;

    		// 발주 필요 (현재 기준 재고소진 계산)
    		$product = $products[$row->product_idx] ?? null;
    		$row->alert         = $product ? RipsForecast::alert($product) : null;
    		$row->recommend_qty = $product?->recommend_qty;
    		return $row;
    	});

    	return $rows;
    }

    // 조회 년월 (YYYY-MM, 없으면 이번 달)
    private function ripsSalesMonth(Request $request)
    {
    	$ym = preg_match('/^\d{4}-\d{2}$/', (string) $request->ym) ? $request->ym : Carbon::today()->format('Y-m');
    	return Carbon::createFromFormat('Y-m-d', $ym . '-01')->startOfDay();
    }

    // 판매 관리 > 월별 : 리치포트 상품별 합계
    public function getRipsSalesMonthly(Request $request)
    {
    	$month    = $this->ripsSalesMonth($request);
    	$formTree = FormTypes::tree();

    	$query = $this->ripsSalesBase()->join('product AS p', 'p.idx', '=', 'rp.product_idx')
    	->whereBetween('s.sales_date', [$month->toDateString(), $month->copy()->endOfMonth()->toDateString()])
    	->when($request->filled('partner_idx'), fn ($q) => $q->where('s.partner_idx', $request->partner_idx))
    	->when($request->filled('forms_idx'), fn ($q) => $q->whereIn('p.forms_idx', FormTypes::descendantIds($request->forms_idx, $formTree)))
    	->when($request->filled('product_name'), fn ($q) => $q->where('p.product_name', 'like', '%' . $request->product_name . '%'));

    	$summary = (clone $query)->selectRaw('COALESCE(SUM(s.qty),0) AS qty, COALESCE(SUM(s.total_amount),0) AS amount, COUNT(DISTINCT rp.product_idx) AS products')->first();

    	$data = $query->groupBy('p.idx')
    	->selectRaw('p.idx, MAX(p.product_name) AS product_name, MAX(p.forms_idx) AS forms_idx,
    		COUNT(DISTINCT CASE WHEN s.qty > 0 THEN s.partner_idx END) AS partner_count,
    		SUM(s.qty) AS qty, SUM(s.total_amount) AS amount')
    	->orderByDesc('qty')->orderBy('p.idx')->paginate(20)->withQueryString();

    	// 상품별 발주 필요 업체 수 (해당 리치포트 상품에 매핑된 업체 상품 기준)
    	$mapped = RipsProduct::whereIn('product_idx', collect($data->items())->pluck('idx'))
    	->when($request->filled('partner_idx'), fn ($q) => $q->where('partner_idx', $request->partner_idx))->get();
    	RipsForecast::apply($mapped);
    	$alertCounts = $mapped->filter(fn ($p) => RipsForecast::alert($p))->groupBy('product_idx')->map->count();

    	$partners  = $this->ripsSalesPartners();
    	$formPaths = FormTypes::pathMap($formTree);

    	return view('admin.ripsSales', compact('data', 'summary', 'month', 'partners', 'formPaths', 'alertCounts'));
    }

    // 판매 관리 > 월별 > 자세히 : 리치포트 상품의 해당 월 일자 · 업체별 판매
    public function getRipsSalesMonthlyDetail(Request $request, $idx)
    {
    	$product = Product::findOrFail($idx);
    	$month   = $this->ripsSalesMonth($request);

    	$query = $this->ripsSalesBase()->where('rp.product_idx', $product->idx)
    	->whereBetween('s.sales_date', [$month->toDateString(), $month->copy()->endOfMonth()->toDateString()])
    	->when($request->filled('partner_idx'), fn ($q) => $q->where('s.partner_idx', $request->partner_idx));

    	$summary = (clone $query)->selectRaw('COALESCE(SUM(s.qty),0) AS qty, COALESCE(SUM(s.total_amount),0) AS amount, COUNT(DISTINCT CASE WHEN s.qty > 0 THEN s.partner_idx END) AS partners')->first();

    	$data = $this->ripsSalesDecorate(
    		$this->ripsSalesDayRows($query)->orderBy('s.sales_date')->orderByRaw('MAX(pt.partner_name)')->paginate(50)->withQueryString()
    	);

    	$partners  = $this->ripsSalesPartners();
    	$formPaths = FormTypes::pathMap();

    	return view('admin.ripsSalesMonthlyDetail', compact('product', 'data', 'summary', 'month', 'partners', 'formPaths'));
    }

    // 판매 관리 > 일별 : 하루 동안의 업체 · 업체 상품별 판매
    public function getRipsSalesDaily(Request $request)
    {
    	$date = Carbon::today();
    	if ($request->filled('date')) {
    		try { $date = Carbon::parse($request->date)->startOfDay(); } catch (\Exception $e) {}
    	}

    	$partnerIdx  = $request->filled('partner_idx') ? (int) $request->partner_idx : null;
    	$categoryMap = $partnerIdx ? RipsCategory::pathMap($partnerIdx) : [];

    	$query = $this->ripsSalesBase()->where('s.sales_date', $date->toDateString())
    	->when($partnerIdx, fn ($q) => $q->where('s.partner_idx', $partnerIdx))
    	->when($partnerIdx && $request->filled('category_idx'), fn ($q) => $q->whereIn('rp.category_idx', RipsCategory::descendantIds($partnerIdx, $request->category_idx)))
    	->when($request->filled('product_name'), fn ($q) => $q->where('rp.product_name', 'like', '%' . $request->product_name . '%'));

    	$summary = (clone $query)->selectRaw('COALESCE(SUM(s.qty),0) AS qty, COALESCE(SUM(s.total_amount),0) AS amount, COUNT(DISTINCT s.partner_idx) AS partners')->first();

    	$data = $this->ripsSalesDecorate(
    		$this->ripsSalesDayRows($query)->orderByRaw('MAX(pt.partner_name)')->orderByRaw('MAX(rp.product_name)')->paginate(50)->withQueryString()
    	);

    	// 목록의 카테고리 경로 (업체별)
    	$categoryPaths = [];
    	foreach (collect($data->items())->pluck('partner_idx')->unique() as $pIdx) {
    		$categoryPaths[$pIdx] = (int) $pIdx === $partnerIdx ? $categoryMap : RipsCategory::pathMap($pIdx);
    	}

    	$partners = $this->ripsSalesPartners();

    	return view('admin.ripsSalesDaily', compact('data', 'summary', 'date', 'partners', 'categoryMap', 'categoryPaths'));
    }

    // 리치포트 발주 업체 후보 : 리치포트 상품의 파트너 + 발주 정보에 입력된 업체
    private function ripsSuppliers()
    {
    	$ids = Product::distinct()->pluck('partner_idx')
    	->merge(RipsOrderItem::whereNotNull('supplier_idx')->distinct()->pluck('supplier_idx'))
    	->unique();

    	return Partner::whereIn('idx', $ids)->orderBy('partner_name')->get(['idx', 'partner_name']);
    }

    // 발주 상세
    public function getRipsOrderDetail(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('ripsOrder.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$order = RipsOrder::with([
    			'partner.head',
    			'items.supplier:idx,partner_name',
    			'items.richportProduct.partners:idx,partner_name',
    			'items.product.richportProduct.partners:idx,partner_name',
    			'logs',
    	])->findOrFail($idx);

    	$categoryMap = RipsCategory::pathMap($order->partner_idx);
    	$suppliers   = Partner::partnersOnly()->orderBy('partner_name')->get(['idx', 'partner_name']);

    	return view('admin.ripsOrderDetail', compact('order', 'categoryMap', 'suppliers'));
    }

    // 진행상태 변경 (빈 일정은 변경일로 채움 : 물류진행중 → 물류 시작일, 통관진행중 → 통관 시작일·물류 종료일, 입고완료 → 입고일·통관 종료일)
    public function setRipsOrderStatus(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('ripsOrder.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$order  = RipsOrder::findOrFail($idx);
    	$status = (string) $request->input('status');

    	if ($order->status === 'canceled') return response()->json(['status' => 'error', 'message' => '취소된 발주는 상태를 변경할 수 없습니다.']);
    	if (!array_key_exists($status, RipsOrder::STATUSES) || $status === 'canceled') return response()->json(['status' => 'error', 'message' => '발주상태를 확인해주세요.']);
    	if ($status === $order->status) return response()->json(['status' => 'error', 'message' => '이미 ' . $order->status_text . ' 상태입니다.']);

    	$admin = Session::get('admin');
    	$today = Carbon::today();
    	$fill  = [
    			'shipping' => ['logistics_start'],
    			'customs'  => ['customs_start', 'logistics_end'],
    			'received' => ['received_date', 'customs_end'],
    	][$status] ?? [];

    	$data = ['status' => $status, 'updated_by' => $admin['name'] ?? ''];
    	foreach ($fill as $field) {
    		if (!$order->$field) $data[$field] = $today;
    	}

    	DB::transaction(function () use ($order, $data, $status, $admin) {
    		RipsOrderLog::create([
    				'order_idx'   => $order->idx,
    				'status'      => $status,
    				'prev_status' => $order->status,
    				'created_by'  => $admin['name'] ?? '',
    		]);
    		$order->update($data);
    	});

    	return response()->json(['status' => 'success']);
    }

    // 일정 / 메모 / 리치포트 발주 정보 저장
    public function setRipsOrderSave(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('ripsOrder.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$order = RipsOrder::with('items.product')->findOrFail($idx);
    	if ($order->status === 'canceled') return response()->json(['status' => 'error', 'message' => '취소된 발주는 수정할 수 없습니다.']);

    	// 날짜 (빈 값 → null, 형식 오류 → false)
    	$date = function ($value) {
    		$value = trim((string) $value);
    		if ($value === '') return null;
    		$d = \DateTime::createFromFormat('Y-m-d', $value);
    		return $d && $d->format('Y-m-d') === $value ? $value : false;
    	};

    	$schedule = [];
    	foreach (['logistics_start' => '물류 시작일', 'logistics_end' => '물류 종료일', 'customs_start' => '통관 시작일', 'customs_end' => '통관 종료일', 'received_date' => '입고일'] as $field => $label) {
    		$schedule[$field] = $date($request->input($field));
    		if ($schedule[$field] === false) return response()->json(['status' => 'error', 'message' => $label . ' 형식이 올바르지 않습니다.']);
    	}
    	if ($schedule['logistics_start'] && $schedule['logistics_end'] && $schedule['logistics_start'] > $schedule['logistics_end']) return response()->json(['status' => 'error', 'message' => '물류 종료일이 시작일보다 빠릅니다.']);
    	if ($schedule['customs_start'] && $schedule['customs_end'] && $schedule['customs_start'] > $schedule['customs_end']) return response()->json(['status' => 'error', 'message' => '통관 종료일이 시작일보다 빠릅니다.']);

    	// 상품별 리치포트 발주 정보
    	$inputItems = (array) $request->input('items', []);
    	$itemData   = [];
    	foreach ($order->items as $item) {
    		$row = (array) ($inputItems[$item->idx] ?? []);

    		$supplierIdx = (int) ($row['supplier_idx'] ?? 0) ?: null;
    		$purchaseDate = $date($row['purchase_date'] ?? '');
    		$qty    = trim((string) ($row['purchase_qty'] ?? ''));
    		$amount = trim((string) ($row['purchase_amount'] ?? ''));

    		if ($purchaseDate === false) return response()->json(['status' => 'error', 'message' => '발주일 형식이 올바르지 않습니다. (' . $item->product_name . ')']);
    		if ($qty !== '' && !ctype_digit($qty)) return response()->json(['status' => 'error', 'message' => '발주 수량은 0 이상의 숫자로 입력해주세요. (' . $item->product_name . ')']);
    		if ($amount !== '' && !ctype_digit($amount)) return response()->json(['status' => 'error', 'message' => '발주 금액은 0 이상의 숫자로 입력해주세요. (' . $item->product_name . ')']);
    		if ($supplierIdx && !Partner::whereKey($supplierIdx)->exists()) return response()->json(['status' => 'error', 'message' => '발주 업체를 찾을 수 없습니다. (' . $item->product_name . ')']);

    		$entered = $supplierIdx || $purchaseDate || $qty !== '' || $amount !== '';
    		$itemData[$item->idx] = [
    				// 처음 입력할 때의 매핑 상품을 기록 (이후 매핑이 바뀌어도 유지)
    				'rp_product_idx'  => $entered ? ($item->rp_product_idx ?: $item->product?->product_idx) : null,
    				'supplier_idx'    => $supplierIdx,
    				'purchase_date'   => $purchaseDate,
    				'purchase_qty'    => $qty === '' ? null : (int) $qty,
    				'purchase_amount' => $amount === '' ? null : (int) $amount,
    		];
    	}

    	$admin = Session::get('admin');
    	DB::transaction(function () use ($order, $schedule, $itemData, $request, $admin) {
    		$order->update($schedule + [
    				'admin_memo' => trim((string) $request->input('admin_memo')) ?: null,
    				'updated_by' => $admin['name'] ?? '',
    		]);
    		foreach ($order->items as $item) {
    			$item->update($itemData[$item->idx]);
    		}
    	});

    	return response()->json(['status' => 'success']);
    }

    /**
     * 파트너 > RIPS 업체 설정 : 등록된 RIPS 업체 중 하나를 선택해 연결 (RIPS 계정은 RIPS 관리 > RIPS 업체 등록 에서만 생성)
     * RIPS 업체 1곳은 파트너 1곳에만 연결
     */
    private function partnerRipsData(Request $request, $partner = null)
    {
    	$ripsPartnerIdx = (int) $request->input('rips_partner_idx');
    	if (!$ripsPartnerIdx) return [['rips_partner_idx' => null], null];

    	if (!Partner::ripsAccounts()->whereKey($ripsPartnerIdx)->exists()) return [null, '선택한 RIPS 업체를 찾을 수 없습니다.'];

    	$mapped = Partner::where('rips_partner_idx', $ripsPartnerIdx)->when($partner, fn ($q) => $q->where('idx', '!=', $partner->idx))->first(['partner_name']);
    	if ($mapped) return [null, '이미 다른 파트너(' . $mapped->partner_name . ')에 연결된 RIPS 업체입니다.'];

    	return [['rips_partner_idx' => $ripsPartnerIdx], null];
    }

    // 파트너 > RIPS 업체 설정 선택 목록 (연결된 파트너 표시)
    private function ripsAccountOptions()
    {
    	$accounts = Partner::ripsAccounts()->orderBy('partner_name')->orderBy('store_name')->get(['idx', 'partner_name', 'store_name', 'rips_id']);
    	$mapped   = Partner::whereIn('rips_partner_idx', $accounts->pluck('idx'))->get(['idx', 'partner_name', 'rips_partner_idx'])->keyBy('rips_partner_idx');

    	return $accounts->each(function ($account) use ($mapped) {
    		$account->mapped_by   = $mapped[$account->idx]->idx ?? '';
    		$account->mapped_name = $mapped[$account->idx]->partner_name ?? '';
    	});
    }
    
    // 파트너 수정
    public function setPartnerModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('partner.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$Partner = Partner::findOrFail($idx);

    	[$branch, $branchError] = $this->partnerBranchData($request, $Partner);
    	if ($branchError) return response()->json(['status' => 'error', 'message' => $branchError]);

    	[$rips, $ripsError] = $this->partnerRipsData($request, $Partner);
    	if ($ripsError) return response()->json(['status' => 'error', 'message' => $ripsError]);
    	$rips += $branch;

    	try {
    		$admin = Session::get('admin');

    		DB::beginTransaction();

    		$Partner->update($rips + [
    				'country'				=> $request->input('country'),
    				'types_idx'				=> $request->input('types_idx'),
    				'cities_idx'			=> $request->input('cities_idx'),
    				'forms_idx'				=> $request->input('forms_idx'),
    				'districts_idx'			=> $request->input('districts_idx'),
					'channel'				=> $request->input('channel'),
					'channel_en'			=> $request->input('channel_en'),
					'channel_vn'			=> $request->input('channel_vn'),
					'logistic'				=> $request->input('logistic'),					
    				'partner_name'			=> $request->input('partner_name'),
    				'partner_name_en'		=> $request->input('partner_name_en'),
    				'partner_name_vn'		=> $request->input('partner_name_vn'),
					'contents'				=> $request->input('contents'),
					'contents_en'			=> $request->input('contents_en'),
					'contents_vn'			=> $request->input('contents_vn'),
    				'home_recommend'		=> $request->input('home_recommend'),
    				'partner_recommend'		=> $request->input('partner_recommend'),
    				'updated_by'            => $admin['name'] ?? '',
    		]);
    		
    		// =================================== Thumb ===================================
    		if ($request->hasFile('pcThumb')) {
    			$oldPath = $Partner->pc_up_name;
    			$file = $request->file('pcThumb');
    			$up_name = $file->store('partnerThumb', 'public');
    			
    			$Partner->update([
    					'pc_ori_name'    => $file->getClientOriginalName(),
    					'pc_up_name'     => $up_name,
    					'pc_file_ext'    => $file->getClientOriginalExtension(),
    					'pc_file_size'   => $file->getSize()
    			]);
    			
    			if ($oldPath) {
    				Storage::disk('public')->delete($oldPath);
    			}
    		}
    		
    		if ($request->hasFile('foThumb')) {
    			$oldPath = $Partner->fo_up_name;
    			$file = $request->file('foThumb');
    			$up_name = $file->store('partnerThumb', 'public');
    			
    			$Partner->update([
    					'fo_ori_name'    => $file->getClientOriginalName(),
    					'fo_up_name'     => $up_name,
    					'fo_file_ext'    => $file->getClientOriginalExtension(),
    					'fo_file_size'   => $file->getSize()
    			]);
    			
    			if ($oldPath) {
    				Storage::disk('public')->delete($oldPath);
    			}
    		}
    		
    		if ($request->hasFile('moThumb')) {
    			$oldPath = $Partner->mo_up_name;
    			$file = $request->file('moThumb');
    			$up_name = $file->store('partnerThumb', 'public');
    			
    			$Partner->update([
    					'mo_ori_name'    => $file->getClientOriginalName(),
    					'mo_up_name'     => $up_name,
    					'mo_file_ext'    => $file->getClientOriginalExtension(),
    					'mo_file_size'   => $file->getSize()
    			]);
    			
    			if ($oldPath) {
    				Storage::disk('public')->delete($oldPath);
    			}
    		}
    		
    		$removedFileIdxs   = json_decode($request->input('removedFileIdxs', '[]'), true) ?: [];
    		$existingFileOrder = json_decode($request->input('existingFileOrder', '[]'), true) ?: [];
    		
    		if (!empty($removedFileIdxs)) {
    			$filesToDelete = PartnerFiles::whereIn('idx', $removedFileIdxs)->get(['up_name']);
    			
    			foreach ($filesToDelete as $f) {
    				Storage::disk('public')->delete($f->up_name);
    			}
    			PartnerFiles::whereIn('idx', $removedFileIdxs)->delete();
    		}
    		
    		// 순서 업데이트
    		foreach ($existingFileOrder as $item) {
    			PartnerFiles::where('idx', $item['idx'])
    			->where('partner_idx', $idx)
    			->update([
    					'orderby' => $item['orderby'],
    			]);
    		}
    		
    		// 새 파일 순서
    		$newFileOrders = $request->input('new_file_order', []); 
    		
    		if ($request->hasFile('new_file_upload')) {
    			foreach ($request->file('new_file_upload') as $i => $file) {
    				$up_name = $file->store('partner', 'public');
    				PartnerFiles::create([
    						'partner_idx' => $idx,
    						'ori_name'      => $file->getClientOriginalName(),
    						'up_name'       => $up_name,
    						'file_ext'      => $file->getClientOriginalExtension(),
    						'file_size'     => $file->getSize(),
    						'orderby'       => $newFileOrders[$i] ?? 0,
    				]);
    			}
    		}
    		
    		DB::commit();
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		DB::rollBack();
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }
    
    // 파트너 삭제
    public function setPartnerDelete(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('partner.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	if (Partner::where('head_idx', $idx)->exists()) {
    		return response()->json(['status' => 'error', 'message' => '소속 지점이 있는 본점은 삭제할 수 없습니다. 지점의 본점을 먼저 변경해주세요.']);
    	}

    	try {
    		$Partner = Partner::findOrFail($idx);
    		$PartnerFilePaths = PartnerFiles::where('Partner_idx', $idx)->pluck('up_name')->filter()->all();
    		$pcThumbPath = $Partner->pc_up_name;
    		$foThumbPath = $Partner->fo_up_name;
    		$moThumbPath = $Partner->mo_up_name;
    		
    		DB::beginTransaction();
    		
    		PartnerFiles::where('Partner_idx', $idx)->delete();
    		$Partner->delete();
    		
    		DB::commit();
    		
    		if (!empty($pcThumbPath)) {
    			try {
    				Storage::disk('public')->delete($pcThumbPath);
    			} catch (\Exception $fe) {
    				\Log::warning('Partner pcThumb file delete failed', [
    						'Partner_idx' => $idx,
    						'paths'         => $pcThumbPath,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		if (!empty($foThumbPath)) {
    			try {
    				Storage::disk('public')->delete($foThumbPath);
    			} catch (\Exception $fe) {
    				\Log::warning('Partner foThumb file delete failed', [
    						'Partner_idx' => $idx,
    						'paths'         => $foThumbPath,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		if (!empty($moThumbPath)) {
    			try {
    				Storage::disk('public')->delete($moThumbPath);
    			} catch (\Exception $fe) {
    				\Log::warning('Partner moThumb file delete failed', [
    						'Partner_idx' => $idx,
    						'paths'         => $moThumbPath,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		if (!empty($PartnerFilePaths)) {
    			try {
    				Storage::disk('public')->delete($PartnerFilePaths);
    			} catch (\Exception $fe) {
    				\Log::warning('Partner file delete failed', [
    						'Partner_idx' => $idx,
    						'paths'         => $PartnerFilePaths,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		if (DB::transactionLevel() > 0) {
    			DB::rollBack();
    		}
    		return response()->json(['status'  => 'error','message' => $e->getMessage(),], 500);
    	}
    }




	


    //================================== 제품 ==================================
    // 제품 목록
    public function getProductList(Request $request)
    {
		if (!auth()->user()->hasPerm('product.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query    = Product::with('partners','forms');
    	$formTree = FormTypes::tree();

    	// 검색 조건 처리
    	if ($request->filled('partner_idx')) {
    		$query->where('product.partner_idx', $request->partner_idx);
    	}

    	// 카테고리 : 선택한 단계 + 하위 카테고리 전체
    	if ($request->filled('forms_idx')) {
    		$query->whereIn('product.forms_idx', FormTypes::descendantIds($request->forms_idx, $formTree));
    	}
    	
    	if ($request->filled('home_recommend')) {
    		$query->where('product.home_recommend', $request->home_recommend);
    	}
    	
    	if ($request->filled('product_recommend')) {
    		$query->where('product.product_recommend', $request->product_recommend);
    	}

		if ($request->filled('product_name')) {
    		$query->where('product.product_name', 'like', '%' . $request->product_name . '%');
    	}
    	
    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	$data->getCollection()->transform(function ($item) {
    		// 추천 플래그 1/0 → Y / N
    		$toYN = function ($v) {
    			return ((string)$v === '1') ? 'Y' : 'N';
    		};
    		
			$item->congbo_text       		= $toYN($item->congbo);
			$item->new_text       			= $toYN($item->new);
			$item->testbed_text      		= $toYN($item->testbed);

			$item->home_recommend_text     	= $toYN($item->home_recommend);
    		$item->product_recommend_text  	= $toYN($item->product_recommend);
    		
    		$item->created_date = optional($item->created_at)->format('Y-m-d');
    		
    		return $item;
    	});
    	
    	$formPaths = FormTypes::pathMap($formTree);
    	$partners  = Partner::partnersOnly()->orderBy('idx')->get();

    	return view('admin.product', compact('data','formTree','formPaths','partners'));
    }
    
	// 제품 엑셀 다운로드
    public function getProductExport(Request $request)
    {
		if (!auth()->user()->hasPerm('product.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$filters = $request->only(['partner_idx', 'forms_idx', 'home_recommend', 'product_recommend', 'product_name']);
    	
    	$timestamp = Carbon::now()->format('Ymd_His');
    	$filename = '제품목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new productExport($filters), $filename);
    }
    
    // 제품 등록
    public function setProductRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('product.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);
		if (!FormTypes::whereKey((int) $request->input('forms_idx'))->exists()) return response()->json(['status' => 'error', 'message' => '카테고리를 선택해주세요.']);

		// 바코드 (등록 시 필수, 중복 불가)
		[$barcode, $barcodeError] = \App\Support\Barcode::check($request->input('barcode'), true, Product::query());
		if ($barcodeError) return response()->json(['status' => 'error', 'message' => $barcodeError]);

    	try {
    		$admin = Session::get('admin');
    		
    		$Product = Product::create([
    				'partner_idx'			=> $request->input('partner_idx'),
					'forms_idx'				=> $request->input('forms_idx'),
					'congbo'				=> $request->input('congbo'),
					'new'					=> $request->input('new'),
					'testbed'				=> $request->input('testbed'),
					'congbo_number'			=> $request->input('congbo_number'),
    				'product_name'			=> $request->input('product_name'),
    				'product_name_en'		=> $request->input('product_name_en'),
    				'product_name_vn'		=> $request->input('product_name_vn'),
    				'barcode'				=> $barcode,
    				'order_price'			=> $request->filled('order_price') ? max(0, (int) $request->input('order_price')) : null,
					'contents'				=> $request->input('contents'),
					'contents_en'			=> $request->input('contents_en'),
					'contents_vn'			=> $request->input('contents_vn'),
    				'home_recommend'		=> $request->input('home_recommend'),
    				'product_recommend'		=> $request->input('product_recommend'),
    				'created_by'			=> $admin['name'] ?? ''
    		]);
    		
    		$Product = Product::findOrFail($Product->idx);
    		
    		if ($request->hasFile('pcThumb')) {
    			$file = $request->file('pcThumb');
    			$up_name = $file->store('productThumb', 'public');
    			$Product->update([
    					'pc_ori_name'    	=> $file->getClientOriginalName(),
    					'pc_up_name'     	=> $up_name,
    					'pc_file_ext'    	=> $file->getClientOriginalExtension(),
    					'pc_file_size'   	=> $file->getSize(),
    			]);
    		}
    		
    		if ($request->hasFile('foThumb')) {
    			$file = $request->file('foThumb');
    			$up_name = $file->store('productThumb', 'public');
    			$Product->update([
    					'fo_ori_name'    	=> $file->getClientOriginalName(),
    					'fo_up_name'     	=> $up_name,
    					'fo_file_ext'    	=> $file->getClientOriginalExtension(),
    					'fo_file_size'   	=> $file->getSize(),
    			]);
    		}
    		
    		if ($request->hasFile('moThumb')) {
    			$file = $request->file('moThumb');
    			$up_name = $file->store('productThumb', 'public');
    			$Product->update([
    					'mo_ori_name'    	=> $file->getClientOriginalName(),
    					'mo_up_name'     	=> $up_name,
    					'mo_file_ext'    	=> $file->getClientOriginalExtension(),
    					'mo_file_size'   	=> $file->getSize(),
    			]);
    		}
    		
    		if ($request->hasFile('productFiles')) {
    			$orderby = 1;
    			foreach ($request->file('productFiles') as $file) {
    				// 파일 저장
    				$up_name = $file->store('product', 'public');
    				ProductFiles::create([
    						'product_idx' 	=> $Product->idx,
    						'ori_name'    	=> $file->getClientOriginalName(),
    						'up_name'     	=> $up_name,
    						'file_ext'    	=> $file->getClientOriginalExtension(),
    						'file_size'   	=> $file->getSize(),
    						'orderby' 		=> $orderby++,
    				]);
    			}
    		}
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }

    // 제품 수정 폼
    public function getProductDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('product.update') && !auth()->user()->hasPerm('product.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data = $idx ? Product::findOrFail($idx) : null;
    	
    	$files = collect();
    	if ($files && $data->idx) {
    		$files = ProductFiles::where('product_idx', $data->idx)->orderByRaw('orderby')->get();
    	}
    	
    	return response()->json(['status' => 'success', 'data' => $data, 'files' => $files]);
    }
    
    // 제품 수정
    public function setProductModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('product.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);
		if (!FormTypes::whereKey((int) $request->input('forms_idx'))->exists()) return response()->json(['status' => 'error', 'message' => '카테고리를 선택해주세요.']);

		// 바코드 (수정 시에는 선택, 중복 불가)
		[$barcode, $barcodeError] = \App\Support\Barcode::check($request->input('barcode'), false, Product::query(), $idx);
		if ($barcodeError) return response()->json(['status' => 'error', 'message' => $barcodeError]);

    	try {
    		$admin = Session::get('admin');

    		DB::beginTransaction();

    		$Product = Product::findOrFail($idx);
    		
    		$Product->update([
    				'partner_idx'			=> $request->input('partner_idx'),
					'forms_idx'				=> $request->input('forms_idx'),
					'congbo_number'			=> $request->input('congbo_number'),
    				'product_name'			=> $request->input('product_name'),
    				'product_name_en'		=> $request->input('product_name_en'),
    				'product_name_vn'		=> $request->input('product_name_vn'),
    				'barcode'				=> $barcode,
    				'order_price'			=> $request->filled('order_price') ? max(0, (int) $request->input('order_price')) : null,
					'contents'				=> $request->input('contents'),
					'contents_en'			=> $request->input('contents_en'),
					'contents_vn'			=> $request->input('contents_vn'),
    				'home_recommend'		=> $request->input('home_recommend'),
    				'product_recommend'		=> $request->input('product_recommend'),
    				'updated_by'            => $admin['name'] ?? '',
    		]);
    		
    		// =================================== Thumb ===================================
    		if ($request->hasFile('pcThumb')) {
    			$oldPath = $Product->pc_up_name;
    			$file = $request->file('pcThumb');
    			$up_name = $file->store('productThumb', 'public');
    			
    			$Product->update([
    					'pc_ori_name'    => $file->getClientOriginalName(),
    					'pc_up_name'     => $up_name,
    					'pc_file_ext'    => $file->getClientOriginalExtension(),
    					'pc_file_size'   => $file->getSize()
    			]);
    			
    			if ($oldPath) {
    				Storage::disk('public')->delete($oldPath);
    			}
    		}
    		
    		if ($request->hasFile('foThumb')) {
    			$oldPath = $Product->fo_up_name;
    			$file = $request->file('foThumb');
    			$up_name = $file->store('productThumb', 'public');
    			
    			$Product->update([
    					'fo_ori_name'    => $file->getClientOriginalName(),
    					'fo_up_name'     => $up_name,
    					'fo_file_ext'    => $file->getClientOriginalExtension(),
    					'fo_file_size'   => $file->getSize()
    			]);
    			
    			if ($oldPath) {
    				Storage::disk('public')->delete($oldPath);
    			}
    		}
    		
    		if ($request->hasFile('moThumb')) {
    			$oldPath = $Product->mo_up_name;
    			$file = $request->file('moThumb');
    			$up_name = $file->store('productThumb', 'public');
    			
    			$Product->update([
    					'mo_ori_name'    => $file->getClientOriginalName(),
    					'mo_up_name'     => $up_name,
    					'mo_file_ext'    => $file->getClientOriginalExtension(),
    					'mo_file_size'   => $file->getSize()
    			]);
    			
    			if ($oldPath) {
    				Storage::disk('public')->delete($oldPath);
    			}
    		}
    		
    		$removedFileIdxs   = json_decode($request->input('removedFileIdxs', '[]'), true) ?: [];
    		$existingFileOrder = json_decode($request->input('existingFileOrder', '[]'), true) ?: [];
    		
    		if (!empty($removedFileIdxs)) {
    			$filesToDelete = ProductFiles::whereIn('idx', $removedFileIdxs)->get(['up_name']);
    			
    			foreach ($filesToDelete as $f) {
    				Storage::disk('public')->delete($f->up_name);
    			}
    			ProductFiles::whereIn('idx', $removedFileIdxs)->delete();
    		}
    		
    		// 순서 업데이트
    		foreach ($existingFileOrder as $item) {
    			ProductFiles::where('idx', $item['idx'])
    			->where('partner_idx', $idx)
    			->update([
    					'orderby' => $item['orderby'],
    			]);
    		}
    		
    		// 새 파일 순서
    		$newFileOrders = $request->input('new_file_order', []); 
    		
    		if ($request->hasFile('new_file_upload')) {
    			foreach ($request->file('new_file_upload') as $i => $file) {
    				$up_name = $file->store('product', 'public');
    				ProductFiles::create([
    						'product_idx' 	=> $idx,
    						'ori_name'      => $file->getClientOriginalName(),
    						'up_name'       => $up_name,
    						'file_ext'      => $file->getClientOriginalExtension(),
    						'file_size'     => $file->getSize(),
    						'orderby'       => $newFileOrders[$i] ?? 0,
    				]);
    			}
    		}
    		
    		DB::commit();
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		DB::rollBack();
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }
    
    // 제품 삭제
    public function setProductDelete(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('product.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$Product = Product::findOrFail($idx);
    		$ProductFilePaths = ProductFiles::where('product_idx', $idx)->pluck('up_name')->filter()->all();
    		$pcThumbPath = $Product->pc_up_name;
    		$foThumbPath = $Product->fo_up_name;
    		$moThumbPath = $Product->mo_up_name;
    		
    		DB::beginTransaction();
    		
    		ProductFiles::where('product_idx', $idx)->delete();
    		$Product->delete();
    		
    		DB::commit();
    		
    		if (!empty($pcThumbPath)) {
    			try {
    				Storage::disk('public')->delete($pcThumbPath);
    			} catch (\Exception $fe) {
    				\Log::warning('Product pcThumb file delete failed', [
    						'product_idx' 	=> $idx,
    						'paths'         => $pcThumbPath,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		if (!empty($foThumbPath)) {
    			try {
    				Storage::disk('public')->delete($foThumbPath);
    			} catch (\Exception $fe) {
    				\Log::warning('Product foThumb file delete failed', [
    						'product_idx' => $idx,
    						'paths'         => $foThumbPath,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		if (!empty($moThumbPath)) {
    			try {
    				Storage::disk('public')->delete($moThumbPath);
    			} catch (\Exception $fe) {
    				\Log::warning('Product moThumb file delete failed', [
    						'product_idx' => $idx,
    						'paths'         => $moThumbPath,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		if (!empty($productFilePaths)) {
    			try {
    				Storage::disk('public')->delete($productFilePaths);
    			} catch (\Exception $fe) {
    				\Log::warning('Product file delete failed', [
    						'product_idx' => $idx,
    						'paths'         => $productFilePaths,
    						'error'         => $fe->getMessage(),
    				]);
    			}
    		}
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		if (DB::transactionLevel() > 0) {
    			DB::rollBack();
    		}
    		return response()->json(['status'  => 'error','message' => $e->getMessage(),], 500);
    	}
    }





    //================================== 카테고리 관리 ==================================
    // form_types 1~4단계 트리 (parent_idx / depth / orderby, rips_category 와 같은 구조)
    // 제품·파트너 RIPS 카테고리 매핑은 1~4단계 어디든 가능, 파트너 등 기존 화면은 1단계만 사용 (FormTypes::top())

    // 카테고리 관리 화면
    public function getFormCategory(Request $request)
    {
		if (!auth()->user()->hasPerm('formCategory.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	return view('admin.formCategory');
    }

    // 카테고리 전체 목록 (하위·제품·파트너 수 포함)
    public function getFormCategoryList(Request $request)
    {
		if (!auth()->user()->hasPerm('formCategory.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data = FormTypes::withCount(['children', 'products', 'partners'])
    	->orderBy('depth')->orderBy('orderby')->orderBy('idx')->get();

    	return response()->json(['status' => 'success', 'data' => $data]);
    }

    // 카테고리 입력값 (ko 필수, en/vn 선택)
    private function formCategoryInput(Request $request)
    {
    	$data = [
    			'subject'    => trim((string) $request->input('subject')),
    			'subject_en' => trim((string) $request->input('subject_en')),
    			'subject_vn' => trim((string) $request->input('subject_vn')),
    	];

    	if ($data['subject'] === '') return [null, '카테고리명(ko)을 입력해주세요.'];

    	return [$data, null];
    }

    // 카테고리 등록 (parent_idx 0 : 1단계)
    public function setFormCategoryRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('formCategory.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	[$data, $error] = $this->formCategoryInput($request);
    	if ($error) return response()->json(['status' => 'error', 'message' => $error]);

    	$parentIdx = (int) $request->input('parent_idx', 0);
    	$depth     = 1;

    	if ($parentIdx > 0) {
    		$parent = FormTypes::find($parentIdx);
    		if (!$parent) return response()->json(['status' => 'error', 'message' => '상위 카테고리를 찾을 수 없습니다.']);
    		if ($parent->depth >= FormTypes::MAX_DEPTH) return response()->json(['status' => 'error', 'message' => '카테고리는 최대 ' . FormTypes::MAX_DEPTH . '단계까지 만들 수 있습니다.']);
    		$depth = $parent->depth + 1;
    	}

    	$item = FormTypes::create($data + [
    			'parent_idx' => $parentIdx,
    			'depth'      => $depth,
    			'orderby'    => (int) FormTypes::where('parent_idx', $parentIdx)->max('orderby') + 1,
    	]);

    	return response()->json(['status' => 'success', 'idx' => $item->idx]);
    }

    // 카테고리 수정 (이름만, 위치는 변경하지 않음)
    public function setFormCategoryModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('formCategory.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	[$data, $error] = $this->formCategoryInput($request);
    	if ($error) return response()->json(['status' => 'error', 'message' => $error]);

    	$item = FormTypes::find($idx);
    	if (!$item) return response()->json(['status' => 'error', 'message' => '카테고리를 찾을 수 없습니다.']);

    	$item->update($data);

    	return response()->json(['status' => 'success']);
    }

    // 같은 상위 안에서 순서 변경 (direction: up | down)
    public function setFormCategorySort(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('formCategory.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$item = FormTypes::find($idx);
    	if (!$item) return response()->json(['status' => 'error', 'message' => '카테고리를 찾을 수 없습니다.']);

    	DB::transaction(function () use ($item, $request) {
    		// 같은 상위의 순서를 1,2,3... 로 다시 매긴 뒤 이웃과 교환
    		$siblings = FormTypes::where('parent_idx', $item->parent_idx)->orderBy('orderby')->orderBy('idx')->lockForUpdate()->get()->values();
    		$pos  = $siblings->search(fn ($s) => $s->idx == $item->idx);
    		$swap = $request->input('direction') === 'up' ? $pos - 1 : $pos + 1;

    		if ($swap >= 0 && $swap < $siblings->count()) {
    			$moved = $siblings[$pos];
    			$siblings[$pos]  = $siblings[$swap];
    			$siblings[$swap] = $moved;
    		}

    		foreach ($siblings as $order => $sibling) {
    			if ($sibling->orderby != $order + 1) $sibling->update(['orderby' => $order + 1]);
    		}
    	});

    	return response()->json(['status' => 'success']);
    }

    // 카테고리 삭제 (하위 카테고리가 있거나 다른 데이터에서 사용 중이면 불가)
    public function setFormCategoryDelete(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('formCategory.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$item = FormTypes::find($idx);
    	if (!$item) return response()->json(['status' => 'error', 'message' => '카테고리를 찾을 수 없습니다.']);

    	if ($item->children()->exists())       return response()->json(['status' => 'error', 'message' => '하위 카테고리가 있어 삭제할 수 없습니다. 하위 카테고리를 먼저 삭제해주세요.']);
    	if ($item->products()->exists())       return response()->json(['status' => 'error', 'message' => '이 카테고리로 등록된 제품이 있어 삭제할 수 없습니다.']);
    	if ($item->partners()->exists())       return response()->json(['status' => 'error', 'message' => '이 카테고리로 등록된 파트너가 있어 삭제할 수 없습니다.']);
    	if ($item->ripsCategories()->exists()) return response()->json(['status' => 'error', 'message' => 'RIPS 카테고리에 매핑되어 있어 삭제할 수 없습니다.']);

    	$item->delete();

    	return response()->json(['status' => 'success']);
    }

    //================================== 체크리스트 ==================================
	// 체크리스트 목록
	public function checkList(Request $request)
	{
		$query = CheckList::with('product:idx,product_name')
			->withCount(['checkexecution' => fn ($q) => $q->where('use_yn', 'Y')]); // 응답 건수

		// 검색: 테스트베드 상품
		if ($request->filled('product_idx')) {
			$query->where('product_idx', $request->product_idx);
		}

		// 검색: 체크리스트 제목
		if ($request->filled('check_name')) {
			$query->where('check_name', 'like', '%' . $request->check_name . '%');
		}

		// 최신순 정렬 및 페이징 (10개씩)
		$data = $query->orderByDesc('idx')->paginate(10)->withQueryString();
		
		// 검색용 테스트베드 상품 목록 (Select Box용)
		$products = $this->surveyProducts();

		return view('admin.checkList', compact('data', 'products'));
	}

	// 설문 대상 상품 : 테스트베드 상품 + 이미 설문에 지정된 상품 (이후 테스트베드 해제되어도 수정 가능)
	private function surveyProducts()
	{
		return Product::where('testbed', 1)->orWhereHas('surveys')->orderBy('product_name')->get(['idx', 'product_name']);
	}

	// 설문 대상 상품 검증
	private function surveyProductValid($productIdx, $list = null)
	{
		if ($list && (int) $list->product_idx === (int) $productIdx) return true;

		return Product::whereKey((int) $productIdx)->where('testbed', 1)->exists();
	}

	// 체크리스트 등록 화면
	public function checkRegistForm()
	{
		$products = $this->surveyProducts();

		return view('admin.checkListForm', compact('products'));
	}

	// 체크리스트 등록
	public function checkRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('checklist.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);
		if (!$this->surveyProductValid($request->product_idx)) return response()->json(['status' => 'error', 'message' => '테스트베드 상품을 선택해주세요.']);

        try {
			$admin = Session::get('admin');

			// 데이터 처리를 위한 트랙젝선 사용
            return DB::transaction(function () use ($request, $admin) {
                $list = CheckList::create([
                    'check_name' => $request->check_name,
                    'product_idx' => $request->product_idx,
                    'use_yn'     => 'Y',
                    'created_at' => Carbon::now(),
                    'created_by' => $admin['name'] ?? 'admin'
                ]);

                $this->saveRelations($list, $request->sections);

                return response()->json(['status' => 'success']);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

	// 체크리스트 수정 화면
	public function checkModifyForm($idx)
	{
		// 수정할 체크리스트와 그 하위 섹션, 아이템, 옵션을 한 번에 로드 (Eager Loading)
		$list = CheckList::with('checksection.checkitem.checkoption')->findOrFail($idx);

		$products = $this->surveyProducts();

		// 수정 화면 뷰로 데이터 전달
		return view('admin.checkListForm', compact('list', 'products'));
	}


    // 체크리스트 수정
	public function checkModify(Request $request, $idx)
	{
		if (!auth()->user()->hasPerm('checklist.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		try {
			$admin = Session::get('admin');

			return DB::transaction(function () use ($request, $idx, $admin) { // $admin 추가
				$list = CheckList::with('checksection.checkitem')->findOrFail($idx);

				$hasResult = CheckExecution::where('list_idx', $idx)->exists();
				if ($hasResult) {
					return response()->json([
						'status' => 'error', 
						'message' => '이미 응답이 등록된 설문조사는 수정할 수 없습니다. (복사 기능을 이용해 새로 만들어주세요.)'
					]);
				}

				if (!$this->surveyProductValid($request->product_idx, $list)) {
					return response()->json(['status' => 'error', 'message' => '테스트베드 상품을 선택해주세요.']);
				}

				$list->update([
					'check_name' => $request->check_name,
					'product_idx' => $request->product_idx,
					'updated_at' => Carbon::now(),
					'updated_by' => $admin['name'] ?? 'admin'
				]);

				foreach ($list->checksection as $section) {
					foreach ($section->checkitem as $item) {
						$item->checkoption()->delete();
					}
					$section->checkitem()->delete();
				}
				$list->checksection()->delete();

				$this->saveRelations($list, $request->sections);

				return response()->json(['status' => 'success']);
			});
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
		}
	}

    // 체크리스트 복사
	public function checkCopy(Request $request, $idx)
	{
		if (!auth()->user()->hasPerm('checklist.copy')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		try {
			$admin = Session::get('admin');

			return DB::transaction(function () use ($idx, $admin) { // $admin 추가
				$origin = CheckList::with('checksection.checkitem.checkoption')->findOrFail($idx);
				
				$newList = CheckList::create([
					'check_name' => $origin->check_name . ' (복사본)',
					'depart_idx' => $origin->depart_idx,
					'product_idx' => $origin->product_idx,
					'use_yn'     => 'Y',
					'created_at' => Carbon::now(),
					'created_by' => $admin['name'] ?? 'admin'
				]);

				foreach ($origin->checksection as $section) {
					$newSection = $newList->checksection()->create([
						'section_name' => $section->section_name,
						'sort_order'   => $section->sort_order,
						'layout_type'  => $section->layout_type ?? '3',
					]);

					foreach ($section->checkitem as $item) {
						$newItem = $newSection->checkitem()->create([
							'd2_name'    => $item->d2_name,
							'd3_name'    => $item->d3_name,
							'item_name'  => $item->item_name,
							'input_type' => $item->input_type,
							'sort_order' => $item->sort_order,
							'has_text'	 => $item->has_text ?? 'N',
						]);

						foreach ($item->checkoption as $option) {
							$newItem->checkoption()->create([
								'option_name' => $option->option_name, // option_title에서 수정
								'sort_order'  => $option->sort_order
							]);
						}
					}
				}
				return response()->json(['status' => 'success']);
			});
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
		}
	}


    // 체크리스트 삭제 (결과가 있으면 비활성화, 없으면 삭제)
    public function checkDelete($idx)
	{
		if (!auth()->user()->hasPerm('checklist.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		try {
			$admin = Session::get('admin');

			$list = CheckList::findOrFail($idx);

			// [체크] 결과 데이터 존재 여부 확인
			$hasResult = CheckExecution::where('list_idx', $idx)->exists();
			if ($hasResult) {
				// 결과가 있으면 실제 삭제하지 않고 사용 여부만 N으로 변경 (데이터 보존)
				$list->update([
					'use_yn' => 'N',
					'updated_at' => Carbon::now(),
					'updated_by' => $admin['name'] ?? 'admin'
				]);
				return response()->json(['status' => 'success', 'message' => '결과 데이터가 있어 비활성화 처리되었습니다.']);
			}

			// 결과가 없으면 수동으로 하위 데이터까지 모두 삭제 처리 가능
			DB::transaction(function () use ($list) {
				foreach ($list->checksection as $section) {
					foreach ($section->checkitem as $item) {
						$item->checkoption()->delete();
					}
					$section->checkitem()->delete();
				}
				$list->checksection()->delete();
				$list->delete(); // 마지막에 마스터 삭제
			});

			return response()->json(['status' => 'success']);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
		}
	}

    // 섹션, 아이템, 옵션 순으로 기존 데이터 삭제 후 재등록
	private function saveRelations($list, $sections)
	{
		if (!$sections) return;

		foreach ($sections as $sData) {
			$section = $list->checksection()->create([
				'section_name' => $sData['section_name'],
				'sort_order'   => $sData['sort_order'],
				'layout_type'  => $sData['layout_type'] ?? '3',
			]);

			if (isset($sData['items'])) { // items 존재 여부 체크 추가
				foreach ($sData['items'] as $iData) {
					$item = $section->checkitem()->create([
						'd2_name'    => $iData['d2_name'] ?? '',
						'd3_name'    => $iData['d3_name'] ?? '',
						'item_name'  => $iData['item_name'],
						'input_type' => $iData['input_type'],
						'sort_order' => $iData['sort_order'],
						'has_text'	 => $iData['has_text'] ?? 'N',
					]);

					if (!empty($iData['options'])) {
						foreach ($iData['options'] as $oData) {
							$item->checkoption()->create([
								'option_name' => $oData['option_name'],
								'sort_order'  => $oData['sort_order']
							]);
						}
					}
				}
			}
		}
	}

	// 설문 결과 : 테스트베드 상품별 목록
	public function checkResultList(Request $request)
	{
		$active = fn ($q) => $q->where('check_execution.use_yn', 'Y');

		$query = Product::with('partners:idx,partner_name')
			->where(fn ($q) => $q->where('testbed', 1)->orWhereHas('surveys'))
			->withCount(['surveys', 'surveyResponses as response_count' => $active])
			->withMax(['surveyResponses as last_response_at' => $active], 'created_at');

		if ($request->filled('product_name')) {
			$query->where('product_name', 'like', '%' . $request->product_name . '%');
		}

		// 최근 응답이 있는 상품부터
		$data = $query->orderByDesc('last_response_at')->orderByDesc('idx')->paginate(10)->withQueryString();

		return view('admin.checkResultList', compact('data'));
	}

	// 설문 결과 상세 : 상품의 설문별 요약 (구글폼 요약과 같은 구성)
	public function checkResultDetail(Request $request, $idx)
	{
		$product = Product::with('partners:idx,partner_name')->findOrFail($idx);

		return view('admin.checkResultDetail', ['product' => $product] + SurveyResult::summary($product, $request->list_idx));
	}

	// 개별 설문 (no : 응답 순번, 1 부터)
	public function checkResultResponse(Request $request, $idx)
	{
		return response()->json(SurveyResult::response($idx, $request->no));
	}


    //================================== 문의 ==================================
    // 문의 목록
    public function getInquireList(Request $request)
    {
		if (!auth()->user()->hasPerm('inquire.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Inquire::with('types');
    	
    	if ($request->filled('types_idx')) {
    		$query->where('types_idx', $request->types_idx);
    	}
    	
    	if ($request->filled('status')) {
    		$query->where('status', $request->status);
    	}
    	
    	/* if ($request->filled('inquire_text')) {
    		$s = trim($request->inquire_text);
    		$query->where(function ($q) use ($s) {
    			$q->where('inquire_company', 'like', "%{$s}%")
    			->orWhere('inquire_name', 'like', "%{$s}%");
    		});
    	} */
    	
    	if ($request->filled('inquire_hp')) {
    		$query->where('inquire_hp', 'like', '%' . $request->inquire_hp . '%');
    	}
    	
    	if ($request->filled('inquire_company')) {
    		$query->where('inquire_company', 'like', '%' . $request->inquire_company . '%');
    	}
    	
    	if ($request->filled('inquire_address')) {
    		$query->where('inquire_address', 'like', '%' . $request->inquire_address . '%');
    	}
    	
    	$data = $query->orderByDesc('idx')->paginate(10)->withQueryString();
    	
    	$inquireTypes = InquireTypes::orderBy('idx')->get();
    	
    	return view('admin.inquire', compact('data','inquireTypes'));
    }
    
    public function getInquireExport(Request $request)
    {
 		if (!auth()->user()->hasPerm('inquire.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

   	$filters = $request->only(['types_idx', 'status', 'inquire_hp', 'inquire_company', 'inquire_address']);
    	
    	$timestamp = Carbon::now()->format('Ymd_His');
    	$filename = '문의목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new inquireExport($filters), $filename);
    }
    
    // 문의 수정 폼
    public function getInquireDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('inquire.save')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$inquire = Inquire::findOrFail($idx);
    		
    		DB::beginTransaction();
    		
    		// response_at이 null일 때만 업데이트
    		if (is_null($inquire->response_at)) {
    			$inquire->update([
    					'response_at' => Carbon::now()
    			]);
    		}
    		
    		if($inquire->status == '1'){
    			$inquire->update([
    					'status' => '2'
    			]);
    		}
    		
    		DB::commit();
    		
    		$data = $idx ? Inquire::with('types')->findOrFail($idx) : null;
    		
    		return response()->json(['status' => 'success', 'data' => $data]);
    	} catch (\Exception $e) {
    		if (DB::transactionLevel() > 0) {
    			DB::rollBack();
    		}
    		return response()->json(['status'  => 'error','message' => $e->getMessage(),], 500);
    	}
    }
    
    // 문의 수정
    public function setInquireModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('inquire.save')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		DB::beginTransaction();
    		
    		$inquire = Inquire::findOrFail($idx);
    		$admin = Session::get('admin');
    		
    		// 기본 업데이트 데이터
    		$updateData = [
    				'status'            => $request->input('status'),
    				'response_contents' => $request->input('response_contents'),
    				'response_by'       => $admin['name'] ?? '',
    		];
    		
    		// status가 3일 경우에만 complete_at 추가
    		if ($request->input('status') == 3) {
    			$updateData['complete_at'] = Carbon::now();
    		}
    		
    		// update 실행
    		$inquire->update($updateData);
    		
    		DB::commit();
    		return response()->json(['status' => 'success']);
    		
    	} catch (\Exception $e) {
    		DB::rollBack();
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }	

	
    //================================== RIPS 문의 ==================================
    // 문의 목록
    public function getInquireRipsList(Request $request)
    {
		if (!auth()->user()->hasPerm('inquireRips.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Ripsinquire::with('types');
    	
    	if ($request->filled('types_idx')) {
    		$query->where('types_idx', $request->types_idx);
    	}
    	
    	if ($request->filled('status')) {
    		$query->where('status', $request->status);
    	}
    	
    	/* if ($request->filled('inquire_text')) {
    		$s = trim($request->inquire_text);
    		$query->where(function ($q) use ($s) {
    			$q->where('inquire_company', 'like', "%{$s}%")
    			->orWhere('inquire_name', 'like', "%{$s}%");
    		});
    	} */
    	
    	if ($request->filled('inquire_hp')) {
    		$query->where('inquire_hp', 'like', '%' . $request->inquire_hp . '%');
    	}
    	
    	if ($request->filled('inquire_company')) {
    		$query->where('inquire_company', 'like', '%' . $request->inquire_company . '%');
    	}
    	
    	if ($request->filled('inquire_address')) {
    		$query->where('inquire_address', 'like', '%' . $request->inquire_address . '%');
    	}
    	
    	$data = $query->orderByDesc('idx')->paginate(10)->withQueryString();
    	
    	$inquireTypes = RipsInquireTypes::orderBy('idx')->get();
    	
    	return view('admin.inquireRips', compact('data','inquireTypes'));
    }
    
    public function getInquireRipsExport(Request $request)
    {
 		if (!auth()->user()->hasPerm('inquireRips.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

   		$filters = $request->only(['types_idx', 'status', 'inquire_hp', 'inquire_company', 'inquire_address']);
    	
    	$timestamp = Carbon::now()->format('Ymd_His');
    	$filename = 'RIPS문의목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new inquireRipsExport($filters), $filename);
    }
    
    // 문의 수정 폼
    public function getInquireRipsDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('inquireRips.save')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$inquire = Ripsinquire::findOrFail($idx);
    		
    		DB::beginTransaction();
    		
    		// response_at이 null일 때만 업데이트
    		if (is_null($inquire->response_at)) {
    			$inquire->update([
    					'response_at' => Carbon::now()
    			]);
    		}
    		
    		if($inquire->status == '1'){
    			$inquire->update([
    					'status' => '2'
    			]);
    		}
    		
    		DB::commit();
    		
    		$data = $idx ? Ripsinquire::with('types')->findOrFail($idx) : null;
    		
    		return response()->json(['status' => 'success', 'data' => $data]);
    	} catch (\Exception $e) {
    		if (DB::transactionLevel() > 0) {
    			DB::rollBack();
    		}
    		return response()->json(['status'  => 'error','message' => $e->getMessage(),], 500);
    	}
    }
    
    // 문의 수정
    public function setInquireRipsModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('inquireRips.save')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		DB::beginTransaction();
    		
    		$inquire = Ripsinquire::findOrFail($idx);
    		$admin = Session::get('admin');
    		
    		// 기본 업데이트 데이터
    		$updateData = [
    				'status'            => $request->input('status'),
    				'response_contents' => $request->input('response_contents'),
    				'response_by'       => $admin['name'] ?? '',
    		];
    		
    		// status가 3일 경우에만 complete_at 추가
    		if ($request->input('status') == 3) {
    			$updateData['complete_at'] = Carbon::now();
    		}
    		
    		// update 실행
    		$inquire->update($updateData);
    		
    		DB::commit();
    		return response()->json(['status' => 'success']);
    		
    	} catch (\Exception $e) {
    		DB::rollBack();
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }


    //================================== 통관 이슈 ==================================
    // 통관 이슈 목록
    public function getCustomsIssueList(Request $request)
    {
		if (!auth()->user()->hasPerm('customsIssue.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = CustomsIssue::query();

    	if ($request->filled('keyword')) {
			$query->where(function ($q) use ($request) {
    			$q->where('title', 'like', '%' . $request->keyword . '%')
      				->orWhere('title_en', 'like', '%' . $request->keyword . '%')
      				->orWhere('title_vn', 'like', '%' . $request->keyword . '%');
    		});
    	}

    	$data = $query->orderByDesc('idx')->paginate(20)->withQueryString();

    	return view('admin.customsIssue', compact('data'));
    }

    // 통관 이슈 입력값 (제목/내용 ko 필수, en/vn 선택)
    private function customsIssueInput(Request $request)
    {
    	$data = [
    			'title'       => trim((string) $request->input('title')),
    			'title_en'    => trim((string) $request->input('title_en')),
    			'title_vn'    => trim((string) $request->input('title_vn')),
    			'contents'    => trim((string) $request->input('contents')),
    			'contents_en' => trim((string) $request->input('contents_en')),
    			'contents_vn' => trim((string) $request->input('contents_vn')),
    	];

    	if ($data['title'] === '') return [null, '제목(ko)을 입력해주세요.'];
    	if ($data['contents'] === '') return [null, '내용(ko)을 입력해주세요.'];

    	if ($request->hasFile('file')) {
    		$file = $request->file('file');
    		if (!$file->isValid()) return [null, '첨부파일 업로드에 실패했습니다.'];
    		if ($file->getSize() > 20 * 1024 * 1024) return [null, '첨부파일은 20MB 이하만 등록할 수 있습니다.'];
    	}

    	return [$data, null];
    }

    // 첨부파일 저장 (대시보드/목록의 다운로드 라우트로만 내려받도록 비공개 디스크에 저장)
    private function customsIssueStoreFile($file)
    {
    	return [
    			'ori_name'  => $file->getClientOriginalName(),
    			'up_name'   => $file->store('customsIssue', 'local'),
    			'file_ext'  => $file->getClientOriginalExtension(),
    			'file_size' => $file->getSize(),
    	];
    }

    // 통관 이슈 등록
    public function setCustomsIssueRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('customsIssue.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	[$data, $error] = $this->customsIssueInput($request);
    	if ($error) return response()->json(['status' => 'error', 'message' => $error]);

    	if ($request->hasFile('file')) {
    		$data += $this->customsIssueStoreFile($request->file('file'));
    	}

    	$admin = Session::get('admin');
    	CustomsIssue::create($data + ['created_by' => $admin['name'] ?? '']);

    	return response()->json(['status' => 'success']);
    }

    // 통관 이슈 상세 (대시보드 레이어 / 수정 폼)
    public function getCustomsIssueDetailForm(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('customsIssue.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	return response()->json(['status' => 'success', 'data' => CustomsIssue::findOrFail($idx)]);
    }

    // 통관 이슈 수정
    public function setCustomsIssueModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('customsIssue.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	[$data, $error] = $this->customsIssueInput($request);
    	if ($error) return response()->json(['status' => 'error', 'message' => $error]);

    	$issue = CustomsIssue::findOrFail($idx);
    	$oldPath = null;

    	if ($request->hasFile('file')) {
    		$oldPath = $issue->up_name;
    		$data += $this->customsIssueStoreFile($request->file('file'));
    	} elseif ($request->input('remove_file') == 1) {
    		$oldPath = $issue->up_name;
    		$data += ['ori_name' => null, 'up_name' => null, 'file_ext' => null, 'file_size' => null];
    	}

    	$admin = Session::get('admin');
    	$issue->update($data + ['updated_by' => $admin['name'] ?? '']);

    	if ($oldPath) {
    		Storage::disk('local')->delete($oldPath);
    	}

    	return response()->json(['status' => 'success']);
    }

    // 통관 이슈 삭제
    public function setCustomsIssueDelete(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('customsIssue.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$issue = CustomsIssue::findOrFail($idx);
    	$path = $issue->up_name;

    	$issue->delete();

    	if ($path) {
    		Storage::disk('local')->delete($path);
    	}

    	return response()->json(['status' => 'success']);
    }

    // 통관 이슈 첨부파일 다운로드
    public function getCustomsIssueDownload(Request $request, $idx)
    {
    	$issue = CustomsIssue::findOrFail($idx);

    	if (!$issue->up_name || !Storage::disk('local')->exists($issue->up_name)) {
    		abort(404, '파일이 존재하지 않습니다.');
    	}

    	return Storage::disk('local')->download($issue->up_name, $issue->ori_name);
    }


    //================================== FAQ ==================================
    // FAQ 목록
    public function getFaqList(Request $request)
    {
	    if (!auth()->user()->hasPerm('faq.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Faq::query();

    	if ($request->filled('use_yn')) {
    		$query->where('use_yn', $request->use_yn);
    	}

    	if ($request->filled('keyword')) {
			$query->where(function ($q) use ($request) {
    			$q->where('question', 'like', '%' . $request->keyword . '%')
      				->orWhere('question_en', 'like', '%' . $request->keyword . '%')
      				->orWhere('question_vn', 'like', '%' . $request->keyword . '%');
    		});
    	}

    	// 사용자 화면과 같은 순서 (정렬순서 → 등록순)
    	$data = $query->orderBy('orderby')->orderBy('idx')->paginate(20)->withQueryString();

    	return view('admin.faq', compact('data'));
    }

    // FAQ 입력값 (질문/답변 ko 필수, en/vn 선택)
    private function faqInput(Request $request)
    {
    	$data = [
    			'question'    => trim((string) $request->input('question')),
    			'question_en' => trim((string) $request->input('question_en')),
    			'question_vn' => trim((string) $request->input('question_vn')),
    			'answer'      => trim((string) $request->input('answer')),
    			'answer_en'   => trim((string) $request->input('answer_en')),
    			'answer_vn'   => trim((string) $request->input('answer_vn')),
    			'orderby'     => (int) $request->input('orderby'),
    			'use_yn'      => $request->input('use_yn') == 1 ? 1 : 0,
    	];

    	if ($data['question'] === '') return [null, '질문(ko)을 입력해주세요.'];
    	if ($data['answer'] === '') return [null, '답변(ko)을 입력해주세요.'];

    	return [$data, null];
    }

    // FAQ 등록
    public function setFaqRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('faq.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	[$data, $error] = $this->faqInput($request);
    	if ($error) return response()->json(['status' => 'error', 'message' => $error]);

    	$admin = Session::get('admin');
    	Faq::create($data + ['created_by' => $admin['name'] ?? '']);

    	return response()->json(['status' => 'success']);
    }

    // FAQ 수정 폼
    public function getFaqDetailForm(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('faq.update') && !auth()->user()->hasPerm('faq.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	return response()->json(['status' => 'success', 'data' => Faq::findOrFail($idx)]);
    }

    // FAQ 수정
    public function setFaqModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('faq.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	[$data, $error] = $this->faqInput($request);
    	if ($error) return response()->json(['status' => 'error', 'message' => $error]);

    	$admin = Session::get('admin');
    	Faq::findOrFail($idx)->update($data + ['updated_by' => $admin['name'] ?? '']);

    	return response()->json(['status' => 'success']);
    }

    // FAQ 삭제
    public function setFaqDelete(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('faq.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	Faq::findOrFail($idx)->delete();

    	return response()->json(['status' => 'success']);
    }


    //================================== TESTBED 문의 ==================================
    // 문의 목록
    public function getInquireTestbedList(Request $request)
    {
		if (!auth()->user()->hasPerm('inquireTestbed.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Testbedinquire::with(['product', 'partner']);

    	// 신청구분 (homepage : 홈페이지, rips : RIPS 업체)
    	$query->source($request->input('source'));

    	if ($request->filled('status')) {
    		$query->where('status', $request->status);
    	}

    	if ($request->filled('inquire_hp')) {
    		$query->where('inquire_hp', 'like', '%' . $request->inquire_hp . '%');
    	}

    	if ($request->filled('inquire_company')) {
    		$query->where('inquire_company', 'like', '%' . $request->inquire_company . '%');
    	}

    	if ($request->filled('inquire_address')) {
    		$query->where('inquire_address', 'like', '%' . $request->inquire_address . '%');
    	}

    	$data = $query->orderByDesc('idx')->paginate(10)->withQueryString();

    	return view('admin.inquireTest', compact('data'));
    }

    public function getInquireTestbedExport(Request $request)
    {
 		if (!auth()->user()->hasPerm('inquireTestbed.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

   		$filters = $request->only(['source', 'status', 'inquire_hp', 'inquire_company', 'inquire_address']);

    	$timestamp = Carbon::now()->format('Ymd_His');
    	$filename = 'TESTBED문의목록_' . $timestamp . '.xlsx';

    	return Excel::download(new inquireTestbedExport($filters), $filename);
    }

    // 문의 수정 폼
    public function getInquireTestbedDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('inquireTestbed.save')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$inquire = Testbedinquire::findOrFail($idx);

    		DB::beginTransaction();

    		// response_at이 null일 때만 업데이트
    		if (is_null($inquire->response_at)) {
    			$inquire->update([
    					'response_at' => Carbon::now()
    			]);
    		}

    		if($inquire->status == '1'){
    			$inquire->update([
    					'status' => '2'
    			]);
    		}

    		DB::commit();

    		$data = $idx ? Testbedinquire::with(['product', 'partner'])->findOrFail($idx) : null;

    		return response()->json(['status' => 'success', 'data' => $data]);
    	} catch (\Exception $e) {
    		if (DB::transactionLevel() > 0) {
    			DB::rollBack();
    		}
    		return response()->json(['status'  => 'error','message' => $e->getMessage(),], 500);
    	}
    }

    // 문의 수정
    public function setInquireTestbedModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('inquireTestbed.save')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		DB::beginTransaction();

    		$inquire = Testbedinquire::findOrFail($idx);
    		$admin = Session::get('admin');
    		$status = (int) $request->input('status');

    		if (!array_key_exists($status, Testbedinquire::STATUSES)) {
    			DB::rollBack();
    			return response()->json(['status' => 'error', 'message' => '처리상태를 선택해주세요.']);
    		}

    		// 기본 업데이트 데이터
    		$updateData = [
    				'status'            => $status,
    				'response_contents' => $request->input('response_contents'),
    				'response_by'       => $admin['name'] ?? '',
    		];

    		// 답변완료(3) / 진행완료(5) 로 바뀔 때 처리완료일 기록
    		if (in_array($status, [3, Testbedinquire::STATUS_DONE]) && (int) $inquire->status !== $status) {
    			$updateData['complete_at'] = Carbon::now();
    		}

    		// 피드백 (RIPS 업체에 공개) : 내용이 바뀔 때만 작성자 / 일시 갱신
    		$feedback = trim((string) $request->input('feedback'));
    		if ($feedback !== trim((string) $inquire->feedback)) {
    			$updateData['feedback']    = $feedback !== '' ? $feedback : null;
    			$updateData['feedback_by'] = $feedback !== '' ? ($admin['name'] ?? auth()->user()?->name) : null;
    			$updateData['feedback_at'] = $feedback !== '' ? Carbon::now() : null;
    		}

    		// update 실행
    		$inquire->update($updateData);

    		DB::commit();
    		return response()->json(['status' => 'success']);

    	} catch (\Exception $e) {
    		DB::rollBack();
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }



    //================================== 관리자 로그 ==================================
    // 관리자 작업 로그 (등록 / 수정 / 삭제), 최신순
    public function getAdminLogList(Request $request)
    {
    	// 기간 : 기본 최근 7일
    	$end   = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::today();
    	$start = $request->filled('start_date') ? Carbon::parse($request->start_date) : $end->copy()->subDays(6);
    	if ($start->gt($end)) [$start, $end] = [$end, $start];

    	$query = AdminLog::whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
    	->when($request->filled('admin_idx'), fn ($q) => $q->where('admin_idx', $request->admin_idx))
    	->when($request->filled('menu'), fn ($q) => $q->where('menu', $request->menu))
    	->when($request->filled('action'), fn ($q) => $q->where('action', $request->action))
    	->when($request->filled('result'), fn ($q) => $q->where('result', $request->result))
    	->when($request->filled('keyword'), fn ($q) => $q->where('target_name', 'like', '%' . $request->keyword . '%'));

    	$actionCounts = (clone $query)->selectRaw('action, COUNT(*) AS cnt')->groupBy('action')->pluck('cnt', 'action');

    	$data = $query->orderByDesc('created_at')->orderByDesc('idx')->paginate(30)->withQueryString();

    	$admins = Admin::orderBy('name')->get(['idx', 'id', 'name']);
    	$menus  = AdminLog::distinct()->orderBy('menu')->pluck('menu');

    	return view('admin.adminLog', compact('data', 'start', 'end', 'admins', 'menus', 'actionCounts'));
    }


    //================================== 관리자 ==================================
    // 관리자 목록
    public function getUserList(Request $request)
    {
		if (!auth()->user()->hasPerm('user.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$admin = Admin::query();
    	
    	if ($request->filled('level')) {
    		$admin->where('level', $request->level);
    	}

    	if ($request->filled('name')) {
    		$admin->where('name', 'like', '%' . $request->name . '%');
    	}

		if ($request->filled('hp')) {
    		$admin->where('hp', 'like', '%' . $request->hp . '%');
    	}

		if ($request->filled('email')) {
    		$admin->where('email', 'like', '%' . $request->email . '%');
    	}

		$admin->where('id', '!=', 'admin');

    	// 최신순 정렬 + 페이지네이션
    	$data = $admin->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	return view('admin.admin', compact('data'));
    }
    
    // 관리자 등록
    public function setUserRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('user.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
			$user = Session::get('admin');
    		
    		// 중복 체크
    		$exists = Admin::where('id', $request->input('id'))->exists();
    		if ($exists) {
    			return response("
                <script>
                    alert('중복된 아이디입니다.');
                </script>
            ");
    		}
    		
    		// 계정 생성
    		$admin = Admin::create([
    				'id'        => $request->input('id'),
    				'name'      => $request->input('name'),
    				'pw'        => Hash::make($request->input('pw')),
    				'hp'        => $request->input('hp'),
    				'email'     => $request->input('email'),
    				'level'     => 2,
    				'created_by'	=> $user['name'] ?? ''
    		]);

			// 권한 저장 
			$permissions = $request->input('permissions'); // 스크립트에서 보낸 배열
			if ($permissions && is_array($permissions)) {
				foreach ($permissions as $permKey) {
					\App\Models\AdminPermissions::create([
						'admin_idx'      => $admin->idx,
						'permission_key' => $permKey
					]);
				}
			}			
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // 관리자 수정 폼
     public function getUserDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('user.update') && !auth()->user()->hasPerm('user.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$admin = Admin::with('permissions')->find($idx);

		if (!$admin) {
			return response()->json(['status' => 'error', 'message' => '사용자를 찾을 수 없습니다.']);
		}

		// 해당 관리자가 가진 권한 키값들만 배열로 추출: ['history.view', 'notice.create', ...]
    	$permissions = $admin->permissions->pluck('permission_key')->toArray();
    	
    	return response()->json(['status' => 'success', 'data' => $admin, 'permissions' => $permissions]);
    }
    
    // 관리자 수정
    public function setUserModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('user.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$user = Session::get('admin');
    		
    		$admin = Admin::findOrFail($idx);
    		$data = [
    		    	'name'         	=> $request->input('name'),		
					'hp'         	=> $request->input('hp'),
    				'email'      	=> $request->input('email'),
    				'updated_by' 	=> $user['name'] ?? null,
    		];
    		
			// 비밀번호가 오고, 공백이 아닐 때만 해시하여 반영
			if ($request->has('pw') && trim((string) $request->input('pw')) !== '') {
    			$data['pw'] = Hash::make($request->input('pw')); // bcrypt
    		}
    		
    		$admin->update($data);

			// 권한 저장 
			$permissions = $request->input('permissions'); // 스크립트에서 보낸 배열
			if ($permissions && is_array($permissions)) {
				// 기존 권한 삭제
				$admin->permissions()->delete();

				// 권한 재설정
				foreach ($permissions as $permKey) {
					\App\Models\AdminPermissions::create([
						'admin_idx'      => $admin->idx,
						'permission_key' => $permKey
					]);
				}
			}	
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // 관리자 삭제
    public function setUserDelete($idx)
    {
		if (!auth()->user()->hasPerm('user.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		// 계정 확인
    	$admin = Admin::findOrFail($idx);
		
		// 계정 삭제
    	$admin->delete();

		// 권한 삭제
		$admin->permissions()->delete();
    	
    	return response()->json(['status' => 'success']);
    }


	//================================== 부서 관리 ==================================
	// 부서 목록 조회
	public function getDepartmentList(Request $request)
	{
		// if (!auth()->user()->hasPerm('department.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$query = Department::query();
		
		if ($request->filled('depart_name')) {
			$query->where('depart_name', 'like', '%' . $request->depart_name . '%');
		}

		// 모든 부서를 페이지네이션으로 가져옴 (삭제된 N 상태도 관리자가 볼 수 있게 함)
		$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
		
		return view('admin.department', compact('data'));
	}

	// 부서 등록
	public function setDepartmentRegist(Request $request)
	{
		// if (!auth()->user()->hasPerm('department.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		try {
			$admin = Session::get('admin');
			
			Department::create([	
				'depart_name' => $request->input('depart_name'),
				'use_yn'      => $request->input('use_yn', 'Y'),
				'created_by'  => $admin['name'] ?? 'system',
				'created_at'  => now()
			]);

			return response()->json(['status' => 'success']);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
		}
	}

    // 부서 수정 폼
    public function getDepartmentDetailForm(Request $request, $idx = null)
    {
		// if (!auth()->user()->hasPerm('department.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data = $idx ? Department::findOrFail($idx) : null;

    	return response()->json(['status' => 'success', 'data' => $data]);
    }	

	// 부서 수정
	public function setDepartmentModify(Request $request, $idx)
	{
		// if (!auth()->user()->hasPerm('department.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		try {
			$admin = Session::get('admin');
			$department = Department::findOrFail($idx);
			
			$department->update([
				'depart_name' => $request->input('depart_name'),
				'use_yn'      => $request->input('use_yn', 'Y'),
				'updated_by'  => $admin['name'] ?? 'system',
				'updated_at'  => now()
			]);

			return response()->json(['status' => 'success']);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
		}
	}

	// 부서 삭제 (비활성화)
	public function setDepartmentDelete($idx)
	{
		// if (!auth()->user()->hasPerm('department.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		try {
			$department = Department::findOrFail($idx);
			
			// 하위 카테고리나 템플릿이 있는지 확인 후 경고
			if ($department->categories()->where('use_yn', 'Y')->exists()) {
				return response()->json(['status' => 'error', 'message' => '하위 카테고리가 존재하여 비활성화할 수 없습니다.']);
			}

			$department->update(['use_yn' => 'N']);

			return response()->json(['status' => 'success']);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
		}
	}


    //================================== 외주사 ==================================
    // 외주사 목록
    public function getOutCompanyList(Request $request)
    {
		if (!auth()->user()->hasPerm('outcompany.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query  = OutCompany::with(['companies', 'outuser'])
					->where('use_yn', 'Y')
					->withCount('outuser');

		// 발전소(Company) 검색 - comp_idx
		if ($request->filled('comp_idx')) {
			$query->whereHas('companies', function($q) use ($request) {
				$q->where('company.idx', $request->comp_idx);
			});
		}

		// 발전소(Company) 검색 - comp_name (텍스트)
		if ($request->filled('comp_name')) {
			$query->whereHas('companies', function($q) use ($request) {
				$q->where('company.comp_name', 'like', '%' . $request->comp_name . '%');
			});
		}

		// 발전소(Company) 검색 - comp_name (텍스트)
		if ($request->filled('out_id')) {
			$query->where('out_company.id', $request->out_id);
		}

		// 외주사(OutCompany) 검색 - idx (Select)
		if ($request->filled('out_idx')) {
			$query->where('out_company.idx', $request->out_idx);
		}

		// 외주사(OutCompany) 검색 - out_name (텍스트)
		if ($request->filled('out_name')) {
			$query->where('out_company.out_name', 'like', '%' . $request->out_name . '%');
		}

		// 담당자(OutUser) 검색 - user_name (텍스트)
		if ($request->filled('user_name')) {
			$query->whereHas('outuser', function($q) use ($request) {
				$q->where('out_user.user_name', 'like', '%' . $request->user_name . '%');
			});
		}

		// 담당자(OutUser) 검색 - user_phone (텍스트)
		if ($request->filled('user_phone')) {
			$query->whereHas('outuser', function($q) use ($request) {
				$q->where('out_user.user_phone', 'like', '%' . $request->user_phone . '%');
			});
		}

    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

		$companyList = Company::orderBy('comp_name', 'asc')->get();
		$outcompanyList = OutCompany::where('use_yn', 'Y')->orderBy('out_name', 'asc')->get();
		$outuserList = OutUser::where('use_yn', 'Y')->orderBy('user_name', 'asc')->get();
    	
    	return view('admin.outCompany', compact('data', 'companyList', 'outcompanyList', 'outuserList'));
    }
    
    // 외주사 등록
    public function setOutCompanyRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('outcompany.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
			$user = Session::get('admin');
    		
    		// 중복 체크
    		$exists = Admin::where('id', $request->input('id'))->exists();
    		if ($exists) {
    			return response("
                <script>
                    alert('중복된 아이디입니다.');
                </script>
            ");
    		}
    		
    		// 계정 생성
    		$outcompany = OutCompany::create([
    				'out_name'      	=> $request->input('out_name'),
    				'id'        		=> $request->input('id'),
    				'password'        	=> Hash::make($request->input('password')),
    				'ceo'        		=> $request->input('ceo'),
					'phone'        		=> $request->input('phone'),
    				'email'     		=> $request->input('email'),
    				'address'     		=> $request->input('address'),
    				'business_number'	=> $request->input('business_number'),
					'use_yn'			=> 'Y',
    				'created_by'		=> $user['name'] ?? ''
    		]);
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // 외주사 수정 폼
     public function getOutCompanyDetail(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('outcompany.update') && !auth()->user()->hasPerm('user.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$data  = OutCompany::with(['companies', 'outuser'])->findOrFail($idx);

		if (!$data) {
			return response()->json(['status' => 'error', 'message' => '사용자를 찾을 수 없습니다.']);
		}

		$companyList = Company::orderBy('comp_name', 'asc')->get();
		$outuserList = OutUser::where('out_idx', $idx)->where('use_yn', 'Y')->get();

    	return view('admin.outCompanyDetail', compact('data', 'companyList', 'outuserList'));
    }
    
    // 외주사 수정
    public function setOutCompanyModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('outcompany.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$user = Session::get('admin');

    		$outcomapny = OutCompany::findOrFail($idx);
    		$data = [
    		    	'out_name'         	=> $request->input('out_name'),		
					'ceo'         		=> $request->input('ceo'),
    				'business_number'   => $request->input('business_number'),
					'phone'         	=> $request->input('phone'),
					'email'         	=> $request->input('email'),
					'address'         	=> $request->input('address'),
    				'updated_by' 		=> $user['name'] ?? null,
    		];
    		
			// 비밀번호가 오고, 공백이 아닐 때만 해시하여 반영
			if ($request->has('password') && trim((string) $request->input('password')) !== '') {
    			$data['password'] = Hash::make($request->input('password')); // bcrypt
    		}
    		
    		$outcomapny->update($data);

			return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // 외주사 삭제
    public function setOutCompanyDelete($idx)
    {
		if (!auth()->user()->hasPerm('outcompany.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		// 외주사 확인
    	$outcomapny = OutCompany::findOrFail($idx);
		
		// 외주사 삭제
		$outcomapny->update([
			'use_yn' => 'N'
		]);
    	
    	return response()->json(['status' => 'success']);
    }

    
    //================================== 외주사 > 발전소 ==================================
    // 외주사 > 발전소 목록
    public function getOutCompanyCompanyList(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('outcompany.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data  = CompanyOut::with(['company', 'outuser'])
				->where('out_idx', $idx)
				->where('use_yn', 'Y')
				->get();

		return response()->json(['status' => 'success', 'data' => $data]);
    }
    
    // 외주사 > 발전소 저장
    public function setOutCompanyCompanySave(Request $request)
	{
		if (!auth()->user()->hasPerm('outcompany.save')) {
			return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);
		}

		try {
			$user = Session::get('admin');

			if ($request->idx) {
				// 1. 수정 시
				$data = CompanyOut::findOrFail($request->idx);
				$data->update([
					'comp_idx'   => $request->comp_idx,
					'out_idx'    => $request->out_idx,
					'work_type'  => $request->work_type,
					'start_date' => $request->start_date,
					'user_idx'   => $request->user_idx ?: null,
					'updated_at' => now(),
					'updated_by' => $user['name'] ?? null,
				]);                 
			} else {
				// 2. 신규 등록 (오타 수정: CompanySalesMaster -> CompanyOut)
				$data = CompanyOut::create([
					'comp_idx'   => $request->comp_idx,
					'out_idx'    => $request->out_idx,
					'work_type'  => $request->work_type,
					'start_date' => $request->start_date,
					'user_idx'   => $request->user_idx ?: null,
					'use_yn'	 => 'Y',
					'created_at' => now(),
					'created_by' => $user['name'] ?? null,
				]);                 
			}

			return response()->json(['status' => 'success', 'message' => '저장되었습니다.', 'data' => $data]);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => '저장 중 오류가 발생했습니다.', 'error' => $e->getMessage()], 500);
		}
	}
    
    // 외주사 > 발전소 삭제
    public function setOutCompanyCompanyDelete(Request $request)
    {
		if (!auth()->user()->hasPerm('outcompany.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		// 외주사 확인
    	$companyout = CompanyOut::findOrFail($request->idx);
		
		// 외주사 삭제
	   	$companyout->update([
					'use_yn'  => 'N',
					'updated_at' => now(),
					'updated_by' => $user['name'] ?? null,
				]); 		
    	
    	return response()->json(['status' => 'success']);
    }

    
    //================================== 외주사 > 담당자 ==================================
    // 외주사 > 담당자 목록
    public function getOutCompanyUserList(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('outcompany.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data  = OutUser::where('out_idx', $idx)
				->where('use_yn', 'Y')
				->orderByDesc('user_name')
				->get();
		
		return response()->json(['status' => 'success', 'data' => $data]);
    }
    
    // 외주사 > 담당자 저장
    public function setOutCompanyUserSave(Request $request)
	{
		if (!auth()->user()->hasPerm('outcompany.save')) {
			return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);
		}

		try {
			$user = Session::get('admin');

			if ($request->idx) {
				// 1. 수정 시
				$data = OutUser::findOrFail($request->idx);
				$data->update([
					'user_name'  => $request->user_name,
					'user_phone' => $request->user_phone,
					'work_type'  => $request->work_type,
					'updated_at' => now(),
					'updated_by' => $user['name'] ?? null,
				]);                 
			} else {
				// 2. 신규 등록 (오타 수정: CompanySalesMaster -> CompanyOut)
				$data = OutUser::create([
					'out_idx'    => $request->out_idx, // 외주사 PK
					'user_name'  => $request->user_name,
					'user_phone' => $request->user_phone,
					'work_type'  => $request->work_type,
					'reg_type'   => 'R', // 직접 등록
					'use_yn'	 => 'Y',
					'created_at' => now(),
					'created_by' => $user['name'] ?? null,
				]);                 
			}

			return response()->json(['status' => 'success', 'message' => '저장되었습니다.', 'data' => $data]);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => '저장 중 오류가 발생했습니다.', 'error' => $e->getMessage()], 500);
		}
	}
    
    // 외주사 > 담당자 삭제
    public function setOutCompanyUserDelete(Request $request)
    {
		if (!auth()->user()->hasPerm('outcompany.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		// 외주사 확인
    	$outuser = OutUser::findOrFail($request->idx);
		
		// 외주사 삭제
		$user = Session::get('admin');
    	$outuser->update([
					'use_yn'  => 'N',
					'updated_at' => now(),
					'updated_by' => $user['name'] ?? null,
				]); 
    	
    	return response()->json(['status' => 'success']);
    }
    	
}