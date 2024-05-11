<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class AuthUserController extends Controller
{
    public function register(Request $request)
 {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Buat token JWT
        $token = JWTAuth::fromUser($user);
        $user->api_token = $token;
        $user->save();
        // event(new User($user));

        // Return JSON response
        return response()->json([
            'message' => 'Registration successful.',
            'user' => $user,
            // 'token' => $token,
        ]);
    }

    public function login(Request $request)
{
    // Validasi input dari request
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Ambil kredensial dari request
    $credentials = $request->only('email', 'password');

    // Cari pengguna berdasarkan alamat email
    $user = User::where('email', $credentials['email'])->first();

    // Jika tidak ada pengguna dengan alamat email tersebut
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Periksa apakah kata sandi cocok
    if (!Hash::check($credentials['password'], $user->password)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Jika autentikasi berhasil, buat token JWT
    $token = JWTAuth::fromUser($user);

    // Simpan token ke dalam kolom api_token pada tabel pengguna
    $user->api_token = $token;
    $user->save();

    // Kirim token sebagai respon
    return response()->json(['api_token' => $token]);
}
}
