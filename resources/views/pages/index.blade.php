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
                <h3 class="text-center my-3">Produk</h3>
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
                                        <i class="fa-solid fa-share text-gray-400"></i> <span class="fs-8 text-gray-400">Bagikan</span>
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

    <!-- Testimoni -->
    <h3 class="text-center my-3">Testimoni</h3>
    <div class="row mt-2">
    <x-carousel id="testimonialCarousel" :images="$testimonials->pluck('testimoni_url')"/>
    </div>
    <!-- Testimoni -->

    <!-- Marketplace -->
    <h3 class="text-center my-3">Marketplace</h3>
    <div class="d-flex flex-wrap justify-content-center gap-2 my-2">
        @foreach ($marketplaces as $marketplace)
            <div class="mb-2">
                <x-marketplace-button type="{{$marketplace->type}}" url="{{$marketplace->marketplace_url}}" name="{{$marketplace->name}}" />
            </div>
        @endforeach
    </div>
    <!-- Marketplace -->

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
        /* Fake Sales Notification */

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
                flex-wrap: wrap;
            }
            .custom-radio-group label {
                border: none;
            }
        }
    </style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    let currentLeadIndex = leads.length - 1;

    const getNextLead = () => {
        const lead = leads[currentLeadIndex];
        currentLeadIndex = (currentLeadIndex - 1 + leads.length) % leads.length;
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

    const timeOrder = new Date(nextLead.time_order);
    const now = new Date();
    const timeDifference = Math.floor((now - timeOrder) / 1000); // Selisih dalam detik

    let timeAgo = '';

    if (timeDifference < 60) {
        timeAgo = `${timeDifference} detik yang lalu`;
    } else if (timeDifference < 3600) {
        const minutesAgo = Math.floor(timeDifference / 60);
        timeAgo = `${minutesAgo} menit yang lalu`;
    } else if (timeDifference < 86400) {
        const hoursAgo = Math.floor(timeDifference / 3600);
        timeAgo = `${hoursAgo} jam yang lalu`;
    } else if (timeDifference < 2592000) {
        const daysAgo = Math.floor(timeDifference / 86400);
        timeAgo = `${daysAgo} hari yang lalu`;
    } else if (timeDifference < 31536000) {
        const monthsAgo = Math.floor(timeDifference / 2592000);
        timeAgo = `${monthsAgo} bulan yang lalu`;
    } else {
        const yearsAgo = Math.floor(timeDifference / 31536000);
        timeAgo = `${yearsAgo} tahun yang lalu`;
    }
    productText.innerHTML = `<p class="message"><b>${nextLead.name}</b> dari <b>${nextLead.regency}</b> baru saja membeli <b>${product.name}</b></p><p class="time">${timeAgo}</p>`;

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
</script>
@endpush
