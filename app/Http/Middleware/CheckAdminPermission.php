<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, string $perm): Response
    {
        // auth()->user()가 Admin 모델 인스턴스인지 확인하고 hasPerm 호출
        if (!auth()->check() || !auth()->user()->hasPerm($perm)) {
            // AJAX 요청인 경우 (CUD 버튼 등)
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error', 
                    'message' => '해당 기능에 대한 접근 권한이 없습니다.'
                ], 403);
            }

            // 일반 페이지 접속인 경우
            // 이전 페이지가 있으면 back(), 없으면 대시보드로 이동
            return redirect()->route('admin.dashboard')
                ->with('error_msg', '해당 메뉴에 접근할 권한이 없습니다.');
        }

        return $next($request);
    }
}