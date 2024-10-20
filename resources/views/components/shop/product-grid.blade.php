<div class="product-grid" id="product-grid">
    <div class="row mb-2">
        <div class="col-12">
            <x-shop.search-sort />
        </div>
    </div>
    @if($searchQuery)
        <div class="row mb-2">
            <div class="col-12">
                <h4 class="mb-3">
                    Hasil pencarian untuk: "{{ $searchQuery }}"
                    <small class="text-muted">({{ $totalProducts }} produk ditemukan)</small>
                </h4>
            </div>
        </div>
    @endif

    <div class="row">
        @forelse ($products as $product)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <x-shop.product-card :product="$product" />
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    @if($searchQuery)
                        Tidak ada produk yang ditemukan untuk kata kunci "{{ $searchQuery }}"
                    @else
                        Tidak ada produk yang tersedia saat ini
                    @endif
                </div>
            </div>
        @endforelse
    </div>

</div>
