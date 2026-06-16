<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order; 
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil hitungan total produk dari tabel mu
        $totalProduk = DB::table('products')->count();

        // 2. Hitung stok berdasarkan indikator (Asumsi field database bernama 'stock')
        // Jika belum ada field stock di database, isi nilai default 0 dulu agar tidak error
        // $stokMenipis = DB::table('products')->where('stock', '>', 0)->where('stock', '<', 10)->count();
        // $stokTersedia = DB::table('products')->where('stock', '>=', 10)->count();
        // $stokHabis = DB::table('products')->where('stock', 0)->count();
        $stokMenipis =  0;
        $stokTersedia =  0;
        $stokHabis =  0;

        // 3. Query Asli menggunakan Model Eloquent
        $totalPenjualan = Order::where('status', 'completed')->sum('total_harga');
        $totalPesanan = Order::count();

        // 4. Data Dummy untuk Top 5 Produk Terlaris
        $topProduk = collect([
            (object) ['nama_produk' => 'Nike Air Zoom Pegasus 40', 'total_terjual' => 45, 'total_pendapatan' => 90000000],
            (object) ['nama_produk' => 'Adidas Ultraboost Light', 'total_terjual' => 12, 'total_pendapatan' => 30000000]
        ]);

        // 5. Data Dummy Log Aktivitas
        $aktivitasTerbaru = collect([
            (object) ['tipe' => 'produk_ditambah', 'deskripsi' => 'Menambahkan produk Running Shoes Airmax', 'user_name' => 'Admin Runora', 'waktu' => '5 menit yang lalu'],
            (object) ['tipe' => 'pesanan_dikirim', 'deskripsi' => 'Mengubah status pesanan #INV-9022 menjadi Dikirim', 'user_name' => 'Admin Runora', 'waktu' => '1 jam yang lalu']
        ]);

        // 6. Data Dummy Grafik Statistik Bulanan
        $grafikPenjualan = [
            ['bulan' => 'Jan', 'total' => 15000000],
            ['bulan' => 'Feb', 'total' => 28000000],
            ['bulan' => 'Mar', 'total' => 42000000],
            ['bulan' => 'Apr', 'total' => 35000000],
            ['bulan' => 'Mei', 'total' => 60000000],
            ['bulan' => 'Jun', 'total' => 120000000],
        ];

        // 7. Data Dummy Status Pesanan Lingkaran
        $statusPesanan = (object) [
            'menunggu_pembayaran' => 3,
            'diproses' => 4,
            'dikirim' => 2,
            'selesai' => 15
        ];

        return view('admin.dashboard', compact(
            'totalProduk', 'stokMenipis', 'stokTersedia', 'stokHabis',
            'totalPenjualan', 'totalPesanan', 'topProduk', 
            'aktivitasTerbaru', 'grafikPenjualan', 'statusPesanan'
        ));
    }
}