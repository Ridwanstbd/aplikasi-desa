@extends('layouts.guest')

@push('scripts')
<script>
    // konsultasi dokter start
    function openModal() {
        $('#consultationModal').modal('show');
    }
    function submitForm(event) {
        event.preventDefault();
        
        // Simulasi pengiriman data ke server
        setTimeout(() => {
            document.getElementById('consultationForm').reset();
            closeModal(); // Menutup modal setelah submit
            
            // Menampilkan SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Pesan Terkirim!',
                text: 'Terima kasih, tim kami akan menghubungi anda dalam rentang waktu 1x24 jam kedepan.',
                confirmButtonText: 'OK'
            });
        }, 500);
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
    <!-- <div class="whatsapp-icon" onclick="openModal()">
    </div> -->

    <!-- Modal -->
    <x-modal id="consultationModal" title="Kirim Pesan Konsultasi">
        <form id="consultationForm" onsubmit="submitForm(event)">
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" class="form-control" id="name" required>
            </div>
            <div class="form-group">
                <label for="message">Pesan:</label>
                <textarea class="form-control" id="message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </x-modal>
</div>
@endsection
