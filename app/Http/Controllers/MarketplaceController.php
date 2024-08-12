<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceLinks;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index()
    {
        $MarketplaceLinks = MarketplaceLinks::latest()->paginate(10);
        $headers = ['Nama','Aksi'];
        return view('pages.admin.marketplace-links.index', compact('MarketplaceLinks', 'headers'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'marketplace_url' => 'nullable|string',
        ]);

        MarketplaceLinks::create($validatedData);
        return redirect()->route('marketplace-links.index')->with('success', 'Kategori berhasil ditambahkan.');
    }
    public function update(Request $request, $id){
    $request->validate([
        'type' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'marketplace_url' => 'nullable|string',
    ]);

    try {
        $category = MarketplaceLinks::find($id);
        $category->update([
            'type' => $request->type,
            'name' => $request->name,
            'marketplace_url' => $request->marketplace_url,
        ]);
        return redirect()->route('marketplace-links.index')->with('success', 'Kategori berhasil diperbarui.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
    public function destroy($id)
    {
            MarketplaceLinks::find($id)->delete();
            return redirect()->route('marketplace-links.index')->with('success', 'Kategori berhasil dihapus.');
    }


}
