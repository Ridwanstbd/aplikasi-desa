<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('posts')->latest()->get();
        return view('pages.admin.blog_category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
        ]);

        try {
            BlogCategory::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'])
            ]);

            return redirect()->back()->with('success', 'Kategori blog berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan kategori blog')->withInput();
        }
    }

    public function update(Request $request, $category_id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_categories', 'name')->ignore($request->category_id),
            ],
        ]);

        try {
            $category = BlogCategory::findOrFail($category_id);

            $category->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'])
            ]);

            return redirect()->back()->with('success', 'Kategori blog berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui kategori blog')->withInput();
        }
    }

    public function delete($category_id)
    {
        try {
            $category = BlogCategory::findOrFail($category_id);

            // Optional: Check if category has related posts
            if ($category->blogPosts()->count() > 0) {
                return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel terkait');
            }

            $category->delete();

            return redirect()->back()->with('success', 'Kategori blog berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kategori blog');
        }
    }
}
