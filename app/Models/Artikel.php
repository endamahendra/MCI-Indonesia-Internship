<?php
namespace App\Models;
use App\Models\KategoriArtikel;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'konten',
        'photo',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function kategoriwartikel()
    {
        return $this->belongsTo(KategoriWArtikel::class);
    }

    public function kategoriartikels()
    {
        return $this->belongsToMany(KategoriArtikel::class, 'kategori_w_artikels');
    }
}
