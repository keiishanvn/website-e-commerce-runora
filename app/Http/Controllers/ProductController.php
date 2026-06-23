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

        // STRATEGI PEMBAGIAN & PEMBATASAN HALAMAN:
        
        // JALUR A: Jika URL diakses melalui rute '/shop' atau sedang memfilter kategori
        if ($request->is('shop*') || $request->has('kategori')) {
            // Ambil SEMUA produk tanpa batasan untuk katalog lengkap
            $products = $query->get();
            return view('shop', compact('products'));
        }

        // JALUR B: Jika diakses via Halaman Utama / Beranda (welcome.blade.php)
        // 🌟 REVISI: Dibatasi maksimal hanya 8 produk terbaru saja agar grid Figma tetap rapi
        $products = $query->latest()->take(8)->get();
        
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