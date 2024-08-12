<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::latest()->paginate(10);
        $headers = ['Nama','Aksi'];
        return view('pages.admin.categories.index', compact('categories', 'headers'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Categories::create($validatedData);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }
    public function update(Request $request, $id){
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    try {
        $category = Categories::find($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
    public function destroy($id)
    {
        $isLinked = Product::where('category_id', $id)->exists();

        if ($isLinked) {
            return redirect()->route('categories.index')->with('error','Kategori tidak dapat dihapus karena masih terhubung dengan Produk.');
        }else{
            Categories::find($id)->delete();
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
        }
    }


}
