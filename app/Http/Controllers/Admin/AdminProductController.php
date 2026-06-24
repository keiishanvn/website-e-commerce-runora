<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; 

class AdminProductController extends Controller
{
    // 1. MENAMPILKAN DAFTAR PRODUK (DENGAN FILTER & PENCARIAN)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $urutkan = $request->input('urutkan');

        $query = DB::table('products');
        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($urutkan == 'termurah') {
            $query->orderBy('harga', 'asc');
        } elseif ($urutkan == 'termahal') {
            $query->orderBy('harga', 'desc');
        } else {
            $query->orderBy('id', 'desc'); 
        }

        $products = $query->get();

        return view('admin.products', compact('products', 'search', 'kategori', 'urutkan'));
    }

    // 2. MENAMPILKAN HALAMAN FORM TAMBAH PRODUK BARU
    public function create()
    {
        return view('admin.product-create');
    }

    // 3. MEMPROSES PENYIMPANAN DATA PRODUK BARU + UPLOAD GAMBAR
    public function store(Request $request)
    {

        $request->validate([
            'nama'      => 'nullable|string|max:255',
            'name'      => 'nullable|string|max:255', 
            'kategori'  => 'required|string',
            'harga'     => 'required|numeric|min:0',
            'diskon'    => 'nullable|numeric|min:0|max:100',
            'deskripsi' => 'nullable|string',
            'stok'      => 'required|integer|min:0',
            'gambar'    => 'required|file|max:2048', // Menggunakan 'file' agar lebih toleran terhadap sistem operasi
            'images.*'  => 'nullable|file|max:2048'
        ]);

        $namaHeroImage = null;

if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            $file = $request->file('gambar');
            $namaHeroImage = time() . '_hero_' . str_replace(' ', '_', $file->getClientOriginalName());

            $file->move(base_path('public/products'), $namaHeroImage);
        }

        $namaProdukFix = $request->input('name') ?? $request->input('nama') ?? 'Produk Tanpa Nama';

        $productId = DB::table('products')->insertGetId([
            'name'        => $namaProdukFix,
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            'diskon'      => $request->diskon ?? 0,
            'deskripsi'   => $request->deskripsi,
            'stok'        => $request->stok,
            'gambar'      => $namaHeroImage, 
            'created_at'  => now(),
            'updated_at'  => now()
        ]);

// Proses Upload Multiple Gambar Detail
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $detailFile) {
                if ($detailFile->isValid()) {
                    $namaDetailImage = time() . '_detail_' . $index . '_' . str_replace(' ', '_', $detailFile->getClientOriginalName());
                    
                    // FIX: Ganti ke base_path juga di sini
                    $detailFile->move(base_path('public/products'), $namaDetailImage);

                    DB::table('product_images')->insert([
                        'product_id'    => $productId, 
                        'gambar_detail' => $namaDetailImage,
                        'created_at'    => now(),
                        'updated_at'    => now()
                    ]);
                }
            }
        }

        return redirect()->route('admin.produk.index')->with('success', 'Produk baru berhasil ditambahkan ke dalam katalog toko!');
    }

    // 5. MENGHAPUS PRODUK + MEMBERSIHKAN FILE GAMBAR LAMA
    public function destroy($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        
        if ($product && $product->gambar) {
            Storage::delete('public/products/' . $product->gambar);
        }

        $detailImages = DB::table('product_images')->where('product_id', $id)->get();
        foreach ($detailImages as $img) {
            Storage::delete('public/products/' . $img->gambar_detail);
        }
        
        DB::table('product_images')->where('product_id', $id)->delete();
        DB::table('products')->where('id', $id)->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus dari katalog toko!');
    }

    // 6. MENAMPILKAN FORM EDIT PRODUK DENGAN DATA LAMA
    public function edit($id)
    {
        $product = DB::table('products')->where('id', $id)->first();

        if (!$product) {
            return redirect()->route('admin.produk.index')->with('error', 'Data produk tidak ditemukan!');
        }

        return view('admin.product-edit', compact('product'));
    }

    // 7. MEMPROSES UPDATE DATA PRODUK KE DATABASE
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string',
            'harga'     => 'required|numeric|min:0',
            'diskon'    => 'nullable|numeric|min:0|max:100',
            'deskripsi' => 'nullable|string',
            'stok'      => 'required|integer|min:0',
            'gambar'    => 'nullable|file|max:2048', 
            'images.*'  => 'nullable|file|max:2048'
        ]);

        $product = DB::table('products')->where('id', $id)->first();
        $namaHeroImage = $product->gambar;

        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            if ($product->gambar) {
                Storage::delete('public/products/' . $product->gambar);
            }

            $file = $request->file('gambar');
            $namaHeroImage = time() . '_hero_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/products', $namaHeroImage);
        }

        DB::table('products')->where('id', $id)->update([
            'name'        => $request->nama,
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            'diskon'      => $request->diskon ?? 0,
            'deskripsi'   => $request->deskripsi,
            'stok'        => $request->stok,
            'gambar'      => $namaHeroImage,
            'updated_at'  => now()
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $detailFile) {
                if ($detailFile->isValid()) {
                    $namaDetailImage = time() . '_detail_' . $index . '_' . str_replace(' ', '_', $detailFile->getClientOriginalName());
                    $detailFile->storeAs('public/products', $namaDetailImage);

                    DB::table('product_images')->insert([
                        'product_id'    => $id,
                        'gambar_detail' => $namaDetailImage,
                        'created_at'    => now(),
                        'updated_at'    => now()
                    ]);
                }
            }
        }

        return redirect()->route('admin.produk.index')->with('success', 'Data produk RUNORA berhasil diperbarui!');
    }
}