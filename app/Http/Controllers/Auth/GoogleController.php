<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Exception;
use Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('social_id', $user->id)->first();
            $finduserByEmail = User::where('email', $user->email)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect('/')->with('success', 'Login success');
            } elseif ($finduserByEmail) {
                // If user exists by email, update their social_id and log them in
                $finduserByEmail->social_id = $user->id;
                $finduserByEmail->save();

                Auth::login($finduserByEmail);
                return redirect('/')->with('success', 'Login success');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => 'google',
                    'password' => bcrypt('my-google'),
                    'role' => 'user',
                ]);

                Auth::login($newUser);
                return redirect('/')->with('success', 'Login success');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
