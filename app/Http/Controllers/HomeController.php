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
        return view('pages.index');
    }
}
