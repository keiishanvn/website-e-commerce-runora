<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController; 
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// ========================================================
// ROUTE PUBLIC / KONSUMEN (TIDAK WAJIB LOGIN)
// ========================================================

// 1. Halaman Utama / Dashboard Public
Route::get('/', [ProductController::class, 'index'])->name('home');

// 2. Halaman Katalog Lengkap / Shop
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');

// 3. Detail Produk saat diklik oleh pembeli
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');


// ========================================================
// ROUTE REGULER YANG MEMBUTUHKAN LOGIN (PEMBELI / USER)
// ========================================================
Route::middleware('auth')->group(function () {
    
    // Dashboard Setelah Login (Khusus Pembeli bawaan Laravel Breeze)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Pengaturan Akun / Profile Pembeli
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // EKOSISTEM KERANJANG BELANJA & CHECKOUT (DINAMIS VIA AJAX & SESSION)
    // A. Tampilan Halaman Keranjang Belanja Utama (Tailwind page)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    
    // B. API AJAX: Tambah ke Keranjang (Mendukung fungsi store kamu)
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
    
    // C. API AJAX: Update Jumlah Item Plus/Minus
    Route::patch('/keranjang/{id}', [CartController::class, 'update'])->name('cart.update');
    
    // D. Hapus satu item dari keranjang belanja
    Route::delete('/keranjang/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // E. FIX TAMBAHAN: Proses Validasi Item Terpilih saat klik tombol Checkout
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // F. FIX TAMBAHAN: Tampilan Form Alamat Pengiriman setelah lolos Checkout
    Route::get('/checkout', [CartController::class, 'checkoutIndex'])->name('checkout.index');
}); 


// ========================================================
// ROUTE GROUP KHUSUS ADMIN (RUNORA MANAGEMENT)
// ========================================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman Utama Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen CRUD Data Produk (Urutan Terstruktur)
    Route::get('/produk', [AdminProductController::class, 'index'])->name('produk.index');        
    Route::get('/produk/create', [AdminProductController::class, 'create'])->name('produk.create');  
    Route::post('/produk', [AdminProductController::class, 'store'])->name('produk.store');      
    Route::get('/produk/{id}/edit', [AdminProductController::class, 'edit'])->name('produk.edit');  
    Route::put('/produk/{id}', [AdminProductController::class, 'update'])->name('produk.update');  
    Route::delete('/produk/{id}', [AdminProductController::class, 'destroy'])->name('produk.destroy'); 
    
    // Distribusi Produk & Pengaturan Sistem Admin
    Route::get('/distribusi', [AdminDashboardController::class, 'distribusi'])->name('distribusi');
    Route::get('/pengaturan', [AdminDashboardController::class, 'pengaturan'])->name('pengaturan');
    Route::put('/pengaturan/update', [AdminDashboardController::class, 'pengaturanUpdate'])->name('pengaturan.update');
});

require __DIR__.'/auth.php';