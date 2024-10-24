<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Shop;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $shop = Shop::first();
        return view('pages.admin.settings.index', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'logo_url' => 'nullable',
            'logo_footer_url' => 'nullable',
            'banners.*' => 'nullable',
            'added_url*' => 'nullable',
            'location_url' => 'nullable|url',
            'meta_pixel_id' => 'nullable|string|max:255',
            'tiktok_pixel_id' => 'nullable|string|max:255',
            'google_tag_id' => 'nullable|string|max:255',
            'testimonials.*' => 'nullable',
        ]);

        try {
            $shop = Shop::findOrFail($id);

            // Handle logo
            if ($request->hasFile('logo_url')) {
                $logo_url = $shop->logo_url;
                if (Storage::exists($logo_url)) {
                    Storage::delete($logo_url);
                }
                $logoName = 'logo-' . \Str::slug($request->name, '-') . '-' . uniqid() . '.' . $request->logo_url->getClientOriginalExtension();
                $logo_url = $request->file('logo_url')->storeAs('/public/logo', $logoName);
                $validated['logo_url'] = $logo_url;
            }

            if ($request->hasFile('logo_footer_url')) {
                $logo_footer_url = $shop->logo_footer_url;
                if ($logo_footer_url && Storage::exists($logo_footer_url)) {
                    Storage::delete($logo_footer_url);
                }
                $logoName = 'logo-footer-' . \Str::slug($request->name, '-') . '-' . uniqid() . '.' . $request->logo_footer_url->getClientOriginalExtension();
                $logo_footer_url = $request->file('logo_footer_url')->storeAs('/public/logo', $logoName);
                $validated['logo_footer_url'] = $logo_footer_url;
            }

            // Update shop
            $shop->update([
                'name' => $validated['name'],
                'logo_url' => $validated['logo_url'] ?? $shop->logo_url,
                'logo_footer_url' => $validated['logo_footer_url'] ?? $shop->logo_footer_url,
                'description' => $validated['description'],
                'location_url' => $validated['location_url'],
                'added_url' => $validated['added_url'],
                'meta_pixel_id' => $validated['meta_pixel_id'],
                'tiktok_pixel_id' => $validated['tiktok_pixel_id'],
                'google_tag_id' => $validated['google_tag_id'],
            ]);

            // Handle banners
            if ($request->hasFile('banners')) {
                foreach ($request->banners as $banner) {
                    $shopbannersName = 'shop-banners-' . \Str::slug($validated['name'], '-') . '-' . uniqid() . '.' . $banner->getClientOriginalExtension();
                    $shop->banners()->create([
                        'shop_id' => $shop->id,
                        'banner_url' => $banner->storeAs('/public/shops', $shopbannersName),
                    ]);
                }
            }
            // Handle testimonials
            if ($request->hasFile('testimonials')) {
                foreach ($request->testimonials as $testimonial) {
                    $shopTestimonialsName = 'shop-testimonials-' . \Str::slug($shop->name, '-') . '-' . uniqid() . '.' . $testimonial->getClientOriginalExtension();
                    $shop->testimonials()->create([
                        'shop_id' => $shop->id,
                        'testimoni_url' => $testimonial->storeAs('/public/shops', $shopTestimonialsName),
                    ]);
                }
            }

            return redirect()->route('settings.index')->with('success', 'Toko Berhasil diperbarui!.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        Storage::delete($banner->banner_url);
        $banner->delete();

        return response()->json(['success' => true]);
    }

    public function destroyTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        Storage::delete($testimonial->testimoni_url);
        $testimonial->delete();

        return response()->json(['success' => true]);
    }
}
