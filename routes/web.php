<?php
// routes/web.php
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/admin/whatsapp-status',[AdminController::class,'whatsappStatus'])->name('admin.whatsapp.status');
});
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/',[AdminController::class,'index'])->name('dashboard');
    Route::get('/whatsapp-setup', [AdminController::class, 'whatsappSetup'])->name('admin.whatsapp-setup');
});

require __DIR__ . '/auth.php';
