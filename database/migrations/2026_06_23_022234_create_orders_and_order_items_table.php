<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat Tabel Orders Utama
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->decimal('total_harga', 12, 2);
            $table->string('metode_pembayaran');
            $table->string('nama_penerima');
            $table->text('alamat_pengiriman');
            $table->string('status')->default('Diproses'); // Pilihan: Diproses, Dikirim, Selesai
            $table->timestamps();
        });

        // 2. Buat Tabel Order Items (Detail Produk Terbeli)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('kuantitas');
            $table->decimal('harga_satuan', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};