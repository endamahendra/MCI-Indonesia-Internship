<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        return view('front.index');
    }
    public function product()
    {
            $products = Product::with('ratings')->get();
            return view('front.product', compact('products'));
    }
}
