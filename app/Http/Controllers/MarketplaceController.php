<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketplaceController extends Controller
{
    public function index()
    {
        $MarketplaceLinks = MarketplaceLinks::latest()->get();
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

        MarketplaceLinks::create($validatedData);
        return redirect()->route('marketplace-links.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'marketplace_url' => 'nullable|string',
            'shop_id' => 'required'
        ]);

        try {
            $category = MarketplaceLinks::find($id);
            $category->update([
                'type' => $request->type,
                'name' => $request->name,
                'marketplace_url' => $request->marketplace_url,
                'shop_id' => $request->shop_id
            ]);
            return redirect()->route('marketplace-links.index')->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reorder(Request $request)
    {
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

            // Jika memindahkan item ke posisi yang lebih tinggi (contoh: dari 2 ke 12)
            if ($oldPosition < $newPosition) {
                MarketplaceLinks::whereBetween('id', [$oldPosition + 1, $newPosition])
                    ->update(['id' => DB::raw('id - 1')]);

                // Update item yang dipindahkan ke posisi baru
                MarketplaceLinks::where('id', $oldPosition)
                    ->update(['id' => $newPosition]);
            }
            // Jika memindahkan item ke posisi yang lebih rendah (contoh: dari 12 ke 6)
            else {
                MarketplaceLinks::whereBetween('id', [$newPosition, $oldPosition - 1])
                    ->update(['id' => DB::raw('id + 1')]);

                // Update item yang dipindahkan ke posisi baru
                MarketplaceLinks::where('id', $oldPosition)
                    ->update(['id' => $newPosition]);
            }

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
