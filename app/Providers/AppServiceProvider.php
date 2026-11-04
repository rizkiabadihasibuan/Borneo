<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Paksa semua URL menggunakan HTTPS di environment production.
        // Ini mencegah Mixed Content error saat hosting berada di belakang
        // HTTPS proxy (seperti EthioDeploy) tetapi APP_URL masih http://.
        if (
            $this->app->environment('production')
            || request()->server('HTTPS') === 'on'
            || request()->server('HTTP_X_FORWARDED_PROTO') === 'https'
            || request()->server('HTTP_X_FORWARDED_SSL') === 'on'
        ) {
            URL::forceScheme('https');
        }
    }
}
