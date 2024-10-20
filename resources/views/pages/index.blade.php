// resources/views/index.blade.php
@extends('layouts.guest')

@section('content')
<div class="container">
    <x-shop.description :shop="$shop" />

    <div class="row justify-content-md-center">
        <div class="col-md-9">
            <x-carousel id="bannerCarousel" :images="$shop->banners->pluck('banner_url')" />
        </div>
    </div>

    <x-shop.filter-form :categories="$categories" :products="$products" />

    <x-shop.share-modal />

    <div class="pt-5">
        <img src="{{asset('assets/img/banner2.png')}}" class="img-fluid" alt="" srcset="">
    </div>

    <x-shop.testimonials />
    <x-shop.marketplace :marketplaces="$marketplaces" />
    <x-shop.shipping />
    <x-shop.fake-sales />
</div>
@endsection

@push('styles')
    <x-shop.styles />
@endpush

@push('scripts')
    <x-shop.scripts :leads="$leads" :productsJson="$productsJson" :testimonials="$testimonials" />
@endpush
