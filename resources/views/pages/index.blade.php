@extends('layouts.guest')

@section('content')
<div class="container">
    <!-- Deskripsi Toko -->
    @if (!empty($shop->first()->description))
        <div class="p-3 p-md-8 m-md-3 text-center">
            <h2 class="fs-2">Selamat Datang di {{$shop->first()->name}}</h2>
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
                            <img src="{{ Storage::url($product->main_image) }}" style="width:11rem; height: 11rem;" alt="...">
                            <div class="p-1">
                                <h5 class="fs-5">Rp{{ number_format($product->hargaTermurah, 0, ',', '.') }}</h5>
                                <p class="fs-6">{{ Str::limit($product->name, 22) }}</p>
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-4">
                                        <button type="button" onclick="shareProduct('{{ route('products.show', $product->slug) }}')" class="btn btn-outline-secondary">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
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

    <!-- Testimoni -->
    <h3 class="text-center my-3">Testimoni</h3>
    <div class="row mt-2">
        @foreach ($testimonials as $testimonial)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="testimonial-box p-2 h-100">
                    <div class="testimonial-image">
                        <img src="{{ Storage::url($testimonial->testimoni_url) }}" alt="Testimonial Image" class="img-fluid">
                    </div>
                </div>
            </div>
        @endforeach
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
    let currentLeadIndex = 0;

    const getNextLead = () => {
        const lead = leads[currentLeadIndex];
        currentLeadIndex = (currentLeadIndex + 1) % leads.length;
        return lead;
    };

    const showAlert = () => {
    const randomLead = getNextLead();
    const product = products.find(p => p.id === randomLead.product_id);

    if (!product) {
        console.error('Product not found');
        return;
    }

    productImage.src = `{{ url('storage/products') }}/${product.main_image.replace('public/products/', '')}`;

    if (!productImage.src) {
        console.error('Image source is invalid or not found');
    }

    const timeOrder = new Date(randomLead.time_order);
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
    productText.innerHTML = `<p class="message"><b>${randomLead.name}</b> dari <b>${randomLead.regency}</b> baru saja membeli <b>${product.name}</b></p><p class="time">${timeAgo}</p>`;

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

    // Share Product
    function shareProduct(url) {
        if (navigator.share) {
            navigator.share({
                title: 'Product Sharing',
                url: url
            }).then(() => {
                console.log('Thanks for sharing!');
            })
            .catch(console.error);
        } else {
            console.log('Share not supported on this browser, do it the old way.');
        }
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

