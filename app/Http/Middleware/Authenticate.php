<?php

namespace app\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
	protected function redirectTo($request)
	{
		if (! $request->expectsJson()) {
			// /admin 또는 /admin/* 접근 시 → 관리자 로그인 페이지로
			if ($request->is('admin') || $request->is('admin/*')) {
				return route('admin.login');
			}
			
			// 그 외 → 메인 페이지로
			return url('/');
		}
	}
}