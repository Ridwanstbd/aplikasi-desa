<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Categories;
use App\Models\Leads;
use App\Models\MarketplaceLinks;
use App\Models\Product;
use App\Models\Testimonial;
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
        $marketplaces = MarketplaceLinks::orderBy('position', 'asc')->get();
        $leads = Leads::with('product')->get();

        $query = Product::with(['variations', 'images', 'category']);

        // Filter by category
        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->kategori);
            });
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q
                    ->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('category', function ($q) use ($request) {
                        $q->where('name', 'LIKE', "%{$request->search}%");
                    });
            });
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'terbaru':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'terlama':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'termurah':
                    $query
                        ->leftJoin('variations', 'products.id', '=', 'variations.product_id')
                        ->orderBy('variations.price', 'asc')
                        ->select('products.*');
                    break;
                case 'termahal':
                    $query
                        ->leftJoin('variations', 'products.id', '=', 'variations.product_id')
                        ->orderBy('variations.price', 'desc')
                        ->select('products.*');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');  // default sorting
        }
        $products = $query->paginate(12);
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
