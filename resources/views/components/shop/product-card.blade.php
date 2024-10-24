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
    <div class="d-flex w-100 justify-content-between align-items-center">
        <div onclick="shareProduct('{{ route('products.show', $product->slug) }}', '{{ $product->name }}')" class="d-flex align-items-center" style="cursor: pointer;">
            <i class="fa-solid fa-share text-gray-400"></i>
        </div>
        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-warning" style="white-space: nowrap;">Lihat detail</a>
    </div>
</div>
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
