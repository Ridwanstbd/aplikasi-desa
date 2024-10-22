<a href="{{ route('products.show', $product->slug) }}" class="position-relative">
    <img src="{{ Storage::url($product->main_image) }}" style="width: 100%; height: auto;" alt="{{ $product->name }}">
    <span class="badge bg-primary position-absolute admin-badge">Bebas Biaya Admin</span>
    <span class="badge bg-danger position-absolute cod-badge">COD</span> <!-- Badge COD -->
</a>
<div class="p-1">
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

<!-- Tambahkan style CSS di bawah ini -->
<style>
    .cod-badge {
        bottom: -75px; /* Default untuk smartphone */
        left: 125px; /* Default untuk smartphone */
    }
    .admin-badge {
        bottom: -75px; /* Default untuk smartphone */
        left: 5px; /* Default untuk smartphone */
    }

    @media (min-width: 768px) {
        .cod-badge {
            bottom: -86px; /* Tablet */
            left: 125px;
        }
        .admin-badge {
        bottom: -86px; /* Default untuk smartphone */
        left: 5px; /* Default untuk smartphone */
    }
    }

    @media (min-width: 992px) {
        .cod-badge {
            bottom: -83px; /* Desktop */
            left: 125px;
        }
        .admin-badge {
        bottom: -83px; /* Default untuk smartphone */
        left: 5px; /* Default untuk smartphone */
    }
    }
</style>
