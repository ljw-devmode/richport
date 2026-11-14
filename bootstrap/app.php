<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 전역 미들웨어 추가 (모든 요청 전에 실행)
        $middleware->append(\App\Http\Middleware\CheckLanguageCookie::class);
        
        $middleware->alias([
        	'admin.level2' => \App\Http\Middleware\CheckAdminLevel2::class,
        	'check.language' => \App\Http\Middleware\CheckLanguageCookie::class,
            'can.admin' => \App\Http\Middleware\CheckAdminPermission::class,
            'admin.log' => \App\Http\Middleware\AdminActionLog::class,
            'ai.api' => \App\Http\Middleware\AiApiAuth::class,
            'rips.partner' => \App\Http\Middleware\CheckRipsPartner::class,
            'user.locale' => \App\Http\Middleware\SetUserLocale::class,
        ]);

        // RIPS 판매 데이터 API 수신 (API 인증키로 인증)
        $middleware->validateCsrfTokens(except: ['rips-api/*', 'ai-api/*']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // CSRF 토큰 만료(419) 시 "Page Expired" 화면 대신 메시지 없이 각 영역의 홈으로 이동
        // (TokenMismatchException 은 렌더 콜백 전에 HttpException(419)로 변환되므로 419 로 잡아야 함)
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() !== 419 || $request->expectsJson()) {
                return null; // AJAX 는 각 화면의 419 처리(로그인 이동)에 맡김
            }

            return redirect(match (true) {
                $request->is('admin', 'admin/*') => '/admin',
                $request->is('rips', 'rips/*')   => '/rips',
                $request->is('check', 'check/*') => '/check',
                default                          => '/',
            });
        });
    })->create();