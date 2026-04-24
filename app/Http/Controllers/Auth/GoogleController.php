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
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Find existing user by google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            // 2. If not found, find by email
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Link existing account with Google
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'auth_provider' => 'google',
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(),
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'auth_provider' => 'google',
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(),
                        'password' => Str::random(24), // random placeholder password
                    ]);
                }
            }

            Auth::login($user, true);

            return redirect()->route('dashboard'); // change this to your route
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}