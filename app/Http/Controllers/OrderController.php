<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Validator;
use DataTables;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }

    public function getdata()
    {
        $orders = Order::with('products', 'user')->get();
        return DataTables::of($orders)->make(true);
    }

public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'products' => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    // Memulai transaksi database
    try {
        \DB::beginTransaction();

        // Buat order baru
        $order = Order::create([
            'user_id' => $request->user_id,
        ]);

        // Loop melalui setiap produk dalam pesanan
        foreach ($request->products as $product) {
            $quantity = $product['quantity'];
            $productModel = Product::findOrFail($product['id']);

            // Pastikan stok cukup untuk order
            if ($productModel->stok < $quantity) {
                // Rollback transaksi dan kembalikan pesan kesalahan
                \DB::rollBack();
                return response()->json(['error' => 'Insufficient stock for product: ' . $productModel->id], 400);
            }

            // Kurangi stok
            $productModel->decrement('stok', $quantity);

            // Hitung total harga
            $total_harga = $productModel->harga * $quantity;

            // Simpan ke tabel order_product
            $order->products()->attach($product['id'], ['quantity' => $quantity, 'total_harga' => $total_harga]);
        }

        // Commit transaksi jika semua operasi berhasil
        \DB::commit();

        // Response berhasil
        return response()->json(['message' => 'Order created successfully'], 201);
    } catch (\Exception $e) {
        // Rollback transaksi jika ada kesalahan
        \DB::rollBack();

        // Tangani kesalahan dan kembalikan pesan error
        return response()->json(['error' => 'Failed to create order: ' . $e->getMessage()], 500);
    }
}
}
