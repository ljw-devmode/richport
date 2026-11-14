<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AdminLog;

/**
 * 관리자 작업 로그 (등록 / 수정 / 삭제 / 엑셀 다운로드)
 * - 관리자 라우트 중 MENUS 에 있는 메뉴의 regist / modify / delete 등 요청만 기록 (조회용 POST 인 detailForm 등은 제외)
 * - 엑셀 다운로드(GET ~.export)는 개인정보 반출 확인용으로 검색조건과 함께 기록
 * - 대상 이름은 처리 전에 읽어 둠 (삭제 후에도 무엇을 지웠는지 남도록)
 * - 비밀번호 · 토큰은 가리고, 파일은 파일명만 기록
 * - 로그 저장에 실패해도 원래 요청에는 영향 없음
 */
class AdminActionLog
{
	/**
	 * 라우트 이름(admin. 다음) 첫 단어 => [메뉴명, 모델, 대상 이름 (컬럼명 또는 함수), 등록 시 이름으로 쓸 입력값]
	 */
	private function menus(): array
	{
		return [
			'history'        => ['연혁',           \App\Models\History::class,        fn ($m) => trim($m->year.'.'.$m->month.' '.$m->subject), 'subject'],
			'portfolio'      => ['도입현황',       \App\Models\Portfolio::class,      'station_name',     'station_name'],
			'partner'        => ['파트너',         \App\Models\Partner::class,        'partner_name',     'partner_name'],
			'ripsPartner'    => ['RIPS 업체',      \App\Models\Partner::class,        'partner_name',     'partner_name'],
			'ripsOrder'      => ['발주 관리',      \App\Models\RipsOrder::class,      'order_no',         null],
			'aiApi'          => ['AI 연동',        \App\Models\AiApiSetting::class,   fn ($m) => 'AI 연동 설정', null],
			'customsIssue'   => ['통관 이슈',      \App\Models\CustomsIssue::class,   'title',            'title'],
			'product'        => ['제품',           \App\Models\Product::class,        'product_name',     'product_name'],
			'formCategory'   => ['카테고리 관리',  \App\Models\FormTypes::class,      'subject',          'subject'],
			'check'          => ['설문조사 관리',  \App\Models\CheckList::class,      'check_name',       'check_name'],
			'inquire'        => ['고객문의',       \App\Models\Inquire::class,        'inquire_company',  null],
			'inquireRips'    => ['RIPS 이용신청',  \App\Models\Ripsinquire::class,    'inquire_company',  null],
			'inquireTestbed' => ['테스트베드 문의', \App\Models\Testbedinquire::class, 'inquire_company',  null],
			'faq'            => ['FAQ',            \App\Models\Faq::class,            'question',         'question'],
			'user'           => ['사용자 관리',    \App\Models\Admin::class,          fn ($m) => trim($m->name.' ('.$m->id.')'), 'name'],
		];
	}

	// 라우트 이름 끝부분 => [작업, 세부 작업]
	const ACTIONS = [
		'regist'        => ['create', null],
		'copy'          => ['create', '복사'],
		'modify'        => ['update', null],
		'save'          => ['update', null],
		'status'        => ['update', '상태 변경'],
		'sort'          => ['update', '순서 변경'],
		'delete'        => ['delete', null],
		'regenerate'    => ['update', '인증키 재발급'],
		'export'        => ['download', '엑셀'],
	];

	// GET 이어도 기록하는 작업 (엑셀 다운로드)
	const GET_ACTIONS = ['export'];

	// 기록하지 않는 입력값 / 가리는 입력값
	const SKIP_KEYS   = ['_token', '_method'];
	const MASK_FIELDS = '/(^|_)(pw|pwd|password|passwd|pass|token|api_key|secret)($|_)/i';

	public function handle(Request $request, Closure $next): Response
	{
		$isGetAction = $request->isMethod('GET') && in_array(last(explode('.', (string) optional($request->route())->getName())), self::GET_ACTIONS);
		if (!$isGetAction && !in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) return $next($request);

		$plan = null;
		try {
			$plan = $this->plan($request);
		} catch (\Throwable $e) {
			Log::warning('admin log plan failed', ['error' => $e->getMessage()]);
		}

		$response = $next($request);

		if ($plan) {
			try {
				$this->write($request, $response, $plan);
			} catch (\Throwable $e) {
				Log::warning('admin log write failed', ['route' => $plan['route_name'] ?? '', 'error' => $e->getMessage()]);
			}
		}

		return $response;
	}

