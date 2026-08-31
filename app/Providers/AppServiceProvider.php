<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // The application layouts load Bootstrap, so all paginators should use
        // Bootstrap markup instead of Laravel's Tailwind/SVG-based default.
        Paginator::useBootstrapFive();
    }
}
