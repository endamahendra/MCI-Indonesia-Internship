<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah pengguna telah login melalui sesi
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Jika pengguna telah login dan perannya adalah 'admin', lanjutkan dengan permintaan
            return $next($request);
        } else {
            // Jika pengguna belum login atau perannya bukan 'admin', kembalikan respons 401 (Unauthorized)
            abort(401, 'Unauthorized.');
        }
    }
}

