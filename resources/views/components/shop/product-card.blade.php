<div class="col-6 col-md-4 col-lg-3 mb-3">
    <div class="card h-100 border-0">
        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
            <img src="{{ Storage::url($product->main_image) }}"
                 class="card-img-top object-fit-cover"
                 style="height: 176px; /* 11rem */"
                 alt="{{ $product->name }}">
        </a>

        <div class="card-body p-2 d-flex flex-column">
            <div class="flex-grow-1">
                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                    <h5 class="text-black mb-1 fs-5">Rp{{ number_format($product->hargaTermurah, 0, ',', '.') }}</h5>
                    <p class="text-success mb-2 fs-6 text-truncate">{{ $product->name }}</p>
                </a>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-auto">
                <div class="cursor-pointer" onclick="shareProduct('{{ route('products.show', $product->slug) }}', '{{ $product->name }}')">
                    <i class="fa-solid fa-share text-gray-400"></i>
                </div>
                <a href="{{ route('products.show', $product->slug) }}"
                   class="btn btn-warning btn-sm px-3">
                    Lihat detail
                </a>
            </div>
        </div>
    </div>
</div>
