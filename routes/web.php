<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController; 
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// ROUTE PUBLIC / KONSUMEN (TIDAK WAJIB LOGIN)
// 1. Halaman Utama / Dashboard Public
Route::get('/', [ProductController::class, 'index'])->name('home');

// 2. Halaman Katalog Lengkap / Shop (FIX: Ditaruh di sini agar bisa diakses umum tanpa login)
Route::get('/shop', [ProductController::class, 'index'])->name('shop');

// 3. Detail Produk saat diklik oleh pembeli
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');


// ROUTE BAWAAN LARAVEL BREEZE (PENGGUNA / PEMBELI)
// Dashboard Setelah Login (Khusus Pembeli)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Pengaturan Akun / Profile (Hanya yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
}); 


// ROUTE GROUP KHUSUS ADMIN 
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Manajemen CRUD Data Produk (Urutan Terstruktur)
    Route::get('/produk', [AdminProductController::class, 'index'])->name('produk.index');          // Daftar Produk
    Route::get('/produk/create', [AdminProductController::class, 'create'])->name('produk.create');  // Form Tambah
    Route::post('/produk', [AdminProductController::class, 'store'])->name('produk.store');          // Proses Simpan
    Route::get('/produk/{id}/edit', [AdminProductController::class, 'edit'])->name('produk.edit');  // Form Edit
    Route::put('/produk/{id}', [AdminProductController::class, 'update'])->name('produk.update');    // Proses Update
    Route::delete('/produk/{id}', [AdminProductController::class, 'destroy'])->name('produk.destroy'); // Hapus Produk

});

require __DIR__.'/auth.php';