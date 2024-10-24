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
<script>
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
</script>
