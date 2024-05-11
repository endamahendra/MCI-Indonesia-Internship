<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Artikel;

class KategoriArtikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // Jika Anda ingin menetapkan relasi antara kategori artikel dan artikel
    public function artikels()
    {
        return $this->belongsToMany(Artikel::class, 'kategori_w_artikels');
    }
}
