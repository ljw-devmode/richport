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
use Maatwebsite\Excel\Facades\Excel;
use function Illuminate\Log\log;
use Carbon\Carbon;
use DateException;
use App\Models\Admin;
use App\Models\AdminPermissions;
use App\Models\Cities;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\CompanyKwh;
use App\Models\CompanyManage;
use App\Models\CompanySales;
use App\Models\CompanyYearKwh;
use App\Models\CompanyYearKwhHours;
use App\Models\CompanyReportMonthly;
use App\Models\FormTypes;
use App\Models\History;
use App\Models\Inquire;
use App\Models\InquireTypes;
use App\Models\KmaObv;
use App\Models\KmaSumgsr;
use App\Models\Notice;
use App\Models\NoticeFiles;
use App\Models\Regions;
use App\Models\ProjectMemo;
use App\Models\Portfolio;
use App\Models\PortfolioFiles;
use App\Models\Types;
use App\Exports\historyExport;
use App\Exports\inquireExport;
use App\Exports\kmaMonthlyExport;
use App\Exports\kmaDbExport;
use App\Exports\noticeExport;
use App\Exports\portfolioExport;
use App\Services\GoogleSheetService;
use App\Services\KmaAsosService;
use App\Services\ReportService;

class AdminController extends Controller
{
	//================================== 로그인 ==================================		
    public function loginGet()
    {
    	
    	/* $account = Admin::findOrFail(1);
    	$data = [
    			'name'       	=> '관리자',
    	];
    	
    	$data['pw'] = Hash::make('manager0809_');
    	
    	$account->update($data); */
    	
        return view('admin.login');
    }
    
