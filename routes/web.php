<?php

use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogTagController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\LiveKonsulController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TrixUploadController;
use App\Http\Controllers\UserClaimController;
use App\Http\Controllers\VetConsultationController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;
Route::post('/update-region', [OrderController::class, 'updateRegion'])->name('update.region');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/detail/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/order/submit', [OrderController::class, 'submit'])->name('order.submit');
Route::get('/order/details', [OrderController::class, 'orderDetailsForm'])->name('order.details.form');
Route::post('/order/details', [OrderController::class, 'submitOrderDetails'])->name('order.details.submit');
Route::get('/order/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel');

Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/remove/{variation_id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/checkout/submit', [CartController::class, 'submitCheckout'])->name('cart.checkout.submit');
Route::get('/cart/checkout/cancel', [CartController::class, 'orderCancel'])->name('cart.checkout.cancel');
Route::post('/vet_consultations', [VetConsultationController::class, 'store'])->name('vet_consult.store');

Route::get('/voucher/{slug}', [UserClaimController::class, 'show'])->name('vouchers.claim.show');
Route::post('/vouchers/claim/{slug}', [UserClaimController::class, 'claim'])->name('vouchers.claim');
Route::get('/live-konsultasi', [LiveKonsulController::class, 'index'])->name('live.konsul');
Route::post('/live-konsultasi', [LiveKonsulController::class, 'store'])->name('live.konsul.store');

Route::prefix('blog')->group(function () {
    Route::get('', [BlogController::class, 'index'])->name('blog.index');
    Route::get('{slug}', [BlogController::class, 'show'])->name('blog.show');
});
Route::prefix('blog')->group(function () {
    Route::get('category/{slug}', [BlogController::class, 'showByCategory'])->name('blog.category');
    Route::get('tag/{slug}', [BlogController::class, 'showByTag'])->name('blog.tag');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::post('trix/upload', [TrixUploadController::class, 'uploadImage'])->name('trix.upload');
    Route::prefix('product')->group(function () {
        Route::get('', [ProductController::class, 'index'])->name('products.index');
        Route::get('create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('{slug}', [ProductController::class, 'update'])->name('products.update');
        Route::patch('{id}/variations', [ProductController::class, 'updateVariations'])->name('products.update.variations');
        Route::delete('{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::delete('delete-image/{id}', [ProductController::class, 'deleteImages'])->name('products.images.delete');
    });

    Route::prefix('marketplace')->group(function () {
        Route::get('', [MarketplaceController::class, 'index'])->name('marketplace-links.index');
        Route::post('store', [MarketplaceController::class, 'store'])->name('marketplace-links.create');
        Route::put('edit/{id}', [MarketplaceController::class, 'update'])->name('marketplace-links.update');
        Route::delete('delete/{id}', [MarketplaceController::class, 'destroy'])->name('marketplace-links.destroy');
        Route::post('reorder', [MarketplaceController::class, 'reorder'])
            ->name('marketplace-links.reorder');
        Route::get('ordered', [MarketplaceController::class, 'getOrdered'])
            ->name('marketplace-links.ordered');
    });

    Route::prefix('category')->group(function () {
        Route::get('', [CategoriesController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoriesController::class, 'store'])->name('categories.create');
        Route::put('categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
    });

    Route::prefix('customer-service')->group(function () {
        Route::get('customer-services', [CustomerServiceController::class, 'index'])->name('customer-service.index');
        Route::post('customer-services', [CustomerServiceController::class, 'create'])->name('customer-service.create');
        Route::put('customer-services/{id}', [CustomerServiceController::class, 'update'])->name('customer-service.update');
        Route::delete('customer-services/{id}', [CustomerServiceController::class, 'destroy'])->name('customer-service.destroy');
    });

    Route::prefix('leads')->group(function () {
        Route::get('', [LeadsController::class, 'index'])->name('leads.index');
        Route::delete('{id}', [LeadsController::class, 'destroy'])->name('leads.destroy');
        Route::get('/sync-to-sheets', [LeadsController::class, 'syncAllLeadsToGoogleSheets'])->name('leads.syncs');
    });

    Route::prefix('shop')->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings/{id}', [SettingController::class, 'update'])->name('settings.update');
        Route::post('banners/store/{id}', [SettingController::class, 'storeBanners'])->name('banners.store');
        Route::post('testimonials/store/{id}', [SettingController::class, 'storeTestimonials'])->name('testimonials.store');
        Route::delete('settings/delete-banner/{id}', [SettingController::class, 'destroyBanner'])->name('banners.destroy');
        Route::delete('settings/delete-testimonial/{id}', [SettingController::class, 'destroyTestimonial'])->name('testimonials.destroy');
    });

    Route::prefix('vouchers')->group(function () {
        Route::get('', [VoucherController::class, 'index'])->name('vouchers.index');
        Route::post('store', [VoucherController::class, 'store'])->name('vouchers.store');
        Route::put('edit/{id}', [VoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('delete/{id}', [VoucherController::class, 'destroy'])->name('vouchers.delete');
        Route::get('barcode/{slug}', [VoucherController::class, 'generateBarcode'])->name('vouchers.barcode');
        Route::get('copy/{slug}', [VoucherController::class, 'copyUrl'])->name('vouchers.copy');
    });
    Route::prefix('user-claims')->group(function () {
        Route::get('', [UserClaimController::class, 'index'])->name('user-claims.index');
        Route::get('/export', [UserClaimController::class, 'export'])->name('user-claims.export');
        Route::get('/sync-to-sheets', [UserClaimController::class, 'syncAllClaimsToGoogleSheets'])->name('sync.user.claims');
    });
    Route::prefix('blog')->group(function () {
        Route::get('', [BlogController::class, 'adminIndex'])->name('admin.blog.index');
        Route::get('create', [BlogController::class, 'create'])->name('admin.blog.create');
        Route::get('{id}', [BlogController::class, 'edit'])->name('admin.blog.edit');
        Route::post('store', [BlogController::class, 'store'])->name('admin.blog.store');
        Route::put('{id}', [BlogController::class, 'update'])->name('admin.blog.update');
        Route::delete('{id}', [BlogController::class, 'destroy'])->name('admin.blog.delete');
    });
    Route::prefix('blog-category')->group(function () {
        Route::get('', [BlogCategoryController::class, 'index'])->name('admin.blog-category.index');
        Route::post('', [BlogCategoryController::class, 'store'])->name('admin.blog-category.store');
        Route::put('{id}', [BlogCategoryController::class, 'update'])->name('admin.blog-category.update');
        Route::delete('{id}', [BlogCategoryController::class, 'delete'])->name('admin.blog-category.delete');
    });
    Route::prefix('blog-tag')->group(function () {
        Route::get('', [BlogTagController::class, 'index'])->name('admin.blog-tag.index');
        Route::post('', [BlogTagController::class, 'store'])->name('admin.blog-tag.store');
        Route::put('/{id}', [BlogTagController::class, 'update'])->name('admin.blog-tag.update');
        Route::delete('/{id}', [BlogTagController::class, 'delete'])->name('admin.blog-tag.delete');
    });
    Route::prefix('vet-consult')->group(function () {
        Route::get('', [VetConsultationController::class, 'index'])->name('vet-consult.index');
        Route::delete('/{id}', [VetConsultationController::class, 'destroy'])->name('vet-consult.destroy');
        Route::get('/sync-to-sheets', [VetConsultationController::class, 'syncAllVetConsultToGoogleSheets'])->name('vet-consult.syncs');
    });
    Route::prefix('live-konsul')->group(function () {
        Route::get('', [LiveKonsulController::class,'stored'])->name('admin.live-konsul');
        Route::delete('/{id}', [LiveKonsulController::class,'delete'])->name('admin.live-konsul.destroy');
    });
});

require __DIR__ . '/auth.php';
