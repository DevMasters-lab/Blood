<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validate the input
        $credentials = $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        // 2. Attempt the login
        if (Auth::attempt($credentials)) {
            
            // 3. STRICT CHECK: Only allow 'admin' usertype
            if (Auth::user()->usertype !== 'admin') {
                Auth::logout(); // Log them out immediately
                return back()->withErrors([
                    'phone' => 'Access denied. You do not have administrator privileges.',
                ]);
            }

            // 4. Success: Send to the Master Sidebar Dashboard
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // 5. Fail: Invalid phone or password
        return back()->withErrors([
            'phone' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}