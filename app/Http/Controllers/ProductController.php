<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\Category;
use App\Models\CategoryProduct;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function index(){
        $categorys = Category::all();
        return view('products.index', compact('categorys'));
    }

public function getdata()
{
    $products = Product::get();
    foreach ($products as $product) {
        $ratings = $product->users()->pluck('rating');
        $ratingcategories = $product->categories()->pluck('nama_kategori');
        $totalRatings = $ratings->count();
        $maxRating = 5;
        $averageRating = $ratings->avg();
        $product->average_rating = $averageRating;
        $product->total_ratings = $totalRatings;
        $product->max_rating = $maxRating;
        $product->nama_kategori = $ratingcategories;
    }

    return DataTables::of($products)
        ->addColumn('rating', function ($product) {
            if ($product->average_rating) {
                return number_format($product->average_rating, 1) . '/' . $product->max_rating . ' of ' . $product->total_ratings . ' Pelanggan';
            } else {
                return 'Belum ada rating';
            }
        })
        ->make(true);
}


public function store(Request $request)
{
    // Validasi data yang diterima dari request
    $validator = Validator::make($request->all(), [
        'sku' => 'required|unique:products',
        'deskripsi' => 'required',
        'harga' => 'required',
        'stok' => 'required',
        'diskon' => 'required',
        'category_id' => 'required|array',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto
    ]);

    // Jika validasi gagal, kembalikan pesan kesalahan
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Pastikan ada file foto dalam request
    if ($request->hasFile('photo')) {
        // Simpan foto ke direktori yang ditentukan
        $photo = $request->file('photo');
        $photoName = time() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('images/product'), $photoName);
    } else {
        // Jika tidak ada file foto dalam request, kembalikan pesan kesalahan
        return response()->json(['error' => 'Photo not found in request'], 400);
    }

    // Buat produk dan simpan ke basis data
    $product = Product::create([
        'sku' => $request->input('sku'),
        'deskripsi' => $request->input('deskripsi'),
        'harga' => $request->input('harga'),
        'stok' => $request->input('stok'),
        'diskon' => $request->input('diskon'),
        'photo' => 'images/product/' . $photoName, // Simpan nama foto ke dalam basis data
    ]);

    // Simpan relasi produk dan kategori
    $product->categories()->attach($request->input('category_id'));

    // Berikan respons sukses dengan data produk yang baru dibuat
    return response()->json(['product' => $product]);
}


public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'sku' => 'required|unique:products,sku,'.$id,
        'deskripsi' => 'required',
        'harga' => 'required',
        'stok' => 'required',
        'diskon' => 'required',
        'category_id' => 'required|array',
        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto (opsional)
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $product = Product::find($id);

    if (!$product) {
        return response()->json(['error' => 'Data not found'], 404);
    }

    if ($request->hasFile('photo')) {
        // Hapus foto lama jika ada
        if ($product->photo) {
            unlink(public_path( $product->photo));
        }
        // Simpan foto baru
        $photo = $request->file('photo');
        $photoName = time() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('images/product'), $photoName);
        $product->photo = 'images/product/' . $photoName;
    }

    // Perbarui data produk
    $product->sku = $request->input('sku');
    $product->deskripsi = $request->input('deskripsi');
    $product->harga = $request->input('harga');
    $product->stok = $request->input('stok');
    $product->diskon = $request->input('diskon');
    $product->save();

    // Simpan relasi produk dan kategori
    $product->categories()->sync($request->input('category_id'));

    return response()->json(['product' => $product]);
}

public function show($id)
{
           $product = Product::with('categories')->find($id);

        if (!$product) {
            return response()->json(['error' => 'Data not found'], 404);
        }

    return response()->json(['product' => $product]);
}

public function destroy($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['error' => 'Data not found'], 404);
    }

    // Hapus foto jika ada
    if ($product->photo) {
        unlink(public_path($product->photo));
    }

    // Hapus produk dari database
    $product->delete();

    return response()->json([], 204);
}

    public function search(Request $request)
    {
        $query = $request->input('query');
        $sortBy = $request->input('sort_by', 'harga');
        $order = $request->input('order', 'asc');

        if (!$query) {
            return response()->json(['error' => 'Search query is required'], 400);
        }

        $products = Product::where('sku', 'LIKE', "%$query%")
            ->orWhere('deskripsi', 'LIKE', "%$query%")
            ->get();

        foreach ($products as $product) {
            $ratings = $product->users()->pluck('rating');
            $totalRatings = $ratings->count();
            $averageRating = $ratings->avg();
            $product->average_rating = $averageRating ?? 0;
            $product->total_ratings = $totalRatings;
        }

        if ($products->isEmpty()) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        // Sorting products based on the sort_by and order parameters
        if ($sortBy === 'harga' || $sortBy === 'average_rating') {
            if ($order === 'asc' || $order === 'desc') {
                $products = $products->sortBy($sortBy, SORT_REGULAR, $order === 'desc');
            } else {
                return response()->json(['error' => 'Invalid order parameter'], 400);
            }
        } else {
            return response()->json(['error' => 'Invalid sort_by parameter'], 400);
        }

        return response()->json(['products' => $products->values()]);
    }
}
