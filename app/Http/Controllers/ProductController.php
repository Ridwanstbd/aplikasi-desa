<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Images;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variations')->latest()->get();
        return view('pages.admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Categories::all();
        $variations = [
            ['name' => '', 'price' => '', 'sku' => '', 'image' => '', 'is_ready' => 0]
        ];
        return view('pages.admin.products.create', compact('categories', 'variations'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['variations', 'images', 'category'])->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(6)
            ->get();
        return view('pages.admin.products.show', compact('product', 'relatedProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required',
            'url_form' => 'nullable',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'variations.*.is_ready' => 'required|boolean',
        ]);

        try {
            $mainImageName = 'product-' . \Str::slug($request->name, '-') . '-' . uniqid() . '.' . $request->main_image->getClientOriginalExtension();
            $main_image = $request->file('main_image')->storeAs('public/products', $mainImageName);

            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'main_image' => $main_image,
                'category_id' => $validated['category_id'],
                'url_form' => $validated['url_form'],
                'slug' => \Str::slug($validated['name'], '-') . '-' . uniqid(),
            ]);

            if ($request->hasFile('images') && $request->images) {
                foreach ($request->images as $image) {
                    $productImagesName = 'product-images-' . \Str::slug($validated['name'], '-') . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $product->images()->create([
                        'product_id' => $product->id,
                        'images' => $image->storeAs('public/products/images', $productImagesName),
                    ]);
                }
            }

            if ($request->has('has_variations') && $request->has_variations) {
                foreach ($request->variations as $variation) {
                    if (isset($variation['image']) && $variation['image']->isValid()) {
                        $variationImageName = 'variation-image-' . \Str::slug($request->name, '-') . '-' . \Str::slug($variation['name_variation'], '-') . '-' . uniqid() . '.' . $variation['image']->getClientOriginalExtension();
                        $path = $variation['image']->storeAs('public/products/variations', $variationImageName);
                        $variation['image'] = $path;
                    } else {
                        unset($variation['image']);
                    }
                    $product->variations()->create($variation);
                }
            } else {
                // perbaiki bagian ini
                $product->variations()->create([
                    'name_variation' => $request->name_variation,
                    'price' => $request->price,
                    'sku' => $request->sku,
                    'is_ready' => $request->is_ready,
                ]);
            }

            return redirect()->route('products.index')->with('success', 'Produk Berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        $categories = Categories::all();
        $images = Images::find($product);
        return view('pages.admin.products.edit', compact('product', 'categories', 'images'));
    }

    public function update(Request $request, $slug)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'main_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'url_form' => 'nullable',
        ]);

        $product = Product::where('slug', $slug)->firstOrFail();

        if ($request->hasFile('main_image')) {
            $main_image = $product->main_image;
            if (Storage::exists($main_image)) {
                Storage::delete($main_image);
            }
            $mainImageName = 'product-' . \Str::slug($request->name, '-') . '-' . uniqid() . '.' . $request->main_image->getClientOriginalExtension();
            $main_image = $request->file('main_image')->storeAs('public/products', $mainImageName);
            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'main_image' => $main_image,
                'category_id' => $validated['category_id'],
                'url_form' => $validated['url_form'],
                'slug' => \Str::slug($validated['name'], '-') . '-' . uniqid(),
            ]);
        } else {
            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'url_form' => $validated['url_form'],
                'slug' => \Str::slug($validated['name'], '-') . '-' . uniqid(),
            ]);
        }
        if ($request->hasFile('images')) {
            foreach ($request->images as $image) {
                $productImagesName = 'product-images-' . \Str::slug($validated['name'], '-') . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $product->images()->create([
                    'product_id' => $product->id,
                    'images' => $image->storeAs('public/products/images', $productImagesName),
                ]);
            }
        }

        return redirect()->route('products.edit', $product->slug)->with('success', 'Informasi Produk Berhasil diperbarui!');
    }

    public function deleteImages($id)
    {
        $image = Images::findOrFail($id);
        Storage::delete($image->images);
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function updateVariations(Request $request, $id)
    {
        $request->validate([
            'variations.*.id' => 'nullable|exists:variations,id',
            'variations.*.name_variation' => 'nullable',
            'variations.*.price' => 'nullable',
            'variations.*.sku' => 'nullable',
            'variations.*.is_ready' => 'nullable|boolean',
        ]);

        try {
            $product = Product::findOrFail($id);

            $product->variations()->delete();
            foreach ($request->variations as $variationData) {
                $variationImage = $variationData['existing_image'] ?? null;
                $index = $variationData['index'];

                if ($request->hasFile("variations.{$index}.image")) {
                    $file = $request->file("variations.{$index}.image");
                    $variationImageName = 'variation-image-' . \Str::slug($product->name, '-') . '-' . \Str::slug($variationData['name_variation'], '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('public/products/variations', $variationImageName);
                    $variationImage = $path;
                }

                if (isset($variationData['id'])) {
                    $variation = Variation::findOrFail($variationData['id']);
                    if ($variation->product_id != $product->id) {
                        throw new \Exception('Invalid variation ID for the product');
                    }

                    $variation->update(array_merge($variationData, ['image' => $variationImage]));
                } else {
                    // Create new variation
                    $newVariationData = [
                        'name_variation' => $variationData['name_variation'],
                        'price' => $variationData['price'],
                        'sku' => $variationData['sku'],
                        'is_ready' => $variationData['is_ready'],
                        'product_id' => $product->id,
                        'image' => $variationImage,
                    ];

                    $product->variations()->create($newVariationData);
                }
            }

            return redirect()->route('products.edit', $product->slug)->with('success', 'Variasi Produk Berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk Berhasil dihapus!');
    }
}
