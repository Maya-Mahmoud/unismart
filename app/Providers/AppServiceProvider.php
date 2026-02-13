<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // يضبط المنطقة الزمنية حسب إعداد Laravel
        date_default_timezone_set(config('app.timezone'));

        // يضمن أن Carbon يستخدم نفس المنطقة الزمنية
        Carbon::setLocale(config('app.locale'));
        Carbon::now(config('app.timezone'));
    }
}
