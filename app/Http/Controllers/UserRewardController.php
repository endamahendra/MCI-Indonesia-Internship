<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserWisataReward;
use App\Models\OrderProduct;
use DataTables;

class UserRewardController extends Controller
{
    public function index()
    {
        return view('reward.index');
    }

    public function getdata()
    {
        $rewards = UserWisataReward::with('travelPackage', 'user')->get();

        $rewards->transform(function ($reward) {
            $userId = $reward->user_id;
            $totalBelanja = OrderProduct::whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->sum('total_harga');
            $reward->totalBelanja = $totalBelanja;

            return $reward;
        });
        return DataTables::of($rewards)->make(true);
    }
}
