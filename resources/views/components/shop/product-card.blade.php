<a href="{{ route('products.show', $product->slug) }}" class="">
    <img src="{{ Storage::url($product->main_image) }}" style="width:100%; height:auto;" alt="...">
</a>
<div class="p-1">
    <a href="{{ route('products.show', $product->slug) }}" class="text-black text-decoration-none">
        <h5 class="fs-5">Rp{{ number_format($product->hargaTermurah, 0, ',', '.') }}</h5>
        <p class="fs-6" style="color: green;" >{{ $product->name }}</p>
    </a>
    <div class="row w-100 justify-content-between align-items-center">
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
