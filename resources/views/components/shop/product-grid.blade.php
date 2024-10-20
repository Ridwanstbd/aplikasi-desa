<div class="row">
    @foreach ($products as $product)
        <x-shop.product-card :product="$product" />
    @endforeach
</div>
