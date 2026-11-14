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
use App\Models\Cities;
use App\Models\CheckExecution;
use App\Models\CheckItem;
use App\Models\CheckList;
use App\Models\CheckOption;
use App\Models\CheckResult;
use App\Models\CheckSection;
use App\Models\CheckSign;
use App\Models\Company;
use App\Models\CompanyOut;
use App\Models\Department;
use App\Models\OutCompany;
use App\Models\OutUser;

class CheckListController extends Controller
{
	//================================== 테스트베드 설문조사 (로그인 없음) ==================================
	// 화면 언어 : ?lang= > 브라우저 언어
	private function surveyLang(Request $request)
	{
		$lang = $request->input('lang');
		if (!in_array($lang, ['ko', 'en', 'vn'], true)) {
			$lang = ['vi' => 'vn', 'en' => 'en'][$request->getPreferredLanguage(['ko', 'en', 'vi'])] ?? 'ko';
		}
		app()->setLocale($lang);

		return $lang;
	}

	// 설문 화면 고정 문구 (문항은 관리자가 등록한 내용 그대로 표시)
	private function surveyLabels($lang)
	{
		return [
			'ko' => [
				'respondent' => '응답자 정보',
				'age'        => '연령대',
				'gender'     => '성별',
				'city'       => '거주지 (시)',
				'district'   => '거주지 (구역)',
				'select'     => '선택해주세요',
				'multi'      => '복수 선택 가능',
				'text'       => '답변을 입력해주세요',
				'remark'     => '비고 (선택)',
				'submit'     => '제출',
				'required'   => '필수 항목입니다.',
				'done'       => '답변이 제출되었습니다. 참여해주셔서 감사합니다.',
				'closed'     => '종료되었거나 참여할 수 없는 설문입니다.',
				'error'      => '오류가 발생했습니다. 다시 시도해주세요.',
				'expired'    => '페이지가 만료되었습니다. 새로고침 후 다시 제출해주세요.',
				'ages'       => CheckExecution::AGE_GROUPS,
				'genders'    => CheckExecution::GENDERS,
			],
			'en' => [
				'respondent' => 'About you',
				'age'        => 'Age group',
				'gender'     => 'Gender',
				'city'       => 'City',
				'district'   => 'District',
				'select'     => 'Please select',
				'multi'      => 'Select all that apply',
				'text'       => 'Enter your answer',
				'remark'     => 'Notes (optional)',
				'submit'     => 'Submit',
				'required'   => 'This field is required.',
				'done'       => 'Your response has been submitted. Thank you for participating.',
				'closed'     => 'This survey is closed or unavailable.',
				'error'      => 'Something went wrong. Please try again.',
				'expired'    => 'This page has expired. Please refresh and submit again.',
				'ages'       => ['1-10' => '1–10', '11-20' => '11–20', '21-30' => '21–30', '31-40' => '31–40', '41-50' => '41–50', '51-59' => '51–59', '60+' => '60 and over'],
				'genders'    => ['M' => 'Male', 'F' => 'Female'],
			],
			'vn' => [
				'respondent' => 'Thông tin người trả lời',
				'age'        => 'Độ tuổi',
				'gender'     => 'Giới tính',
				'city'       => 'Tỉnh/Thành phố',
				'district'   => 'Quận/Huyện',
				'select'     => 'Vui lòng chọn',
				'multi'      => 'Có thể chọn nhiều đáp án',
				'text'       => 'Nhập câu trả lời',
				'remark'     => 'Ghi chú (không bắt buộc)',
				'submit'     => 'Gửi',
				'required'   => 'Vui lòng trả lời mục này.',
				'done'       => 'Câu trả lời của bạn đã được gửi. Cảm ơn bạn đã tham gia.',
				'closed'     => 'Khảo sát này đã kết thúc hoặc không khả dụng.',
				'error'      => 'Đã xảy ra lỗi. Vui lòng thử lại.',
				'expired'    => 'Trang đã hết hạn. Vui lòng tải lại trang và gửi lại.',
				'ages'       => ['1-10' => '1–10 tuổi', '11-20' => '11–20 tuổi', '21-30' => '21–30 tuổi', '31-40' => '31–40 tuổi', '41-50' => '41–50 tuổi', '51-59' => '51–59 tuổi', '60+' => 'Từ 60 tuổi'],
				'genders'    => ['M' => 'Nam', 'F' => 'Nữ'],
			],
		][$lang];
	}

