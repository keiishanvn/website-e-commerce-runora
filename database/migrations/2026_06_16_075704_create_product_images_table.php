<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            // Menghubungkan id produk ke tabel products asli
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('gambar_detail'); // Kolom penyimpan nama berkas foto tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};