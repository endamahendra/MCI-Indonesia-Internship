<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\Category;
use App\Models\CategoryProduct;
use Validator;
use DataTables;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function index(){
        $categorys = Category::all();
                return view('products.index', compact('categorys'));
    }

    public function getdata()
    {
        $products = Product::with('categories')->get();
        return DataTables::of($products)->make(true);
    }

public function store(Request $request)
{
    // Validasi data yang diterima dari request
    $validator = Validator::make($request->all(), [
        'sku' => 'required|unique:products',
        'deskripsi' => 'required',
        'harga' => 'required',
        'stok' => 'required',
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
        $photo->move(public_path('photos'), $photoName);
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
        'photo' => $photoName, // Simpan nama foto ke dalam basis data
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
        'category_id' => 'required|array',
    ]);
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }
    $product = Product::find($id);
    if (!$product) {
        return response()->json(['error' => 'Data not found'], 404);
    }
    if ($request->hasFile('photo')) {
        if ($product->photo) {
            unlink(public_path('photos/' . $product->photo));
        }
        $photo = $request->file('photo');
        $photoName = time() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('photos'), $photoName);
        $product->photo = $photoName;
    }
    $product->sku = $request->input('sku');
    $product->deskripsi = $request->input('deskripsi');
    $product->harga = $request->input('harga');
    $product->stok = $request->input('stok');
    $product->save();
    $product->categories()->sync($request->input('category_id'));
    return response()->json(['product' => $product]);
}

        public function show($id)
        {
            $product = Product::find($id);

            if (!$product) {
                return view('errors.404');
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
        unlink(public_path('photos/' . $product->photo));
    }

    // Hapus produk dari database
    $product->delete();

    return response()->json([], 204);
}


}
