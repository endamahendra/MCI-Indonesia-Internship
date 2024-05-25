<?php
namespace App\Models;
use App\Models\Product;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'link',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function OrderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity', 'total_harga');
    }
}
