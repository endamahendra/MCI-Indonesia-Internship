<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\Comment;
use App\Models\User;
use App\Models\KategoriArtikel;
use App\Models\KategoriWArtikel;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use DataTables;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;

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
        'judul' => 'required|unique:artikels,judul',
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
        'judul' => [
            'required',
            Rule::unique('artikels', 'judul')->ignore($id, 'id')
        ],
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

public function commentStore(Request $request)
{
    try {
        $token = $request->bearerToken();
        $userData = JWTAuth::setToken($token)->getPayload()->toArray();
        $user_id = $userData['sub'];

        $user = User::findOrFail($user_id);

        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'artikel_id' => 'exists:artikels,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $comment = Comment::create([
            'user_id' => $user_id,
            'artikel_id' => $request->artikel_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar Anda berhasil ditambahkan.',
            'comment' => $comment,
        ], Response::HTTP_CREATED);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to store comment.',
            'error' => $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

public function showDiskusi(Request $request)
{
    try {
        $comments = Comment::whereNull('artikel_id')->get();
        if ($comments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No comments found for this article.',
            ], Response::HTTP_NOT_FOUND);
        }

        // Response JSON berhasil dengan data komentar
        return response()->json([
            'success' => true,
            'comments' => $comments,
        ], Response::HTTP_OK);

    } catch (\Exception $e) {
        // Tangkap dan tangani exception jika terjadi
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch comments.',
            'error' => $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
}
