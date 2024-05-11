<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_product';
    // Relasi many-to-one dengan model Order 
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi many-to-one dengan model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
