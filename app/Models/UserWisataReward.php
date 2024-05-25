<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWisataReward extends Model
{
    use HasFactory;
        protected $fillable = [
        'travel_package_id',
        'user_id',
    ];
        public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function travelPackage()
    {
        return $this->belongsTo(TravelPackage::class);
    }
}
