<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Milon\Barcode\DNS2D;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();

        return view('pages.admin.vouchers.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'discount_amount' => 'required|numeric'
        ]);

        $slug = Str::slug($validated['slug']);

        Voucher::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'discount_amount' => $validated['discount_amount'],
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dibuat.');
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'discount_amount' => 'required|numeric'
        ]);

        $voucher = Voucher::where('slug', $slug)->first();

        if (!$voucher) {
            return redirect()->route('vouchers.index')->with('error', 'Voucher tidak ditemukan.');
        }

        $newSlug = Str::slug($request->input('slug'));

        $voucher->update([
            'name' => $request->input('name'),
            'slug' => $newSlug,
            'description' => $request->input('description'),
            'discount_amount' => $request->input('discount_amount'),
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return redirect()->route('vouchers.index')->with('error', 'Voucher tidak ditemukan.');
        }

        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }

    public function generateBarcode($slug)
    {
        $voucher = Voucher::where('slug', $slug)->first();

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $url = env('APP_URL') . "/voucher/{$voucher->slug}";

        $qrcode = new DNS2D();
        $qrcodeImage = base64_decode($qrcode->getBarcodePNG($url, 'QRCODE', 10, 10));

        return response($qrcodeImage, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $voucher->slug . '.png"');
    }

    public function copyUrl($slug)
    {
        $voucher = Voucher::where('slug', $slug)->first();

        if (!$voucher) {
            return back()->with('error', 'Voucher tidak ditemukan');
        }

        $url = env('APP_URL') . "/voucher/{$voucher->slug}";

        return response()->json([
            'success' => true,
            'url' => $url,
            'message' => 'URL telah disalin'
        ]);
    }
}
