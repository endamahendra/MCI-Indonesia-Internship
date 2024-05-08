<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class Front
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mendapatkan token dari header Authorization
        $token = $request->bearerToken();

        // Jika token tidak ada, kembalikan respons 401 (Unauthorized)
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            // Mendapatkan data pengguna dari token
            $userData = JWTAuth::setToken($token)->getPayload()->toArray();

            // Memeriksa apakah pengguna dengan ID yang sesuai dengan token ada
            $user = User::find($userData['sub']);

            // Jika pengguna tidak ditemukan, kembalikan respons 401 (Unauthorized)
            if (!$user) {
                return response()->json(['message' => 'Invalid token'], 401);
            }

            // Memeriksa peran pengguna
            if ($user->role === 'user') {
                // Jika pengguna memiliki peran 'user', lanjutkan dengan permintaan
                return $next($request);
            } else {
                // Jika pengguna memiliki peran lain selain 'user', kembalikan respons 401 (Unauthorized)
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            // Tangani kesalahan yang mungkin terjadi saat memverifikasi token
            return response()->json(['message' => 'Invalid token'], 401);
        }
    }

}
