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
        $forceHttps = (bool) config('app.force_https', false);
        $appUrl = config('app.url');
        if ($forceHttps || (is_string($appUrl) && str_starts_with($appUrl, 'https://'))) {
            URL::forceScheme('https');
        }
    }
}
