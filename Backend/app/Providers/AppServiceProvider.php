<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // يمكنك تسجيل أي خدمات أو binding هنا إذا احتجت
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // يمكنك إضافة أي إعدادات عند إقلاع التطبيق
    }
}
