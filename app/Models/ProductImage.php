<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_images'; // Menegaskan nama tabelnya

    protected $fillable = [
        'product_id',
        'gambar_detail' // <─── WAJIB ADA BIAR MULTIPLE GAMBAR DETAIL LOLOS
    ];
}