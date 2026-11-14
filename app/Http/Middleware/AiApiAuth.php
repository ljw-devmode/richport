<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AiApiLog;
use App\Models\AiApiSetting;

/**
 * AI 연동 API 인증 · 요청 로그
 * - Authorization: Bearer {인증키} (또는 X-AI-KEY 헤더)
 * - 관리자 > RIPS 관리 > AI 연동 에서 API 사용 · 인증키 · 허용 IP 설정
 * - 모든 요청(인증 실패 포함)을 ai_api_log 에 기록
 */
class AiApiAuth
{
	public function handle(Request $request, Closure $next): Response
	{
		$setting = AiApiSetting::current();
		$token   = (string) ($request->bearerToken() ?: $request->header('X-AI-KEY'));

		if (!$setting->exists || (int) $setting->api_use !== 1) {
			$response = $this->error(403, 'API_DISABLED', 'AI 연동 API 가 사용 중지 상태입니다.');
		} elseif ($token === '' || !$setting->api_key || !hash_equals((string) $setting->api_key, $token)) {
			$response = $this->error(401, 'UNAUTHORIZED', '인증키가 올바르지 않습니다.');
		} elseif (!$setting->ipAllowed($request->ip())) {
			$response = $this->error(403, 'IP_NOT_ALLOWED', '허용되지 않은 IP 입니다. ('.$request->ip().')');
		} else {
			$response = $next($request);
			try { $setting->forceFill(['last_request_at' => now()])->saveQuietly(); } catch (\Throwable $e) {}
		}

		$this->log($request, $response);
		return $response;
	}

	private function error(int $status, string $code, string $message): JsonResponse
	{
		return response()->json(['status' => 'error', 'code' => $code, 'message' => $message], $status);
	}

	private function log(Request $request, Response $response): void
	{
		try {
			$data = $response instanceof JsonResponse ? (array) $response->getData(true) : [];
			$ok   = $response->getStatusCode() < 400 && ($data['status'] ?? 'success') === 'success';
			AiApiLog::create([
				'method'      => $request->method(),
				'endpoint'    => mb_substr('/'.$request->path(), 0, 200),
				'status_code' => $response->getStatusCode(),
				'result'      => $ok ? 'success' : 'fail',
				'item_count'  => $data['meta']['count'] ?? null,
				'message'     => $ok ? null : mb_substr((string) ($data['message'] ?? ''), 0, 500),
				'ip'          => $request->ip(),
				'created_at'  => now(),
			]);
		} catch (\Throwable $e) {
			Log::warning('ai api log failed', ['error' => $e->getMessage()]);
		}
	}
}
