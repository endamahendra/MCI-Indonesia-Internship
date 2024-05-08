<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view ('dashboard');
    }

    public function getdata(Request $request)
{
    // Mendapatkan user yang sedang login
    $user = Auth::user();

    // Memeriksa apakah pengguna telah login
    if ($user) {
        // Jika iya, kembalikan respons JSON dengan informasi pengguna dan token
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'token' => $user->api_token,
            ],
        ]);
    } else {
        // Jika tidak, kembalikan pesan kesalahan
        return response()->json(['error' => 'User not found'], 404);
    }
}
}
