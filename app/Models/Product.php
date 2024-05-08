<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'deskripsi',
        'harga',
        'stok',
        'photo',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product');
    }
}
