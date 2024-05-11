<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
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
    // Mengambil user_id dari Auth
    $token = $request->bearerToken();
    $userData = JWTAuth::setToken($token)->getPayload()->toArray();
    $user_id = $userData['sub'];

    // Melakukan query untuk mendapatkan data pengguna
    $user = User::findOrFail($user_id);

    // Memeriksa apakah alamat dan nomor telepon tersedia
    if (!$user->alamat || !$user->no_hp) {
        return response()->json(['error' => 'Please provide complete address and phone number'], 400);
    }

    // Validasi input
    $validator = Validator::make($request->all(), [
        'products' => 'required|array', // Ganti 'product_id' menjadi 'products'
        'products.*.id' => 'required|exists:products,id', // Pastikan setiap produk yang dimasukkan valid
        'products.*.quantity' => 'required|integer|min:1', // Validasi kuantitas produk
    ]);

    // Memulai transaksi database
    try {
        \DB::beginTransaction();

        // Buat order baru
        $order = Order::create([
            'user_id' => $user_id,
        ]);

        // Loop melalui setiap produk dalam pesanan
        foreach ($request->products as $product) {
            $quantity = $product['quantity']; // Perbaiki pengambilan jumlah produk
            $productModel = Product::findOrFail($product['id']);

            // Pastikan stok cukup untuk order
            if ($productModel->stok < $quantity) {
                // Rollback transaksi dan kembalikan pesan kesalahan
                \DB::rollBack();
                return response()->json(['error' => 'Insufficient stock for product: ' . $productModel->id], 400);
            }

            // Kurangi stok
            $productModel->decrement('stok', $quantity);

            // Hitung total harga untuk produk
            $total_harga = $productModel->harga * $quantity;

            // Simpan ke tabel order_product
            $order->products()->attach($product['id'], ['quantity' => $quantity, 'total_harga' => $total_harga]);
        }

        // Hitung total harga order
        $total_order_harga = $order->products()->sum('total_harga');
        $order->update(['harga' => $total_order_harga]);

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
