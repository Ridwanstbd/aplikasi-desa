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

            // Get the item being moved
            $item = MarketplaceLinks::where('position', $oldPosition)->first();

            if (!$item) {
                throw new \Exception('Item tidak ditemukan');
            }

            // Moving item to a higher position
            if ($oldPosition < $newPosition) {
                MarketplaceLinks::whereBetween('position', [$oldPosition + 1, $newPosition])
                    ->update([
                        'position' => DB::raw('position - 1'),
                        'updated_at' => now()
                    ]);
            }
            // Moving item to a lower position
            else {
                MarketplaceLinks::whereBetween('position', [$newPosition, $oldPosition - 1])
                    ->update([
                        'position' => DB::raw('position + 1'),
                        'updated_at' => now()
                    ]);
            }

            // Update the moved item's position
            $item->position = $newPosition;
            $item->save();

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
