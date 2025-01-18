<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogPostTag;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with(['author', 'category', 'tags'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(10);

        return view('pages.blog.index', compact('posts'));
    }

    public function showByCategory($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $posts = BlogPost::with(['author', 'category', 'tags'])
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(10);

        return view('pages.blog.showByCategory', compact('category', 'posts'));
    }

    public function showByTag($slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();
        $posts = $tag
            ->posts()
            ->with(['author', 'category', 'tags'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(10);

        return view('pages.blog.showByTag', compact('tag', 'posts'));
    }

    public function show($slug)
    {
        $post = BlogPost::with(['author', 'category', 'tags'])
            ->where('slug', $slug)
            ->where(function ($query) {
                $query
                    ->where('status', 'published')
                    ->where('published_at', '<=', now());

                if (Auth::check()) {
                    $query->orWhere('status', 'draft');
                }
            })
            ->firstOrFail();

        return view('pages.blog.show', compact('post'));
    }

    public function adminIndex()
    {
        $posts = BlogPost::with(['author', 'category', 'tags'])
            ->latest()
            ->paginate(15);

        return view('pages.admin.blog.stores', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();

        return view('pages.admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:blog_posts,title',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date|required_if:status,published',
            'featured_image' => 'nullable|image|max:2048'
        ]);

        try {
            if (empty($validated['excerpt'])) {
                $validated['excerpt'] = Str::limit(strip_tags($validated['content']), 300);
            }
            $featuredImage = null;
            if ($request->hasFile('featured_image')) {
                $featuredImage = $request->file('featured_image')->store('featured_images', 'public');
            }

            $post = BlogPost::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
                'author_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'featured_image' => $featuredImage,
                'published_at' => $validated['status'] === 'published'
                    ? ($validated['published_at'] ?? now())
                    : null,
            ]);

            // Proses tags jika ada
            if (!empty($validated['tags'])) {
                foreach ($validated['tags'] as $tagName) {
                    // Cek apakah tag sudah ada atau buat baru
                    $tag = BlogTag::firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)]
                    );

                    // Simpan hubungan ke tabel pivot BlogPostTag
                    BlogPostTag::create([
                        'post_id' => $post->id,
                        'tag_id' => $tag->id,
                    ]);
                }
            }

            return redirect()
                ->route('admin.blog.index')
                ->with('success', 'Artikel berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal membuat artikel. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $post = BlogPost::with(['category', 'tags'])->findOrFail($id);
        $categories = BlogCategory::all();
        $tags = BlogTag::all();

        return view('pages.admin.blog.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_posts', 'title')->ignore($id),
            ],
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'status' => 'required|in:published,draft',
            'featured_image' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date|required_if:status,published',
        ]);

        try {
            if (empty($validated['excerpt'])) {
                $validated['excerpt'] = Str::limit(strip_tags($validated['content']), 300);
            }
            $featuredImage = ($request->hasFile('featured_image')) ? $request->file('featured_image')->store('featured_images', 'public') : $post->featured_image;

            $post->update([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'featured_image' => $featuredImage,
                'published_at' => $validated['status'] === 'published'
                    ? ($validated['published_at'] ?? now())
                    : null,
            ]);

            if (!empty($validated['tags'])) {
                $tagIds = collect($validated['tags'])->map(function ($tagName) {
                    return BlogTag::firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)]
                    )->id;
                });

                $post->tags()->sync($tagIds);
            } else {
                $post->tags()->detach();
            }

            return redirect()
                ->route('admin.blog.index')
                ->with('success', 'Artikel berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui artikel. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $post = BlogPost::findOrFail($id);
            $post->tags()->detach();
            $post->delete();

            return redirect()
                ->route('admin.blog.index')
                ->with('success', 'Artikel berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus artikel. ' . $e->getMessage());
        }
    }
}
