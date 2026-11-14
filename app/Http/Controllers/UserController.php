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
use App\Models\Cities;
use App\Models\Contents;
use App\Models\Districts;
use App\Models\Faq;
use App\Models\FormTypes;
use App\Models\History;
use App\Models\Inquire;
use App\Models\InquireTypes;
use App\Models\Notice;
use App\Models\NoticeFiles;
use App\Models\Partner;
use App\Models\PartnerFiles;
use App\Models\Popup;
use App\Models\Portfolio;
use App\Models\PortfolioFiles;
use App\Models\Product;
use App\Models\ProductFiles;
use App\Models\Ripsinquire;
use App\Models\RipsInquireTypes;
use App\Models\Types;
use App\Models\Testbedinquire;
use App\Models\TestbedInquireTypes;


class UserController extends Controller
{
	// 사용자 화면 (ko / en / vn 공용, 언어는 라우트의 user.locale 미들웨어로 설정) ////////////

	// 이름 검색 : 한국어/영어/베트남어 컬럼 중 하나라도 포함
	private function whereLikeAnyLang($query, string $field, $keyword)
	{
		$query->where(function ($q) use ($field, $keyword) {
			$q->where($field, 'like', '%'.$keyword.'%')
			->orWhere($field.'_en', 'like', '%'.$keyword.'%')
			->orWhere($field.'_vn', 'like', '%'.$keyword.'%');
		});
	}

	// 메인 화면
    public function index(Request $request) 
	{
    	$data = Portfolio::with(['cities', 'districts'])
    	->where('home_recommend', '1')->orderByDesc('idx')->get();

    	// 홈 노출 상품
    	$products = Product::with(['partners'])
    	->where('home_recommend', '1')->orderByDesc('idx')->get();

    	// 홈 노출 파트너
    	$partners = Partner::with(['forms', 'products' => function ($q) {
    		$q->orderByDesc('idx');
    	}])->where('home_recommend', '1')->orderByDesc('idx')->get();

    	return view('user.'.app()->getLocale().'.index', compact('data', 'products', 'partners'));
    }
    
    // 신청 필수값 검사 (화면 검사를 거치지 않은 요청 대비) - 비어 있으면 현재 언어 안내 문구, 아니면 null
    private function inquireRequired(Request $request, array $keys)
    {
    	foreach ($keys as $key) {
    		if (trim((string) $request->input($key)) === '') {
    			return response()->json(['status' => 'error', 'message' => [
    				'en' => 'Please fill in all required fields.',
    				'vn' => 'Vui lòng nhập đầy đủ các mục bắt buộc.',
    			][app()->getLocale()] ?? '필수 항목을 모두 입력해주세요.']);
    		}
    	}
    	return null;
    }

