<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Leads;
use App\Models\Categories;
use App\Models\Banner;
use App\Models\Testimonial;
use App\Models\MarketplaceLinks;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $categories = Categories::latest()->get();
        $banners = Banner::latest()->pluck('banner_url');
        $testimonials = Testimonial::latest()->get();
        $marketplaces = MarketplaceLinks::latest()->get();
        $kategori = $request->input('kategori');
        $search = $request->input('search');
        $sort = $request->input('sort');
        $leads = Leads::with('product')->get();

        $query = Product::with(['variations', 'images', 'category']);

        if ($kategori) {
            $query->whereHas('category', function ($q) use ($kategori) {
                $q->where('id', $kategori);
            });
        }

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($sort) {
            switch ($sort) {
                case 'terbaru':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'terlama':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'termurah':
                    $query->leftJoin('variations', 'products.id', '=', 'variations.product_id')
                          ->orderBy('variations.price', 'asc')
                          ->select('products.*');
                    break;
                case 'termahal':
                    $query->leftJoin('variations', 'products.id', '=', 'variations.product_id')
                          ->orderBy('variations.price', 'desc')
                          ->select('products.*');
                    break;
            }
        }

        $products = $query->paginate(24);
        $products->appends($request->except('page'));

        return view('pages.index', [
            'products' => $products,
            'banners' => $banners,
            'testimonials' => $testimonials,
            'marketplaces' => $marketplaces,
            'categories' => $categories,
            'leads' => $leads,
            'productsJson' => $products->items(),
        ]);
    }
}
