<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use jeemce\helpers\AuthHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AuthHelper::$access = null;
    }
}
