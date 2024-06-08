<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\TravelPackage;
use App\Models\UserWisataReward;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
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
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Memulai transaksi database
        try {
            \DB::beginTransaction();

            // Buat order baru
            $order = Order::create([
                'user_id' => $user_id,
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

                // Hitung total harga untuk produk
                $total_harga = ($productModel->harga * $quantity) - (($productModel->harga * ($productModel->diskon / 100)) * $quantity);

                // Simpan ke tabel order_product
                $order->products()->attach($product['id'], ['quantity' => $quantity, 'total_harga' => $total_harga]);
            }

            // Hitung total harga order
            $total_order_harga = $order->products()->sum('total_harga');
            $order->update(['harga' => $total_order_harga]);

            // Cek dan update rewards
            $this->checkRewards($user_id);

            // Pesan WhatsApp
            $message = "Selamat siang, saya ingin memesan produk:\n\n";
            $i = 1;
            $totalGrand = 0;

            foreach ($order->products as $product) {
                $subtotal = $product->pivot->quantity * $product->harga;
                $totalGrand += $subtotal;
                $message .= "{$i}. {$product->deskripsi} ({$product->pivot->quantity} x Rp {$product->harga}) = Rp {$subtotal}\n";
                $i++;
            }

            $message .= "\nTotal: Rp {$totalGrand}\n";
            $message .= "Terima kasih, saya berharap pesanan saya segera diproses!";

            $waNumber = "+6281958898182";
            $waMessage = urlencode($message);
            $waLink = "https://wa.me/{$waNumber}?text={$waMessage}";

            // Simpan link WhatsApp ke dalam kolom 'link' pada tabel order
            $order->update(['link' => $waLink]);

            // Commit transaksi jika semua operasi berhasil
            \DB::commit();

            // Response berhasil
            return response()->json(['message' => 'Order created successfully', 'product' => $order->products, 'grand_total_harga' => $total_order_harga], 201);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            \DB::rollBack();

            // Tangani kesalahan dan kembalikan pesan error
            return response()->json(['error' => 'Failed to create order: ' . $e->getMessage()], 500);
        }
    }

    private function checkRewards($userId)
    {
        // Hitung total belanja user
        $totalBelanja = OrderProduct::whereHas('order', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('total_harga');

        // Ambil semua wisata
        $travels = TravelPackage::all();

        foreach ($travels as $travel) {
            if ($totalBelanja >= $travel->target) {
                // Cek apakah user sudah mendapatkan reward ini
                $existingReward = UserWisataReward::where('user_id', $userId)->where('travel_package_id', $travel->id)->first();
                if (!$existingReward) {
                    // Simpan reward baru untuk user
                    UserWisataReward::create([
                        'user_id' => $userId,
                        'travel_package_id' => $travel->id,
                    ]);
                }
            }
        }
    }

    public function getBestSellingProducts()
    {
        $bestSellingProducts = OrderProduct::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $productIds = $bestSellingProducts->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get();

        $bestSellingProducts = $bestSellingProducts->map(function ($item) use ($products) {
            $product = $products->where('id', $item->product_id)->first();
            $item->product = $product;
            return $item;
        });

        return response()->json(['best_selling_products' => $bestSellingProducts]);
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:diproses,selesai,batal'
        ]);

        $order = Order::findOrFail($orderId);
        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Order status updated successfully']);
    }

    public function show($id)
    {
        $order = Order::with('products')->findOrFail($id);

        return view('orders.show', compact('order'));
    }

}