	// 설문 화면
	public function getSurvey(Request $request, $token)
	{
		$lang   = $this->surveyLang($request);
		$labels = $this->surveyLabels($lang);
		$list   = CheckList::with(['product', 'checksection.checkitem.checkoption'])->where('survey_token', $token)->firstOrFail();
		$closed = $list->use_yn !== 'Y' || !$list->product;

		$cities = Cities::with('districts')->orderBy('idx')->get()->map(fn ($city) => [
			'idx'       => $city->idx,
			'name'      => $city->tr('name'),
			'districts' => $city->districts->map(fn ($district) => ['idx' => $district->idx, 'name' => $district->tr('name')])->values(),
		]);

		return view('survey.form', compact('list', 'lang', 'labels', 'closed', 'cities'));
	}

	// 답변 저장 (객관식 필수, 주관식·비고 선택)
	public function setSurveyAnswer(Request $request, $token)
	{
		$labels = $this->surveyLabels($this->surveyLang($request));
		$list   = CheckList::with(['product', 'checksection.checkitem.checkoption'])->where('survey_token', $token)->firstOrFail();

		if ($list->use_yn !== 'Y' || !$list->product) return response()->json(['status' => 'error', 'message' => $labels['closed']]);

		$city        = Cities::with('districts')->find((int) $request->input('cities_idx'));
		$districtIdx = (int) $request->input('districts_idx');
		$demographicsValid = array_key_exists((string) $request->input('age_group'), CheckExecution::AGE_GROUPS)
			&& array_key_exists((string) $request->input('gender'), CheckExecution::GENDERS)
			&& $city && $city->districts->contains('idx', $districtIdx);

		if (!$demographicsValid) return response()->json(['status' => 'error', 'message' => $labels['required']]);

		$results = (array) $request->input('results', []);
		$remarks = (array) $request->input('text_results', []);
		$rows    = [];

		foreach ($list->checksection as $section) {
			foreach ($section->checkitem as $item) {
				$base  = ['list_idx' => $list->idx, 'section_idx' => $section->idx, 'item_idx' => $item->idx, 'input_type' => $item->input_type];
				$value = $results[$item->idx] ?? null;

				if ($item->input_type === 'input') {
					$text = is_string($value) ? mb_substr(trim($value), 0, 2000) : '';
					if ($text !== '') $rows[] = $base + ['option_idx' => null, 'result_value' => $text];
					continue;
				}

				// 이 문항의 옵션만 인정 (단일 선택은 1개만)
				$optionIds = array_values(array_intersect($item->checkoption->pluck('idx')->all(), array_map('intval', array_filter((array) $value, 'is_scalar'))));
				if ($item->input_type === 'radio') $optionIds = array_slice($optionIds, 0, 1);
				if (!$optionIds) return response()->json(['status' => 'error', 'message' => $labels['required'], 'item_idx' => $item->idx]);

				$remark = is_string($remarks[$item->idx] ?? null) ? mb_substr(trim($remarks[$item->idx]), 0, 2000) : '';
				foreach ($optionIds as $i => $optionIdx) {
					$rows[] = $base + ['option_idx' => $optionIdx, 'result_value' => ($i === 0 && $remark !== '') ? $remark : null];
				}
			}
		}

		try {
			DB::transaction(function () use ($list, $request, $city, $districtIdx, $rows) {
				$execution = CheckExecution::create([
					'list_idx'      => $list->idx,
					'age_group'     => $request->input('age_group'),
					'gender'        => $request->input('gender'),
					'cities_idx'    => $city->idx,
					'districts_idx' => $districtIdx,
					'use_yn'        => 'Y',
					'created_at'    => Carbon::now(),
					'created_by'    => 'survey',
				]);
				$execution->checkresult()->createMany($rows);
			});

			return response()->json(['status' => 'success']);
		} catch (\Exception $e) {
			Log::error('survey answer save failed', ['list_idx' => $list->idx, 'error' => $e->getMessage()]);
			return response()->json(['status' => 'error', 'message' => $labels['error']], 500);
		}
	}


