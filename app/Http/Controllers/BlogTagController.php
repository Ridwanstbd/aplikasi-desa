<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogTagController extends Controller
{
    public function index()
    {
        $tags = BlogTag::withCount('blogPosts')
            ->latest()
            ->get();

        return view('pages.admin.blog_tag.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_tags,name',
        ]);

        try {
            BlogTag::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'])
            ]);

            return redirect()->back()->with('success', 'Tag berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan tag')
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_tags', 'name')->ignore($id),
            ],
        ]);

        try {
            $tag = BlogTag::findOrFail($id);

            $tag->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'])
            ]);

            return redirect()->back()->with('success', 'Tag berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui tag')
                ->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $tag = BlogTag::findOrFail($id);

            if ($tag->blogPosts()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Tag tidak dapat dihapus karena masih digunakan dalam artikel');
            }

            $tag->delete();

            return redirect()->back()->with('success', 'Tag berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus tag');
        }
    }
}
