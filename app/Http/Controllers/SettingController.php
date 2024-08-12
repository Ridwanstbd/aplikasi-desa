<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Shop;

class SettingController extends Controller
{
    public function index() {
        $shop = Shop::first();
        return view('pages.admin.settings.index',compact('shop'));
    }
    public function update(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'logo_url' => 'nullable',
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
                $logo_url = $request->file('logo_url')->storeAs('public/logo', $logoName);
                $validated['logo_url'] = $logo_url;
            }

            // Update shop
            $shop->update([
                'name' => $validated['name'],
                'logo_url' => $validated['logo_url'] ?? $shop->logo_url,
                'description' => $validated['description'],
                'location_url' => $validated['location_url'],
                'added_url' => $validated['added_url'],
                'meta_pixel_id' => $validated['meta_pixel_id'],
                'tiktok_pixel_id' => $validated['tiktok_pixel_id'],
                'google_tag_id' => $validated['google_tag_id'],
            ]);

            // Handle banners
            if ($request->hasFile('banners')) {
                foreach ($shop->banners as $banner) {
                    if (Storage::exists($banner->banner_url)) {
                        Storage::delete($banner->banner_url);
                    }
                    $banner->delete();
                }
                foreach ($request->banners as $banner) {
                    $shopbannersName = 'shop-banners-' . \Str::slug($validated['name'], '-') . '-' . uniqid() . '.' . $banner->getClientOriginalExtension();
                    $shop->banners()->create([
                        'shop_id' => $shop->id,
                        'banner_url' => $banner->storeAs('public/shops', $shopbannersName),
                    ]);
                }
            }

            // Handle testimonials
            if ($request->hasFile('testimonials')) {
                foreach ($shop->testimonials as $testimonial) {
                    if (Storage::exists($testimonial->testimonial_url)) {
                        Storage::delete($testimonial->testimonial_url);
                    }
                    $testimonial->delete();
                }
                foreach ($request->testimonials as $testimonial) {
                    $shopTestimonialsName = 'shop-testimonials-' . \Str::slug($validated['name'], '-') . '-' . uniqid() . '.' . $testimonial->getClientOriginalExtension();
                    $shop->testimonials()->create([
                        'shop_id' => $shop->id,
                        'testimoni_url' => $testimonial->storeAs('public/shops', $shopTestimonialsName),
                    ]);
                }
            }

            return redirect()->route('settings.index')->with('success','Toko Berhasil diperbarui!.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}