    // 문의사항 등록
    public function setInquireRegist(Request $request) 
	{
    	if ($error = $this->inquireRequired($request, ['types_idx', 'inquire_company', 'inquire_hp'])) return $error;

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

    // RIPS 문의사항 등록
    public function setRipsInquireRegist(Request $request) 
	{
    	if ($error = $this->inquireRequired($request, ['types_idx', 'inquire_company', 'inquire_address', 'inquire_hp'])) return $error;

    	try {
    		
    		Ripsinquire::create([
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
    
    // TESTBED 문의사항 등록
    public function setTestbedInquireRegist(Request $request) 
	{
    	if ($error = $this->inquireRequired($request, ['types_idx', 'inquire_company', 'inquire_hp', 'inquire_address'])) return $error;
    	if (!Product::whereKey((int) $request->input('types_idx'))->exists()) return response()->json(['status' => 'error', 'message' => 'Invalid product.']);

    	try {
    		
    		Testbedinquire::create([
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
    
    // 회사 소개
    public function company_about(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.company_about');
    }

    // 오시는길
    public function company_contact(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.company_contact');
    }
	
    // 크루소개
    public function company_crew(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.company_crew');
    }		

    // 연혁
    public function company_history(Request $request) 
	{
		// [2025 => Collection, 2024 => Collection, ...] 형태로 생성
    	$histories = History::orderBy('year', 'desc')
	    ->orderBy('month', 'desc')->orderBy('idx', 'desc')->get()->groupBy('year');   
	    
	    return view('user.'.app()->getLocale().'.company_history', compact('histories'));
    }

	// RIPS > 주요특징
	public function rips_about(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.rips_about');
    }

	// RIPS > 안전재고예측
	public function rips_inventory(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.rips_inventory');
    }

	// RIPS > 발주예측
	public function rips_order(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.rips_order');
    }

	// RIPS > 왜 필요한가?
	public function rips_why(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.rips_why');
    }

	// RIPS > 도입사례
	public function rips_portpolio(Request $request) 
	{
		$perPage = 8;

		$types = Types::orderBy('idx', 'asc')->get();

    	$data = Portfolio::with(['types', 'cities', 'districts'])
    	->where('portfolio_recommend', '1')          // 도입사례 노출만
		->when($request->filled('types_idx'), function ($q) use ($request) {
    		$q->where('types_idx', $request->types_idx);
		})->when($request->filled('station_name'), function ($q) use ($request) {
    		$this->whereLikeAnyLang($q, 'station_name', $request->station_name);
		})->orderByDesc('created_at')->paginate($perPage)->withQueryString();		
    	
    	return view('user.'.app()->getLocale().'.rips_portpolio', compact('data', 'types'));
    }

	// RIPS > 가격정책
	public function rips_price(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.rips_price');
    }
	
	// RIPS > 이용 전 체크사항
	public function rips_check(Request $request)
	{
    	// 관리자 > 고객문의 > FAQ 에서 노출로 등록한 항목
    	$faqs = Faq::visible()->get();

    	return view('user.'.app()->getLocale().'.rips_check', compact('faqs'));
    }
	
	// RIPS > 문의하기
	public function rips_inquire(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.rips_inquire');
    }



	// 파트너 > 한국
	public function partner_korea(Request $request)
	{
    	$perPage = 8;

		$types = FormTypes::top()->get();

    	$data = Partner::with(['cities', 'districts', 'forms'])
    	->where('country', 'korea')
    	->where('partner_recommend', '1')            // 파트너 노출만
    	->when($request->filled('forms_idx'), function ($q) use ($request) {
    		$q->where('forms_idx', $request->forms_idx);
    	})->when($request->filled('partner_name'), function ($q) use ($request) {
    		$this->whereLikeAnyLang($q, 'partner_name', $request->partner_name);
    	})->orderByDesc('created_at')->paginate($perPage)->withQueryString();
    	
    	return view('user.'.app()->getLocale().'.partner_korea', compact('data', 'types'));
    }
	
	// 파트너 > 베트남
	public function partner_vietnam(Request $request) 
	{
    	$perPage = 8;

		$types = Types::orderBy('idx', 'asc')->get();
    	
    	$data = Partner::with(['types', 'cities', 'districts', 'forms'])
    	->where('country', 'vietnam')
    	->where('partner_recommend', '1')            // 파트너 노출만
    	->when($request->filled('types_idx'), function ($q) use ($request) {
    		$q->where('types_idx', $request->types_idx);
    	})->when($request->filled('partner_name'), function ($q) use ($request) {
    		$this->whereLikeAnyLang($q, 'partner_name', $request->partner_name);
    	})->orderByDesc('created_at')->paginate($perPage)->withQueryString();

    	return view('user.'.app()->getLocale().'.partner_vietnam', compact('data', 'types'));
    }
	
	// 상품 > 상품등록
	public function product_congbo(Request $request) 
	{
    	$perPage = 8;

		$types = Types::orderBy('idx', 'asc')->get();
		$formTypes = FormTypes::top()->get();
    	
    	$data = Product::with(['forms', 'partners'])
    	->where('congbo', '1')            // 추천만 노출
    	->when($request->filled('forms_idx'), function ($q) use ($request) {
    		// 1단계로 검색 → 2~4단계에 등록된 제품도 포함
    		$q->whereIn('forms_idx', FormTypes::descendantIds($request->forms_idx));
    	})->when($request->filled('partner_idx'), function ($q) use ($request) {
    		$q->where('partner_idx', $request->partner_idx);
    	})->when($request->filled('product_name'), function ($q) use ($request) {
    		$this->whereLikeAnyLang($q, 'product_name', $request->product_name);
    	})->orderByDesc('created_at')->paginate($perPage)->withQueryString();

		// 최근 4개
		$lastdata = $data->take(4);

		// 3. 최근 4개를 제외한 나머지 추출
		$remaindata = $data->slice(4);		

    	return view('user.'.app()->getLocale().'.product_congbo', compact('data', 'lastdata', 'remaindata', 'types', 'formTypes'));
    }

	// 상품 > 신상품
	public function product_new(Request $request) 
	{
    	$perPage = 8;

    	$data = Product::with(['forms', 'partners'])
		->where('new', '1')
		->when($request->filled('product_name'), function ($q) use ($request) {
    		$this->whereLikeAnyLang($q, 'product_name', $request->product_name);
		})->orderByDesc('created_at')->paginate($perPage)->withQueryString();

    	return view('user.'.app()->getLocale().'.product_new', compact('data'));
    }

    // 상품 > 신상품 상세
    public function product_detail(Request $request, $idx = null) 
	{
    	// 상품 상세
    	$data = Product::with(['forms', 'partners'])->findOrFail($idx);

    	// 화면 스크립트가 쓰는 값을 현재 언어로 (비어 있으면 한국어)
    	$data->setAttribute('product_name', $data->tr('product_name'));
    	$data->setAttribute('contents', $data->tr('contents'));
    	$data->partners?->setAttribute('partner_name', $data->partners->tr('partner_name'));
    	$data->forms?->setAttribute('subject', $data->forms->tr('subject'));

    	// 이미지들
		$files = collect();
    	if ($files && $data->idx) {
    		$files = ProductFiles::where('product_idx', $data->idx)->orderByRaw('orderby')->get();
    	}
   
		return response()->json(['status' => 'success', 'data' => $data, 'files' => $files]);
    }	

    // 테스트베드
    public function testbed_about(Request $request) 
	{
    	$perPage = 8;

    	$data = Product::with(['forms', 'partners'])
		->where('testbed', '1')
		->when($request->filled('product_name'), function ($q) use ($request) {
    		$this->whereLikeAnyLang($q, 'product_name', $request->product_name);
		})->orderByDesc('created_at')->paginate($perPage)->withQueryString();
    	
    	return view('user.'.app()->getLocale().'.testbed_about', compact('data'));
    }	





    
    // 개인정보처리방침
    public function policy(Request $request) 
	{
    	return view('user.'.app()->getLocale().'.policy');
    }



	public function report(GoogleSheetService $sheet) 
	{
        // 대시보드는 캐시 필수 (API 호출비용/속도/쿼터)
        $rows = Cache::remember('dashboard_sheet_rows', 60, function () use ($sheet) {
            return $sheet->getRange('dashboard!A1:N1000'); // 시트명/범위
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

        return view('user.'.app()->getLocale().'.report', compact('header', 'items', 'summary'));
    }

    public function reportMemoStore(Request $request) 
	{
        $memo = ProjectMemo::create(['name' => $request->name,'memo' => $request->memo,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]);

        return ProjectMemo::where('name', $request->name)->orderByDesc('created_at')->get();
    }

    public function reportMemoList($name) 
	{
        return ProjectMemo::where('name', $name)->orderByDesc('created_at')->get();
    }

    public function reportMemoDelete(Request $request, $idx) 
	{
		$request->validate(['name' => 'required|string',]);

        ProjectMemo::find($idx)?->delete();

        return ProjectMemo::where('name', $request->name)->orderByDesc('created_at')->get();
    }    

    public function reportMemoUpdate(Request $request, $idx) 
	{
        $request->validate(['memo' => 'required|string','name' => 'required|string',]);

        $m = ProjectMemo::findOrFail($idx);

        // 안전: 다른 프로젝트 메모를 실수로 수정 방지(선택)
        if ($m->name !== $request->name) {
            return response()->json(['error' => 'project mismatch'], 400);
        }

        $m->memo = $request->memo;
        $m->save();

        return ProjectMemo::where('name', $request->name)->orderByDesc('created_at')->get();
    }

	// 일사량과 발전량 조회
	public function compareMonthly(Request $request, KmaAsosService $asos) 
	{
		// 관측소 목록(DB)
		$stations = DB::table('kma_obv')->select('stn_code', 'stn_name')->orderBy('stn_name')->get();

		// 기본 관측소 (예: 수원 119)
		$defaultStn = DB::table('kma_obv')->where('stn_name', '수원')->value('stn_code') ?? 119;

		$stnId = (int) $request->get('stnId', $defaultStn);
		$year  = (int) $request->get('year', 2026);
		$month = (int) $request->get('month', 1);

		// 선택된 관측소명
		$stnName = $stations->firstWhere('stn_code', $stnId)->stn_name ?? "stnId={$stnId}";
        $target = Carbon::create($year, $month, 1);

		// 현재월이면: 1일 ~ 어제까지만
		$today = Carbon::now('Asia/Seoul')->startOfDay();
		$isCurrentMonth = ($target->year === $today->year) && ($target->month === $today->month);

		// "어제"가 같은 달에 없을 수 있으니(매월 1일) 안전 처리
		$yesterday = (clone $today)->subDay();
		$targetEndDay = $isCurrentMonth ? max(1, (int)$yesterday->day) : $target->daysInMonth;

		$prevMonth = (clone $target)->subMonthNoOverflow();
        $prevYearSameMonth = (clone $target)->subYear();

    	$prevMonthEndDay = min($prevMonth->daysInMonth, $targetEndDay);
    	$prevYearEndDay  = min($prevYearSameMonth->daysInMonth, $targetEndDay);

		// "start/end 날짜(YYYYMMDD)"를 만들어서 호출
		$startTarget = $target->copy()->day(1)->format('Ymd');
		$endTarget   = $target->copy()->day($targetEndDay)->format('Ymd');

		$startPrevMon = $prevMonth->copy()->day(1)->format('Ymd');
		$endPrevMon   = $prevMonth->copy()->day($prevMonthEndDay)->format('Ymd');

		$startPrevYear = $prevYearSameMonth->copy()->day(1)->format('Ymd');
		$endPrevYear   = $prevYearSameMonth->copy()->day($prevYearEndDay)->format('Ymd');		

		// 월 데이터(일별 sumGsr)
		$dataPrevYear = $asos->getMonthlySumGsrByRange($startPrevYear, $endPrevYear, $stnId);
		$dataPrevMon  = $asos->getMonthlySumGsrByRange($startPrevMon, $endPrevMon, $stnId);
		$dataTarget   = $asos->getMonthlySumGsrByRange($startTarget, $endTarget, $stnId);

        $daysInTarget = $target->daysInMonth;

        // 표 row 만들기
        $rows = [];
        for ($d = 1; $d <= $daysInTarget; $d++) {
            $rows[] = [
                'day' => $d, 'target' => $dataTarget[$d] ?? null,
                'prev_year' => $dataPrevYear[$d] ?? null, 'prev_month' => $dataPrevMon[$d] ?? null,
            ];
        }

        // 합계 (null 제외)
        $sumPrevYear = array_sum(array_filter($dataPrevYear, fn($v) => $v !== null));
        $sumPrevMon  = array_sum(array_filter($dataPrevMon, fn($v) => $v !== null));
        $sumTarget   = array_sum(array_filter($dataTarget, fn($v) => $v !== null));

        // 일사량으로 kwh 계산하는 계수
        $mjToKwh = 0.277784755950055;

        return view('user.'.app()->getLocale().'.kma', [
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

	// SPC 리포트 보기
	public function getSpcMonthlyReport(Request $request)
    {
		/*********************************************************/
		// 파라미터 체크
		/*********************************************************/
        // 파라미터 유효성 검증
		$validated = $request->validate([
			'year'      => 'required|integer|between:2024,' . date('Y'),
			'month'     => 'required|integer|between:1,12',
			'group_idx' => 'required_without:spc_name',
			'spc_name'  => 'nullable|string',  // 파라미터는 spc_name으로 받고, 실제는 group_name 로 사용
		]);

		// validate 배열을 $year, $month, $group_idx, $group_name 로 사용할 수 있도록 변환
		$year       = $validated['year'];
		$month      = $validated['month'];
		$group_idx  = $validated['group_idx'] ?? null;
		$group_name = $validated['spc_name'] ?? null;

		// group_idx 가 없고 spc_name 만 있을때 group_idx 확인
		if (!$group_idx) {
			$group_idx = DB::table('company_group')->where('group_name', $group_name)->value('idx');
		}

		// group_idx 가 없고 spc_name 만 있을때 spc_name 이 잘못되면 서비스 불가
		if (!$group_idx) {
			abort(404, '존재하지 않는 그룹입니다.');
		}

		// group_idx 가 있을때 유효한지 확인, 잘못되면 서비스 불가
		$companies = Company::where('group_idx', $group_idx)->orderBy('idx')->get();

		if ($companies->isEmpty()) {
			abort(404, '존재하지 않는 그룹입니다.');
		}


		/*********************************************************/
		// SPC 정보 획득
		/*********************************************************/
		// companygroup 에 대한 데이터 획득 / 이하 해당 데이터를 재활용해서 DB 조회 최소화
		$company_group = CompanyGroup::find($group_idx);
		
		// spc명 획득
		$group_name = $company_group->group_name;

		// 리포트 표시 기준 획득
		$summarysales = $company_group->summarysales;
		$allperiod = $company_group->allperiod;
		$startdate = $company_group->startdate;
		$selectperiod = $company_group->selectperiod;
		$selectcompany = $company_group->selectcompany;
		$compare = $company_group->compare;
		$chartyear = $company_group->chartyear;
		$chartmonth = $company_group->chartmonth;

		// SPC 하위 발전소 목록화
		$companyMap = $companies->keyBy('idx');
		
		// SPC 전체 발전소 용량 합계
		$totalVolume = $companies->sum('volume');

		// SPC 하위 발전소 개수
		$companiesCount = $companies->count();

		/*********************************************************/
		// 리포트 관련 날짜 계산
		/*********************************************************/
		// 리포트 표지 하단 일자 표시
		$today = Carbon::now('Asia/Seoul')->startOfDay()->format('Y-m-d');

		// 해당월의 1~말일, 전년동월, 전월, 1개월뒤, 2개월뒤, 3개월뒤 계산
		$baseDate = Carbon::create($year, $month, 1);
        $start_date = $baseDate->copy()->startOfMonth()->format('Y-m-d');	// 선택 년월의 시작일
		$end_date   = $baseDate->copy()->endOfMonth()->format('Y-m-d');		// 선택 년월의 말일
		$prevMonth = $baseDate->copy()->subMonth();							// 선택 년월의 전년도 년월
		$prevYear  = $baseDate->copy()->subYear();							// 선택 년월의 전월
		$lastYearSameMonth = $prevMonth->copy()->format('Y-m');				// 선택 년월의 전년도 년월 포멧 변경
		$lastMonth = $prevYear->copy()->format('Y-m');						// 선택 년월의 전월 포멧 변경
		$next1Month = $baseDate->copy()->addMonth()->format('Y년 m월');		// 1개월 뒤
		$next2Month = $baseDate->copy()->addMonths(2)->format('Y년 m월');   // 2개월 뒤
		$next3Month = $baseDate->copy()->addMonths(3)->format('Y년 m월');   // 3개월 뒤


		/*********************************************************/
		// 리포트 데이터 획득 후 리포트용 데이터로 변환
		/*********************************************************/
		// 해당 SPC의 전체 리포트 데이터 획득 / 이하 해당 데이터를 재활용해서 DB 조회 최소화
		$allGroupReports = CompanyReportMonthly::where('group_idx', $group_idx)->get();

		// allgroupreports 데이터에서 선택 년월에 대한 리포트 데이터 획득
		$reports = $allGroupReports->where('tgt_year', $year)->where('tgt_month', $month)->keyBy('comp_idx'); 

		// 전체 년간 목표 발전량 합계를 획득					
		$totalTgtKwh = $reports->sum('tgt_yearkwh');

		// 해당 년월의 합계를 획득
		$totalMonthActKwh = $reports->sum('act_kwh');
		$totalMonthSmp = $reports->sum('smp_amount');
		$totalMonthRec = $reports->sum('rec_amount');

		// 선택 년월에 해당하는 period 년차 획득
		$period = $reports->first()?->period;

		// 해당 년차의 시작부터 선택월까지 데이터만 추출해서 정리
		$periodAccumulatedData = $allGroupReports->where('period', $period)
			->filter(function ($report) use ($year, $month) {
				return $report->tgt_year < $year || ($report->tgt_year == $year && $report->tgt_month <= $month);
			});

		// 해당 년차 정리된 데이터에서 발전량, SMP, REC 누적 합계 획득 (년차 시작월 ~ 선택월)
		$totalPeriodActKwh = $periodAccumulatedData->sum('act_kwh');
		$totalPeriodSmp    = $periodAccumulatedData->sum('smp_amount');
		$totalPeriodRec    = $periodAccumulatedData->sum('rec_amount');

		// 선택 년월과 전년도, 전월과 일사량, 발전량 비교
		$comparison = $allGroupReports->groupBy('comp_idx')
			->map(function ($group) use ($year, $month, $lastYearSameMonth, $lastMonth, $prevMonth, $prevYear) {
			
			// 현재월 데이터
			$cur = $group->where('tgt_year', $year)->where('tgt_month', $month)->first();

			// 전월 데이터
    		$preMonth = $group->where('tgt_year', $prevMonth->year)->where('tgt_month', $prevMonth->month)->first();

			// 전년도 데이터
    		$preYear = $group->where('tgt_year', $prevYear->year)->where('tgt_month', $prevYear->month)->first();

			// 증감율 계산 (a/b-1 로 증감률만 추출)
			$analyze = function($new, $old) {
				$new = (float)($new ?? 0);
				$old = (float)($old ?? 0);
				$rate = ($old > 0) ? round((($new / $old) - 1) * 100, 2) : 0;
				
				// 증감율과 증가, 감소 표시 구성
				return (object)[
					'rate'   => $rate,
					'status' => ($rate > 0) ? 'up' : (($rate < 0) ? 'down' : 'same')
				];
			};

			$actPrevMonth = $analyze($cur?->act_kwh, $preMonth?->act_kwh);
			$actPrevYear  = $analyze($cur?->act_kwh, $preYear?->act_kwh);
			$kmaPrevMonth = $analyze($cur?->cur_month_kma, $cur?->pre_month_kma);
			$kmaPrevYear  = $analyze($cur?->cur_month_kma, $cur?->pre_year_kma);

			return (object)[
				'comp_idx' => $group->first()->comp_idx,
				
				// 발전량 증감 데이터
				'act_month' => (object)$actPrevMonth, // $item->act_month->rate, $item->act_month->status
				'act_year'  => (object)$actPrevYear,
				
				// 일사량 증감 데이터
				'kma_month' => (object)$kmaPrevMonth,
				'kma_year'  => (object)$kmaPrevYear,
			];
		})->keyBy('comp_idx');

		// 선택 년월에 해당하는 년차의 발전소별 발전량 
		$periodCompanys = $periodAccumulatedData->groupBy('comp_idx')
			->map(function ($group) {
				return (object)[
					'comp_idx' => $group->first()->comp_idx,
					'tgt_yearkwh' => $group->first()->tgt_yearkwh, // 년차별 목표 발전량은 12개월이 동일로 첫번째에서 획득
					'sum_act_kwh' => round($group->sum('act_kwh'), 2), // 실제 발전량 합계
				];
			})->keyBy('comp_idx');

		// 그래프 표시용 데이터 생성 배열로 생성 필요로 배열화 구성
		// 선택 년월에 해당하는 발전량 그래프 표시를 위해서 발전소와 발전량을 배열로 변경
		$labels = [];
		$values = [];
		foreach ($companies as $c) {
			$labels[] = $c->comp_name;

			$values[] = isset($reports[$c->idx])
				? round($reports[$c->idx]->act_hours, 2)
				: 0;
		}

		// SPC 전체 데이터에서 선택 년에 해당하는 년간 그래프 표시를 위해 년간 데이터 획득
		$chartrows = $allGroupReports->where('tgt_year', $year);

		// 년간 그래프를 위해서 각 월단위로 배열 생성
		// [발전소][월] 형태로 구성
		$monthlyReports = [];
		foreach ($chartrows as $row) {
			$monthlyReports[$row->comp_idx][$row->tgt_month] = $row;
		}
		
		// 월 단위 그래프 표시를 위해서 월 단위로 배열 재구성
		$monthlabels = [];
		$monthvalues = [];
		for ($m = 1; $m <= 12; $m++) {
			$monthlabels[] = $m . '월';
		}
		
		// 월별 그래프 표시용 데이터 생성
		foreach ($companies as $c) {
			$data = [];

			for ($m = 1; $m <= 12; $m++) {
				$val = $monthlyReports[$c->idx][$m]->act_hours ?? 0;
				$data[] = round($val, 2);
			}

			$monthvalues[] = [
				'label' => $c->comp_name,
				'data' => $data,
			];
		}

		// 선택 년차에 대한 목표 발전량, 실제 발전량 데이터 구성
   		$summary = $allGroupReports->groupBy(function ($item) {
				// 발전소(comp_idx) + 년차(period) + 년차별 목표 발전량(tgt_yearkwh)으로 그룹화 (group by 같은 효과)
				return "{$item->comp_idx}_{$item->period}_{$item->tgt_yearkwh}";
			})
			->map(function ($group) {
				// comp_idx, period, tgt_yearkwh 는 12개월이 모두 동일해서 1번째 줄에서 정보 획득
				$first = $group->first(); 
				
				return (object)[
					'comp_idx'    => $first->comp_idx,
					'period'      => $first->period,
					'tgt_yearkwh' => $first->tgt_yearkwh,
					'sum_act_kwh' => $group->sum('act_kwh'), // 해당 년차 전체 발전량 계산
					// 필요시 sales, smp, rec 등 년차별 합계가 필요하면 여기에 추가
				];
			})
			->values(); // 인덱스 배열로 재정렬

		// summary 에서 전체 년차에 대한 데이터를 배열로 재구성 [period][comp_idx]
		$summarydata = [];
		foreach ($summary as $row) {
			// 집계하려는 항목과 일치하는 회사 정보 획득
			$company = $companyMap[$row->comp_idx] ?? null;

			if (!$company || !$company->start_date) {
				continue;
			}

			$startDate = Carbon::parse($company->start_date);
			$periodData = (int)$row->period;

			// 시작년월 (n년차 시작)
			$startYm = $startDate->copy()->addMonths(($periodData - 1) * 12);

			// 종료년월 (n년차 끝)
			$endYm = $startDate->copy()->addMonths($periodData * 12)->subMonth();

			// row에 추가
			$row->start_ym = $startYm->format('y-m');
			$row->end_ym   = $endYm->format('y-m');
			
			$summarydata[$row->period][$row->comp_idx] = $row;
		}		
		
		// summary 를 period 단위로 분류
    	$periods = collect($summary)->pluck('period')->unique()->sort()->values();

		// 선택 년도에 해당하는 전체 O&M 내역 획득
		$yearlyManages = CompanyManage::with('company:idx,comp_name')
    				->selectRaw("comp_idx, day, type, manage, result, count")
    				->whereYear('day', $year) // 1년치 전체 조회
    				->whereIn('comp_idx', $companies->pluck('idx'))
    				->get();

		// 선택 년도의 O&M 내역에서 선택월의 시작일~말일까지의 데이터를 획득
		$manages = $yearlyManages->filter(function ($item) use ($start_date, $end_date) {
    		return $item->day >= $start_date && $item->day <= $end_date;
		});		
		
		// 타입별을 기준으로 발전소별 O&M 내역을 재구성
		// 	"정기 점검": {  "1": { "comp_name": "디엠산업", "days": "02-02, 02-20" }}
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

		// 년간 정기 점검, 긴급 출동, 소모품 점검, 합계로 분류
		$targetType = '정기 점검';		// 각 셀에 표시할 타입
		$emergencyType = '긴급 출동';	// 우측 끝에 합산할 타입
		$changeType = '소모품 교체';	// 우측 끝에 합산할 타입
		$months = range(1, 12);		// 1월부터 12월까지

		// 월별 데이터 가공, collect와 map을 통해서 1~12월까지 loop가 실행됨
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

		// O&M 항목별 년간 합계
		$yearManageSummary = [
			'month' => '합계',
			'total_change' => $yearManageData->sum('monthly_change'), // 하단 우측 끝 (연간 소모품 교체 총합)
			'total_emergency' => $yearManageData->sum('monthly_emergency') // 하단 우측 끝 (연간 긴급 출동 총합)
		];

		// O&M 내역 하단에 년간 합계 추가
		foreach ($companies as $company) {
			$yearManageSummary[$company->idx] = $yearManageData->sum($company->idx);
		}

		// 선택 월의 정기 점검 데이터를 추출
		$checkData = $manages->filter(function ($item) {
    		return $item->type === '정기 점검';
		})->map(function ($item) {
    		return [
				'comp_name' => $item->company->comp_name,
				'day'       => \Carbon\Carbon::parse($item->day)->format('m-d'),
				'manage'    => $item->manage ?? '',
				'result'    => $item->result ?? '이상 없음',
				'count'    => $item->count ?? ''
			];
		})->sortBy('comp_name')->sortBy('day')->values();	
		
		// 선택 월의 긴급 출동 데이터를 추출
		$emergencyData = $manages->filter(function ($item) {
    		return $item->type === '긴급 출동';
		})->map(function ($item) {
    		return [
				'comp_name' => $item->company->comp_name,
				'day'       => \Carbon\Carbon::parse($item->day)->format('m-d'),
				'manage'    => $item->manage ?? '',
				'result'    => $item->result ?? '이상 없음',
				'count'    => $item->count ?? ''
			];
		})->sortBy('comp_name')->sortBy('day')->values();

		// 선택 월의 소모품 교체 데이터를 추출
		$changeData = $manages->filter(function ($item) {
    		return $item->type === '소모품 교체';
		})->map(function ($item) {
    		return [
				'comp_name' => $item->company->comp_name,
				'day'       => \Carbon\Carbon::parse($item->day)->format('m-d'),
				'manage'    => $item->manage ?? '',
				'result'    => $item->result ?? '',
				'count'    => $item->count ?? '교체 없음'
			];
		})->sortBy('comp_name')->sortBy('day')->values();

		// 선택 월의 이슈 데이터를 추출
		$issueData = $manages->filter(function ($item) {
    		return $item->type === '이슈';
		})->map(function ($item) {
    		return [
				'comp_name' => $item->company->comp_name,
				'day'       => \Carbon\Carbon::parse($item->day)->format('m-d'),
				'manage'    => $item->manage ?? ''
			];
		})->sortBy('comp_name')->sortBy('day')->values();	

		// 일사량으로 발전량 산정하는 계수
		$mjToKwh = 0.277784755950055;

		$data = [
			'group_name' => $group_name,				// SPC 명
            'year' => $year,							// 선택 년도
            'month' => $month,							// 선택 월
			'period' => $period,						// 선택 년,월에 해당하는 년차 수
			'lastYearSameMonth' => $lastYearSameMonth,	// 전년도 동월
			'lastMonth' => $lastMonth,					// 전월
			'next1Month' => $next1Month,				// 1개월 뒤
			'next2Month' => $next2Month,				// 2개월 뒤
			'next3Month' => $next3Month,				// 3개월 뒤
            'today' => $today,							// 오늘
			'start_date' => $start_date,				// 선택 년,월의 시작일
			'end_date' => $end_date,					// 선택 년,월의 말일
            'companiesCount' => $companiesCount,		// SPC 내 발전소 수
			'totalVolume' => $totalVolume,				// SPC 내 발전소의 총 발전 용량
			'summarysales' => $summarysales,			// 요약 내 매출 표시 여부
			'allperiod' => $allperiod,					// 현황 내 전체 년차 현황 표시 여부
			'startdate' => $startdate,					// 현황 내 전체 상업운전 시작일 표시 여부			
			'selectperiod' => $selectperiod,			// 현황 내 선택 년,월 현황 표시 여부
			'selectcompany' => $selectcompany,			// 현황 내 선택 년,월 발전소별 발전량 표시 여부
			'compare' => $compare,						// 현황 내 선택 년,월 현황과 전년도, 전월 비교 표시 여부
			'chartyear' => $chartyear,					// 년간 그래프 표시 여부
			'chartmonth' => $chartmonth,				// 월간 그래프 표시 여부
			'totalTgtKwh' => $totalTgtKwh,				// 선택 년에 해당하는 년차의 목표 발전량
			'totalPeriodActKwh' => $totalPeriodActKwh,	// 선택 년월에 해당하는 년차의 발전량 합계
			'totalPeriodSmp' => $totalPeriodSmp,		// 선택 년월에 해당하는 년차의 SMP 합계
			'totalPeriodRec' => $totalPeriodRec,		// 선택 년월에 해당하는 년차의 REC 합계
			'totalMonthActKwh' => $totalMonthActKwh,	// 선택 년월의 발전량 합계
			'totalMonthSmp' => $totalMonthSmp,			// 선택 년월의 SMP 합계
			'totalMonthRec' => $totalMonthRec,			// 선택 년월의 REC 합계
			'mjToKwh' => $mjToKwh,						// 일사량으로 발전량 산정하는 계수
        ];			

    	return view('report.spcMonthReport', 
			compact('data', 				// data 배열
					'companies', 			// 발전소 목록 배열
					'reports', 				// 리포트 데이터
					'labels', 				// 년간 그래프에 표시할 발전소 목록
					'values', 				// 년간 그래프에 표시할 발전소 별 값
					'monthlabels', 			// 월 그래프에 표시할 발전소 목록
					'monthvalues', 			// 월 그래프에 표시할 발전소 별 값
					'summarydata', 			// 전체 년차 데이터
					'periods', 				// 선택 년월 현황
					'periodCompanys', 		// 선택 년월에 해당하는 년차의 발전소별 발전량
					'comparison',			// 선택 년,월 현황과 전년도, 전월 비교 데이터
					'manageData', 			// O&M 타입별로 재구성한 데이터
					'yearManageData', 		// 년간 월별 O&M 건수
					'yearManageSummary', 	// 년간 O&M 합계
					'checkData', 			// 정기 점검 데이터
					'emergencyData', 		// 긴급 출동 데이터
					'changeData', 			// 소모품 교체 데이터
					'issueData'));			// 이슈 데이터
    }

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

}