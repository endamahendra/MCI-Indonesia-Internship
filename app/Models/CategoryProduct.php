<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = 'category_product';

    // Definisikan relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // Definisikan relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
