<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'deskripsi',
        'tanggal',
        'target',
        'photo',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_wisata_rewards');
    }

}
