<form id="filterForm" method="GET" action="{{ route('home') }}">
    <div class="row">
        <div class="col-md-2">
            <x-shop.category-filter :categories="$categories" />
        </div>
        <div class="col-md-10">
            <h3 class="text-center py-2 my-1 w-100 text-white" style="background-color: #2a401a;">PRODUK</h3>
            <x-shop.product-grid
                :products="$products"
                :search-query="request('search')"
            />
            {{ $products->links('vendor.pagination.custom') }}
        </div>
    </div>
</form>
