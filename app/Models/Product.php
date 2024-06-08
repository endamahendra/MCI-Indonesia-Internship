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
        'diskon',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product');
    }
     public function users()
    {
        return $this->belongsToMany(User::class, 'ratings')->withPivot('rating')->withTimestamps();
    }
    public function orderProducts()
    {
    return $this->hasMany(OrderProduct::class);
    }
public function ratings()
{
    return $this->hasMany(Rating::class);
}


}
