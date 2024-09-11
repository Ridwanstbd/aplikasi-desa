<?php

namespace App\Http\Controllers;

use App\Models\CsRotation;
use App\Models\CustomerService;
use App\Models\Leads;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function submit(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'variation_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil harga variasi berdasarkan ID
        $variation = Variation::find($request->input('variation_id'));
        if (!$variation) {
            return redirect()->back()->with('error', 'Variasi tidak ditemukan.');
        }

        // Kalikan jumlah dengan harga variasi
        $quantity = $request->input('quantity');
        $totalPrice = $quantity * $variation->price;

        // Data pemesanan
        $orderData = [
            'product_id' => $request->input('product_id'),
            'variation_id' => $request->input('variation_id'),
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'timestamp' => now(),
        ];

        // Simpan data pesanan di sesi
        $request->session()->put('orderData', $orderData);

        // Redirect ke halaman form data diri pemesan
        return redirect()->route('order.details.form');
    }

    public function orderDetailsForm(Request $request)
    {
        $orderData = $request->session()->get('orderData');

        if (!$orderData) {
            return redirect()->route('home');
        }
        $product = Product::find($orderData['product_id']);
        $variation = Variation::find($orderData['variation_id']);

        return view('pages.order.detail', [
            'orderData' => $orderData,
            'product' => $product,
            'variation' => $variation,
        ]);
    }

    public function submitOrderDetails(Request $request)
    {
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

        // Ambil data pesanan dari sesi
        $orderData = $request->session()->get('orderData');

        if (!$orderData) {
            return redirect()->route('home')->with('error', 'Data pesanan tidak ditemukan.');
        }

        // Ambil detail produk dan variasi
        $product = Product::find($orderData['product_id']);
        $variation = Variation::find($orderData['variation_id']);

        if (!$product || !$variation) {
            return redirect()->back()->with('error', 'Produk atau variasi tidak ditemukan.');
        }

        // Hitung total harga
        $totalPrice = $orderData['quantity'] * $variation->price;

        // Data diri pemesan
        $customerData = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'product_id' => $orderData['product_id'],
            'province' => $request->input('province_name'),
            'regency' => $request->input('regency_name'),
            'district' => $request->input('district_name'),
            'village' => $request->input('village_name'),
            'detail_order' => 'Produk: ' . $product->name . ', Variasi: ' . $variation->name_variation . ', Jumlah: ' . $orderData['quantity'] . ', Total Harga: Rp ' . number_format($totalPrice, 0, ',', '.'),
            'time_order' => now()
        ];

        // Simpan data pemesan ke database
        Leads::create($customerData);

        // Buat pesan WhatsApp
        $whatsappMessage = "Halo Kak Mohon diproses pesanan saya:\n";
        $whatsappMessage .= 'Produk: ' . $product->name . "\n";
        $whatsappMessage .= 'Variasi: ' . $variation->name_variation . "\n";
        $whatsappMessage .= 'Harga Variasi: Rp ' . number_format($variation->price, 0, ',', '.') . "\n";
        $whatsappMessage .= 'Jumlah : ' . $orderData['quantity'] . "\n";
        $whatsappMessage .= 'Total : Rp ' . number_format($totalPrice, 0, ',', '.') . "\n" . "\n";
        $whatsappMessage .= 'Nama: ' . $customerData['name'] . "\n";
        $whatsappMessage .= 'Telepon: ' . $customerData['phone'] . "\n";
        $whatsappMessage .= 'Alamat: ' . $customerData['address'] . "\n";
        $whatsappMessage .= 'Desa: ' . $customerData['village'] . "\n";
        $whatsappMessage .= 'Kecamatan: ' . $customerData['district'] . "\n";
        $whatsappMessage .= 'Kota/Kabupaten: ' . $customerData['regency'] . "\n";
        $whatsappMessage .= 'Provinsi: ' . $customerData['province'] . "\n";

        // Ambil nomor customer service
        $rotation = CsRotation::first();
        if (!$rotation) {
            // Jika belum ada record di tabel, buat yang pertama
            $rotation = CsRotation::create(['current_cs_id' => 1]);
        }
        $customerService = CustomerService::find($rotation->current_cs_id);
        if (!$customerService) {
            return redirect()->back()->with('error', 'Customer service tidak ditemukan.');
        }
        $minCsId = CustomerService::min('id');

        $nextCsId = CustomerService::where('id', '>', $rotation->current_cs_id)
            ->min('id');
        if (!$nextCsId) {
            $nextCsId = $minCsId;
        }

        $rotation->update(['current_cs_id' => $nextCsId]);

        // URL WhatsApp
        $whatsappUrl = 'https://wa.me/' . $customerService->phone . '?text=' . urlencode($whatsappMessage);

        $request->session()->forget('orderData');
        // Redirect ke WhatsApp
        return redirect($whatsappUrl);
    }

    public function cancelOrder(Request $request)
    {
        // Hapus data pesanan dari sesi
        $request->session()->forget('orderData');

        // Redirect ke halaman utama
        return redirect()->route('home')->with('success', 'Pesanan Anda telah dibatalkan.');
    }
}
