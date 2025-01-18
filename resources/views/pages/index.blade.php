<<<<<<< HEAD
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$systems->name}}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                        </div>
                        @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </header>

                    <main class="mt-6">
                        
                    </main>

                    <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
=======
@extends('layouts.guest')

@push('scripts')
<script>
    // konsultasi dokter start
    function openModal() {
        $('#consultationModal').modal('show');
    }
    
    // Konsultsi dokter end
    // Filter Form
    document.querySelectorAll('input[name="kategori"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    // Filter Form
    const testimonials = @json($testimonials);
    const testimonialContainer = document.getElementById('testimonialContainer');

    function createSlides(testimonials, chunkSize) {
        testimonialContainer.innerHTML = ''; // Kosongkan kontainer sebelum memasukkan elemen baru

        // Base URL untuk mengambil gambar dari storage
        const baseUrl = "{{ url('storage/shops') }}";

        // Chunking the testimonials array
        for (let i = 0; i < testimonials.length; i += chunkSize) {
            const testimonialChunk = testimonials.slice(i, i + chunkSize);
            const isActive = i === 0 ? 'active' : '';

            // Membuat carousel item dan grid kolom untuk setiap chunk
            let slide = `
                <div class="carousel-item ${isActive}">
                    <div class="row">`;

            testimonialChunk.forEach(testimonial => {
                // Menghapus 'public/shops/' dari testimonial.testimoni_url dan membuat URL yang benar
                const imageUrl = `${baseUrl}/${testimonial.testimoni_url.replace('public/shops/', '')}`;

                const imgClass = chunkSize === 3 ? 'small-laptop-img' : '';
                slide += `
                    <div class="col">
                        <img src="${imageUrl}" class="d-block img-fluid ${imgClass}" alt="Testimonial">
                    </div>
                `;
            });

            slide += `</div></div>`;
            testimonialContainer.insertAdjacentHTML('beforeend', slide);
        }
    }

    function updateCarousel() {
        const screenWidth = window.innerWidth;

        let chunkSize;
        if (screenWidth < 768) {
            chunkSize = 1; // Smartphone: 1 testimonial per slide
        } else if (screenWidth >= 768 && screenWidth < 992) {
            chunkSize = 2; // Tablet: 2 testimonials per slide
        } else {
            chunkSize = 3; // Laptop: 3 testimonials per slide
        }

        createSlides(testimonials, chunkSize);
    }
    $(document).ready(function(){
    setInterval(function(){
        $('#carouselEkspedisi').carousel('next');
        $('#carouselBank').carousel('next');
    }, 2000);
});

    window.addEventListener('resize', updateCarousel);
    window.addEventListener('DOMContentLoaded', updateCarousel);
</script>
@endpush

@push('styles')
    <x-shop.styles />
@endpush

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
    <x-shop.adventages />
    <x-shop.testimonials />
    <x-shop.marketplace :marketplaces="$marketplaces" />
    <x-shop.shipping />
    <x-shop.banks />
    <!-- WhatsApp Icon -->
    <div class="doctor-icon" onclick="openModal()">
        <img id="doctor-icon" src="{{asset('assets/icons/doctor-consul.png')}}" alt="Consultation">
    </div>

    <!-- Modal -->
    <x-modal id="consultationModal" title="Kirim Pesan Konsultasi">
        <x-form 
            id="consultationForm"
            action="{{ route('vet_consult.store') }}"
            method="POST"
            class="consultation-form">
            
            <x-input type="text" name="full_name" label="Nama Lengkap" required="true" />
            <x-input type="text" name="address" label="Alamat" required="true" />
            <x-input type="text" name="phone_number" label="Nomor WhatsApp" required="true" />
            <input type="hidden" name="consultation_date" value="{{ now()->format('Y-m-d') }}" />
            <x-input type="textarea" name="notes" label="Detail Sakit Hewan" />
            <x-button type="submit" class="btn-primary" label="Kirim Konsultasi"/>
        </x-form>
    </x-modal>
</div>
@endsection
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
