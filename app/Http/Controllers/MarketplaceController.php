<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketplaceController extends Controller
{
    public function index()
    {
        $MarketplaceLinks = MarketplaceLinks::orderBy('position', 'asc')->get();
        $headers = ['Nama', 'Aksi'];
        return view('pages.admin.marketplace-links.index', compact('MarketplaceLinks', 'headers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'marketplace_url' => 'nullable|string',
            'shop_id' => 'required'
        ]);
        // Ambil position tertinggi untuk shop_id yang diberikan
        $lastPosition = MarketplaceLinks::where('shop_id', $request->shop_id)->max('position');

        // Jika tidak ada data sebelumnya, set position menjadi 1
        $newPosition = $lastPosition ? $lastPosition + 1 : 1;

        // Tambahkan position ke validated data
        $validatedData['position'] = $newPosition;

        MarketplaceLinks::create($validatedData);
        return redirect()->route('marketplace-links.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'marketplace_url' => 'nullable|string',
            'shop_id' => 'required',
            'position' => 'required|integer'
        ]);

        try {
            // Cek apakah ada marketplace lain dengan posisi yang sama
            $existingMarketplace = MarketplaceLinks::where('position', $request->position)
                ->where('shop_id', $request->shop_id)
                ->where('id', '!=', $id)  // pastikan tidak mengecek diri sendiri
                ->first();

            if ($existingMarketplace) {
                return back()->withErrors(['error' => 'Posisi yang dipilih sudah digunakan oleh marketplace lain.']);
            }
            $marketplace = MarketplaceLinks::find($id);
            $oldPosition = $marketplace->position;

            // Cek apakah ada marketplace lain dengan posisi yang sama
            if ($oldPosition != $request->position) {
                // Update posisi marketplace lain
                if ($request->position < $oldPosition) {
                    // Jika posisi baru lebih kecil
                    MarketplaceLinks::where('shop_id', $request->shop_id)
                        ->where('position', '>=', $request->position)
                        ->where('position', '<', $oldPosition)
                        ->increment('position');  // Setiap posisi yang lebih besar dari posisi baru ditambah 1
                } else {
                    // Jika posisi baru lebih besar
                    MarketplaceLinks::where('shop_id', $request->shop_id)
                        ->where('position', '<=', $request->position)
                        ->where('position', '>', $oldPosition)
                        ->decrement('position');  // Setiap posisi yang lebih kecil dari posisi baru dikurangi 1
                }
            }

            // Lanjutkan update jika tidak ada posisi yang sama
            $marketplace = MarketplaceLinks::find($id);
            $marketplace->update([
                'type' => $request->type,
                'name' => $request->name,
                'marketplace_url' => $request->marketplace_url,
                'shop_id' => $request->shop_id,
                'position' => $request->position
            ]);

            return redirect()->route('marketplace-links.index')->with('success', 'Marketplace berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
