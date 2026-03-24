<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\CompanyKwh;
use App\Models\CompanyManage;
use App\Models\CompanyReportMonthly;
use App\Models\CompanySales;
use App\Models\CompanyYearKwh;
use App\Models\CompanyYearKwhHours;
use App\Models\Contents;
use App\Models\Contact;
use App\Models\FormTypes;
use App\Models\History;
use App\Models\Inquire;
use App\Models\KmaObv;
use App\Models\KmaSumgsr;
use App\Models\News;
use App\Models\Notice;
use App\Models\NoticeFiles;
use App\Models\Popup;
use App\Models\Portfolio;
use App\Models\PortfolioFiles;
use App\Models\Product;
use App\Models\ProjectMemo;
use App\Models\Reference;
use App\Models\ReferenceFiles;
use App\Services\GoogleSheetService;
use App\Services\KmaAsosService;
use App\Services\ReportService;

class UserController extends Controller
{

	// 한국어 /////////////////////////////////////////////////////////

	// 메인 화면
    public function index_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('home_recommend', '1')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.ko.index', compact('data'));
    }
    
    // 문의사항 등록
    public function setInquireRegist_ko(Request $request)
    {
    	try {
    		
    		Inquire::create([
    				'types_idx'			=> $request->input('types_idx'),
    				'inquire_date'		=> Carbon::now(),
    				'inquire_company'	=> $request->input('inquire_company'),
    				'inquire_hp'		=> $request->input('inquire_hp'),
    				'inquire_address'	=> $request->input('inquire_address'), 
    				'inquire_contents'	=> $request->input('inquire_contents'),
    		]);
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // 솔라테크 소개
    public function company_about_ko(Request $request)
    {
    	return view('user.ko.company_about');
    }
    // 연혁
    public function company_history_ko(Request $request)
    {
    	$histories = History::orderBy('year', 'desc')
	    ->orderBy('month', 'desc')
	    ->orderBy('idx', 'desc')
	    ->get()
	    ->groupBy('year');   // [2025 => Collection, 2024 => Collection, ...]
	    
	    return view('user.ko.company_history', compact('histories'));
    }
    // 특허 / 인증
    public function company_patent_ko(Request $request)
    {
    	return view('user.ko.company_patent');
    }
    
    // 왜 솔라테크인가
    public function business_index_ko(Request $request)
    {
    	return view('user.ko.business_index');
    }
    
    // 신재생에너지 > 지붕임대 태양광
    public function business_solar_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '1')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.ko.business_solar', compact('data'));
    }
    
    // 신재생에너지 > RPS (전력판매)
    public function business_rps_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '2')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.ko.business_rps', compact('data'));
    }
    
    // 신재생에너지 > RE100 리스
    public function business_lease_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '3')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.ko.business_lease', compact('data'));
    }
    
    // 신재생에너지 > QNA
    public function business_qna_ko(Request $request)
    {
    	return view('user.ko.business_qna');
    }
    
    // 스마트팩토리 > AUTOMATION
    public function smartfactory_automation_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '4')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.ko.smartfactory_automation', compact('data'));
    }
    
    // 스마트팩토리 > MES
    public function smartfactory_mes_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '5')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.ko.smartfactory_mes', compact('data'));
    }
    
    // 스마트팩토리 > FEMS
    public function smartfactory_fems_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '6')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.ko.smartfactory_fems', compact('data'));
    }

    // 포트폴리오
    public function portfolio_ko(Request $request)
    {
    	$types = FormTypes::orderBy('idx', 'asc')->get();
    	
    	$perPage = 8;
    	
    	$data = Portfolio::with(['regions', 'cities', 'formTypes'])
    	->where('portfolio_recommend', '1')          // 추천만 노출
    	->when($request->filled('form_idx'), function ($q) use ($request) {
    		$q->where('form_idx', $request->form_idx);
    	})
    	->when($request->filled('station_name'), function ($q) use ($request) {
    		$q->where('station_name', 'like', '%'.$request->station_name.'%');
    	})
    	->orderByDesc('completion_date')
    	->paginate($perPage)
    	->withQueryString();
    	
    	return view('user.ko.portfolio', compact('data', 'types'));
    }
    
    // 포트폴리오 상세
    public function portfolio_detail_ko(Request $request, $idx)
    {
    	// 포트폴리오 본문 + 지역/도시 관계 로드
    	$data = Portfolio::with(['regions', 'cities', 'formTypes'])
    	->findOrFail($idx);

		if (!empty($data['youtube_iframe'])) {
			$data['youtube_iframe'] = '<iframe
		   width="560"
		   height="315"
		   src="https://www.youtube.com/embed/'.$data['youtube_iframe'].'?controls=0&amp;autoplay=1&amp;mute=1&amp;loop=1&amp;rel=0&amp;playlist='.$data['youtube_iframe'].'"
		   title="YouTube video player"
		   frameborder="0"
		   allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"
		   allowfullscreen></iframe>';
		} else {

		}
    	
    	// 시공사례 이미지들
    	$files = PortfolioFiles::where('portfolio_idx', $idx)->orderBy('orderby', 'asc')->orderBy('idx', 'asc')->get();
    	
    	return view('user.ko.portfolio_detail', compact('data', 'files'));
    }
    
    // 회사소식
    public function solarteq_news_ko(Request $request)
    {
    	$perPage = 8;
    	
    	$data = Notice::with(['firstFile'])
    	->orderByDesc('writed_at')
    	->paginate($perPage)
    	->withQueryString();
    	
    	return view('user.ko.solarteq_news', compact('data'));
    }
    
    // 솔라테크소식 수정 폼
    public function getNoticeDetailForm_ko(Request $request, $idx = null)
    {
    	$data = $idx ? Notice::findOrFail($idx) : null;
    	
    	$files = collect();
    	if ($data && $data->idx) {
    		$files = NoticeFiles::where('notice_idx', $data->idx)->orderByRaw('orderby')->get();
    	}
    	
    	return response()->json(['status' => 'success', 'data' => $data, 'files' => $files]);
    }
    
    // 조직문화
    public function solarteq_culture_ko(Request $request)
    {
    	return view('user.ko.solarteq_culture');
    }
    
    // 개인정보처리방침
    public function policy_ko(Request $request)
    {
    	return view('user.ko.policy');
    }


	// 지원사업 > 건물지원사업
    public function gov_building_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '8')
    	->orderByDesc('completion_date')
    	->get();
    	
    	return view('user.ko.gov_building', compact('data'));
    }
    
	// 지원사업 > 금융지원사업
    public function gov_finance_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '9')
    	->orderByDesc('completion_date')
    	->get();
    	
    	return view('user.ko.gov_finance', compact('data'));		
    }

	// 지원사업 > 주택지원사업
    public function gov_house_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '10')
    	->orderByDesc('completion_date')
    	->get();
    	
    	return view('user.ko.gov_house', compact('data'));		
    }

	// 지원사업 > 탄소중립설비지원사업
    public function gov_carbon_ko(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '11')
    	->orderByDesc('completion_date')
    	->get();
    	
    	return view('user.ko.gov_carbon', compact('data'));		
    }
	


	// 영어 /////////////////////////////////////////////////////////

	// 메인 화면
    public function index_en(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('home_recommend', '1')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.en.index', compact('data'));
    }
    
    // 문의사항 등록
    public function setInquireRegist_en(Request $request)
    {
    	try {
    		
    		Inquire::create([
    				'types_idx'			=> $request->input('types_idx'),
    				'inquire_date'		=> Carbon::now(),
    				'inquire_company'	=> $request->input('inquire_company'),
    				'inquire_hp'		=> $request->input('inquire_hp'),
    				'inquire_address'	=> $request->input('inquire_address'), 
    				'inquire_contents'	=> $request->input('inquire_contents'),
    		]);
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // 솔라테크 소개
    public function company_about_en(Request $request)
    {
    	return view('user.en.company_about');
    }
    // 연혁
    public function company_history_en(Request $request)
    {
    	$histories = History::orderBy('year', 'desc')
	    ->orderBy('month', 'desc')
	    ->orderBy('idx', 'desc')
	    ->get()
	    ->groupBy('year');   // [2025 => Collection, 2024 => Collection, ...]
	    
	    return view('user.en.company_history', compact('histories'));
    }
    // 특허 / 인증
    public function company_patent_en(Request $request)
    {
    	return view('user.en.company_patent');
    }
    
    // 왜 솔라테크인가
    public function business_index_en(Request $request)
    {
    	return view('user.en.business_index');
    }
    
    // 신재생에너지 > 지붕임대 태양광
    public function business_solar_en(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '1')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.en.business_solar', compact('data'));
    }
    
    // 신재생에너지 > RPS (전력판매)
    public function business_rps_en(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '2')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.en.business_rps', compact('data'));
    }
    
    // 신재생에너지 > RE100 리스
    public function business_lease_en(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '3')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.en.business_lease', compact('data'));
    }
    
    // 신재생에너지 > QNA
    public function business_qna_en(Request $request)
    {
    	return view('user.en.business_qna');
    }
    
    // 스마트팩토리 > AUTOMATION
    public function smartfactory_automation_en(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '4')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.en.smartfactory_automation', compact('data'));
    }
    
    // 스마트팩토리 > MES
    public function smartfactory_mes_en(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '5')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.en.smartfactory_mes', compact('data'));
    }
    
    // 스마트팩토리 > FEMS
    public function smartfactory_fems_en(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '6')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.en.smartfactory_fems', compact('data'));
    }

    // 포트폴리오
    public function portfolio_en(Request $request)
    {
    	$types = FormTypes::orderBy('idx', 'asc')->get();
    	
    	$perPage = 8;
    	
    	$data = Portfolio::with(['regions', 'cities', 'formTypes'])
    	->where('portfolio_recommend', '1')          // 추천만 노출
    	->when($request->filled('form_idx'), function ($q) use ($request) {
    		$q->where('form_idx', $request->form_idx);
    	})
    	->when($request->filled('station_name'), function ($q) use ($request) {
    		$q->where('station_name', 'like', '%'.$request->station_name.'%');
    	})
    	->orderByDesc('completion_date')
    	->paginate($perPage)
    	->withQueryString();
    	
    	return view('user.en.portfolio', compact('data', 'types'));
    }
    
    // 포트폴리오 상세
    public function portfolio_detail_en(Request $request, $idx)
    {
    	// 포트폴리오 본문 + 지역/도시 관계 로드
    	$data = Portfolio::with(['regions', 'cities', 'formTypes'])
    	->findOrFail($idx);

		$data['youtube_iframe'] = '<iframe
       width="560"
       height="315"
       src="https://www.youtube.com/embed/'.$data['youtube_iframe'].'?controls=0&amp;autoplay=1&amp;mute=1&amp;loop=1&amp;rel=0&amp;playlist='.$data['youtube_iframe'].'"
       title="YouTube video player"
       frameborder="0"
       allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"
       allowfullscreen></iframe>';
    	
    	// 시공사례 이미지들
    	$files = PortfolioFiles::where('portfolio_idx', $idx)->orderBy('orderby', 'asc')->orderBy('idx', 'asc')->get();
    	
    	return view('user.en.portfolio_detail', compact('data', 'files'));
    }
    
    // 회사소식
    public function solarteq_news_en(Request $request)
    {
    	$perPage = 8;
    	
    	$data = Notice::with(['firstFile'])
    	->orderByDesc('writed_at')
    	->paginate($perPage)
    	->withQueryString();
    	
    	return view('user.en.solarteq_news', compact('data'));
    }
    
    // 솔라테크소식 수정 폼
    public function getNoticeDetailForm_en(Request $request, $idx = null)
    {
    	$data = $idx ? Notice::findOrFail($idx) : null;
    	
    	$files = collect();
    	if ($data && $data->idx) {
    		$files = NoticeFiles::where('notice_idx', $data->idx)->orderByRaw('orderby')->get();
    	}
    	
    	return response()->json(['status' => 'success', 'data' => $data, 'files' => $files]);
    }
    
    // 조직문화
    public function solarteq_culture_en(Request $request)
    {
    	return view('user.en.solarteq_culture');
    }
    
    // 개인정보처리방침
    public function policy_en(Request $request)
    {
    	return view('user.en.policy');
    }




	// 중국어 /////////////////////////////////////////////////////////

	// 메인 화면
    public function index_cn(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('home_recommend', '1')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.cn.index', compact('data'));
    }
    
    // 문의사항 등록
    public function setInquireRegist_cn(Request $request)
    {
    	try {
    		
    		Inquire::create([
    				'types_idx'			=> $request->input('types_idx'),
    				'inquire_date'		=> Carbon::now(),
    				'inquire_company'	=> $request->input('inquire_company'),
    				'inquire_hp'		=> $request->input('inquire_hp'),
    				'inquire_address'	=> $request->input('inquire_address'), 
    				'inquire_contents'	=> $request->input('inquire_contents'),
    		]);
    		
    		return response()->json(['status' => 'success']);
    	} catch (\Exception $e) {
    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    	}
    }
    
    // 솔라테크 소개
    public function company_about_cn(Request $request)
    {
    	return view('user.cn.company_about');
    }
    // 연혁
    public function company_history_cn(Request $request)
    {
    	$histories = History::orderBy('year', 'desc')
	    ->orderBy('month', 'desc')
	    ->orderBy('idx', 'desc')
	    ->get()
	    ->groupBy('year');   // [2025 => Collection, 2024 => Collection, ...]
	    
	    return view('user.cn.company_history', compact('histories'));
    }
    // 특허 / 인증
    public function company_patent_cn(Request $request)
    {
    	return view('user.cn.company_patent');
    }
    
    // 왜 솔라테크인가
    public function business_index_cn(Request $request)
    {
    	return view('user.cn.business_index');
    }
    
    // 신재생에너지 > 지붕임대 태양광
    public function business_solar_cn(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '1')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.cn.business_solar', compact('data'));
    }
    
    // 신재생에너지 > RPS (전력판매)
    public function business_rps_cn(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '2')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.cn.business_rps', compact('data'));
    }
    
    // 신재생에너지 > RE100 리스
    public function business_lease_cn(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '3')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.cn.business_lease', compact('data'));
    }
    
    // 신재생에너지 > QNA
    public function business_qna_cn(Request $request)
    {
    	return view('user.cn.business_qna');
    }
    
    // 스마트팩토리 > AUTOMATION
    public function smartfactory_automation_cn(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '4')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.cn.smartfactory_automation', compact('data'));
    }
    
    // 스마트팩토리 > MES
    public function smartfactory_mes_cn(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '5')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.cn.smartfactory_mes', compact('data'));
    }
    
    // 스마트팩토리 > FEMS
    public function smartfactory_fems_cn(Request $request)
    {
    	$data = Portfolio::with(['regions', 'cities', 'formTypes','types'])
    	->where('introduce_recommend', '1')
    	->where('types_idx', '6')
    	->orderByDesc('idx')
    	->get();
    	
    	return view('user.cn.smartfactory_fems', compact('data'));
    }

    // 포트폴리오
    public function portfolio_cn(Request $request)
    {
    	$types = FormTypes::orderBy('idx', 'asc')->get();
    	
    	$perPage = 8;
    	
    	$data = Portfolio::with(['regions', 'cities', 'formTypes'])
    	->where('portfolio_recommend', '1')          // 추천만 노출
    	->when($request->filled('form_idx'), function ($q) use ($request) {
    		$q->where('form_idx', $request->form_idx);
    	})
    	->when($request->filled('station_name'), function ($q) use ($request) {
    		$q->where('station_name', 'like', '%'.$request->station_name.'%');
    	})
    	->orderByDesc('completion_date')
    	->paginate($perPage)
    	->withQueryString();
    	
    	return view('user.cn.portfolio', compact('data', 'types'));
    }
    
    // 포트폴리오 상세
    public function portfolio_detail_cn(Request $request, $idx)
    {
    	// 포트폴리오 본문 + 지역/도시 관계 로드
    	$data = Portfolio::with(['regions', 'cities', 'formTypes'])
    	->findOrFail($idx);

		$data['youtube_iframe'] = '<iframe
       width="560"
       height="315"
       src="https://www.youtube.com/embed/'.$data['youtube_iframe'].'?controls=0&amp;autoplay=1&amp;mute=1&amp;loop=1&amp;rel=0&amp;playlist='.$data['youtube_iframe'].'"
       title="YouTube video player"
       frameborder="0"
       allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"
       allowfullscreen></iframe>';
    	
    	// 시공사례 이미지들
    	$files = PortfolioFiles::where('portfolio_idx', $idx)->orderBy('orderby', 'asc')->orderBy('idx', 'asc')->get();
    	
    	return view('user.cn.portfolio_detail', compact('data', 'files'));
    }
    
    // 회사소식
    public function solarteq_news_cn(Request $request)
    {
    	$perPage = 8;
    	
    	$data = Notice::with(['firstFile'])
    	->orderByDesc('writed_at')
    	->paginate($perPage)
    	->withQueryString();
    	
    	return view('user.cn.solarteq_news', compact('data'));
    }
    
    // 솔라테크소식 수정 폼
    public function getNoticeDetailForm_cn(Request $request, $idx = null)
    {
    	$data = $idx ? Notice::findOrFail($idx) : null;
    	
    	$files = collect();
    	if ($data && $data->idx) {
    		$files = NoticeFiles::where('notice_idx', $data->idx)->orderByRaw('orderby')->get();
    	}
    	
    	return response()->json(['status' => 'success', 'data' => $data, 'files' => $files]);
    }
    
    // 조직문화
    public function solarteq_culture_cn(Request $request)
    {
    	return view('user.cn.solarteq_culture');
    }
    
    // 개인정보처리방침
    public function policy_cn(Request $request)
    {
    	return view('user.cn.policy');
    }


	public function report(GoogleSheetService $sheet)
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

        return view('user.ko.report', compact('header', 'items', 'summary'));
    }

    public function reportMemoStore(Request $request)
    {
        $memo = ProjectMemo::create([
            'name' => $request->name,
            'memo' => $request->memo,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return ProjectMemo::where('name', $request->name)
            ->orderByDesc('created_at')
            ->get();
    }

    public function reportMemoList($name)
    {
        return ProjectMemo::where('name', $name)
            ->orderByDesc('created_at')
            ->get();
    }

    public function reportMemoDelete(Request $request, $idx)
    {
		$request->validate([
            'name' => 'required|string',
        ]);

        ProjectMemo::find($idx)?->delete();

        return ProjectMemo::where('name', $request->name)
            ->orderByDesc('created_at')
            ->get();
    }    

    public function reportMemoUpdate(Request $request, $idx)
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

	// 일사량과 발전량 조회
	public function compareMonthly(Request $request, KmaAsosService $asos)
    {
		// ✅ 관측소 목록(DB)
		$stations = DB::table('kma_obv')
			->select('stn_code', 'stn_name')
			->orderBy('stn_name')
			->get();

		// ✅ 기본 관측소 (예: 수원 119) - DB에 있으면 그걸 기본으로
		$defaultStn = DB::table('kma_obv')->where('stn_name', '수원')->value('stn_code') ?? 119;

		$stnId = (int) $request->get('stnId', $defaultStn);
		$year  = (int) $request->get('year', 2026);
		$month = (int) $request->get('month', 2);

		// ✅ 선택된 관측소명
		$stnName = $stations->firstWhere('stn_code', $stnId)->stn_name ?? "stnId={$stnId}";
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
		$dataPrevYear = $asos->getMonthlySumGsrByRange($startPrevYear, $endPrevYear, $stnId);
		$dataPrevMon  = $asos->getMonthlySumGsrByRange($startPrevMon, $endPrevMon, $stnId);
		$dataTarget   = $asos->getMonthlySumGsrByRange($startTarget, $endTarget, $stnId);

        $daysInTarget = $target->daysInMonth;

        // 표 row 만들기
        $rows = [];
        for ($d = 1; $d <= $daysInTarget; $d++) {
            $rows[] = [
                'day' => $d,
                'prev_year' => $dataPrevYear[$d] ?? null,
                'prev_month' => $dataPrevMon[$d] ?? null,
                'target' => $dataTarget[$d] ?? null,
            ];
        }

        // 합계 (null 제외)
        $sumPrevYear = array_sum(array_filter($dataPrevYear, fn($v) => $v !== null));
        $sumPrevMon  = array_sum(array_filter($dataPrevMon, fn($v) => $v !== null));
        $sumTarget   = array_sum(array_filter($dataTarget, fn($v) => $v !== null));

        // 단위 변환 참고: ASOS 일사량(합계일사)은 보통 MJ/m²로 제공되는 경우가 많습니다.
        // 필요하면 kWh/m²로 변환: 1 MJ/m² = 0.277784755950055 kWh/m²
        $mjToKwh = 0.277784755950055;

        return view('user.ko.kma', [
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


	// 월별 리포트
    public function report_month(Request $request)
    {
    	return view('report.month');
    }

	// SPC 리포트 보기
	public function getSpcMonthlyReport(Request $request)
    {
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


}