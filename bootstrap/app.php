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
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();