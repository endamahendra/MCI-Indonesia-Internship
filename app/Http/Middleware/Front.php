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
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $userData = JWTAuth::setToken($token)->getPayload()->toArray();
            $user = User::find($userData['sub']);
            if (!$user) {
                return response()->json(['message' => 'Invalid token'], 401);
            }
            if ($user->role === 'user') {
                return $next($request);
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }
    }

}
