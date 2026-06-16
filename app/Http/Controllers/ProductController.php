<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 1. MENAMPILKAN DATA PRODUK (DI BERANDA / KATALOG SHOP)
     */
    public function index(Request $request)
    {
        // Ambil parameter filter 'kategori' dari URL (Contoh: ?kategori=Apparel)
        $kategori = $request->input('kategori');

        // Buat query dasar mengambil data produk menggunakan Eloquent
        $query = Product::query();

        // Jika ada filter kategori yang masuk dan nilainya bukan 'all'
        if ($kategori && $kategori != 'all') {
            $query->where('kategori', $kategori);
        }

        // Eksekusi ambil data produknya
        $products = $query->get();

        // STRATEGI PEMBAGIAN HALAMAN:
        // Jika URL diakses melalui rute '/shop', maka lemparkan datanya ke shop.blade.php
        if ($request->is('shop*') || $request->has('kategori')) {
            return view('shop', compact('products'));
        }

        // Jika diakses biasa (Halaman Utama / Beranda), lemparkan ke welcome.blade.php
        return view('welcome', compact('products')); 
    }

    /**
     * 2. MELIHAT DETAIL SATU PRODUK TERTENTU
     */
    public function show($id)
    {
        // Cari data berdasarkan ID, jika tidak ada otomatis melempar error 404
        $product = Product::findOrFail($id);
        
        return view('product-detail', compact('product'));
    }
}