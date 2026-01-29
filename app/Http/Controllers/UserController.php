<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
//use App\Http\Requests\ContactRequest;
use App\Models\Reference;
use App\Models\ReferenceFiles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Contents;
use App\Models\Contact;
use App\Models\Popup;
use Illuminate\Support\Facades\Response;
use App\Models\Product;
use App\Models\Inquire;
use App\Models\News;
use App\Models\Portfolio;
use App\Models\FormTypes;
use App\Models\PortfolioFiles;
use App\Models\Notice;
use App\Models\NoticeFiles;
use App\Models\History;

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
    
    // 신재생에너지 > RPS 태양광발전
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
    
    // 신재생에너지 > RPS 태양광발전
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
    
    // 신재생에너지 > RPS 태양광발전
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
}