<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BloodRequestController;
use App\Http\Controllers\Api\DonationInvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================
// 1. PUBLIC ROUTES (No Login Needed)
// ==========================

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Feed (Anyone can view requests)
Route::get('/requests', [BloodRequestController::class, 'index']);
Route::get('/requests/{id}', [BloodRequestController::class, 'show']);


// ==========================
// 2. PROTECTED ROUTES (Login Required)
// ==========================
Route::middleware('auth:sanctum')->group(function () {

    // User Profile
    Route::get('/me', [AuthController::class, 'me']);

    // Blood Requests (Create)
    Route::post('/requests', [BloodRequestController::class, 'store']);
    
    // Donation Invoices (Wallet)
    Route::post('/invoices', [DonationInvoiceController::class, 'store']); // Submit
    Route::get('/invoices', [DonationInvoiceController::class, 'index']);  // View List

});