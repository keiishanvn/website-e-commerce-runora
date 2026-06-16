<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; 

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Sepatu Lari Running Adidas Ultra',
            // Pastikan nama kolom di bawah ini sesuai dengan file migration kamu ya!
            // Kalau di migration pakai Bahasa Inggris (description, price, discount_price), sesuaikan di sini.
            'deskripsi' => 'Sepatu lari super nyaman dengan teknologi boost terbaru, cocok untuk marathon maupun daily running.',
            'harga' => 1500000,
            'diskon' => 1200000, 
            'stok' => 15,
            'gambar' => 'sepatu-adidas.jpg',
        ]);

    }
}