	//================================== 이전 체크리스트 (라우트 미연결) ==================================
	// 체크리스트 접속을 위한 사용자 성명, 전화번호 인증 (별도 비밀번호 등 체크 없음)
	public function checkLoginView(Request $request)
	{
		return view('checklist.login');
	}	

	// 체크리스트 접속을 위한 업체 로그인
	public function checkAuth(Request $request) 
	{
		$outcompany = OutCompany::where('id', $request->id)->first();
		
		if($outcompany && Hash::check($request->pw, $outcompany->password)) {
			Auth::guard('outcompany')->login($outcompany);

			// 세션에 정보 저장 (이후 모든 점검/조회 시 이 정보를 기준값으로 사용)
			Session::put('outcompany', [
				'out_idx' => $outcompany->idx,
				'out_name' => $outcompany->out_name,
				'ceo' => $outcompany->ceo,
				'phone' => $outcompany->phone,
				'email' => $outcompany->email,
				'address' => $outcompany->address,
				'id' => $outcompany->id,
			]);

			return response()->json(['status' => 'success']);
		}

		return back()->withErrors(['msg' => '정보가 일치하지 않습니다.']);
	}

	public function userCheck(Request $request) 
	{
		$user = Session::get('outcompany');
		return view('checklist.user', compact('user'));
	}	

	// 체크리스트 접속을 위한 사용자 성명, 전화번호 인증 (별도 비밀번호 등 체크 없음)
	public function userAuth(Request $request) 
	{
		$outcompanySession = Session::get('outcompany');

		$outuser = OutUser::where('user_name', $request->user_name)
					->where('user_phone', $request->user_phone)
					->where('use_yn', 'Y')
					->first();

		if(!$outuser) {
			$outuser = OutUser::create([
				'out_idx' 	=> $outcompanySession['out_idx'],
				'work_type' => '구분 없음',
				'user_name' => $request->user_name,
				'user_phone'=> $request->user_phone,
				'reg_type'	=> 'C',  // 체크리스트
				'use_yn'	=> 'Y',
				'created_by'=> '체크리스트',
			]);		
		}

		// 세션에 정보 저장 (이후 모든 점검/조회 시 이 정보를 기준값으로 사용)
		$outcompanySession['user_idx'] = $outuser->idx;
    	$outcompanySession['user_name'] = $outuser->user_name;
		$outcompanySession['user_phone'] = $outuser->user_phone;
		Session::put('outcompany', $outcompanySession);

		return response()->json(['status' => 'success']);
	}	

	// 로그아웃
	public function checkLogout()
	{
		Session::forget('outcompany');
		return redirect()->route('check.login');
	}	

	// 체크리스트 접속
	public function checkMainView(Request $request) 
	{
		if (!Session::has('outcompany')) {
			return redirect()->route('check.login');
		}

		// 세션에 저장된 [name, phone] 배열을 가져옵니다.
    	$user = Session::get('outcompany');

		$checklistQuery = CheckList::with('checksection', 'department'); // 섹션 정보 포함

		$checklist = $checklistQuery->clone()->orderBy('check_name')->get();

		// 체크 리스트 제목 필터
		if ($request->filled('check_name')) {
			$checklistQuery->where('check_name', 'like', '%' . $user['user_name'] . '%');
		}

		// 이름순 정렬
		$check = $checklistQuery->orderBy('check_name')->get();

		// 체크 결과 (로그인 사용자의 이름, 전화번호로 체크 결과 필터)
		$executionQuery = CheckExecution::with('checkresult', 'checklist') // 섹션 정보 포함
					->where('use_yn', 'Y')
					->where('user_name', $user['user_name'])
					->where('user_phone', $user['user_phone']);

		$execution = $executionQuery->clone()->orderBy('user_name')->get();

		// 검색용 프로젝트 목록 (Select Box용)
		$projects = $executionQuery->clone()->pluck('project_name')->unique()->sort();

		// 체크 결과 프로젝트명 필터
		if ($request->filled('project_name')) {
			$executionQuery->where('project_name', $request->project_name);
		}
		
		// 최신순 정렬		
		$result = $executionQuery->orderByDesc('created_at')->get();

		return view('checklist.main', compact('checklist', 'check', 'execution', 'result', 'projects'));
	}

