<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        Session::put('previous_url', url()->previous());
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        $user->api_token = $token;
        $user->save();
        if ($user->role === 'admin') {
            return redirect()->intended('/dashboard');
        }

        $previousUrl = Session::pull('previous_url');

        // Jika tidak ada URL sebelumnya, arahkan ke URL default
        if (!$previousUrl) {
            return redirect('/');
        }
        return redirect()->to($previousUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
