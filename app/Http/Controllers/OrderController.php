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
        $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Redirecting...</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
                flex-direction: column;
                text-align: center;
            }
            #redirect-message {
                margin-bottom: 20px;
            }
            .whatsapp-link {
                color: #25D366;
                text-decoration: none;
                font-weight: bold;
                padding: 10px 20px;
                border: 2px solid #25D366;
                border-radius: 5px;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }
            .whatsapp-link:hover {
                background-color: #25D366;
                color: white;
            }
            .whatsapp-link svg {
                vertical-align: middle;
            }
        </style>
    </head>
    <body>
        <div id="redirect-message">
            <p>Silahkan klik tombol di bawah untuk konfirmasi via WhatsApp</p>
            <a href="' . $whatsappUrl . '" class="whatsapp-link" target="_blank" onclick="redirectHome()">
                <span>Buka WhatsApp</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
        </div>
        <script>
            function redirectHome() {
                setTimeout(function() {
                    window.location.href = "' . route('home') . '";
                }, 500);
            }
        </script>
    </body>
    </html>';
        return response($html);
    }

    public function cancelOrder(Request $request)
    {
        // Hapus data pesanan dari sesi
        $request->session()->forget('orderData');

        // Redirect ke halaman utama
        return redirect()->route('home')->with('success', 'Pesanan Anda telah dibatalkan.');
    }
}