	// 체크 페이지 구성
	public function checkExecuteForm($idx)
	{
		// 세션 체크 (인증된 사용자만)
		if (!Session::has('outcompany')) {
			return redirect()->route('check.login');
		}

		// 세션에 저장된 [name, phone] 배열을 가져옵니다.
    	$user = Session::get('outcompany');

		// 템플릿 구조 전체 로드 (Section > Item > Option 순서대로)
		$list = CheckList::with(['checksection.checkitem.checkoption' => function($query) {
			$query->orderBy('sort_order');
		}])->findOrFail($idx);

		
		$companylist = CompanyOut::with('company')
						->where('out_idx', $user['out_idx'])
						->where('use_yn', 'Y')
						->get()
						->map(function($item) {
							return $item->company;
						})->filter();

		return view('checklist.execute', compact('list', 'user', 'companylist'));
	}
	
	// 체크, 서명, 위경도 저장
	public function checkExecuteSave(Request $request)
	{
		try {
			return DB::transaction(function () use ($request) {

				// 점검 실행 마스터(Execution) 생성
				$execution = CheckExecution::create([
					'list_idx'     => $request->list_idx,
					'project_idx' => $request->project_idx,
					'project_name' => $request->project_name,
					'user_comp'    => $request->user_comp,
					'user_name'    => $request->user_name,
					'user_phone'   => $request->user_phone,
					'user_email'   => $request->user_email,
					'user_lat'     => $request->user_lat,
					'user_lng'     => $request->user_lng,
					'user_sign'    => $this->saveSignature($request), 
					'use_yn'       => 'Y',
					'created_by'   => $request->user_name
				]);

				// 개별 문항 답변(Result) 저장
				if ($request->has('results')) {
					foreach ($request->results as $itemIdx => $val) {
						$item = CheckItem::find($itemIdx);
						if ($item) {
							$extraText = $request->input("text_results.{$itemIdx}");

							// $execution->checkresult() 관계를 사용하여 저장
							$execution->checkresult()->create([
								'exec_idx'     => $execution->idx,
								'list_idx'     => $request->list_idx, // 모델 fillable에 있는 list_idx 추가
								'section_idx'  => $item->section_idx,
								'item_idx'     => $itemIdx,
								'input_type'   => $item->input_type,
								'option_idx'   => ($item->input_type != 'input') ? $val : null,
								'result_value' => ($item->input_type == 'input') ? $val : ($extraText ?: null),
							]);
						}
					}
				}

				return response()->json(['status' => 'success']);
			});
		} catch (\Exception $e) {
			// 에러 발생 시 로그 기록 및 메시지 반환
			return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
		}
	}

	// 체크 결과 보기
	public function checkResultView($idx)
	{
		// 세션 체크
		if (!Session::has('outcompany')) return redirect()->route('check.login');

		// 결과 마스터와 상세 답변, 그리고 원본 템플릿 구조를 함께 로드
		$execution = CheckExecution::with(['checkresult.checkitem', 'checklist.checksection.checkitem.checkoption'])
					->findOrFail($idx);

		return view('checklist.result', compact('execution'));
	}

