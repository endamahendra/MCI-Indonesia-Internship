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

            if ($finduser)
            {
                Auth::login($finduser);
                return redirect('/');

            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => 'google',
                    'password' => bcrypt('my-google'),
                    'role' => 'user',
                ]);
                $newUser->save();
                Auth::login($newUser);
                return redirect('/')->with('success', 'Login success');
                // return Redirect::route('')->with('success', 'Login success');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
