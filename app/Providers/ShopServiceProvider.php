<?php

namespace App\Providers;

use App\Models\Shop;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ShopServiceProvider extends ServiceProvider
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
        if (Schema::hasTable('shops')) {
            // Lakukan query
            $shop = Shop::first();
            View::share('shop', $shop);
        }
    }
}
