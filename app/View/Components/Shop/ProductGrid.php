<?php

namespace App\View\Components\Shop;

use Illuminate\View\Component;

class ProductGrid extends Component
{
    public $products;
    public $searchQuery;
    public $totalProducts;

    public function __construct($products, $searchQuery = null)
    {
        $this->products = $products;
        $this->searchQuery = $searchQuery;
        $this->totalProducts = $products->total();
    }

    public function render()
    {
        return view('components.shop.product-grid');
    }
}
