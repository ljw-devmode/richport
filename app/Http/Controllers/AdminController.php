<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use DateException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use function Illuminate\Log\log;
use App\Models\Inquire;
use App\Models\History;
use App\Models\Portfolio;
use App\Models\Types;
use App\Models\PortfolioFiles;
use App\Models\Notice;
use App\Models\NoticeFiles;
use App\Models\Regions;
use App\Models\Cities;
use App\Models\FormTypes;
use App\Models\InquireTypes;
use App\Exports\historyExport;
use App\Exports\portfolioExport;
use App\Exports\noticeExport;
use App\Exports\inquireExport;

class AdminController extends Controller
{
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
    
    public function getCities($regions_idx)
    {
    	$cities = Cities::where('regions_idx', $regions_idx)->orderBy('name_kr')->get(['idx', 'name_kr']);
    	
    	return response()->json($cities);
    }
    
    public function getDashBoardList(Request $request)
    {
    	return view('admin.dashboard');
    }
    
    
    //================================== 회사소개 ==================================
    // 회사소개 목록
    public function getHistoryList(Request $request)
    {
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
    
    public function getHistoryExport(Request $request)
    {
    	$filters = $request->only(['year', 'subject']);
    	
    	$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
    	$filename = '연혁목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new historyExport($filters), $filename);
    }
    
    // 회사소개 등록
    public function setHistoryRegist(Request $request)
    {
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
    	$data = $idx ? History::findOrFail($idx) : null;
    	
    	return response()->json(['status' => 'success', 'data' => $data]);
    }
    
    // 회사소개 수정
    public function setHistoryModify(Request $request, $idx)
    {
    	try {
    		$admin = Session::get('admin');
    		
    		DB::beginTransaction();
    		
    		$history = History::findOrFail($idx);
    		
    		\Log::info('history modify request', [
    				'idx'    => $idx,
    				'params' => $request->all(),
    		]);
    		
    		$result = $history->update([
    				'subject'		=> $request->input('subject'),
					'subject_en'		=> $request->input('subject_en'),
					'subject_cn'		=> $request->input('subject_cn'),
    				'year'			=> $request->input('year'),
    				'month'			=> $request->input('month'),
    				'updated_by'	=> $admin['name'] ?? ''
    		]);
    		
    		\Log::info('history update result', ['success' => $result]);
    		
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
    // 사업소개 목록
    public function getPortfolioList(Request $request)
    {
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
    
    public function getPortfolioExport(Request $request)
    {
    	$filters = $request->only(['types_idx', 'is_url', 'is_blog', 'home_recommend', 'introduce_recommend', 'portfolio_recommend', 'regions_idx', 'cities_idx', 'completion_date', 'station_name']);
    	
    	$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
    	$filename = '사업소개목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new portfolioExport($filters), $filename);
    }
    
    // 사업소개 등록
    public function setPortfolioRegist(Request $request)
    {
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
    
    // 사업소개 수정 폼
    public function getPortfolioDetailForm(Request $request, $idx = null)
    {
    	$data = $idx ? Portfolio::findOrFail($idx) : null;
    	
    	$files = collect();
    	if ($files && $data->idx) {
    		$files = PortfolioFiles::where('portfolio_idx', $data->idx)->orderByRaw('orderby')->get();
    	}
    	
    	return response()->json(['status' => 'success', 'data' => $data, 'files' => $files]);
    }
    
    // 사업소개 수정
    public function setPortfolioModify(Request $request, $idx)
    {
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
    
    // 사업소개 삭제
    public function setPortfolioDelete(Request $request, $idx)
    {
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
    
    //================================== 솔라테크소식 ==================================
    // 솔라테크소식 목록
    public function getNoticeList(Request $request)
    {
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
    
    public function getNoticeExport(Request $request)
    {
    	$filters = $request->only(['gubun', 'subject']);
    	
    	$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
    	$filename = '솔라테크소식목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new noticeExport($filters), $filename);
    }
    
    // 솔라테크소식 등록
    public function setNoticeRegist(Request $request)
    {
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
    	$filters = $request->only(['types_idx', 'status', 'inquire_hp', 'inquire_company', 'inquire_address']);
    	
    	$timestamp = Carbon::now()->format('Y_m_d_H_i_s');
    	$filename = '문의목록_' . $timestamp . '.xlsx';
    	
    	return Excel::download(new inquireExport($filters), $filename);
    }
    
    // 문의 수정 폼
    public function getInquireDetailForm(Request $request, $idx = null)
    {
    	
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
    /* public function getAccountList(Request $request)
    {
    	$query = Admin::query();
    	
    	if ($request->filled('name')) {
    		$query->where('name', 'like', '%' . $request->name . '%');
    	}
    	
    	// 최신순 정렬 + 페이지네이션
    	$data = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    	
    	return view('admin.account', compact('data'));
    }
    
    // 관리자 등록 폼
    public function getAccountRegistForm(Request $request)
    {
    	return view('admin.account_regist');
    } */
    
    // 관리자 등록
    public function setAccountRegist(Request $request)
    {
    	try {
    		// 필수값 체크
    		if (!$request->filled('id')) {
    			return response("
                <script>
                    alert('아이디를 입력해주세요.');
                    history.back();
                </script>
            ");
    		}
    		
    		if (!$request->filled('pw')) {
    			return response("
                <script>
                    alert('비밀번호를 입력해주세요.');
                    history.back();
                </script>
            ");
    		}
    		
    		// name 없으면 '관리자'로 기본값 설정
    		$name = $request->filled('name') ? $request->input('name') : '관리자';
    		
    		// 중복 체크
    		$exists = Admin::where('id', $request->input('id'))->exists();
    		if ($exists) {
    			return response("
                <script>
                    alert('중복된 아이디입니다.');
                    history.back();
                </script>
            ");
    		}
    		
    		$admin = Session::get('admin');
    		
    		// 계정 생성
    		Admin::create([
    				'id'        => $request->input('id'),
    				'name'      => $name,
    				'pw'        => Hash::make($request->input('pw')),
    				'hp'        => '01012345678',
    				'email'     => 'test@solarteq.com',
    				'level'     => 1,
    				'created_by'=> $admin['name'] ?? ''
    		]);
    		
    		return response("
            <script>
                alert('관리자 계정이 생성되었습니다.');
                window.location.href = '/admin';
            </script>
        ");
    		
    	} catch (\Exception $e) {
    		return response("
            <script>
                alert('오류가 발생했습니다: " . addslashes($e->getMessage()) . "');
                history.back();
            </script>
        ");
    	}
    }
    
    // 관리자 수정 폼
    /* public function getAccountDetailForm(Request $request, $idx = null)
    {
    	$data = $idx ? Admin::findOrFail($idx) : null;
    	
    	return view('admin.account_detail', compact('data'));
    } */
    
    // 관리자 수정
    /* public function setAccountModify(Request $request, $idx)
    {
    	try {
    		$admin = Session::get('admin');
    		
    		$account = Admin::findOrFail($idx);
    		$data = [
    				'id'    	 	=> $request->input('id'),
    				'name'       	=> $request->input('name'),
    				'hp'         	=> $request->input('hp'),
    				'email'      	=> $request->input('email'),
    				'updated_by' 	=> $admin['name'] ?? null,
    		];
    		
    		// 비밀번호가 들어왔을 때만 해시하여 반영
    		if ($request->filled('pw')) {
    			$data['pw'] = Hash::make($request->input('pw')); // bcrypt
    		}
    		
    		$account->update($data);
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    } */
    
    // 관리자 삭제
    /* public function setAccountDelete($idx)
    {
    	$admin = Admin::findOrFail($idx);
    	$admin->delete();
    	
    	return response()->json(['status' => 'success']);
    } */
    
    //로그아웃
    public function logout(Request $request)
    {
    	Session::forget('admin');
    	Auth::guard('admin')->logout();          // 1) 가드 로그아웃
    	$request->session()->invalidate();       // 2) 세션 무효화
    	$request->session()->regenerateToken();  // 3) CSRF 토큰 재발급
    	
    	
    	return redirect()->route('admin.login');
    }
}