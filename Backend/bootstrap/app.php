<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // إذا أردت إضافة أي middleware عالمي، قم بإضافته هنا
        // مثال:
         $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
        // $middleware->add(\App\Http\Middleware\TrustProxies::class);
        // $middleware->add(\App\Http\Middleware\VerifyCsrfToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // إعدادات الاستثناءات الخاصة بك يمكن إضافتها هنا
        // مثال: تسجيل الاستثناءات أو تخصيص صفحة الخطأ
    })
    ->create();
