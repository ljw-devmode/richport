<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminLevel2
{
	public function handle(Request $request, Closure $next)
	{
		$admin = session('admin'); // 로그인 시 저장했던 세션: ['level' => ...]
		if (!isset($admin['level']) || (int)$admin['level'] !== 2) {
			// 접근 차단 → 인덱스로 리다이렉트 + alert 메시지
			return redirect()
			->route('admin.index')
			->with('alert', '올바르지않은 접근입니다.');
		}
		return $next($request);
	}
}