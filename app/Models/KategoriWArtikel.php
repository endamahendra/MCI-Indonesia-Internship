<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = 'kategori_w_artikels';

    public function artikel()
    {
        return $this->belongsTo(Artikel::class);
    }

    public function kategoriartikel()
    {
        return $this->belongsTo(KategoriArtikel::class);
    }
}
