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

    public function reorder(Request $request)
    {
        // Pastikan request adalah AJAX
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request method'
            ], 400);
        }

        $request->validate([
            'old_position' => 'required|integer|min:1',
            'new_position' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $oldPosition = $request->old_position;
            $newPosition = $request->new_position;

            if ($oldPosition == $newPosition) {
                return response()->json([
                    'success' => true,
                    'message' => 'Posisi tidak berubah'
                ]);
            }

            // Mengambil item yang dipindahkan
            $item = DB::table('marketplace_links')->where('position', $oldPosition)->first();

            if (!$item) {
                throw new \Exception('Item tidak ditemukan');
            }

            // Memindahkan item ke posisi yang lebih tinggi
            if ($oldPosition < $newPosition) {
                DB::table('marketplace_links')
                    ->whereBetween('position', [$oldPosition + 1, $newPosition])
                    ->update([
                        'position' => DB::raw('position - 1'),
                        'updated_at' => now()
                    ]);
            }
            // Memindahkan item ke posisi yang lebih rendah
            else {
                DB::table('marketplace_links')
                    ->whereBetween('position', [$newPosition, $oldPosition - 1])
                    ->update([
                        'position' => DB::raw('position + 1'),
                        'updated_at' => now()
                    ]);
            }

            // Update posisi item yang dipindahkan
            DB::table('marketplace_links')
                ->where('id', $item->id)
                ->update([
                    'position' => $newPosition,
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Urutan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah urutan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all marketplace links in order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrdered()
    {
        $links = MarketplaceLinks::orderBy('id', 'asc')->get();
        return response()->json($links);
    }

    public function destroy($id)
    {
        MarketplaceLinks::find($id)->delete();
        return redirect()->route('marketplace-links.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
