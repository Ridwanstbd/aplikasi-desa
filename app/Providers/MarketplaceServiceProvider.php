<?php

namespace App\Providers;

use App\Models\MarketplaceLinks;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


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
        $mp = MarketplaceLinks::first();
        View::share('mp',$mp);
    }
}
