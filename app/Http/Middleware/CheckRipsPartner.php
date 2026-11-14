<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RIPS 이용 업체 인증
 * - 로그인하지 않았거나 RIPS 사용이 해제된 업체는 로그인 화면으로
 * - URL 의 업체ID({rid})가 로그인한 업체와 다르면 본인 화면으로
 */
class CheckRipsPartner
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard   = Auth::guard('rips');
        $partner = $guard->user();

        if (!$partner || (int) $partner->rips_use !== 1) {
            $guard->logout();

            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => '로그인이 필요합니다.'], 401);
            }
            return redirect()->route('rips.login');
        }

        if ($request->route('rid') !== $partner->rips_id) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);
            }
            return redirect()->route('rips.dashboard', ['rid' => $partner->rips_id]);
        }

        return $next($request);
    }
}
