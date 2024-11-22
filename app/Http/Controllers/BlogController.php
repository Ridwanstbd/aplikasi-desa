<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use Str;

class BlogController extends Controller
{
    // Kategori BLOG
    public function categories()
    {
        $categories = BlogCategory::withCount('blogPosts')->latest()->get();
        return view('pages.admin.blog_category.index', compact('categories'));
    }

    public function store_category(Request $request)
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

    public function update_category(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:blog_categories,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_categories', 'name')->ignore($request->category_id),
            ],
        ]);

        try {
            $category = BlogCategory::findOrFail($validated['category_id']);

            $category->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'])
            ]);

            return redirect()->back()->with('success', 'Kategori blog berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui kategori blog')->withInput();
        }
    }

    public function delete_category(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:blog_categories,id',
        ]);

        try {
            $category = BlogCategory::findOrFail($validated['category_id']);

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

    // Tag BLOG
    public function tags()
    {
        $tags = BlogTag::withCount('blogPosts')
            ->latest()
            ->get();

        return view('pages.admin.blog_tag.index', compact('tags'));
    }

    public function store_tag(Request $request)
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

    public function update_tag(Request $request, $id)
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

    public function delete_tag(Request $request, $id)
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

    // BLOG
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
            ->blogPosts()
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
            'tags.*' => 'exists:blog_tags,id',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date|required_if:status,published',
        ]);

        try {
            // Generate excerpt if not provided
            if (empty($validated['excerpt'])) {
                $excerpt = strip_tags($validated['content']);
                $validated['excerpt'] = Str::limit($excerpt, 300);
            }

            $post = BlogPost::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
                'author_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'published_at' => $validated['status'] === 'published'
                    ? ($validated['published_at'] ?? now())
                    : null,
            ]);

            // Sync tags
            if (!empty($validated['tags'])) {
                $post->tags()->sync($validated['tags']);
            }

            return redirect()
                ->route('admin.blog.index')
                ->with('success', 'Artikel berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal membuat artikel')
                ->withInput();
        }
    }

    public function stores()
    {
        $posts = BlogPost::with(['author', 'category', 'tags'])
            ->latest()
            ->paginate(15);

        return view('pages.admin.blog.stores', compact('posts'));
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
            'tags.*' => 'exists:blog_tags,id',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date|required_if:status,published',
        ]);

        try {
            // Generate excerpt if not provided
            if (empty($validated['excerpt'])) {
                $excerpt = strip_tags($validated['content']);
                $validated['excerpt'] = Str::limit($excerpt, 300);
            }

            $post->update([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'published_at' => $validated['status'] === 'published'
                    ? ($validated['published_at'] ?? now())
                    : null,
            ]);

            // Sync tags
            if (isset($validated['tags'])) {
                $post->tags()->sync($validated['tags']);
            } else {
                $post->tags()->detach();
            }

            return redirect()
                ->route('admin.blog.index')
                ->with('success', 'Artikel berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui artikel')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $post = BlogPost::findOrFail($id);

            // Delete related tags
            $post->tags()->detach();

            // Delete the post
            $post->delete();

            return redirect()
                ->route('admin.blog.index')
                ->with('success', 'Artikel berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus artikel');
        }
    }
}
