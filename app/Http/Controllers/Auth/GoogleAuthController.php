<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(32)),
                'auth_provider' => 'google',
                'usertype' => 'user',
                'status' => 'active',
                'kyc_status' => 'verified',
                'kyc_verified_at' => now(),
                'last_login_at' => now(),
            ]
        );

        Auth::login($user, true);

        return redirect('/user/dashboard')->with('success', 'Logged in with Google successfully!');
    }
}