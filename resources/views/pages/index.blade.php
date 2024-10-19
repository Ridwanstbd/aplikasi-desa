@extends('layouts.guest')

@section('content')
<div class="container">
    <!-- Deskripsi Toko -->
    @if (!empty($shop->first()->description))
        <div class="p-3 p-md-8 m-md-3 text-center">
            <h2 class="fs-2">Selamat Datang di <span class="d-block d-md-inline">{{$shop->first()->name}}</span></h2>
            <p class="fs-4">{{$shop->first()->description}}</p>
        </div>
    @endif
    <!-- Deskripsi Toko -->

    <div class="row justify-content-md-center">
        <div class="col-md-9">
            <!-- Banner -->
            <x-carousel id="bannerCarousel" :images="$shop->banners->pluck('banner_url')" />
            <!-- Banner -->
        </div>
    </div>

    <!-- Filter Form -->
    <form id="filterForm" method="GET" action="{{ route('home') }}">
        <!-- Product with Category -->
        <div class="row">
            <div class="col-md-2">
                <div class="container mt-3">
                    <span class="fs-5">Kategori</span>
                    <div class="custom-radio-group">
                        <input type="radio" id="semua_produk" name="kategori" value="" {{ request('kategori') == '' ? 'checked' : '' }}>
                        <label for="semua_produk">SEMUA</label>
                        @foreach ($categories as $category)
                            <input type="radio" id="{{ $category->id }}" name="kategori" value="{{ $category->id }}"
                                   {{ request('kategori') == $category->id ? 'checked' : '' }}>
                            <label for="{{ $category->id }}">{{ strtoupper($category->name) }}</label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <h3 class="text-center my-3">PRODUK</h3>
                <div class="d-flex gap-2 mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                    <select name="sort" class="form-control" onchange="document.getElementById('filterForm').submit();">
                        <option value="" disabled selected>Sortir berdasarkan</option>
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Termurah</option>
                        <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Termahal</option>
                    </select>
                </div>
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                            <a href="{{ route('products.show', $product->slug) }}" class="">
                                <img src="{{ Storage::url($product->main_image) }}" style="width:11rem; height: 11rem;" alt="...">
                            </a>
                            <div class="p-1">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-black text-decoration-none">
                                <h5 class="fs-5">Rp{{ number_format($product->hargaTermurah, 0, ',', '.') }}</h5>
                                <p class="fs-6" style="color: green;" >{{ $product->name }}</p>
                                </a>
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-4">
                                    <div onclick="shareProduct('{{ route('products.show', $product->slug) }}', '{{ $product->name }}')" class="d-flex align-items-center" style="cursor: pointer;">
                                        <i class="fa-solid fa-share text-gray-400"></i>
                                    </div>
                                    </div>
                                    <div class="col-8">
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-warning" style="white-space: nowrap;">Lihat detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $products->links('vendor.pagination.custom') }}
            </div>
        </div>
        <!-- Product with Category -->
    </form>
    <!-- Modal Bagikan Produk -->
    <div class="modal fade" id="shareProductModal" tabindex="-1" aria-labelledby="shareProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareProductModalLabel">Bagikan Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="share-buttons-container">
                    <!-- Share buttons akan dimuat melalui JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-5">
        <img src="{{asset('assets/img/banner2.png')}}" class="img-fluid" alt="" srcset="">
    </div>

    <!-- Testimoni -->
    <h3 class="text-center pt-5 mb-3">TESTIMONI</h3>
    <div class="row mt-2">
    <div id="carouselTestimonial" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner" id="testimonialContainer">
        <!-- di tampilkan javascript per item dinamis -->
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselTestimonial" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselTestimonial" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

    </div>
    <!-- Testimoni -->

    <!-- Marketplace -->
    <h3 class="text-center pt-5 pb-3">MARKETPLACE</h3>
    <div class="d-flex flex-wrap justify-content-center gap-2 my-2">
        @foreach ($marketplaces as $marketplace)
            <div class="mb-2">
                <x-marketplace-button type="{{$marketplace->type}}" url="{{$marketplace->marketplace_url}}" name="{{$marketplace->name}}" />
            </div>
        @endforeach
    </div>
    <!-- Marketplace -->
    <h3 class="text-center pt-5 pb-3">Jasa Pengiriman</h3>
    <div id="carouselEkspedisi" class="carousel slide mb-2" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="row">
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/jne.png') }}" alt="Image 1">
                    </div>
                </div>
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/jnt.png') }}" alt="Image 2">
                    </div>
                </div>
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/sicepat.png') }}" alt="Image 3">
                    </div>
                </div>
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/ninja.png') }}" alt="Image 4">
                    </div>
                </div>
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/grab.png') }}" alt="Image 5">
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="row">
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/gosend.png') }}" alt="Image 6">
                    </div>
                </div>
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/gokil.png') }}" alt="Image 7">
                    </div>
                </div>
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/jtr.png') }}" alt="Image 8">
                    </div>
                </div>
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/indah.png') }}" alt="Image 9">
                    </div>
                </div>
                <div class="col-2">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/ninja.png') }}" alt="Image 10">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Fake sales Notification -->
    <div id="product-alert">
        <div id="product-info" class="product-info">
            <img id="product-image" src="" alt="Product">
            <div id="product-text"></div>
        </div>
        <button id="close-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <!-- Fake sales Notification -->
</div>

@endsection

