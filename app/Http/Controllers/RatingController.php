<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\User;
use App\Models\Product;
use Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with('user', 'product')->get();
        return response()->json(['ratings' => $ratings], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5', // Misalnya, skala rating dari 1 hingga 5
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $token = $request->bearerToken();
        $userData = JWTAuth::setToken($token)->getPayload()->toArray();
        $user_id = $userData['sub'];

        $user = User::findOrFail($user_id);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Cek apakah pengguna sudah memberikan rating untuk produk tertentu
        $existingRating = Rating::where('user_id', $user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingRating) {
            return response()->json(['error' => 'User has already rated this product'], 400);
        }

        $rating = new Rating();
        $rating->user_id = $user_id;
        $rating->product_id = $request->product_id;
        $rating->rating = $request->rating;
        $rating->save();

        return response()->json(['message' => 'Rating created successfully', 'rating' => $rating], 201);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5', // Misalnya, skala rating dari 1 hingga 5
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Cek otentikasi pengguna
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Temukan rating yang akan diperbarui
        $rating = Rating::find($id);

        // Pastikan pengguna hanya dapat memperbarui rating mereka sendiri
        if ($rating && $rating->user_id != $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Perbarui rating
        if ($rating) {
            $rating->rating = $request->rating;
            $rating->save();
            return response()->json(['message' => 'Rating updated successfully', 'rating' => $rating], 200);
        } else {
            return response()->json(['error' => 'Rating not found'], 404);
        }
    }

    public function destroy($id)
    {
        // Cek otentikasi pengguna
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Temukan rating yang akan dihapus
        $rating = Rating::find($id);

        // Pastikan pengguna hanya dapat menghapus rating mereka sendiri
        if ($rating && $rating->user_id != $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Hapus rating
        if ($rating) {
            $rating->delete();
            return response()->json(['message' => 'Rating deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Rating not found'], 404);
        }
    }
    public function getByProduct($product_id)
    {
        $ratings = Rating::where('product_id', $product_id)->with('user')->get();

        return response()->json(['ratings' => $ratings], 200);
    }

}