	// 체크한 결과 수정시 원본은 보관(use_yn = n), 를 수정하려고 할 때 최초 작성본은 보관하고 복사본을 생성하여 저장
	public function checkExecuteCopy($idx)
	{
		try {
			return DB::transaction(function () use ($idx) {
				// 원본 데이터
				$origin = CheckExecution::with('checkresult')->findOrFail($idx);

				// 원본 비활성화
				$origin->update([
					'use_yn' => 'N',
					'updated_at' => now(),
					'updated_by' => Session::get('check_user.name')
				]);

				// 사본 생성
				$newExec = CheckExecution::create([
					'parent_idx'   => $origin->idx,
					'list_idx'     => $origin->list_idx,
					'project_idx'  => $origin->project_idx,
					'project_name' => $origin->project_name,
					'user_comp'    => $origin->user_comp,
					'user_name'    => $origin->user_name,
					'user_phone'   => $origin->user_phone,
					'user_email'   => $origin->user_email,
					'user_lat'     => $origin->user_lat,
					'user_lng'     => $origin->user_lng,
					'user_sign'    => $origin->user_sign, // 기존 서명 일단 복제
					'use_yn'       => 'Y',
					'created_at'   => now(),
					'created_by'   => Session::get('check_user.name')
				]);

				// 답변 사본 생성
				foreach ($origin->checkresult as $res) {
					$newExec->checkresult()->create([
						'exec_idx'     => $newExec->idx,
						'list_idx'     => $origin->list_idx,
						'section_idx'  => $res->section_idx,
						'item_idx'     => $res->item_idx,
						'input_type'   => $res->input_type,
						'option_idx'   => $res->option_idx,
						'result_value' => $res->result_value,
					]);
				}

				// 사본 idx 로 이동
				return redirect()->route('check.modify', [
                	'idx' => $origin->list_idx, 
                	'exec_idx' => $newExec->idx
            	]);
			});
		} catch (\Exception $e) {
			dd($e);
			return back()->with('error', '수정 모드 전환 중 오류가 발생했습니다.');
		}
	}

	// 수정화면 구성
	public function checkModifyForm($idx, $exec_idx = null)
	{
		$user = Session::get('outcompany');
		$list = CheckList::with(['checksection.checkitem.checkoption'])->findOrFail($idx);
		
		$existingResults = null;
		$execution = null;

		if ($exec_idx) {
			// 사본 데이터를 가져옴 (수정 모드)
			$execution = CheckExecution::with('checkresult')->findOrFail($exec_idx);
			
			// item_idx를 키로 해서 답변 데이터를 매핑 (조회 속도 향상)
			$existingResults = $execution->checkresult->keyBy('item_idx');
		}

		return view('checklist.modify', compact('list', 'user', 'existingResults', 'execution'));
	}
	
	// 수정처리
	public function checkExecuteUpdate(Request $request)
	{
    	return DB::transaction(function () use ($request) {
			// 사본 정보 확인
			$execution = CheckExecution::findOrFail($request->exec_idx);
			
			// 답변은 수정보다 재등록으로 처리하기 위해서 삭제
			$execution->checkresult()->delete();

			// 서명이 있을 때만 업데이트
			if ($request->filled('signature_image')) {
				$newSignPath = $this->saveSignature($request);
				$execution->user_sign = $newSignPath;
			} 

			// 마스터 정보 업데이트성
			$execution->fill([
				'list_idx'     => $request->list_idx,
				'project_idx'  => $request->project_idx,
				'project_name' => $request->project_name,
				'user_comp'    => $request->user_comp,
				'user_name'    => $request->user_name,
				'user_phone'   => $request->user_phone,
				'user_lat'     => $request->user_lat,
				'user_lng'     => $request->user_lng,
			]);
			$execution->save();

			// 3. 상세 답변(Result) 저장 (기존 로직 동일)
			foreach ($request->results as $itemIdx => $val) {
				$item = CheckItem::find($itemIdx);
				$extraText = $request->input("text_results.{$itemIdx}");

				$execution->checkresult()->create([
					'section_idx'  => $item->section_idx,
					'list_idx'     => $request->list_idx,
					'item_idx'     => $itemIdx,
					'input_type'   => $item->input_type,
					'option_idx'   => ($item->input_type != 'input') ? $val : null,
					'result_value' => ($item->input_type == 'input') ? $val : ($extraText ?: null),
				]);
			}

        	return response()->json(['status' => 'success']);
    	});
	}
	
	// 서명 저장 함수
	private function saveSignature(Request $request)
	{
		if ($request->filled('user_sign')) {
			$imageData = $request->user_sign;
			
			// Base64 헤더 제거
			$image = str_replace('data:image/png;base64,', '', $imageData);
			$image = str_replace(' ', '+', $image);
			
			// 파일명 생성 및 저장 경로 설정
			$fileName = 'sign_' . time() . '.png';
			$signPath = 'signatures/' . $fileName;
			
			// Storage 폴더에 실제 파일 저장
			Storage::disk('public')->put($signPath, base64_decode($image));
			
			return $signPath; // 저장된 경로 반환
		}

		return null; // 서명이 없으면 null 반환
	}

	
	public function downloadFile($path, $ori_name) {
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