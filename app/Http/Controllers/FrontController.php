<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Artikel;
use App\Models\CategoryProduct;
use App\Models\Category;
use App\Models\Comment;
use App\Models\KategoriArtikel;
use App\Models\Order;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
public function index()
{
    // Ambil 12 produk dengan jumlah terjual tertinggi
    $topProducts = Product::select('products.*')
                    ->join('order_product', 'products.id', '=', 'order_product.product_id')
                    ->groupBy('products.id')
                    ->orderByDesc(DB::raw('SUM(order_product.quantity)'))
                    ->take(12)
                    ->get();

    // Hitung jumlah terjual untuk 12 produk teratas
    $topProductIds = $topProducts->pluck('id')->toArray();
    $topProducts->each(function ($product) {
        $product->sold_quantity = $product->orderProducts()->sum('quantity');
    });

    // Jika kurang dari 12 produk terjual, tambahkan produk lain yang tidak terjual
    $remainingCount = 12 - count($topProducts);
    if ($remainingCount > 0) {
        $remainingProducts = Product::select('products.*')
                            ->leftJoin('order_product', 'products.id', '=', 'order_product.product_id')
                            ->whereNotIn('products.id', $topProductIds)
                            ->groupBy('products.id')
                            ->orderBy('products.id')
                            ->take($remainingCount)
                            ->get();

        // Hitung jumlah terjual untuk produk yang tidak termasuk dalam topProducts
        $remainingProducts->each(function ($product) {
            $product->sold_quantity = $product->orderProducts()->sum('quantity'); // Gunakan orderProducts() untuk mengakses relasi
        });
        $products = $topProducts->merge($remainingProducts);
    } else {
        $products = $topProducts;
    }

    // Ambil 5 kategori berdasarkan produk terlaris
    $categoryIds = CategoryProduct::whereIn('category_product.product_id', $topProductIds)
        ->select('category_id')
        ->leftJoin('order_product', 'category_product.product_id', '=', 'order_product.product_id')
        ->groupBy('category_id')
        ->orderByDesc(DB::raw('SUM(order_product.quantity)'))
        ->take(5)
        ->pluck('category_id')
        ->toArray();

    // Ambil data kategori sesuai urutan kategori terlaris
    $categories = Category::whereIn('id', $categoryIds)
        ->orderByRaw("FIELD(id, " . implode(',', $categoryIds) . ")")
        ->get();

    // Ambil 1 produk terlaris dari setiap kategori
    $categoryPhotos = [];
    foreach ($categories as $category) {
        $topProduct = Product::select('products.*')
                        ->join('category_product', 'products.id', '=', 'category_product.product_id')
                        ->join('order_product', 'products.id', '=', 'order_product.product_id')
                        ->where('category_product.category_id', $category->id)
                        ->groupBy('products.id')
                        ->orderByDesc(DB::raw('SUM(order_product.quantity)'))
                        ->first();

        if ($topProduct) {
            $categoryPhotos[$category->id] = $topProduct->photo;
        }
    }

    // Ambil 4 produk dengan diskon tertinggi
    $topDiscountedProducts = Product::where('diskon', '>', 0)
        ->orderByDesc('diskon')
        ->take(4)
        ->get();

    // Hitung berapa produk yang sudah diambil
    $countTopDiscounted = $topDiscountedProducts->count();

    // Jika produk dengan diskon kurang dari 4, ambil produk dengan harga termurah
    if ($countTopDiscounted < 4) {
        $remainingProducts = Product::whereNotIn('id', $topDiscountedProducts->pluck('id')->toArray())
            ->orderBy('harga')
            ->take(4 - $countTopDiscounted)
            ->get();
        // Gabungkan produk-produk dari kedua hasil query
        $penawaranTerbaik = $topDiscountedProducts->merge($remainingProducts);
    } else {
        $penawaranTerbaik = $topDiscountedProducts;
    }
    return view('front.index', compact('products', 'categories', 'categoryPhotos', 'penawaranTerbaik'));
}



public function product($deskripsi = null)
{
    if ($deskripsi) {
        $category = Category::where('deskripsi', $deskripsi)->firstOrFail();
        $products = Product::whereHas('categories', function ($query) use ($category) {
            $query->where('category_id', $category->id);
        })->with('ratings')->paginate(28);
        return view('front.product', compact('products', 'category'));
    } else {
        $products = Product::with('ratings')->paginate(28);
        return view('front.product', compact('products'));
    }
}

    public function userDashboard()
    {
        return view('front.dashboarduser');
    }


    public function forder()
    {
        return view('front.orderuser');
    }
    public function orderUser()
    {
        $orders = Order::with('products', 'user')
                        ->where('user_id', auth()->user()->id)
                        ->select(['id', 'user_id', 'status', 'jasa_kirim', 'resi', 'tanggal_pengiriman', 'tanggal_diterima','created_at']);

        return DataTables::of($orders)
                ->make(true);
    }
public function fnews($slug = null)
{
    $categories = KategoriArtikel::withCount('artikels')->get();
    if ($slug) {
        $kategori = KategoriArtikel::where('deskripsi', $slug)->firstOrFail();
        $artikels = Artikel::whereHas('kategoriartikels', function ($query) use ($kategori) {
            $query->where('kategori_artikel_id', $kategori->id);
        })->with('kategoriartikels', 'user')->get();
    } else {
        $artikels = Artikel::with('kategoriartikels', 'user')->get();
    }
    $artikel_terbaru = Artikel::latest('created_at')->take(4)->get();

    return view('front.news', compact('artikels', 'categories', 'artikel_terbaru'));
}


public function fnewsdetail($judul)
{
    $artikel = Artikel::with('kategoriartikels', 'user')->where('judul', $judul)->firstOrFail();
    $artikel_terbaru = Artikel::latest('created_at')->take(4)->get();
    $categories = KategoriArtikel::withCount('artikels')->get();
    $comments = Comment::where('artikel_id', $artikel->id)
                        ->whereNull('parent_id')
                        ->with('replies.user')
                        ->get();
    return view('front.newsdetaill', compact('artikel', 'categories', 'artikel_terbaru', 'comments'));
}


        public function searchnews(Request $request)
    {
        $keyword = $request->get('keyword');
        $artikels = Artikel::where('judul', 'like', "%$keyword%")
                           ->orWhere('konten', 'like', "%$keyword%")
                           ->get();
        $categories = KategoriArtikel::withCount('artikels')->get();
        $artikel_terbaru = Artikel::latest('created_at')->take(4)->get();
        return view('front.news', compact('artikels', 'keyword', 'categories', 'artikel_terbaru'));
    }

    public function fcomment(Request $request)
    {
        // Validasi input
        $request->validate([
            'content' => 'required',
            'artikel_id' => 'required|exists:artikels,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // Simpan komentar ke database
        Comment::create([
            'user_id' => auth()->id(),
            'artikel_id' => $request->artikel_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentar Anda berhasil ditambahkan.');
    }
}
