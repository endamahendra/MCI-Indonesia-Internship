<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriArtikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // Jika Anda ingin menetapkan relasi antara kategori artikel dan artikel
    public function articles()
    {
        return $this->belongsToMany(Artikel::class);
    }
}
