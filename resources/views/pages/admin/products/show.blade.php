@extends('layouts.guest')
@section('content')
<section class="container">
    <x-breadcrumbs :links="[
        ['url' => route('home'), 'label' => 'Semua Produk'],
        ['url' => route('products.show', $product->slug), 'label' => 'Detail'],
    ]"/>
    <div class="row mb-3">
        <div class="col-lg-6 d-flex flex-column flex-md-row">
            <!-- Gambar Kecil -->
            <div class="position-relative thumbnail-container">
                <div class="d-flex flex-row flex-md-column align-items-center" id="thumbnails-container">
                    <div class="thumbnail-wrapper overflow-auto" style="max-height: 60vh;">
                        @foreach ($product->images as $image)
                        <div class="mb-3">
                            <img src="{{ Storage::url($image->images) }}" style="width: 5rem; height: 5rem;" class="thumbnail mb-3" data-image-url="{{ Storage::url($image->images) }}">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Gambar Utama -->
            <div class="d-flex w-100">
                <img id="main-image" src="{{ Storage::url($product->main_image) }}" style="height: 60vh; width: max-content;" alt="{{$product->name}}"/>
            </div>
        </div>
        <!-- Detail Produk -->
        <div class="col-lg-6">
            <h2>{{$product->name}}</h2>
            <p id="price-range" class="fs-4 text-danger">
                Rp {{ number_format($product->variations->min('price'), 0, ',', '.') }} - Rp {{ number_format($product->variations->max('price'), 0, ',', '.') }}
            </p>
            <div class="variation-choose" id="variation-choose-container">
                <p>Variasi:</p>
                @php
                    $availableVariations = $product->variations->filter(function($variation) {
                        return $variation->is_ready == 1;
                    });
                @endphp
                @if ($availableVariations->isEmpty())
                    <p>Produk Tidak Tersedia</p>
                @else
                    @foreach ($availableVariations as $variation)
                    <div>
                        <input type="radio" id="{{ $variation->id }}" name="variation" value="{{ $variation->id }}" data-price="{{ $variation->price }}" data-image-url="{{ Storage::url($variation->image) }}">
                        <label for="{{ $variation->id }}">
                        @if($variation->image && Storage::exists($variation->image))
                            <img src="{{ Storage::url($variation->image) }}" style="width: 2rem; height: 2rem; margin-right: 0.5rem;">
                        @endif
                            {{ strtoupper($variation->name_variation) }}
                        </label>
                    </div>
                    @endforeach
                @endif
            </div>
            <div class="mt-3">
                @if ($product->url_form)
                    <a class="text-decoration-none" href="{{$product->url_form}}" target="_blank">
                        <button class="btn btn-primary">
                            <span style="white-space: nowrap;">Beli Sekarang</span>
                        </button>
                    </a>
                @else
                <form id="order-form" action="{{route('order.submit')}}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variation_id" id="selected-variation-id">
                    <label for="quantity" class="form-label">Jumlah</label>
                    <div class="col-4 col-md-3 mb-3">
                        <div class="d-flex gap-1 align-items-center">
                            <div class="fs-4 btn-decrement" data-variation-id=""><i class="bi bi-dash-circle"></i></div>
                            <input type="number" class="form-control" style="width: 3rem;" id="quantity" name="quantity" min="1" value="1" required>
                            <div class="fs-4 btn-increment" data-variation-id=""><i class="bi bi-plus-circle"></i></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-secondary" id="order-button">
                        <span style="white-space: nowrap;">Pesan Sekarang</span>
                    </button>
                    <button type="button" class="btn btn-warning" id="add-to-cart-button">
                        <span style="white-space: nowrap;">Tambah ke Keranjang</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    <p>{!! nl2br(e($product->description)) !!}</p>
    <div class="row mt-3">
        <h4>Produk Lainnya</h4>
        @foreach ($relatedProducts as $relatedProduct)
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <div class="card h-100">
                <img src="{{ Storage::url($relatedProduct->main_image) }}" class="card-img-top" alt="{{$relatedProduct->name}}">
                <div class="card-body d-flex flex-column">
                    <h5 class="fs-5">Rp{{ number_format($relatedProduct->hargaTermurah, 0, ',', '.') }}</h5>
                    <p class="fs-6">{{ Str::limit($relatedProduct->name, 32) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                                    <div class="">
                                        <button type="button" onclick="shareProduct('{{ route('products.show', $relatedProduct->slug) }}')" class="btn btn-outline-secondary">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </div>
                                    <div class="">
                                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-warning mt-auto">Lihat</a>
                                    </div>
                                </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
@push('styles')
    <style>
        .variation-choose {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.625rem;
        }
        .variation-choose input[type="radio"] {
            display: none;
        }
        .variation-choose label {
            padding: 0.2rem 0.8rem;
            border-radius: 0.5rem;
            color: black;
            cursor: pointer;
            white-space: nowrap;
            border: 0.125rem solid gray;
        }
        .variation-choose input[type="radio"]:checked + label {
            background-color: orange;
            color: white;
        }
        /* Mengatur style untuk gambar thumbnail */
        .thumbnail-wrapper {
            overflow-y: auto;
        }

        /* Menampilkan scrollbar untuk Chrome, Safari, dan Edge */
        .thumbnail-wrapper::-webkit-scrollbar {
            display: block;
        }

        /* Menampilkan scrollbar untuk Firefox */
        .thumbnail-wrapper {
            scrollbar-width: auto;
        }

        /* Menambahkan jarak antar gambar kecil */
        .thumbnail {
            display: inline-block;
            margin-right: 0.5rem;
        }

        /* Media query untuk tampilan mobile */
        @media (max-width: 768px) {
            .thumbnail-container {
                order: 1;
                width: 100%;
                overflow-x: auto;
            }

            .thumbnail-wrapper {
                display: flex;
                flex-direction: row;
                max-height: none;
                overflow-x: auto;
                width: 100%;
            }

            .thumbnail {
                margin-right: 1rem;
                margin-bottom: 0;
            }

            #thumbnails-container {
                flex-direction: row;
            }
        }
    </style>
@endpush
@push('scripts')
<script>
    function showAlert(icon, title, text){
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonText: 'Ok'
            });
        }
        @if (session('success'))
            showAlert('success','Sukses!','{{ session('success') }}')
        @endif
        @if (session('error'))
            @php $errorMessages = session('error'); @endphp
            @if (is_array($errorMessages))
                @foreach ($errorMessages as $errorMessage)
                    showAlert('error','Oops...','{{ $errorMessage }}')
                @endforeach
            @else
                    showAlert('error','Oops...','{{ $errorMessages }}')
            @endif
        @endif
