<form id="filterForm" method="GET" action="{{ route('home') }}">
    <div class="row">
        <div class="col-md-2">
            <x-shop.category-filter :categories="$categories" />
        </div>
        <div class="col-md-10">
            <h3 class="text-center my-3">PRODUK</h3>
            <x-shop.search-sort />
            <x-shop.product-grid :products="$products" />
            {{ $products->links('vendor.pagination.custom') }}
        </div>
    </div>
</form>