	// 기록할 요청인지 판단하고, 처리 전에 대상 정보를 읽어 둠
	private function plan(Request $request): ?array
	{
		$routeName = (string) optional($request->route())->getName();
		if (!str_starts_with($routeName, 'admin.')) return null;

		$parts  = explode('.', substr($routeName, 6));
		$menus  = $this->menus();
		$prefix = $parts[0];
		$last   = end($parts);

		if (!isset($menus[$prefix]) || !isset(self::ACTIONS[$last])) return null;

		[$menu, $model, $label, $inputKey] = $menus[$prefix];
		[$action, $detail] = self::ACTIONS[$last];

		$targetIdx  = $request->route('idx');
		$targetName = null;

		if ($targetIdx && ($row = $model::find($targetIdx))) {
			$targetName = is_callable($label) ? $label($row) : $row->{$label};
		} elseif ($action === 'create' && $inputKey) {
			$targetName = $request->input($inputKey) ?? $request->input('regist_'.$inputKey);
		}

		// RIPS 업체 매핑 : 업체 + 매핑한 카테고리 / 상품
		if ($prefix === 'ripsPartner') {
			if ($cidx = $request->route('cidx')) {
				$detail     = '카테고리 매핑';
				$targetName = trim($targetName.' · '.(\App\Models\RipsCategory::find($cidx)->name ?? '#'.$cidx));
			} elseif ($pidx = $request->route('pidx')) {
				$detail     = '상품 매핑 · 발주금액';
				$targetName = trim($targetName.' · '.(\App\Models\RipsProduct::find($pidx)->product_name ?? '#'.$pidx));
			} elseif ($routeName === 'admin.ripsPartner.info.save') {
				$detail = '업체 정보';
			} elseif ($routeName === 'admin.ripsPartner.regist') {
				$detail = '업체 등록';
			}
		}
		if ($prefix === 'ripsOrder' && $last === 'save') $detail = '발주 정보 저장';

		// 엑셀 다운로드 : 대상 = 검색조건 (없으면 전체)
		if ($action === 'download') {
			$filters    = collect($request->query())->except(['page'])->filter(fn ($v) => $v !== null && $v !== '' && $v !== []);
			$targetName = $menu.' 목록 엑셀 · '.($filters->isEmpty() ? '전체' : '검색조건 '.$filters->map(fn ($v, $k) => $k.'='.(is_array($v) ? implode(',', $v) : $v))->implode(', '));
		}

		return [
			'menu'          => $menu,
			'action'        => $action,
			'action_detail' => $detail,
			'target_idx'    => is_numeric($targetIdx) ? (int) $targetIdx : null,
			'target_name'   => $targetName !== null ? mb_substr(strip_tags((string) $targetName), 0, 255) : null,
			'route_name'    => $routeName,
		];
	}

	private function write(Request $request, Response $response, array $plan): void
	{
		[$result, $message, $newIdx] = $this->result($response);

		$admin = auth('admin')->user();

		$plan['target_idx'] = $plan['target_idx'] ?? $newIdx;	// 등록은 응답의 새 idx

		AdminLog::create($plan + [
			'admin_idx'      => $admin?->idx,
			'admin_id'       => $admin?->id,
			'admin_name'     => $admin?->name,
			'method'         => $request->method(),
			'url'            => mb_substr($request->getRequestUri(), 0, 500),
			'request_data'   => $this->requestData($request),
			'result'         => $result,
			'result_message' => $message ? mb_substr($message, 0, 500) : null,
			'ip'             => $request->ip(),
			'user_agent'     => mb_substr((string) $request->userAgent(), 0, 500),
			'created_at'     => now(),
		]);
	}

	/**
	 * 처리 결과 : JSON 은 status 값, 화면 이동은 오류 메시지 유무로 판단
	 * @return array [success|fail, 실패 메시지, 새로 등록된 idx]
	 */
	private function result(Response $response): array
	{
		if ($response->getStatusCode() >= 400 && !$response instanceof JsonResponse) {
			return ['fail', 'HTTP '.$response->getStatusCode(), null];
		}

		if ($response instanceof JsonResponse) {
			$data   = (array) $response->getData(true);
			$status = $data['status'] ?? null;
			$ok     = $response->getStatusCode() < 400 && ($status === null || $status === 'success' || $status === true);
			$newIdx = $data['idx'] ?? ($data['data']['idx'] ?? null);
			return [$ok ? 'success' : 'fail', $ok ? null : (string) ($data['message'] ?? 'HTTP '.$response->getStatusCode()), is_numeric($newIdx) ? (int) $newIdx : null];
		}

		// 이번 요청에서 새로 남긴(flash) 오류만 실패로 봄
		$session = session();
		$flashed = (array) $session->get('_flash.new', []);
		if (array_intersect(['errors', 'error'], $flashed)) {
			$error = $session->get('error') ?? optional($session->get('errors'))->first();
			return ['fail', is_string($error) ? $error : '처리 실패', null];
		}

		return ['success', null, null];
	}

	// 요청 내용 (비밀번호 · 토큰 가림, 파일은 이름만, 긴 값은 자름)
	private function requestData(Request $request): ?string
	{
		$clean = function ($value, $key = '') use (&$clean) {
			if (is_string($key) && preg_match(self::MASK_FIELDS, $key)) return '******';
			if ($value instanceof UploadedFile) return '[파일] '.$value->getClientOriginalName();
			if (is_array($value)) {
				$out = [];
				foreach ($value as $k => $v) $out[$k] = $clean($v, is_string($k) ? $k : $key);
				return $out;
			}
			if (is_string($value) && mb_strlen($value) > 500) return mb_substr($value, 0, 500).'…';
			return $value;
		};

		$data = $clean(array_diff_key($request->all(), array_flip(self::SKIP_KEYS)));
		if (!$data) return null;

		$json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		return mb_strlen($json) > 60000 ? mb_substr($json, 0, 60000) : $json;
	}
}
