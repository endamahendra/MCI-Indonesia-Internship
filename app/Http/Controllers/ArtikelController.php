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
        public function show($id)
        {
            $artikel = Artikel::with('kategoriartikels')->find($id);

            if (!$artikel) {
                return view('errors.404');
            }
            return response()->json(['artikel' => $artikel]);
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
        return response()->json([
            'data' => null,
            'code' => 400,
            'message' => 'Validation Error',
            'error' => $validator->errors()
        ], 400);
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
        ]);

        $artikel->kategoriartikels()->attach($request->input('kategoriartikel_id'));

        \DB::commit();
        return response()->json([
            'data' => $artikel,
            'code' => 201,
            'message' => 'Artikel created successfully',
        ], 201);
    } catch (\Exception $e) {
        \DB::rollBack();
        return response()->json([
            'data' => null,
            'code' => 500,
            'message' => 'Failed to create Artikel',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'kategoriartikel_id' => 'required|array',
        'judul' => 'required',
        'konten' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'data' => null,
            'code' => 400,
            'message' => 'Validation Error',
            'error' => $validator->errors()
        ], 400);
    }

    try {
        \DB::beginTransaction();

        $artikel = Artikel::findOrFail($id);

        $artikel->judul = $request->judul;
        $artikel->konten = $request->konten;

        if ($request->hasFile('photo')) {
            if ($artikel->photo) {
                unlink(public_path($artikel->photo));
            }
            $image = $request->file('photo');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/artikel'), $imageName);
            $artikel->photo = 'images/artikel/' . $imageName;
        }

        $artikel->save();

        $artikel->kategoriartikels()->sync($request->input('kategoriartikel_id'));

        \DB::commit();
        return response()->json([
            'data' => $artikel,
            'code' => 200,
            'message' => 'Artikel updated successfully',
        ], 200);
    } catch (\Exception $e) {
        \DB::rollBack();
        return response()->json([
            'data' => null,
            'code' => 500,
            'message' => 'Failed to update Artikel',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function destroy($id)
{
    $artikel = Artikel::find($id);

    if (!$artikel) {
        return response()->json([
            'data' => null,
            'code' => 404,
            'message' => 'Data not found',
            'error' => 'Data not found'
        ], 404);
    }

    if ($artikel->photo) {
        unlink(public_path($artikel->photo));
    }

    $artikel->delete();

    return response()->json([
        'data' => null,
        'code' => 204,
        'message' => 'Artikel deleted successfully',
    ], 204);
}


}
