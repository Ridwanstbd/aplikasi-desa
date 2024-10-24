<a href="{{ route('products.show', $product->slug) }}">
    <img src="{{ Storage::url($product->main_image) }}" style="width: 100%; height: auto;" alt="{{ $product->name }}">
</a>
<div class="p-1">
    <div class="d-flex">
        <span class="badge bg-primary">Bebas Biaya Admin</span>
        <span class="badge bg-danger">COD</span>
    </div>
    <a href="{{ route('products.show', $product->slug) }}" class="text-black text-decoration-none">
        <h5 class="fs-5">Rp{{ number_format($product->hargaTermurah, 0, ',', '.') }}</h5>
        <p class="fs-6" style="color: green;">{{ $product->name }}</p>
    </a>
    <div class="row w-100 justify-content-between align-items-center">
        <div class="col-auto">
            <div onclick="shareProduct('{{ route('products.show', $product->slug) }}', '{{ $product->name }}')" class="d-flex align-items-center" style="cursor: pointer;">
                <i class="fa-solid fa-share text-gray-400"></i>
            </div>
        </div>
        <div class="col-auto text-end">
            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-warning" style="white-space: nowrap;">Lihat detail</a>
        </div>
    </div>
</div>
