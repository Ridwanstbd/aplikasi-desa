<?php

namespace App\Providers;

use App\Models\CustomerService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
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
        if (\Illuminate\Support\Facades\Schema::hasTable('customer_services')) {
            // Lakukan query
            $cs = CustomerService::latest()->get();
            View::share('cs', $cs);
        }
    }
}
