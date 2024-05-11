<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\KategoriWArtikel;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
// use Carbon\Carbon;

class ArtikelController extends Controller
{
    public function index()
    {
        $kategoriartikels = KategoriArtikel::all();
        return view('artikels.index', compact('kategoriartikels'));
    }
    public function getdata()
    {
        $artikels = Artikel::with('kategoriartikels', 'user')->get();
        return DataTables::of($artikels)->make(true);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategoriartikel_id' => 'required|array',
            'judul' => 'required',
            'konten' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

        try {
            \DB::beginTransaction();

            $image = $request->file('photo');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/artikel'), $imageName);

            $artikel = Artikel::create([
                'user_id' => Auth::id(),
                'judul' => $request->judul,
                'konten' => $request->konten,
                'photo' => 'images/artikel/' . $imageName,
                // 'tanggal' => Carbon::now(),
            ]);

            $artikel->kategoriartikels()->attach($request->input('kategoriartikel_id'));

            \DB::commit();
            return response()->json(['message' => 'Artikel created successfully'], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to create Artikel: ' . $e->getMessage()], 500);
        }
    }
}
