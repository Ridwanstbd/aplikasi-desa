<?php

namespace App\Providers;

use App\Models\MarketplaceLinks;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MarketplaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('marketplace_links')) {
            // Lakukan query
            $mp = MarketplaceLinks::first();
            View::share('mp', $mp);
        }
    }
}
