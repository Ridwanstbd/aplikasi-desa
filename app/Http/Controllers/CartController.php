<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CustomerService;
use App\Models\Leads;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    public function index() {
        $cart = session()->get('cart', []);
        return view('pages.cart.index',compact('cart'));
    }
    public function addToCart(Request $request) {
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'variation_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);
        $product_id = $validatedData['product_id'];
        $variation_id = $validatedData['variation_id'];
        $quantity = $validatedData['quantity'];
        $product = Product::find($product_id);
        $variation = Variation::find($variation_id);

        if (!$product || !$variation) {
            return response()->json(['error' => 'Produk atau variasi tidak ditemukan'], 404);
        }

        // Struktur data keranjang (misalnya menggunakan session)
        $cart = Session::get('cart', []);
        // Jika item sudah ada di keranjang, tambahkan jumlahnya
        if (isset($cart[$variation_id])) {
            $cart[$variation_id]['quantity'] += $quantity;
        } else {
            $cart[$variation_id] = [
                'product_id' => $product_id,
                'variation_id' => $variation_id,
                'quantity' => $quantity,
                'name' => $product->name,
                'name_variation' => $variation->name_variation,
                'price' => $variation->price,
                'image' => $variation->image,
            ];
        }

        // Simpan kembali data keranjang ke session
        Session::put('cart', $cart);
        return response()->json(['success' => 'Produk berhasil ditambahkan ke keranjang']);
    }
    public function updateQuantity(Request $request) {
        $cart = session()->get('cart', []);
        $variationId = $request->input('variation_id');
        $quantity = $request->input('quantity');

        if(isset($cart[$variationId])) {
            $cart[$variationId]['quantity'] = $quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true, 'message' => 'Kuantitas berhasil diperbarui']);
        }

        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan dalam keranjang']);
    }
    public function removeFromCart($variationId) {
        $cart = session()->get('cart', []);

        if (isset($cart[$variationId])) {
            unset($cart[$variationId]);
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang');
        }

        return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan dalam keranjang');
    }
    public function checkout() {
        $cart = session()->get('cart', []);
        $totalBelanja = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        return view('pages.cart.order', compact('cart', 'totalBelanja'));
    }
    public function submitCheckout(Request $request){
        // Validasi request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'address' => 'required|string|max:255',
        'province_name' => 'required|string|max:255',
        'regency_name' => 'required|string|max:255',
        'district_name' => 'required|string|max:255',
        'village_name' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Ambil data keranjang dari sesi
    $cart = session('cart', []);

    if (empty($cart)) {
        return redirect()->route('home')->with('error', 'Keranjang belanja kosong.');
    }

    $cart = array_values($cart);

    // Hitung total harga
    $totalPrice = 0;
    foreach ($cart as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }

    // Buat detail pesanan
    $detailOrder = '';
    foreach ($cart as $index => $item) {
        $detailOrder .= ($index + 1) . ". Produk: " . $item['name'] . ", Variasi: " . $item['name_variation'] . ", Jumlah: " . $item['quantity'] . ", Harga: Rp " . number_format($item['price'], 0, ',', '.') . ", Subtotal: Rp " . number_format($item['price'] * $item['quantity'], 0, ',', '.') . "\n";
    }
    $detailOrder .= "Total Harga: Rp " . number_format($totalPrice, 0, ',', '.');
    $product_id = $cart[0]['product_id'];
    // Data diri pemesan
    $customerData = [
        'name' => $request->input('name'),
        'phone' => $request->input('phone'),
        'address' => $request->input('address'),
        'product_id' => $product_id,
        'province' => $request->input('province_name'),
        'regency' => $request->input('regency_name'),
        'district' => $request->input('district_name'),
        'village' => $request->input('village_name'),
        'detail_order' => $detailOrder,
        'time_order' => now()
    ];

    // Simpan data pemesan ke database
    Leads::create($customerData);

    // Buat pesan WhatsApp
    $whatsappMessage = "Halo Kak, Mohon diproses pesanan saya:\n";
    foreach ($cart as $index => $item) {
        $whatsappMessage .= ($index + 1) . ". Produk: " . $item['name'] . "\n";
        $whatsappMessage .= "   Variasi: " . $item['name_variation'] . "\n";
        $whatsappMessage .= "   Harga: Rp " . number_format($item['price'], 0, ',', '.') . "\n";
        $whatsappMessage .= "   Jumlah: " . $item['quantity'] . "\n";
        $whatsappMessage .= "   Subtotal: Rp " . number_format($item['price'] * $item['quantity'], 0, ',', '.') . "\n\n";
    }
    $whatsappMessage .= "Total Harga: Rp " . number_format($totalPrice, 0, ',', '.') . "\n\n";
    $whatsappMessage .= "Nama: " . $customerData['name'] . "\n";
    $whatsappMessage .= "Telepon: " . $customerData['phone'] . "\n";
    $whatsappMessage .= "Alamat: " . $customerData['address'] . "\n";
    $whatsappMessage .= "Desa: " . $customerData['village'] . "\n";
    $whatsappMessage .= "Kecamatan: " . $customerData['district'] . "\n";
    $whatsappMessage .= "Kota/Kabupaten: " . $customerData['regency'] . "\n";
    $whatsappMessage .= "Provinsi: " . $customerData['province'] . "\n";

    // Ambil nomor customer service
    $csIndex = Cache::get('cs_index', 1);
    $customerService = CustomerService::find($csIndex);
    if (!$customerService) {
        return redirect()->back()->with('error', 'Customer service tidak ditemukan.');
    }

    // Update index customer service di cache
    $nextCsIndex = $csIndex + 1;
    if ($nextCsIndex > CustomerService::max('id')) {
        $nextCsIndex = 1;
    }
    Cache::put('cs_index', $nextCsIndex);

    // URL WhatsApp
    $whatsappUrl = "https://wa.me/" . $customerService->phone . "?text=" . urlencode($whatsappMessage);

    // Redirect ke WhatsApp
    return redirect($whatsappUrl);
    }

    public function orderCancel(Request $request){
    // Hapus data keranjang dari sesi
    $request->session()->forget('cart');

    // Tampilkan pesan sukses
    return redirect()->route('home')->with('success', 'Pesanan telah dibatalkan.');
    }

}
