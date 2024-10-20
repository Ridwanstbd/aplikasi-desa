<div class="col-6 col-md-4 col-lg-3 mb-3">
    <a href="{{ route('products.show', $product->slug) }}" class="">
        <img src="{{ Storage::url($product->main_image) }}" style="width:11rem; height: 11rem;" alt="...">
    </a>
    <div class="p-1">
        <a href="{{ route('products.show', $product->slug) }}" class="text-black text-decoration-none">
            <h5 class="fs-5">Rp{{ number_format($product->hargaTermurah, 0, ',', '.') }}</h5>
            <p class="fs-6 text-truncate mb-0" style="color: green;" >{{ $product->name }}</p>
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
