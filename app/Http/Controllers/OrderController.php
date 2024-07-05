<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rating;
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
    try {
        $request->validate([
            'status' => 'required|in:Diproses,Dikirim,Dibatalkan,Diterima,Selesai,Selesai dan Sudah Diulas',
            'resi' => 'nullable|string', // Validasi nomor resi opsional
            'jasa_kirim' => 'nullable|string' // Validasi jasa kirim opsional
        ]);

        $order = Order::findOrFail($orderId);
        $order->status = $request->status;

        if ($request->status === 'Dibatalkan') {
            $order->resi = null;
            $order->jasa_kirim = null;
            $order->tanggal_pengiriman = null; // Reset tanggal_pengiriman jika status diubah menjadi 'Dibatalkan'
        } elseif ($request->status === 'Dikirim') {
            if ($request->has('resi')) {
                $order->resi = $request->resi;
            }
        
            if ($request->has('jasa_kirim')) {
                $order->jasa_kirim = $request->jasa_kirim;
            }
            $order->tanggal_pengiriman = now(); // Simpan tanggal_pengiriman saat status menjadi 'Dikirim'
        } elseif ($request->status === 'Diterima') {
            $order->tanggal_diterima = now(); // Simpan tanggal_diterima saat status menjadi 'Diterima'
        }
        
        $order->save();

        return response()->json(['message' => 'Order status updated successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to update order status', 'error' => $e->getMessage()], 500);
    }
}





    public function show($id)
    {
        $order = Order::with('products')->findOrFail($id);
        // dd($order)->products;
        return view('orders.show', compact('order'));
    }

public function fstore(Request $request)
{
    $user_id = Auth::id();
    $user = User::findOrFail($user_id);
    if (!$user->alamat || !$user->no_hp) {
            return redirect()->route('update.user', $user_id)
            ->with('error', 'Order gagal. Silakan lengkapi profil Anda terlebih dahulu.');
    }

    $products = json_decode($request->input('products'), true);
    $validator = Validator::make(['products' => $products], [
        'products' => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
    try {
        \DB::beginTransaction();
        $order = Order::create([
            'user_id' => $user_id,
        ]);
        $total_order_harga = 0;

        foreach ($products as $product) {
            $quantity = $product['quantity'];
            $productModel = Product::findOrFail($product['id']);
            if ($productModel->stok < $quantity) {
                \DB::rollBack();
                return redirect()->back()->with('error', 'Insufficient stock for product: ' . $productModel->id);
            }

            // Simpan diskon saat produk dipesan
            $product_price = $productModel->harga;
            $product_discount = $productModel->diskon / 100;
            $discounted_price = $product_price - ($product_price * $product_discount);

            $total_harga = $discounted_price * $quantity;

            $order->products()->attach($product['id'], [
                'quantity' => $quantity,
                'total_harga' => $total_harga,
                'diskon' => $productModel->diskon, // Simpan diskon pada saat produk dipesan
            ]);

            $productModel->decrement('stok', $quantity);

            $total_order_harga += $total_harga;
        }

        $order->update(['harga' => $total_order_harga]);
        $this->checkRewards($user_id);

        // Pesan WhatsApp
        $message = "Selamat siang, saya {$user->name} user dengan id {$user_id} ingin memesan produk:\n\n";
        $i = 1;

        foreach ($order->products as $product) {
            $subtotal = $product->pivot->quantity * $product->pivot->total_harga;
            $message .= "{$i}. {$product->sku} ({$product->pivot->quantity} x Rp " . number_format($product->harga, 0, ',', '.') . " - Diskon: " . ($product->pivot->diskon) . "%) = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            $i++;
        }

        $message .= "\nTotal: Rp " . number_format($total_order_harga, 0, ',', '.') . "\n";
        $message .= "Terima kasih, saya berharap pesanan saya segera diproses!";

        $waNumber = "+6281958898182";
        $waMessage = urlencode($message);
        $waLink = "https://wa.me/{$waNumber}?text={$waMessage}";
        $order->update(['link' => $waLink]);

        \DB::commit();

        // Redirect with success message and WhatsApp link
        return redirect()->route('orders.sukses', $order->id)
           ->with('success', 'Pesanan berhasil dibuat. Klik <a href="' . $waLink . '" target="_blank">di sini</a> untuk mengonfirmasi pesanan Anda melalui WhatsApp.');
    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->back()->with('error', 'Failed to create order: ' . $e->getMessage());
    }
}



public function sukses(Order $order)
{
    return view('front.ordersukses', compact('order'));
}
 public function showRatingForm($orderId)
    {
        $order = Order::with('products')->findOrFail($orderId);
        return view('front.rating', compact('order'));
    }

public function submitRating(Request $request, $orderId)
    {
        $request->validate([
            'ratings.*.product_id' => 'required|exists:products,id',
            'ratings.*.rating' => 'required|integer|min:1|max:5',
            'ratings.*.review' => 'nullable|string',
        ]);

        foreach ($request->ratings as $ratingData) {
            // Update jika sudah ada rating sebelumnya, atau buat baru jika belum ada
            Rating::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $ratingData['product_id'],
                ],
                [
                    'rating' => $ratingData['rating'],
                    'comment' => $ratingData['review'] ?? null,
                ]
            );
        }

        $order = Order::findOrFail($orderId);
        $order->status = 'Selesai dan Sudah Diulas';
        $order->save();

        return redirect()->back()->with('success', 'Terima kasih atas ulasannya!');
    }
}
