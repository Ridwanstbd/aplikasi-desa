<?php

namespace App\Providers;

use App\View\Components\Form\Checkbox;
use App\View\Components\Form\Input;
use App\View\Components\Form\InputImage;
use App\View\Components\Form\Select;
use App\View\Components\Anchor;
use App\View\Components\Breadcrumbs;
use App\View\Components\Button;
use App\View\Components\Card;
use App\View\Components\Carousel;
use App\View\Components\CarouselPreview;
use App\View\Components\Form;
use App\View\Components\ImagePreview;
use App\View\Components\InputImagePng;
use App\View\Components\MarketplaceButton;
use App\View\Components\Modal;
use App\View\Components\TableProduct;
use App\View\Components\Variation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
        Blade::component('breadcrumbs', Breadcrumbs::class);
        Blade::component('anchor', Anchor::class);
        Blade::component('form', Form::class);
        Blade::component('checkbox', Checkbox::class);
        Blade::component('input', Input::class);
        Blade::component('input-image', InputImage::class);
        Blade::component('input-image-png', InputImagePng::class);
        Blade::component('button', Button::class);
        Blade::component('marketplace-button', MarketplaceButton::class);
        Blade::component('card', Card::class);
        Blade::component('carousel', Carousel::class);
        Blade::component('carousel-preview', CarouselPreview::class);
        Blade::component('select', Select::class);
        Blade::component('modal', Modal::class);
        Blade::component('table-product', TableProduct::class);
        Blade::component('variation', Variation::class);
        Blade::component('image-preview', ImagePreview::class);
        View::composer('*', function ($view) {
            $cart = session()->get('cart', []);
            $totalQuantity = array_sum(array_column($cart, 'quantity'));
            $view->with('totalQuantity', $totalQuantity);
        });
    }
}