    public function loginPost(Request $request)
    {
    	$credentials = $request->validate([
    			'id' => 'required|string',
    			'pw' => 'required|string',
    	]);
    	
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
	public function getCities($regions_idx)
    {
    	$cities = Cities::where('regions_idx', $regions_idx)->orderBy('name_kr')->get(['idx', 'name_kr']);
    	
    	return response()->json($cities);
    }


	//================================== 대시보드 ==================================
    // 대시보드
	public function getDashboard(GoogleSheetService $sheet)
    {
        // 대시보드는 캐시 필수 (API 호출비용/속도/쿼터)
        $rows = Cache::remember('dashboard_sheet_rows', 60, function () use ($sheet) {
            return $sheet->getRange('dashboard!A1:L1000'); // 시트명/범위
        });

        // 1행이 헤더라면 매핑
        $header = $rows[0] ?? [];
        $dataRows = array_slice($rows, 1);

        $items = array_map(function ($row) use ($header) {
            return array_combine($header, array_pad($row, count($header), null));
        }, $dataRows);

        // 여기서 상태별 집계/필터링 등 가공
        $summary = [
            'total' => count($items),
            'total_kw' => array_sum(array_map(fn($x) => (float) str_replace(',', '', $x['용량(kW)'] ?? 0), $items)),

            'contract' => collect($items) -> filter(fn($x) => !empty(trim($x['계약 날인'] ?? ''))) -> count(),
            'contract_kw' => collect($items) -> filter(fn($x) => !empty(trim($x['계약 날인'] ?? ''))) -> sum(fn($x) => (float) str_replace(',', '', $x['용량(kW)'] ?? 0)),            

            'process_confirm' => count(array_filter($items, fn($x)=>$x['공사 단계']==='인허가 진행 중')),
            'process_confirm_kw' => collect($items) -> where('공사 단계', '인허가 진행 중') -> sum(fn($x) => (float) str_replace(',', '', $x['용량(kW)'] ?? 0)),

			'process_ready' => collect($items) -> filter(fn($x) => !empty(trim($x['사용전 검사'] ?? ''))) -> count(),
            'process_ready_kw' => collect($items) -> filter(fn($x) => !empty(trim($x['사용전 검사'] ?? ''))) -> sum(fn($x) => (float) str_replace(',', '', $x['용량(kW)'] ?? 0)),   

            'issue_red' => count(array_filter($items, fn($x)=>$x['이슈']==='협의 난항')),
            'issue_red_kw' => collect($items) -> where('이슈', '협의 난항') -> sum(fn($x) => (float) str_replace(',', '', $x['용량(kW)'] ?? 0)),
            'issue_yellow' => count(array_filter($items, fn($x)=>$x['이슈']==='협의 중')),
            'issue_yellow_kw' => collect($items) -> where('이슈', '협의 중') -> sum(fn($x) => (float) str_replace(',', '', $x['용량(kW)'] ?? 0)),
            'issue_green' => count(array_filter($items, fn($x)=>$x['이슈']==='원활')),
            'issue_green_kw' => collect($items) -> where('이슈', '원활') -> sum(fn($x) => (float) str_replace(',', '', $x['용량(kW)'] ?? 0)),

            'completed' => count(array_filter($items, fn($x)=>$x['공사 단계']==='완공')),
            'completed_kw' => collect($items) -> where('공사 단계', '완공') -> sum(fn($x) => (float) str_replace(',', '', $x['용량(kW)'] ?? 0)),

            'management' => collect($items) -> filter(fn($x) => !empty(trim($x['운영 여부'] ?? ''))) -> count(),
            'management_kw' => collect($items) -> filter(fn($x) => !empty(trim($x['운영 여부'] ?? ''))) -> sum(fn($x) => (float) str_replace(',', '', $x['용량(kW)'] ?? 0)), 			
        ];

        return view('admin.dashboard', compact('header', 'items', 'summary'));
    }

	// 메모 목록
    public function dashboardMemoList($name)
    {
        return ProjectMemo::where('name', $name)
            ->orderByDesc('created_at')
            ->get();
    }

	// 메모 등록
	public function dashboardMemoSave(Request $request)
    {
        $memo = ProjectMemo::create([
            'name' => $request->name,
            'memo' => $request->memo
        ]);

        return ProjectMemo::where('name', $request->name)
            ->orderByDesc('created_at')
            ->get();
    }

	// 메모 삭제
    public function dashboardMemoDelete(Request $request, $idx)
    {
		$request->validate([
            'name' => 'required|string',
        ]);

        ProjectMemo::find($idx)?->delete();

        return ProjectMemo::where('name', $request->name)
            ->orderByDesc('created_at')
            ->get();
    }    

	// 메모 수정
    public function dashboardMemoUpdate(Request $request, $idx)
    {
        $request->validate([
            'memo' => 'required|string',
            'name' => 'required|string',
        ]);

        $m = ProjectMemo::findOrFail($idx);

        // 안전: 다른 프로젝트 메모를 실수로 수정 방지(선택)
        if ($m->name !== $request->name) {
            return response()->json(['error' => 'project mismatch'], 400);
        }

        $m->memo = $request->memo;
        $m->save();

        return ProjectMemo::where('name', $request->name)
            ->orderByDesc('created_at')
            ->get();
    }
    
    
    //================================== 회사소개 ==================================
    // 회사소개 목록
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
      				->orWhere('subject_cn', 'like', '%' . $request->subject . '%');
    		});
    	}
    	
    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	return view('admin.history', compact('data','year'));
    }
    
	// 회사소개 엑셀 다운로드
    public function getHistoryExport(Request $request)
    {
	    if (!auth()->user()->hasPerm('history.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$filters = $request->only(['year', 'subject']);
    	
    	$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
    	$filename = '연혁목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new historyExport($filters), $filename);
    }
    
    // 회사소개 등록
    public function setHistoryRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('history.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		History::create([
    				'subject'		=> $request->input('subject'),
					'subject_en'		=> $request->input('subject_en'),
					'subject_cn'		=> $request->input('subject_cn'),
    				'year'			=> $request->input('year'),
    				'month'			=> $request->input('month'),
    				'created_by'	=> $admin['name'] ?? ''
    		]);
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // 회사소개 수정 폼
    public function getHistoryDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('history.update') && auth()->user()->hasPerm('history.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$data = $idx ? History::findOrFail($idx) : null;
    	
    	return response()->json(['status' => 'success', 'data' => $data]);
    }
    
    // 회사소개 수정
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
					'subject_cn'		=> $request->input('subject_cn'),
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
    
    // 회사소개 삭제
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
    
    
    //================================== 사업소개 ==================================
    // 포트폴리오 목록
    public function getPortfolioList(Request $request)
    {
		if (!auth()->user()->hasPerm('portfolio.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Portfolio::with('types','formtypes','regions','cities');
    	
    	// 검색 조건 처리
    	if ($request->filled('types_idx')) {
    		$query->where('portfolio.types_idx', $request->types_idx);
    	}
    	
    	if ($request->filled('form_idx')) {
    		$query->where('portfolio.form_idx', $request->form_idx);
    	}
    	
    	if ((int)$request->is_url === 1) {
    		$query->where('portfolio.url', '!=', '');
    	}
    	
    	if ((int)$request->is_blog === 1) {
    		$query->where('portfolio.blog', '!=', '');
    	}
    	
    	if ($request->filled('home_recommend')) {
    		$query->where('portfolio.home_recommend', $request->home_recommend);
    	}
    	
    	if ($request->filled('introduce_recommend')) {
    		$query->where('portfolio.introduce_recommend', $request->introduce_recommend);
    	}
    	
    	if ($request->filled('portfolio_recommend')) {
    		$query->where('portfolio.portfolio_recommend', $request->portfolio_recommend);
    	}
    	
    	if ($request->filled('regions_idx')) {
    		$query->where('portfolio.regions_idx', $request->regions_idx);
    	}
    	
    	if ($request->filled('cities_idx')) {
    		$query->where('portfolio.cities_idx', $request->cities_idx);
    	}
    	
    	if ($request->filled('completion_date')) {
    		$query->whereDate('portfolio.completion_date', [$request->completion_date]);
    	}
    	
    	if ($request->filled('station_name')) {
    		$query->where('portfolio.station_name', 'like', '%' . $request->station_name . '%');
    	}
    	
    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	$data->getCollection()->transform(function ($item) {
    		// URL / BLOG 존재 여부 → O / X
    		$item->is_url  = (!empty($item->url))  ? 'O' : 'X';
    		$item->is_blog = (!empty($item->blog)) ? 'O' : 'X';
    		
    		$item->volume_formatted = number_format((int)$item->volume);
    		
    		// 추천 플래그 1/0 → Y / N
    		$toYN = function ($v) {
    			return ((string)$v === '1') ? 'Y' : 'N';
    		};
    		
    		$item->home_recommend_text       = $toYN($item->home_recommend);
    		$item->introduce_recommend_text  = $toYN($item->introduce_recommend);
    		$item->portfolio_recommend_text  = $toYN($item->portfolio_recommend);
    		
    		$item->created_date = optional($item->created_at)->format('Y-m-d');
    		
    		return $item;
    	});
    	
    	$types = Types::orderBy('idx')->get();
    	$regions = Regions::orderBy('idx')->get();
    	$forms = FormTypes::orderBy('idx')->get();
    	
    	return view('admin.portfolio', compact('data','regions','types','forms'));
    }
    
	// 포트폴리오 엑셀 다운로드
    public function getPortfolioExport(Request $request)
    {
		if (!auth()->user()->hasPerm('portfolio.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$filters = $request->only(['types_idx', 'is_url', 'is_blog', 'home_recommend', 'introduce_recommend', 'portfolio_recommend', 'regions_idx', 'cities_idx', 'completion_date', 'station_name']);
    	
    	$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
    	$filename = '사업소개목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new portfolioExport($filters), $filename);
    }
    
    // 포트폴리오 등록
    public function setPortfolioRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('portfolio.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		$portfolio = Portfolio::create([
    				'year'					=> $request->input('year'),
    				'types_idx'				=> $request->input('types_idx'),
    				'form_idx'				=> $request->input('form_idx'),
    				'completion_date'		=> $request->input('completion_date'),
    				'regions_idx'			=> $request->input('regions_idx'),
    				'cities_idx'			=> $request->input('cities_idx'),
    				'volume'				=> $request->input('volume'),
    				'price'					=> $request->input('price'),
					'price_en'				=> $request->input('price_en'),
					'price_cn'				=> $request->input('price_cn'),
    				'station_name'			=> $request->input('station_name'),
    				'station_name_en'		=> $request->input('station_name_en'),
    				'station_name_cn'		=> $request->input('station_name_cn'),
    				'url'					=> $request->input('url') ?? '',
    				'blog'					=> $request->input('blog') ?? '',
    				'youtube_iframe'		=> $request->input('youtube_iframe') ?? '',
    				'youtube_subject'		=> $request->input('youtube_subject') ?? '',
    				'youtube_subject_en'	=> $request->input('youtube_subject_en') ?? '',
    				'youtube_subject_cn'	=> $request->input('youtube_subject_cn') ?? '',
    				'youtube_contents'		=> $request->input('youtube_contents') ?? '',
    				'youtube_contents_en'	=> $request->input('youtube_contents_en') ?? '',
    				'youtube_contents_cn'	=> $request->input('youtube_contents_cn') ?? '',
    				'blog_contents'			=> $request->input('blog_contents') ?? '',
    				'blog_contents_en'		=> $request->input('blog_contents_en') ?? '',
    				'blog_contents_cn'		=> $request->input('blog_contents_cn') ?? '',
    				'home_recommend'		=> $request->input('home_recommend'),
    				'introduce_recommend'	=> $request->input('introduce_recommend'),
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
    				'year'					=> $request->input('year'),
    				'types_idx'				=> $request->input('types_idx'),
    				'form_idx'				=> $request->input('form_idx'),
    				'completion_date'		=> $request->input('completion_date'),
    				'regions_idx'			=> $request->input('regions_idx'),
    				'cities_idx'			=> $request->input('cities_idx'),
    				'volume'				=> $request->input('volume'),
    				'price'					=> $request->input('price'),
    				'price_en'				=> $request->input('price_en'),
    				'price_cn'				=> $request->input('price_cn'),
    				'station_name'			=> $request->input('station_name'),
    				'station_name_en'		=> $request->input('station_name_en'),
    				'station_name_cn'		=> $request->input('station_name_cn'),
    				'url'					=> $request->input('url') ?? '',
    				'blog'					=> $request->input('blog') ?? '',
    				'youtube_iframe'		=> $request->input('youtube_iframe') ?? '',
    				'youtube_subject'		=> $request->input('youtube_subject') ?? '',
    				'youtube_subject_en'	=> $request->input('youtube_subject_en') ?? '',
    				'youtube_subject_cn'	=> $request->input('youtube_subject_cn') ?? '',
    				'youtube_contents'		=> $request->input('youtube_contents') ?? '',
    				'youtube_contents_en'	=> $request->input('youtube_contents_en') ?? '',
    				'youtube_contents_cn'	=> $request->input('youtube_contents_cn') ?? '',
    				'blog_contents'			=> $request->input('blog_contents') ?? '',
    				'blog_contents_en'		=> $request->input('blog_contents_en') ?? '',
    				'blog_contents_cn'		=> $request->input('blog_contents_cn') ?? '',
    				'home_recommend'		=> $request->input('home_recommend'),
    				'introduce_recommend'	=> $request->input('introduce_recommend'),
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




    
	//================================== SPC 리포트 ==================================	
	// SPC 리포트 목록
    public function getReportSpcList(Request $request)
    {
		if (!auth()->user()->hasPerm('spc_report.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data = DB::table('company as comp')
				->leftJoin('company_kwh as kwh', 'kwh.comp_idx', '=', 'comp.idx')
				->leftJoin('company_group as grp', 'grp.idx', '=', 'comp.group_idx')
				->leftJoin('company_sales as sales', function ($join) {
					$join->on('sales.comp_idx', '=', 'comp.idx')
						->on('sales.comp_idx', '=', 'kwh.comp_idx')
						->on('sales.tgt_year', '=', 'kwh.year')
						->on('sales.tgt_month', '=', 'kwh.month');
				})
				->where('comp.group_idx', '<>', 0) // group_idx 가 0인 것은 spc 미 소속 발전소
				->select(
					'kwh.year',
					'kwh.month',
					'grp.idx as group_idx',
					'grp.group_name',
					DB::raw('COUNT(comp.idx) as comp_count'),
					DB::raw('ROUND(SUM(kwh.act_kwh), 2) as total_kwh'),
					DB::raw('ROUND(SUM(sales.sales), 2) as total_sales')
				)
				->groupBy('kwh.year', 'kwh.month', 'grp.idx', 'grp.group_name')
				->orderByDesc('kwh.year')
				->orderByDesc('kwh.month')
				->orderBy('grp.group_name')
				->paginate(10)->withQueryString();
    	
    	return view('admin.reportSpc', compact('data'));
    }

	// SPC 리포트 보기
	public function getSpcMonthlyReport(Request $request)
    {
		if (!auth()->user()->hasPerm('spc_report.detail')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

        $year = $request->input('year');
        $month = $request->input('month');
        $group_idx = $request->input('group_idx');

        if (!$year || !$month || !$group_idx) {
            abort(400, '파라미터 누락');
        }

		$mjToKwh = 0.277784755950055;

		// 리포트 표지 하단 일자 표시
		$today = Carbon::now('Asia/Seoul')->startOfDay()->format('Y-m-d');

		// 해당월의 1~말일, 전년동월, 전월 계산
		$baseDate = Carbon::create($year, $month, 1);
        $start_date = $baseDate->copy()->startOfMonth()->format('Y-m-d');
		$end_date   = $baseDate->copy()->endOfMonth()->format('Y-m-d');
		$lastYearSameMonth = $baseDate->copy()->subYear()->format('Y-m');
		$lastMonth = $baseDate->copy()->subMonth()->format('Y-m');
		$next1Month = $baseDate->copy()->addMonth()->format('Y년 m월');      // 1개월 뒤
		$next2Month = $baseDate->copy()->addMonths(2)->format('Y년 m월');   // 2개월 뒤
		$next3Month = $baseDate->copy()->addMonths(3)->format('Y년 m월');   // 3개월 뒤

		// 발전소 목록
		$companies = Company::where('group_idx', $group_idx)
			->orderBy('idx')
			->get();

		// 회사목록 배열화
		$companyMap = $companies->keyBy('idx');
		
		// 전체 발전소 용량 합계
		$totalVolume = $companies->sum('volume');

		// 발전소 개수
		$companiesCount = $companies->count();

		// 리포트 데이터
		$reports = CompanyReportMonthly::where('group_idx', $group_idx)
					->where('tgt_year', $year)
					->where('tgt_month', $month)
					->get()
					->keyBy('comp_idx'); 

		// 발전소별 발전시간 그래프 표시 데이터
		$labels = [];
		$values = [];

		foreach ($companies as $c) {
			$labels[] = $c->comp_name;

			$values[] = isset($reports[$c->idx])
				? round($reports[$c->idx]->act_hours, 2)
				: 0;
		}		
		
		// 년차별 집계
   		$summary = CompanyReportMonthly::selectRaw("
						comp_idx,
						period,
						tgt_yearkwh,
						SUM(act_kwh) as sum_act_kwh
					")
					->where('group_idx', $group_idx)
					->groupBy('comp_idx', 'period', 'tgt_yearkwh')
					->get();
		
		// 년차별 집계 구조 변환 [period][comp_idx]
		$summarydata = [];
		foreach ($summary as $row) {
			// 집계하려는 항목과 일치하는 회사 정보 획득
			$company = $companyMap[$row->comp_idx] ?? null;

			if (!$company || !$company->start_date) {
				continue;
			}

			$startDate = Carbon::parse($company->start_date);
			$period = (int)$row->period;

			// 시작년월 (n년차 시작)
			$startYm = $startDate->copy()->addMonths(($period - 1) * 12);

			// 종료년월 (n년차 끝)
			$endYm = $startDate->copy()->addMonths($period * 12)->subMonth();

			// row에 추가
			$row->start_ym = $startYm->format('y-m');
			$row->end_ym   = $endYm->format('y-m');
			
			$summarydata[$row->period][$row->comp_idx] = $row;
		}		
		
		// 존재하는 period 목록
    	$periods = collect($summary)->pluck('period')->unique()->sort()->values();

		// 관리 내역 획득
		$yearlyManages = CompanyManage::with('company:idx,comp_name')
    				->selectRaw("comp_idx, day, type, manage")
    				->whereYear('day', $year) // 1년치 전체 조회
    				->whereIn('comp_idx', $companies->pluck('idx'))
    				->get();

		// 년간 관리 내역에서 start_date >= 표시할 범위 <= end_date 의 데이터를 추출
		$manages = $yearlyManages->filter(function ($item) use ($start_date, $end_date) {
    		return $item->day >= $start_date && $item->day <= $end_date;
		});		
		
		// 타입별을 기준으로 발전소별 점검일자를 나열 
		// 	"정기 점검": {  "1": { "comp_name": "디엠산업", "days": "02-02, 02-20" },  "2": { "comp_name": "대현", "days": "02-04" }},
  		//  "긴급 출동": {  "1": { "comp_name": "디엠산업", "days": "02-21" }} 형태
		// groupBy('type') 를 통해서 type별로 분류
		$manageData = $manages->groupBy('type')->map(function ($itemsInType) {
			// groupBy('comp_idx') 를 통해서 comp_idx별로 분류
			return $itemsInType->groupBy('comp_idx')->map(function ($group) {
				// comp_name, days 목록으로 내용 구성
				return [
					'comp_idx'  => $group->first()->comp_idx,
					'comp_name' => $group->first()->company->comp_name,
					'days'      => $group->map(fn($item) => \Carbon\Carbon::parse($item->day)->format('m-d'))
										->unique()->sort()->implode(', ')
				];
			});
		});	

		// 년간 정기 점검, 긴급 출동 그룹화
		$targetType = '정기 점검';   // 각 셀에 표시할 타입
		$changeType = '소모품 교체';      // 우측 끝에 합산할 타입
		$emergencyType = '긴급 출동';      // 우측 끝에 합산할 타입
		$months = range(1, 12);     // 1월부터 12월까지

		// 2. 월별 데이터 가공, collect와 map을 통해서 1~12월까지 loop가 실행됨
		$yearManageData = collect($months)->map(function ($month) use ($yearlyManages, $companies, $targetType, $changeType, $emergencyType) {
			
			// 해당 월의 데이터만 필터링 (Carbon 객체 생성을 최소화하여 성능 최적화)
			// collect와 map을 통해서 생성된 loop를 통해서 1월부터 12월까지 각 월별 데이터를 filter를 통해서 추출
			$monthData = $yearlyManages->filter(function ($item) use ($month) {
				return \Carbon\Carbon::parse($item->day)->month == $month;
			});

			// row의 첫번째 칸에 month 로 00월의 값을 추가
			$row = ['month' => $month . '월'];

			// [중앙] 각 발전소별 "정기 점검" 건수
			// month 에 해당 하는 월에 row[키 companiex->idx]로 정기점검 건수가 추가됨 ([키] 에서 키가 같으면 업데이트 없으면 추가됨)
			foreach ($companies as $company) {
				$row[$company->idx] = $monthData->where('comp_idx', $company->idx)
									->where('type', $targetType)
									->count();
			}

			// [우측 끝] 해당 월의 모든 발전소 "소모품 교체" 총합
			$row['monthly_change'] = $monthData->where('type', $changeType)->count();

			// [우측 끝] 해당 월의 모든 발전소 "긴급 출동" 총합
			$row['monthly_emergency'] = $monthData->where('type', $emergencyType)->count();

			return $row;
		});

		// [하단] 연간 합계(Summary) 계산
		$yearManageSummary = [
			'month' => '합계',
			'total_change' => $yearManageData->sum('monthly_change'), // 하단 우측 끝 (연간 소모품 교체 총합)
			'total_emergency' => $yearManageData->sum('monthly_emergency') // 하단 우측 끝 (연간 긴급 출동 총합)
		];

		// 하단 각 발전소별 "정기 점검" 연간 총합 추가 ([키] 에서 키가 같으면 업데이트 없으면 추가됨)
		foreach ($companies as $company) {
			$yearManageSummary[$company->idx] = $yearManageData->sum($company->idx);
		}

		// 해당월의 정기 점검 데이터를 추출
		$checkData = $manages->filter(function ($item) {
    		return $item->type === '정기 점검';
		})->map(function ($item) {
    		return [
				'comp_name' => $item->company->comp_name,
				'day'       => \Carbon\Carbon::parse($item->day)->format('m-d'),
				'manage'    => $item->manage ?? ''
			];
		})->sortBy('comp_name')->sortBy('day')->values();	
		
		// 해당월의 긴급 출동 데이터를 추출
		$emergencyData = $manages->filter(function ($item) {
    		return $item->type === '긴급 출동';
		})->map(function ($item) {
    		return [
				'comp_name' => $item->company->comp_name,
				'day'       => \Carbon\Carbon::parse($item->day)->format('m-d'),
				'manage'    => $item->manage ?? ''
			];
		})->sortBy('comp_name')->sortBy('day')->values();

		// 해당월의 소모품 교체 데이터를 추출
		$changeData = $manages->filter(function ($item) {
    		return $item->type === '소모품 교체';
		})->map(function ($item) {
    		return [
				'comp_name' => $item->company->comp_name,
				'day'       => \Carbon\Carbon::parse($item->day)->format('m-d'),
				'manage'    => $item->manage ?? ''
			];
		})->sortBy('comp_name')->sortBy('day')->values();

		// 해당월의 이슈 데이터를 추출
		$issueData = $manages->filter(function ($item) {
    		return $item->type === '이슈';
		})->map(function ($item) {
    		return [
				'comp_name' => $item->company->comp_name,
				'day'       => \Carbon\Carbon::parse($item->day)->format('m-d'),
				'manage'    => $item->manage ?? ''
			];
		})->sortBy('comp_name')->sortBy('day')->values();	


		$data = [
            'year' => $year,
            'month' => $month,
			'lastYearSameMonth' => $lastYearSameMonth,
			'lastMonth' => $lastMonth,
			'next1Month' => $next1Month,
			'next2Month' => $next2Month,
			'next3Month' => $next3Month,		
            'today' => $today,
			'start_date' => $start_date,
			'end_date' => $end_date,
            'companiesCount' => $companiesCount,
			'totalVolume' => $totalVolume,
        ];			

    	return view('report.spcMonthReport', compact('mjToKwh', 'data', 'companies', 'reports', 'labels', 'values','summarydata', 'periods', 'manageData', 'yearManageData', 'yearManageSummary', 'checkData', 'emergencyData', 'changeData', 'issueData'));
    }

		// SPC 리포트 PDF 다운로드
	public function downloadSpcReportPdf($idx)
	{
		if (!auth()->user()->hasPerm('spc_report.detail')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$data = Report::findOrFail($idx);

		$pdf = Pdf::loadView('report.pdf', compact('data'))->setPaper('a4', 'portrait');

		return $pdf->download('SPC리포트_'.$idx.'.pdf');
	}	


	//================================== 발전소 관리 ==================================	
    // 회사(발전소) 목록
    public function getCompanyList(Request $request)
    {
		if (!auth()->user()->hasPerm('company.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Company::with('companygroup', 'companykwh', 'kmaobv', 'regions', 'cities');
    	
    	// 검색 조건 처리
    	if ($request->filled('comp_name')) {
    		$query->where('comp_name', 'like', '%' . $request->comp_name . '%');
    	}

    	if ($request->filled('regions_idx')) {
    		$query->where('regions_idx', $request->regions_idx);
    	}
    	
    	if ($request->filled('cities_idx')) {
    		$query->where('cities_idx', $request->cities_idx);
    	}		

    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

    	$data->getCollection()->transform(function ($item) {
    		$item->volume_formatted = number_format((float)$item->volume, 2, '.', ',');
			$item->start_date ? optional($item->start_date)->format('Y-m-d') : '';
			$item->volume_formatted ? number_format((float)$item->tgt_kwh, 2, '.', ',') : '';
			$item->volume_formatted ? number_format((float)$item->tgt_rate, 2, '.', ',') : '';
    		$item->created_date = optional($item->created_at)->format('Y-m-d');
    		
    		return $item;
    	});	
		
    	$regions = Regions::orderBy('idx')->get();
		$kmaobv = KmaObv::orderBy('stn_name')->get();
		$companygroup = CompanyGroup::orderBy('group_name')->get();
		
    	return view('admin.company', compact('data', 'regions', 'kmaobv', 'companygroup'));
    }

    // 회사(발전소) 등록
    public function setCompanyRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('company.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		$Company = Company::create([
    				'comp_name'		=> $request->input('comp_name'),
					'regions_idx'	=> $request->input('regions_idx') ?? '',
					'cities_idx'	=> $request->input('cities_idx') ?? '',
					'volume'		=> $request->input('volume') ?? 0,
					'tgt_kwh'		=> $request->input('tgt_kwh') ?? 0,
					'tgt_rate'		=> $request->input('tgt_rate') ?? 0,
					'start_date'	=> $request->input('start_date') ?? null,
					'kma_idx'		=> $request->input('kma_idx') ?? null,
					'group_idx'		=> $request->input('group_idx') ?? null,
    				'created_by'	=> $admin['name'] ?? ''
    		]);
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }

    // 회사(발전소) 상세
    public function getCompanyDetail(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('company.detail') && !auth()->user()->hasPerm('company.update') && !auth()->user()->hasPerm('company.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$data = Company::with('companygroup', 'companykwh', 'companyyearkwh', 'companyyearkwhhours',  'companysales', 'companymanage', 'kmaobv', 'regions', 'cities')->findOrFail($idx);

		$data->volume_formatted = number_format((float)$data->volume, 2, '.', ',');
		$data->tgt_kwh_formatted = number_format((float)$data->tgt_kwh, 2, '.', ',');
		$data->tgt_rate_formatted = number_format((float)$data->tgt_rate, 2, '.', ',');

		$kwh = $data?->companykwh?->sortBy('year')->sortBy('month')->groupBy('year') ?? collect();
		$yearkwh = $data?->companyyearkwh?->sortBy('period')->values();
		$yearkwhhours = $data?->companyyearkwhhours?->sortBy('year')->sortBy('month')->groupBy('year') ?? collect();
		$sales = $data?->companysales?->sortBy('type')->sortBy('day')->values();

		$manages = $data?->companymanage?->sortBy('type') ?? collect();
		$ckeck = $manages->where('type', '정기 점검')->sortByDesc('day')->values();
		$emergency = $manages->where('type', '긴급 출동')->sortByDesc('day')->values();
		$change = $manages->where('type', '소모품 교체')->sortByDesc('day')->values();
		$issue = $manages->where('type', '이슈')->sortByDesc('day')->values();
		
		$companygroup = CompanyGroup::orderBy('group_name')->get();
		$kmaobv = KmaObv::orderBy('stn_name')->get();
		$regions = Regions::orderBy('idx')->get();
				
		return view('admin.companyDetail', compact('data', 'kwh', 'yearkwh', 'yearkwhhours', 'sales', 'ckeck', 'emergency', 'change', 'issue', 'companygroup', 'kmaobv', 'regions'));
    }	
    
    // 회사(발전소) 수정
    public function setCompanyModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('company.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		DB::beginTransaction();
    		
    		$Company = Company::findOrFail($idx);

    		$Company->update([
    				'comp_name'		=> $request->input('comp_name'),
					'regions_idx'	=> $request->input('regions_idx') ?? '',
					'cities_idx'	=> $request->input('cities_idx') ?? '',
					'volume'		=> $request->input('volume') ?? '',
					'tgt_kwh'		=> $request->input('tgt_kwh') ?? '',
					'tgt_rate'		=> $request->input('tgt_rate') ?? '',
					'start_date'	=> $request->input('start_date') ?? '',
					'kma_idx'		=> $request->input('kma_idx') ?? '',
					'group_idx'		=> $request->input('group_idx') ?? '',
    				'updated_by'	=> $admin['name'] ?? '',
					'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
    		]);			

    		DB::commit();
    		return response()->json(['status' => 'success']);

    	} catch (\Exception $e) {
    		DB::rollBack();
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }
    
    // 회사(발전소) 삭제
    public function setCompanyDelete(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('company.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$Company = Company::findOrFail($idx);
    		
    		DB::beginTransaction();
    		$Company->delete();
    		DB::commit();
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		if (DB::transactionLevel() > 0) {
    			DB::rollBack();
    		}
    		return response()->json(['status'  => 'error','message' => $e->getMessage(),], 500);
    	}
    }

	//================================== 발전소 > 발전량 ==================================	
	// 발전량 저장
	public function saveCompanyKwh(Request $request)
	{
		if (!auth()->user()->hasPerm('company.kwhview') && !auth()->user()->hasPerm('company.kwhupdate')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$admin = Session::get('admin');
		
		$request->validate([
			'comp_idx' => 'required|integer',
			'year' => 'required|integer',
			'month' => 'required|integer|min:1|max:12',
			'kwh' => 'required|numeric',
		]);

		$data = CompanyKwh::updateOrCreate(
			[
				'comp_idx' => $request->comp_idx,
				'year' => $request->year,
				'month' => $request->month,
			],
			[
				'act_kwh' => $request->kwh,
				'updated_by' => $admin['name'] ?? '',
			]
		);

		return response()->json([
			'status' => 'success',
			'message' => '저장되었습니다.',
			'data' => $data,
		]);
	}

	// 발전량 삭제
	public function deleteCompanyKwh(Request $request)
	{
		if (!auth()->user()->hasPerm('company.kwhdelete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$request->validate([
			'idx' => 'required|integer',
		]);

		$data = CompanyKwh::findOrFail($request->idx);
		$data->delete();

		return response()->json([
			'status' => 'success',
			'message' => '삭제되었습니다.',
		]);
	}

	// 발전량 내역
    public function getCompanyKwh(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('company.kwhview')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$Company = Company::findOrFail($idx);

    	$data = $idx ? Company::with(['companykwh' => function ($q) { $q->orderByDesc('year')->orderBy('month'); }])->findOrFail($idx) : null;

		$kwh = $data?->companykwh?->groupBy('year') ?? collect();						

    	return response()->json(['status' => 'success', 'data' => $data, 'kwh' => $kwh]);
    }

	//================================== 발전소 > 목표 발전량 ==================================		
	// 목표 발전량 저장
	public function saveCompanyYearKwh(Request $request)
	{
		if (!auth()->user()->hasPerm('company.yearkwhcreate') && !auth()->user()->hasPerm('company.yearkwhupdate')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$admin = Session::get('admin');
		
		$request->validate([
			'comp_idx' => 'required|integer',
			'period' => 'required|integer',
		]);

		$data = CompanyYearKwh::updateOrCreate(
			[
				'comp_idx' => $request->comp_idx,
				'period' => $request->period,
			],
			[
				'year_kwh' => $request->year_kwh,
				'updated_by' => $admin['name'] ?? '',
			]
		);

		return response()->json([
			'status' => 'success',
			'message' => '저장되었습니다.',
			'data' => $data,
		]);
	}

	// 목표 발전량 삭제
	public function deleteCompanyYearKwh(Request $request)
	{
		if (!auth()->user()->hasPerm('company.yearkwhdelete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$request->validate([
			'idx' => 'required|integer',
		]);

		$data = CompanyYearKwh::findOrFail($request->idx);
		$data->delete();

		return response()->json([
			'status' => 'success',
			'message' => '삭제되었습니다.',
		]);
	}

	// 목표 발전량 내역
    public function getCompanyYearKwh(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('company.yearkwhview')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$Company = Company::findOrFail($idx);

    	$data = $idx ? Company::with(['companyyearkwh' => function ($q) { $q->orderBy('period'); }])->findOrFail($idx) : null;

		$yearkwh = $data?->companyyearkwh ?? collect();						

    	return response()->json(['status' => 'success', 'data' => $data, 'yearkwh' => $yearkwh]);
    }	


	//================================== 발전소 > 목표 발전시간 ==================================	
	// 목표 발전시간 저장
	public function saveCompanyYearKwhHours(Request $request)
	{
		if (!auth()->user()->hasPerm('company.yearkwhhourscreate') && !auth()->user()->hasPerm('company.yearkwhhoursupdate')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$admin = Session::get('admin');
		
		$request->validate([
			'comp_idx' => 'required|integer',
			'year' => 'required|integer',
			'month' => 'required|integer|min:1|max:12',
			'tgt_hours' => 'required|numeric',
		]);

		$data = CompanyYearKwhHours::updateOrCreate(
			[
				'comp_idx' => $request->comp_idx,
				'year' => $request->year,
				'month' => $request->month,
			],
			[
				'tgt_hours' => $request->tgt_hours,
				'updated_by' => $admin['name'] ?? '',
			]
		);

		return response()->json([
			'status' => 'success',
			'message' => '저장되었습니다.',
			'data' => $data,
		]);
	}

	// 목표 발전시간 삭제
	public function deleteCompanyYearKwhHours(Request $request)
	{
		if (!auth()->user()->hasPerm('company.yearkwhhoursdelete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$request->validate([
			'idx' => 'required|integer',
		]);

		$data = CompanyYearKwhHours::findOrFail($request->idx);
		$data->delete();

		return response()->json([
			'status' => 'success',
			'message' => '삭제되었습니다.',
		]);
	}

	// 목표 발전시간 내역
    public function getCompanyYearKwhHours(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('company.yearkwhhoursview')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$Company = Company::findOrFail($idx);

    	$data = $idx ? Company::with(['companyyearkwhhours' => function ($q) { $q->orderByDesc('year')->orderBy('month'); }])->findOrFail($idx) : null;

		$yearkwhhours = $data?->companyyearkwhhours?->groupBy('year') ?? collect();						

    	return response()->json(['status' => 'success', 'data' => $data, 'yearkwhhours' => $yearkwhhours]);
    }


	//================================== 발전소 > 매출 ==================================		
	// 매출 저장
	public function saveCompanySales(Request $request)
	{
		if (!auth()->user()->hasPerm('company.salecreate') && !auth()->user()->hasPerm('company.saleupdate')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$admin = Session::get('admin');
		
		$request->validate([
			'comp_idx' => 'required|integer',
			'sales' => 'required|integer',
		]);

		if ($request->idx) {
			$data = CompanySales::findOrFail($request->idx);

			$data->update([
				'tgt_year' => $request->tgt_year,
				'tgt_month' => $request->tgt_month,
				'supplier' => $request->supplier,
				'buyer' => $request->buyer,
				'day' => $request->day,
				'type' => $request->type,
				'title' => $request->title,				
				'quantity' => $request->quantity,
				'unitprice' => $request->unitprice,
				'sales' => $request->sales,
				'supplyprice' => $request->supplyprice,
				'vat' => $request->vat,
				'memo' => $request->memo,
				'updated_by' => $admin['name'] ?? '',
			]);
		} else {
			$data = CompanySales::create([
				'comp_idx' => $request->comp_idx,
				'tgt_year' => $request->tgt_year,
				'tgt_month' => $request->tgt_month,
				'supplier' => $request->supplier,
				'buyer' => $request->buyer,
				'day' => $request->day,
				'type' => $request->type,
				'title' => $request->title,		
				'quantity' => $request->quantity,
				'unitprice' => $request->unitprice,
				'sales' => $request->sales,
				'supplyprice' => $request->supplyprice,
				'vat' => $request->vat,
				'memo' => $request->memo,
				'created_by' => $admin['name'] ?? '',
			]);
		}

		return response()->json([
			'status' => 'success',
			'message' => '저장되었습니다.',
			'data' => $data,
		]);

		return response()->json([
			'status' => 'success',
			'message' => '저장되었습니다.',
			'data' => $data,
		]);
	}

	// 매출 삭제
	public function deleteCompanySales(Request $request)
	{
		if (!auth()->user()->hasPerm('company.saledelete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$data = CompanySales::findOrFail($request->idx);
		$data->delete();

		return response()->json([
			'status' => 'success',
			'message' => '삭제되었습니다.',
		]);
	}

	// 매출 내역
    public function getCompanySales(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('company.saleview')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$Company = Company::findOrFail($idx);

    	$data = $idx ? Company::with(['companysales' => function ($q) { $q->orderBy('type')->orderBy('day'); }])->findOrFail($idx) : null;

		$sales = $data?->companysales ?? collect();						

    	return response()->json(['status' => 'success', 'sales' => $sales]);
    }	

	//================================== 발전소 > O&M, 이슈 ==================================		
	// O&M, 이슈 저장
	public function saveCompanyManage(Request $request)
	{
		if (!auth()->user()->hasPerm('company.checkcreate') && !auth()->user()->hasPerm('company.checkupdate') && !auth()->user()->hasPerm('company.emergencycreate') && !auth()->user()->hasPerm('company.emergencyupdate') || !auth()->user()->hasPerm('company.changecreate') && !auth()->user()->hasPerm('company.changeupdate') && !auth()->user()->hasPerm('company.issuecreate') && !auth()->user()->hasPerm('company.issueupdate')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$admin = Session::get('admin');
		
		if ($request->idx) {
			$data = CompanyManage::findOrFail($request->idx);

			$data->update([
				'day'     => $request->day,
				'type'    => $request->type,
				'manage'  => $request->manage,
				'result'  => $request->result,
				'count'  => $request->count,
				'updated_by' => $admin['name'] ?? '',
			]);
		} else {
			$data = CompanyManage::create([
				'comp_idx' => $request->comp_idx,
				'day'     => $request->day,
				'type'    => $request->type,
				'manage' => $request->manage,
				'result'  => $request->result,
				'count'  => $request->count,
				'created_by' => $admin['name'] ?? '',
			]);
		}

		return response()->json([
			'status' => 'success',
			'message' => '저장되었습니다.',
			'data' => $data,
		]);
	}

	// O&M, 이슈 삭제
	public function deleteCompanyManage(Request $request)
	{
		if (!auth()->user()->hasPerm('company.checkdelete') && !auth()->user()->hasPerm('company.emergencydelete') && !auth()->user()->hasPerm('company.changedelete') && !auth()->user()->hasPerm('company.issuedelete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$data = CompanyManage::findOrFail($request->idx);
		$data->delete();

		return response()->json([
			'status' => 'success',
			'message' => '삭제되었습니다.',
		]);
	}

    // O&M, 이슈 내역
    public function getCompanyManage(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('company.checkview') && !auth()->user()->hasPerm('company.emergencyview') && !auth()->user()->hasPerm('company.changeview') && !auth()->user()->hasPerm('company.issueview')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$data = Company::with('companymanage')->findOrFail($idx);

		$manages = $data?->companymanage ?? collect();
		$ckeck = $manages->where('type', '정기 점검')->sortByDesc('day')->values();
		$emergency = $manages->where('type', '긴급 출동')->sortByDesc('day')->values();
		$change = $manages->where('type', '소모품 교체')->sortByDesc('day')->values();
		$issue = $manages->where('type', '이슈')->sortByDesc('day')->values();

    	return response()->json(['status' => 'success', 'checkList' => $ckeck, 'emergencyList' => $emergency, 'changeList' => $change, 'issueList' => $issue]);
    }	


	//================================== SPC 관리 ==================================
	// SPC 목록
    public function getCompanySpcList(Request $request)
    {
		if (!auth()->user()->hasPerm('spc.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = CompanyGroup::withCount('company');
    	
    	// 검색 조건 처리
    	if ($request->filled('group_name')) {
    		$query->where('group_name', 'like', '%' . $request->group_name . '%');
    	}

    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	return view('admin.companySpc', compact('data'));
    }

    // SPC 등록
    public function setCompanySpcRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('spc.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		$Spc = CompanyGroup::create([
    				'group_name'       => $request->input('group_name'),
    				'created_by'	=> $admin['name'] ?? ''
    		]);
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // SPC 수정 폼
    public function getCompanySpcDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('spc.create') && !auth()->user()->hasPerm('spc.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data = $idx ? CompanyGroup::with(['company' => function ($q) {
            					$q->with(['regions', 'cities'])
              					->orderBy('comp_name', 'asc');
        					}
    					])->findOrFail($idx) : null;

    	return response()->json(['status' => 'success', 'group_name' => $data->group_name, 'company' => $data->company, 'company_count' => $data->company->count()]);
    }
    
    // SPC 수정
    public function setCompanySpcModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('spc.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		DB::beginTransaction();
    		
    		$Spc = CompanyGroup::findOrFail($idx);
    		
    		$Spc->update([
    				'group_name'       => $request->input('group_name'),
    				'updated_by'	=> $admin['name'] ?? '',
					'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
    		]);
    		
    		DB::commit();

    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		DB::rollBack();
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    	}
    }
    
    // SPC 삭제
    public function setCompanySpcDelete(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('spc.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$Spc = CompanyGroup::findOrFail($idx);
    		
    		DB::beginTransaction();
    		$Spc->delete();
    		DB::commit();
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		if (DB::transactionLevel() > 0) {
    			DB::rollBack();
    		}
    		return response()->json(['status'  => 'error','message' => $e->getMessage(),], 500);
    	}
    }


	//================================== 일사량과 발전량 ==================================
	// 일사량 전녀도, 전월 비교
	public function getCompareMonthly(Request $request, KmaAsosService $asos)
    {
		if (!auth()->user()->hasPerm('kma_monthly.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		// ✅ 관측소 목록(DB)
		$stations = DB::table('kma_obv')
			->select('idx', 'stn_name')
			->orderBy('stn_name')
			->get();

		// ✅ 기본 관측소 (예: 수원 119) - DB에 있으면 그걸 기본으로
		$defaultStn = DB::table('kma_obv')->where('stn_name', '수원')->value('idx') ?? 119;

		$stnId = (int) $request->get('stnId', $defaultStn);
		$year  = (int) $request->get('year', 2026);
		$month = (int) $request->get('month', 2);

		// ✅ 선택된 관측소명
		$stnName = $stations->firstWhere('idx', $stnId)->stn_name ?? "stnId={$stnId}";
        $target = Carbon::create($year, $month, 1);

		// ✅ 현재월이면: 1일 ~ 어제까지만
		$today = Carbon::now('Asia/Seoul')->startOfDay();
		$isCurrentMonth = ($target->year === $today->year) && ($target->month === $today->month);

		// "어제"가 같은 달에 없을 수 있으니(매월 1일) 안전 처리
		$yesterday = (clone $today)->subDay();
		$targetEndDay = $isCurrentMonth ? max(1, (int)$yesterday->day) : $target->daysInMonth;

		$prevMonth = (clone $target)->subMonthNoOverflow();
        $prevYearSameMonth = (clone $target)->subYear();

    	$prevMonthEndDay = min($prevMonth->daysInMonth, $targetEndDay);
    	$prevYearEndDay  = min($prevYearSameMonth->daysInMonth, $targetEndDay);

		// ✅ 여기서부터는 "start/end 날짜(YYYYMMDD)"를 만들어서 호출
		$startTarget = $target->copy()->day(1)->format('Ymd');
		$endTarget   = $target->copy()->day($targetEndDay)->format('Ymd');

		$startPrevMon = $prevMonth->copy()->day(1)->format('Ymd');
		$endPrevMon   = $prevMonth->copy()->day($prevMonthEndDay)->format('Ymd');

		$startPrevYear = $prevYearSameMonth->copy()->day(1)->format('Ymd');
		$endPrevYear   = $prevYearSameMonth->copy()->day($prevYearEndDay)->format('Ymd');		

		// ✅ 월 데이터(일별 sumGsr) 가져오기
		// $dataPrevYear = $asos->getMonthlySumGsrByRange($startPrevYear, $endPrevYear, $stnId);
		// $dataPrevMon  = $asos->getMonthlySumGsrByRange($startPrevMon, $endPrevMon, $stnId);
		// $dataTarget   = $asos->getMonthlySumGsrByRange($startTarget, $endTarget, $stnId);

		$dprevYear = KmaSumgsr::where('stn_idx', $stnId)
							->whereBetween('date', [$startPrevYear, $endPrevYear])
							->orderByDesc('date')
							->get();
		$dprevMon = KmaSumgsr::where('stn_idx', $stnId)
							->whereBetween('date', [$startPrevMon, $endPrevMon])
							->orderByDesc('date')
							->get();
		$dtarget = KmaSumgsr::where('stn_idx', $stnId)
							->whereBetween('date', [$startTarget, $endTarget])
							->orderByDesc('date')
							->get();

		// 합계 (null 제외)
		$sumPrevYear = $dprevYear->sum('sumgsr');
		$sumPrevMon = $dprevMon->sum('sumgsr');
		$sumTarget = $dtarget->sum('sumgsr');

        $dataPrevYear = $dprevYear->toArray();
		$dataPrevMon = $dprevMon->toArray();
		$dataTarget = $dtarget->toArray();

		$daysInTarget = $target->daysInMonth;

        // 표 row 만들기
        $rows = [];
        for ($d = 0; $d <= $daysInTarget - 1; $d++) {
            $rows[] = [
                'day' => $d + 1,
				'prev_year'  => isset($dataPrevYear[$d])  ? $dataPrevYear[$d]['sumgsr']  : null,
				'prev_month' => isset($dataPrevMon[$d])   ? $dataPrevMon[$d]['sumgsr']   : null,
				'target'     => isset($dataTarget[$d])    ? $dataTarget[$d]['sumgsr']    : null,
            ];
        }

        // 합계 (null 제외)
        // $sumPrevYear = array_sum(array_filter($dataPrevYear, fn($v) => $v !== null));
        // $sumPrevMon  = array_sum(array_filter($dataPrevMon, fn($v) => $v !== null));
        // $sumTarget   = array_sum(array_filter($dataTarget, fn($v) => $v !== null));

        // 단위 변환 참고: ASOS 일사량(합계일사)은 보통 MJ/m²로 제공되는 경우가 많습니다.
        // 필요하면 kWh/m²로 변환: 1 MJ/m² = 0.277784755950055 kWh/m²
        $mjToKwh = 0.277784755950055;

        return view('admin.companyKma', [
			'stations' => $stations,
			'stnId' => $stnId,
			'stnName' => $stnName,
			'year' => $year,
			'month' => $month,

            'target' => $target,
            'prevMonth' => $prevMonth,
            'prevYear' => $prevYearSameMonth,

            'rows' => $rows,
            'sumPrevYear' => $sumPrevYear,
            'sumPrevMon' => $sumPrevMon,
            'sumTarget' => $sumTarget,

            'mjToKwh' => $mjToKwh,

        	'targetEndDay' => $targetEndDay,
        	'isCurrentMonth' => $isCurrentMonth,			
        ]);
    }

	// 일사량 전녀도, 전월 비교 엑셀 다운로드
	public function kmaCompareMonthlyExport(Request $request) 
	{
		if (!auth()->user()->hasPerm('kma_monthly.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		// ✅ 관측소 목록(DB)
		$stations = DB::table('kma_obv')
			->select('idx', 'stn_name')
			->orderBy('stn_name')
			->get();

		// ✅ 기본 관측소 (예: 수원 119) - DB에 있으면 그걸 기본으로
		$defaultStn = DB::table('kma_obv')->where('stn_name', '수원')->value('idx') ?? 119;

		$stnId = (int) $request->get('stnId', $defaultStn);
		$year  = (int) $request->get('year', 2026);
		$month = (int) $request->get('month', 2);

		// ✅ 선택된 관측소명
		$stnName = $stations->firstWhere('idx', $stnId)->stn_name ?? "stnId={$stnId}";
        $target = Carbon::create($year, $month, 1);

		// ✅ 현재월이면: 1일 ~ 어제까지만
		$today = Carbon::now('Asia/Seoul')->startOfDay();
		$isCurrentMonth = ($target->year === $today->year) && ($target->month === $today->month);

		// "어제"가 같은 달에 없을 수 있으니(매월 1일) 안전 처리
		$yesterday = (clone $today)->subDay();
		$targetEndDay = $isCurrentMonth ? max(1, (int)$yesterday->day) : $target->daysInMonth;

		$prevMonth = (clone $target)->subMonthNoOverflow();
        $prevYearSameMonth = (clone $target)->subYear();

    	$prevMonthEndDay = min($prevMonth->daysInMonth, $targetEndDay);
    	$prevYearEndDay  = min($prevYearSameMonth->daysInMonth, $targetEndDay);

		// ✅ 여기서부터는 "start/end 날짜(YYYYMMDD)"를 만들어서 호출
		$startTarget = $target->copy()->day(1)->format('Ymd');
		$endTarget   = $target->copy()->day($targetEndDay)->format('Ymd');

		$startPrevMon = $prevMonth->copy()->day(1)->format('Ymd');
		$endPrevMon   = $prevMonth->copy()->day($prevMonthEndDay)->format('Ymd');

		$startPrevYear = $prevYearSameMonth->copy()->day(1)->format('Ymd');
		$endPrevYear   = $prevYearSameMonth->copy()->day($prevYearEndDay)->format('Ymd');		

		// ✅ 월 데이터(일별 sumGsr) 가져오기
		// $dataPrevYear = $asos->getMonthlySumGsrByRange($startPrevYear, $endPrevYear, $stnId);
		// $dataPrevMon  = $asos->getMonthlySumGsrByRange($startPrevMon, $endPrevMon, $stnId);
		// $dataTarget   = $asos->getMonthlySumGsrByRange($startTarget, $endTarget, $stnId);

		$dprevYear = KmaSumgsr::where('stn_idx', $stnId)
							->whereBetween('date', [$startPrevYear, $endPrevYear])
							->orderByDesc('date')
							->get();
		$dprevMon = KmaSumgsr::where('stn_idx', $stnId)
							->whereBetween('date', [$startPrevMon, $endPrevMon])
							->orderByDesc('date')
							->get();
		$dtarget = KmaSumgsr::where('stn_idx', $stnId)
							->whereBetween('date', [$startTarget, $endTarget])
							->orderByDesc('date')
							->get();

		// 합계 (null 제외)
		$sumPrevYear = $dprevYear->sum('sumgsr');
		$sumPrevMon = $dprevMon->sum('sumgsr');
		$sumTarget = $dtarget->sum('sumgsr');

        $dataPrevYear = $dprevYear->toArray();
		$dataPrevMon = $dprevMon->toArray();
		$dataTarget = $dtarget->toArray();

		$daysInTarget = $target->daysInMonth;

        // 표 row 만들기
        $rows = [];
        for ($d = 0; $d <= $daysInTarget - 1; $d++) {
            $rows[] = [
                'day' => $d + 1,
				'prev_year'  => isset($dataPrevYear[$d])  ? $dataPrevYear[$d]['sumgsr']  : null,
				'prev_month' => isset($dataPrevMon[$d])   ? $dataPrevMon[$d]['sumgsr']   : null,
				'target'     => isset($dataTarget[$d])    ? $dataTarget[$d]['sumgsr']    : null,
            ];
        }

		$info = [
			'name' => $stnName, // 관측소 이름
			'year' => $year,
			'month' => $month
		];

		$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
    	$filename = '일사량비교_' . $timestamp . '.xlsx';

		return Excel::download(new kmaMonthlyExport($info, $rows), $filename);
	}

	// 일사량 기간 조회
	public function getCompareMonthlyDB(Request $request)
    {
		if (!auth()->user()->hasPerm('kma_db.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		// ✅ 관측소 목록(DB)
		$stations = DB::table('kma_obv')
			->select('idx', 'stn_name')
			->orderBy('stn_name')
			->get();

		// ✅ 기본 관측소 (예: 수원 119) - DB에 있으면 그걸 기본으로
		$defaultStn = DB::table('kma_obv')->where('stn_name', '수원')->value('idx') ?? 119;

		$stnId = (int) $request->get('stnId', $defaultStn);
		$start_date = $request->get('start_date');
		$end_date = $request->get('end_date');

		// ✅ 선택된 관측소명
		$stnName = $stations->firstWhere('idx', $stnId)->stn_name ?? "stnId={$stnId}";

		// ✅ 월 데이터(일별 sumGsr) 가져오기
		$rows = KmaSumgsr::where('stn_idx', $stnId)
							->whereBetween('date', [$start_date, $end_date])
							->orderByDesc('date')
							->get();

        // 합계 (null 제외)
        $totalsumgsr = $rows->sum('sumgsr');

        // 단위 변환 참고: ASOS 일사량(합계일사)은 보통 MJ/m²로 제공되는 경우가 많습니다.
        // 필요하면 kWh/m²로 변환: 1 MJ/m² = 0.277784755950055 kWh/m²
        $mjToKwh = 0.277784755950055;

        return view('admin.companyKmaDB', [
			'stations' => $stations,
			'stnId' => $stnId,
			'stnName' => $stnName,
			'start_date' => $start_date,
            'end_date' => $end_date,

            'rows' => $rows,
            'totalsumgsr' => $totalsumgsr,
            'mjToKwh' => $mjToKwh,
        ]);
    }
	
	// 일사량 기간 조회 엑셀 다운로드
	public function kmaDbExport(Request $request) 
	{
		if (!auth()->user()->hasPerm('kma_db.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		// ✅ 관측소 목록(DB)
		$stations = DB::table('kma_obv')
			->select('idx', 'stn_name')
			->orderBy('stn_name')
			->get();

		// ✅ 기본 관측소 (예: 수원 119) - DB에 있으면 그걸 기본으로
		$defaultStn = DB::table('kma_obv')->where('stn_name', '수원')->value('idx') ?? 119;

		$stnId = (int) $request->get('stnId', $defaultStn);
		$start_date = $request->get('start_date');
		$end_date = $request->get('end_date');

		// ✅ 선택된 관측소명
		$stnName = $stations->firstWhere('idx', $stnId)->stn_name ?? "stnId={$stnId}";

		// ✅ 월 데이터(일별 sumGsr) 가져오기
		$data = KmaSumgsr::where('stn_idx', $stnId)
							->whereBetween('date', [$start_date, $end_date])
							->orderByDesc('date')
							->get();

        // 합계 (null 제외)
        $totalsumgsr = $data->sum('sumgsr');

		$dataTarget = $data->toArray();

        // 표 row 만들기
        $rows = [];
        for ($d = 0; $d <= $data->count() - 1; $d++) {
            $rows[] = [
                'day' => $d + 1,
				'target' => isset($data[$d])    ? $data[$d]['sumgsr']    : null,
            ];
        }

		$info = [
			'name' => $stnName, // 관측소 이름
			'start_date' => $start_date,
			'end_date' => $end_date
		];

		$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
    	$filename = '일사량기간조회_' . $timestamp . '.xlsx';

		return Excel::download(new kmaDbExport($info, $rows), $filename);
	}	

	// 기상청 일사량 동기화 (기상청 -> DB)
	public function syncYearlyKma(Request $request, KmaAsosService $asos)
	{
		$year = $request->input('year');

		if (!$year) {
			return response()->json(['message' => '년도는 필수입니다'], 400);
		}

		// $startDt = $year . '0101';
		// $endDt   = $year . '1231';
		$startDt = '20220101'; // 2022.01.01~2026.03.16일 반영되어있음 (2026.03.17)
		$endDt   = '20221231'; 

		// 관측소 전체 조회
		$stations = KmaObv::all();

		foreach ($stations as $station) {
				
			try {
				$items = $asos->fetchDailyAsos($startDt, $endDt, $station->idx);

				foreach ($items as $row) {

					$date = $row['tm'] ?? null;
					if (!$date) continue;

					$val = $row['sumGsr'] ?? null;

					if ($val === null || $val === '' || $val === '-99' || $val === '-99.0') {
						$val = null;
					} else {
						$val = (float)$val;
					}

					KmaSumgsr::updateOrCreate(
						[
							'stn_idx' => $station->idx,
							'date' => $date,
						],
						[
							'sumgsr' => $val,
						]
					);
				}
			} catch (\Exception $e) {
				\Log::error("KMA sync error: {$station->idx} / {$e->getMessage()}");
			}
		}

		return response()->json(['status' => 'success']);
	}

	
	
    //================================== 솔라테크소식 ==================================
    // 솔라테크소식 목록
    public function getNoticeList(Request $request)
    {
		if (!auth()->user()->hasPerm('notice.view')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$query = Notice::query();
    	
    	// 검색 조건 처리
    	if ($request->filled('gubun')) {
    		$query->where('gubun', $request->gubun);
    	}
    	
    	if ($request->filled('subject')) {
			$query->where(function ($q) use ($request) {
    			$q->where('subject', 'like', '%' . $request->subject . '%')
      				->orWhere('subject_en', 'like', '%' . $request->subject . '%')
      				->orWhere('subject_cn', 'like', '%' . $request->subject . '%');				
    		});
		}

    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	return view('admin.notice', compact('data'));
    }
    
	// 솔라테크소식 엑셀 다운로드
    public function getNoticeExport(Request $request)
    {
 		if (!auth()->user()->hasPerm('notice.excel')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

		$filters = $request->only(['gubun', 'subject']);
    	
    	$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
    	$filename = '솔라테크소식목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new noticeExport($filters), $filename);
    }
    
    // 솔라테크소식 등록
    public function setNoticeRegist(Request $request)
    {
		if (!auth()->user()->hasPerm('notice.create')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		$notice = Notice::create([
    				'subject'       => $request->input('subject'),
    				'subject_en'    => $request->input('subject_en'),
    				'subject_cn'    => $request->input('subject_cn'),
    				'gubun'         => $request->input('gubun'),
    				'contents'      => $request->input('contents'),
    				'contents_en'   => $request->input('contents_en'),
    				'contents_cn'   => $request->input('contents_cn'),
    				'writed_at'		=> $request->input('writed_at'),
    				'created_by'	=> $admin['name'] ?? ''
    		]);
    		
    		if ($request->hasFile('noticeFiles')) {
    			$orderby = 1;
    			foreach ($request->file('noticeFiles') as $file) {
    				$up_name = $file->store('notice', 'public');
    				NoticeFiles::create([
    						'notice_idx' 	=> $notice->idx,
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
    
    // 솔라테크소식 수정 폼
    public function getNoticeDetailForm(Request $request, $idx = null)
    {
		if (!auth()->user()->hasPerm('notice.update') && !auth()->user()->hasPerm('notice.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	$data = $idx ? Notice::findOrFail($idx) : null;
    	
    	$files = collect();
    	if ($files && $data->idx) {
    		$files = NoticeFiles::where('notice_idx', $data->idx)->orderByRaw('orderby')->get();
    	}
    	
    	return response()->json(['status' => 'success', 'data' => $data, 'files' => $files]);
    }
    
    // 솔라테크소식 수정
    public function setNoticeModify(Request $request, $idx)
    {
		if (!auth()->user()->hasPerm('notice.update')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

    	try {
    		$admin = Session::get('admin');
    		
    		DB::beginTransaction();
    		
    		$notice = Notice::findOrFail($idx);
    		
    		$notice->update([
    				'subject'       => $request->input('subject'),
    				'subject_en'    => $request->input('subject_en'),
    				'subject_cn'    => $request->input('subject_cn'),
    				'gubun'         => $request->input('gubun'),
    				'contents'      => $request->input('contents'),
    				'contents_en'   => $request->input('contents_en'),
    				'contents_cn'   => $request->input('contents_cn'),
    				'writed_at'		=> $request->input('writed_at'),
    				'updated_by'	=> $admin['name'] ?? '',
    		]);
    		
    		$removedFileIdxs   = json_decode($request->input('removedFileIdxs', '[]'), true) ?: [];
    		$existingFileOrder = json_decode($request->input('existingFileOrder', '[]'), true) ?: [];
    		
    		if (!empty($removedFileIdxs)) {
    			$filesToDelete = NoticeFiles::whereIn('idx', $removedFileIdxs)->get(['up_name']);
    			
    			foreach ($filesToDelete as $f) {
    				Storage::disk('public')->delete($f->up_name);
    			}
    			NoticeFiles::whereIn('idx', $removedFileIdxs)->delete();
    		}
    		
    		// 순서 업데이트
    		foreach ($existingFileOrder as $item) {
    			NoticeFiles::where('idx', $item['idx'])
    			->where('notice_idx', $idx)
    			->update([
    					'orderby' => $item['orderby'],
    			]);
    		}
    		
    		// 새 파일 순서
    		$newFileOrders = $request->input('new_file_order', []);
    		
    		if ($request->hasFile('new_file_upload')) {
    			foreach ($request->file('new_file_upload') as $i => $file) {
    				$up_name = $file->store('notice', 'public');
    				NoticeFiles::create([
    						'notice_idx' => $idx,
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
    
    // 솔라테크소식 삭제
    public function setNoticeDelete(Request $request, $idx)
    {
 		if (!auth()->user()->hasPerm('notice.delete')) return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);

	   	try {
    		$notice = Notice::findOrFail($idx);
    		$noticeFilePaths = NoticeFiles::where('notice_idx', $idx)->pluck('up_name')->filter()->all();
    		
    		DB::beginTransaction();
    		
    		NoticeFiles::where('notice_idx', $idx)->delete();
    		$notice->delete();
    		
    		DB::commit();
    		
    		if (!empty($noticeFilePaths)) {
    			try {
    				Storage::disk('public')->delete($noticeFilePaths);
    			} catch (\Exception $fe) {
    				\Log::warning('Notice file delete failed', ['notice_idx'=> $idx,'paths'=> $noticeFilePaths, 'error'=> $fe->getMessage(),]);
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
    	
    	$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
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

    	$admin = Admin::findOrFail($idx);
    	$admin->delete();

		// 기존 권한 삭제
		$admin->permissions()->delete();
    	
    	return response()->json(['status' => 'success']);
    }
}