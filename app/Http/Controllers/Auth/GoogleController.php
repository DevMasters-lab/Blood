<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        session()->forget('url.intended');

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'auth_provider' => 'google',
                        'avatar' => $googleUser->getAvatar() ?: $user->avatar,
                        'email_verified_at' => $user->email_verified_at ?: now(),
                        'last_login_at' => now(),
                    ]);
                } else {
                    $user = User::create([
                        'name' => $googleUser->getName() ?: 'Google User',
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'auth_provider' => 'google',
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(),
                        'last_login_at' => now(),
                        'password' => Str::random(24),
                        'usertype' => 'user',
                        'status' => 'active',
                    ]);
                }
            } else {
                $user->update([
                    'avatar' => $googleUser->getAvatar() ?: $user->avatar,
                    'last_login_at' => now(),
                ]);
            }

            Auth::login($user, true);

            session()->forget('url.intended');

            if ($user->usertype === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/dashboard');
        } catch (\Throwable $e) {
            return redirect('/login')->with('error', 'Google login failed. Please try again.');
        }
    }
}