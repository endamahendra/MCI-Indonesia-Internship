<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriArtikel;
use Validator;
use DataTables;

class KategoriArtikelController extends Controller
{
    public function index()
    {
        return view('kategori_artikels.index');
    }

    public function getdata()
    {
        $kategoriArtikels = KategoriArtikel::all();

        return DataTables::of($kategoriArtikels)->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $kategoriArtikel = KategoriArtikel::create([
            'nama_kategori' => $request->input('nama_kategori'),
            'deskripsi' => $request->input('deskripsi'),
        ]);

        return response()->json(['kategori_artikel' => $kategoriArtikel]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $kategoriArtikel = KategoriArtikel::find($id);
        if (!$kategoriArtikel) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $kategoriArtikel->nama_kategori = $request->input('nama_kategori');
        $kategoriArtikel->deskripsi = $request->input('deskripsi');
        $kategoriArtikel->save();

        return response()->json(['kategori_artikel' => $kategoriArtikel]);
    }

    public function show($id)
    {
        $kategoriArtikel = KategoriArtikel::find($id);

        if (!$kategoriArtikel) {
            return view('errors.404');
        }

        return response()->json(['kategori_artikel' => $kategoriArtikel]);
    }

    public function destroy($id)
    {
        KategoriArtikel::destroy($id);
        return response()->json([], 204);
    }
}

