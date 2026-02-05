<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // 1. Register (Public)
    public function register(Request $request)
    {
        // Validate Inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users', // Main ID
            'password' => 'required|string|min:6',     // Password for MVP
            'id_number' => 'required|string',          // KYC
            'id_photo' => 'required|file|mimes:jpg,jpeg,png|max:5120', // 5MB Max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 400);
        }

        // Create User
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'id_number' => $request->id_number,
            'kyc_status' => 'pending', // Default is pending
            'status' => 'active'
        ]);

        // Upload ID Photo
        if ($request->hasFile('id_photo')) {
            $path = $request->file('id_photo')->store('proofs/kyc', 'public');
            $user->idPhoto()->create([
                'file_type' => 'id_photo',
                'path' => $path,
                'original_name' => $request->file('id_photo')->getClientOriginalName(),
            ]);
        }

        // Generate Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully. KYC is pending.',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    // 2. Login (Public)
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('phone', $request->phone)->first();

        // Check Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone or password'
            ], 401);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'kyc_status' => $user->kyc_status, // Useful for App Logic
            ]
        ]);
    }

    // 3. Get My Profile (Protected)
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load('idPhoto')
        ]);
    }
}