document.addEventListener("DOMContentLoaded", function() {
    const thumbnailsContainer = document.querySelector('#thumbnails-container');
    const thumbnails = thumbnailsContainer.querySelectorAll('.thumbnail-wrapper .mb-3 .thumbnail');
    const mainImage = document.getElementById('main-image');
    const priceRange = document.getElementById('price-range');
    const variationRadios = document.querySelectorAll('input[name="variation"]');
    const variationContainer = document.getElementById('variation-choose-container');
    const orderForm = document.getElementById('order-form');
    const orderButton = document.getElementById('order-button');
    const selectedVariationId = document.getElementById('selected-variation-id');
    const decrementButtons = document.querySelectorAll('.btn-decrement');
    const incrementButtons = document.querySelectorAll('.btn-increment');
    const quantityInput = document.getElementById('quantity');

    // Disable form submit jika url_form ada isinya
    @if ($product->url_form)
    variationContainer.style.display = 'none';
    orderButton.disabled = true;
    @endif

    const updatePriceRange = () => {
        const selectedRadio = document.querySelector('input[name="variation"]:checked');
        if (selectedRadio) {
            const pricePerUnit = parseFloat(selectedRadio.getAttribute('data-price'));
            const quantity = parseInt(quantityInput.value);
            const totalPrice = pricePerUnit * quantity;
            priceRange.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
        }
    };


    decrementButtons.forEach(button => {
        button.addEventListener('click', function(event){
            event.preventDefault();
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1){
                quantityInput.value = currentValue - 1;
                updatePriceRange();
            }
        })
    })

    incrementButtons.forEach(button => {
        button.addEventListener('click', function(event){
            event.preventDefault();
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
            updatePriceRange();
        })
    })

    // Mengganti gambar utama saat gambar kecil di-hover
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('mouseover', function() {
            mainImage.src = thumbnail.getAttribute('data-image-url');
        });
    });


    // Mengubah harga ketika variasi dipilih
    variationRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updatePriceRange();
            selectedVariationId.value = radio.value;
            const imageUrl = radio.getAttribute('data-image-url');
            if (imageUrl && imageUrl !== '/storage/') {
                mainImage.src = imageUrl;
            }
        });
    });

    // Jika hanya ada satu variasi, pilih variasi tersebut dan sembunyikan pilihan variasi
    if (variationRadios.length === 1) {
        variationRadios[0].checked = true;
        updatePriceRange();
        selectedVariationId.value = variationRadios[0].value;
        variationContainer.style.display = 'none';
    }



});

$(document).ready(function() {
    $('#add-to-cart-button').on('click',function(){
        const productId = $('input[name="product_id"]').val();
        const variationId = $('#selected-variation-id').val();
        const quantity = $('#quantity').val();

        const cartData = {
        product_id: productId,
        variation_id: variationId,
        quantity: quantity
        };

        $.ajax({
            url: '{{ route('cart.add') }}',
            type: 'POST',
            data: JSON.stringify(cartData),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
            if (data.success) {
            location.reload()
            showAlert('success', 'Sukses!', 'Produk berhasil ditambahkan ke keranjang');
            } else {
            showAlert('error', 'Oops...', data.error || 'Terjadi kesalahan');
            }
            },
            error: function(xhr, status, error) {
            showAlert('error', 'Oops...', error);
            }
        });

    });
});
</script>
@endpush
