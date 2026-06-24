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

// 2. Halaman Katalog Lengkap / Shop
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');

// 3. Detail Produk saat diklik oleh pembeli
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');


// ROUTE REGULER YANG MEMBUTUHKAN LOGIN (PEMBELI / USER)
Route::middleware('auth')->group(function () {
    
    // Dashboard Setelah Login (Khusus Pembeli bawaan Laravel Breeze)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Pengaturan Akun / Profile Pembeli Bawaan Laravel Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // EKOSISTEM KERANJANG BELANJA, DASHBOARD AKUN & CHECKOUT (FIXED VIA CARTCONTROLLER)
    // A. Tampilan Halaman Keranjang Belanja Utama
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    
    // B. API AJAX: Tambah ke Keranjang
   Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'store'])->name('cart.add');
    
    // C. Jalur Instan "Beli Sekarang" dari halaman detail produk
    Route::post('/beli-sekarang', [CartController::class, 'beliSekarang'])->name('cart.buy_now');
    
    // D. API AJAX: Update Jumlah Item Plus/Minus
    Route::match(['POST', 'PATCH'], '/keranjang/{id}', [CartController::class, 'update'])->name('cart.update');
    
    // E. Hapus satu item dari keranjang belanja
    Route::delete('/keranjang/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // F. Proses Validasi Item Terpilih saat klik tombol Checkout di keranjang
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // G. Tampilan Halaman Formulir Alamat Pengiriman / Pembayaran setelah lolos Checkout
    Route::get('/checkout', [CartController::class, 'checkoutIndex'])->name('checkout.index');
    
    // FIX PROSES CHECKOUT: Dialihkan ke CartController agar proses simpan riwayat & hapus cart lancar jaya
    Route::post('/checkout/proses', [CartController::class, 'process'])->name('checkout.process');
    
    // H. DASHBOARD AKUN: Jalur Halaman Riwayat Pesanan Pembeli (Figma Layout)
    Route::get('/riwayat-pesanan', [CartController::class, 'riwayatIndex'])->name('riwayat.pesanan');
    
    // I. DASHBOARD AKUN: Jalur Halaman Pengaturan Akun (Figma Layout)
    Route::get('/pengaturan-akun', [CartController::class, 'settingsIndex'])->name('user.settings');
    Route::post('/pengaturan-akun/update', [CartController::class, 'settingsUpdate'])->name('user.settings.update');
}); 


// ROUTE GROUP KHUSUS ADMIN
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman Utama Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen CRUD Data Produk
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

// Shortcut Pembersih Session
Route::get('/clear-session-sekar', function() {
    session()->forget('checkout_items');
    return "Session checkout lama berhasil dibuang! Sekarang silakan kembali ke katalog atau keranjang dan coba klik tombolnya lagi.";
});

require __DIR__.'/auth.php';