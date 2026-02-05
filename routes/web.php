<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\WebAuthController;
use App\Models\BloodRequest;
use App\Http\Controllers\Web\UserWebController;

Route::get('/', function () {
    // Fetch open requests, sorted by urgency
    $requests = BloodRequest::where('status', 'open')
                ->orderBy('needed_date', 'asc')
                ->with('requester') // Load user data
                ->paginate(9);
                
    return view('welcome', compact('requests'));
});
// 2. Admin Authentication
Route::get('/admin/login', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [WebAuthController::class, 'login']);
Route::post('/admin/logout', [WebAuthController::class, 'logout'])->name('logout');

// 3. Admin Dashboard (Protected)
Route::middleware('auth')->prefix('admin')->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Manage Requests
    Route::get('/requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::post('/requests/{id}/status', [AdminController::class, 'updateRequestStatus'])->name('admin.requests.status');

    // Verify KYC
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/verify', [AdminController::class, 'verifyUser'])->name('admin.users.verify');
});

// ==========================
// PUBLIC USER AUTH (Web)
// ==========================
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserWebController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserWebController::class, 'register']);
    
    Route::get('/login', [UserWebController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [UserWebController::class, 'login']);
});

Route::post('/logout', [UserWebController::class, 'logout'])->name('user.logout');

// ==========================
// USER DASHBOARD (Protected)
// ==========================
Route::middleware('auth')->prefix('portal')->group(function () {
    Route::get('/dashboard', [UserWebController::class, 'dashboard'])->name('user.dashboard');
    
    // Create Request
    Route::get('/requests/create', [UserWebController::class, 'showCreateRequestForm'])->name('user.requests.create');
    Route::post('/requests', [UserWebController::class, 'storeRequest'])->name('user.requests.store');
});

// Manage Donations
Route::get('/donations', [AdminController::class, 'donations'])->name('admin.donations');
Route::post('/donations/{id}/status', [AdminController::class, 'updateDonationStatus'])->name('admin.donations.status');

// ==========================
// USER DASHBOARD (Protected)
// ==========================
Route::middleware('auth')->prefix('portal')->group(function () {
    Route::get('/dashboard', [UserWebController::class, 'dashboard'])->name('user.dashboard');
    
    // --- ADD THESE TWO MISSING LINES ---
    Route::get('/donate', [UserWebController::class, 'showDonateForm'])->name('user.donate');
    Route::post('/donate', [UserWebController::class, 'storeDonation'])->name('user.donate.store');
    // -----------------------------------

    // Create Request
    Route::get('/requests/create', [UserWebController::class, 'showCreateRequestForm'])->name('user.requests.create');
    Route::post('/requests', [UserWebController::class, 'storeRequest'])->name('user.requests.store');
});