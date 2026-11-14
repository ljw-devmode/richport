<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * 사용자 화면 언어 설정 (라우트 그룹 /ko, /en, /vn)
 * - 모델의 tr() 이 이 언어 기준으로 _en / _vn 컬럼을 선택
 */
class SetUserLocale
{
    public function handle(Request $request, Closure $next, string $lang = 'ko'): Response
    {
        App::setLocale($lang);

        return $next($request);
    }
}