@push('styles')
    <style>
        .custom-radio-group {
            display: flex;
            gap: 0.625rem;
            overflow-x: auto;
            padding-bottom: 0.625rem;
        }
        .custom-radio-group label {
            cursor: pointer;
        }
        .custom-radio-group input[type="radio"] {
            display: none;
        }
        .custom-radio-group input[type="radio"]:checked + label {
            color: orange;
        }
        /* Fake Sales Notification */
        #product-alert {
            width: 350px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: none;
            align-items: flex-start;
            gap: 0.5rem;
            cursor: pointer;
            z-index: 999;
            background-color: white;
        }

        .product-info {
            display: flex;
        }

        .product-info img {
            width: 100px;
            height: 100px;
            margin-right: 10px;
        }

        .message {
            font-size: 16px;
        }

        .time {
            font-size: 14px;
            margin-top: 16px;
            color: #a0a0a0;
        }
        #close-btn {
            background-color: transparent;
            color: red;
            border: none;
            outline: none;
        }
        .small-laptop-img {
            width: 80%;
            max-width: 400px;
            max-height: fit-content;
            margin: 0 auto;
        }
        /* Fake Sales Notification */

        .image-container {
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            position: absolute;
            top: 0;
            left: 0;
        }

        @media (max-width: 767.98px) {
            .custom-radio-group {
                flex-wrap: nowrap;
            }
            .custom-radio-group label {
                border: 0.125rem solid orange;
            }
            .custom-radio-group input[type="radio"] {
                display: none;
            }
            .custom-radio-group label {
                padding: 0.625rem 1.25rem;
                border-radius: 1.875rem;
                color: orange;
                cursor: pointer;
                white-space: nowrap;
            }
            .custom-radio-group input[type="radio"]:checked + label {
                background-color: orange;
                color: white;
            }
        }

        @media (min-width: 768px) {
            .custom-radio-group {
                flex-direction: column;
            }
            .custom-radio-group label {
                border: none;
            }
        }
    </style>
@endpush

@push('scripts')
<script>
    // Fake Sales Notification
    const productAlert = document.getElementById("product-alert");
    const productInfo = document.getElementById("product-info");
    const closeAlertBtn = document.getElementById("close-btn");
    const productText = document.getElementById("product-text");
    const productImage = document.getElementById("product-image");
    const leads = @json($leads);
    const products = @json($productsJson);
    let alertTimeout;
    let currentLeadIndex = leads.length - 1; // Mulai dari index terbesar
    let leadCount = 0; // Menghitung jumlah lead yang telah ditampilkan

    const getNextLead = () => {
        if (leadCount >= 3) {
            clearTimeout(alertTimeout); // Hentikan setelah 3 lead ditampilkan
            return;
        }

        const lead = leads[currentLeadIndex];
        currentLeadIndex--; // Kurangi index untuk mengambil lead sebelumnya
        leadCount++; // Tambahkan hitungan lead yang telah ditampilkan
        return lead;
    };


    const showAlert = () => {
    const nextLead = getNextLead();
    const product = products.find(p => p.id === nextLead.product_id);

    if (!product) {
        console.error('Product not found');
        return;
    }

    productImage.src = `{{ url('storage/products') }}/${product.main_image.replace('public/products/', '')}`;

    if (!productImage.src) {
        console.error('Image source is invalid or not found');
    }

    // Batasi panjang product.name hingga 24 karakter
    let productName = product.name.length > 24 ? product.name.slice(0, 24) + '...' : product.name;

    // Menampilkan pesan tanpa waktu order
    productText.innerHTML = `<p class="message"><b>${nextLead.name}</b> dari <b>${nextLead.regency}</b> baru saja membeli <b>${productName}</b></p>`;

    if (productInfo) {
        productInfo.onclick = () => window.location.href = `{{ url('product/detail') }}/${product.slug}`;
    } else {
        console.error('Product info element not found');
    }

    productAlert.style.display = "flex";
    alertTimeout = setTimeout(() => {
        productAlert.style.display = "none";
        showAlert();
    }, 5000);
};
    closeAlertBtn.addEventListener("click", () => {
        clearTimeout(alertTimeout);
        productAlert.style.display = "none";
        setTimeout(showAlert, getRandomDisplayTime() * 1000);
    });

    document.addEventListener("DOMContentLoaded", () => {
        showAlert();
    });
    // Fake Sales Notification

    function shareProduct(url, productName) {
        const encodedUrl = encodeURIComponent(url);
        const encodedProductName = encodeURIComponent(productName);

        const fbShare = `<a href="https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}" target="_blank" class="btn btn-primary mb-2"><i class="fab fa-facebook-f"></i> Facebook</a>`;
        const waShare = `<a href="https://wa.me/?text=${encodedProductName}%20${encodedUrl}" target="_blank" class="btn btn-success mb-2"><i class="fab fa-whatsapp"></i> WhatsApp</a>`;
        const igShare = `<a href="https://www.instagram.com/?url=${encodedUrl}" target="_blank" class="btn btn-danger mb-2"><i class="fab fa-instagram"></i> Instagram</a>`;
        const telegramShare = `<a href="https://telegram.me/share/url?url=${encodedUrl}&text=${encodedProductName}" target="_blank" class="btn btn-info mb-2"><i class="fab fa-telegram-plane"></i> Telegram</a>`;
        const twitterShare = `<a href="https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedProductName}" target="_blank" class="btn btn-primary mb-2" style="background-color: #1DA1F2;"><i class="fab fa-twitter"></i> Twitter</a>`;

        document.getElementById('share-buttons-container').innerHTML = `${fbShare} ${waShare} ${igShare} ${telegramShare} ${twitterShare}`;

        const shareProductModal = new bootstrap.Modal(document.getElementById('shareProductModal'));
        shareProductModal.show();
    }
    // Share Product

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
    }, 2000);
});

    window.addEventListener('resize', updateCarousel);
    window.addEventListener('DOMContentLoaded', updateCarousel);
</script>
@endpush
