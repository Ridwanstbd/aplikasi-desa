@extends('layouts.app')
@section('content')
<section class="section">
    <div class="pagetitle">
        <h1>Pengaturan</h1>
        @include('partials.breadcrumbs', [ 'links' => [
            ['url' => route('settings.index'), 'label' => 'Pengaturan Toko'],
            ]])
        </div><!-- End Page Title -->
        <x-card title="Pengaturan Toko">
        @if ($errors->any())
            <div class="container">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="container">
                {{ session('error') }}
            </div>
        @endif
        <form class="row g-3" action="{{route('settings.update',1)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <x-input type="text" label="Nama Toko" name="name" value="{{ $shop->name }}" placeholder="Nama Toko" required=true />
                </div>
                <div class="col-md-6">
                    <x-input type="textarea" placeholder="Isi Deskripsi" value="{{ $shop->description ?: null }}" name="description" label="Deskripsi Toko" required=true></x-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <input type="hidden" name="logo_url" value="{{$shop->logo_url}}" />
                    <x-input-image-png name="logo_url" placeholder="Pilih Foto" label="Logo Toko *png" />
                    <div class="d-flex mb-2">
                        @if ($shop->logo_url)
                        <x-image-preview :image="$shop->logo_url"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                @foreach ($shop->banners as $index => $banner)
                <input type="hidden" name="banners[]" value="{{$banner->banner_url}}"  >
                @endforeach
                    <x-input-image name="banners" label="Banner" multiple=true />
                    <x-carousel id="bannerCarousel" :images="$shop->banners->pluck('banner_url')" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                <input type="hidden" name="logo_footer_url" value="{{$shop->logo_footer_url}}" />
                <x-input-image-png name="logo_footer_url" placeholder="Pilih Logo untuk footer (footer backgroundnya hitam)" label="Logo Footer *png (footer backgroundnya hitam)" />
                    <div class="d-flex mb-2">
                       @if ($shop->logo_footer_url)
                        <x-image-preview :image="$shop->logo_footer_url"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <x-input type="text" placeholder="contoh: https://maps.app.goo.gl/XXXXX" name="location_url" value="{{$shop->location_url}}" label="Lokasi Toko" />
                </div>
                <div class="col-md-6">
                @foreach ($shop->testimonials as $index => $testimonial)
                <input type="hidden" name="testimonials[]" value="{{$testimonial->testimoni_url}}"  >
                @endforeach
                    <x-input-image name="testimonials" label="Testimonials" multiple=true />
                    <div class="d-flex flex-wrap mb-2">
                    <x-carousel-preview id="testimonialCarousel" :images="$shop->testimonials->pluck('testimoni_url')" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <x-input type="text" placeholder="contoh: https://" value="{{ $shop->added_url ?: null }}" name="added_url" label="Web Qurban"></x-input>
                </div>
                <div class="col-md-6">
                    <x-input type="text" placeholder="ID Pixel Meta (jika ada)" value="{{ $shop->meta_pixel_id ?: null }}" name="meta_pixel_id" label="ID Pixel Meta"></x-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <x-input type="text" placeholder="ID Pixel Tiktok (jika ada)" value="{{ $shop->tiktok_pixel_id ?: null}}" name="tiktok_pixel_id" label="ID Pixel Tiktok"></x-input>
                </div>
                <div class="col-md-6">
                    <x-input type="text" placeholder="contoh: GTM-XXXXXX (jika ada)" value="{{ $shop->google_tag_id ?: null}}" name="google_tag_id" label="ID Tag Manager Google"></x-input>
                </div>
            </div>
            <div class="d-grid mx-auto">
              <x-button type="submit" label="Simpan"/>
            </div>
        </form>
        </x-card>
</section>

@endsection
