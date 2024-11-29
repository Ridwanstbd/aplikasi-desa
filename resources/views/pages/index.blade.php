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
        <img src="{{asset('assets/icons/doctor-icon.png')}}" alt="Consultation" width="80" height="80">
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
