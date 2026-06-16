<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Taktik Penyelamat: Daftarkan semua kolom database kamu di sini!
    protected $fillable = [
        'name',
        'kategori',
        'deskripsi',
        'harga',
        'diskon',
        'stok',
        'gambar' // <─── WAJIB ADA BIAR GAMBAR UTAMA GA DI-BLOKIR LARAVEL
    ];
}