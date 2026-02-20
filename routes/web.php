<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\WebAuthController; // For Admin Login
use App\Http\Controllers\Web\UserWebController; // For User Login
use App\Models\BloodRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Accessible by everyone)
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', function (Request $request) {
    $query = BloodRequest::where('status', 'open');

    if ($request->filled('blood_type')) {
        $query->where('blood_type', $request->blood_type);
    }

    $requests = $query->latest()->paginate(6);
    return view('welcome', compact('requests'));
})->name('home');

// Request Details
Route::get('/request/{id}', function ($id) {
    $request = BloodRequest::with('requester')->findOrFail($id);
    return view('request_details', compact('request'));
})->name('request.show');

// About Page
Route::get('/about', function () {
    return view('about');
})->name('about');


/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES (Login/Register)
|--------------------------------------------------------------------------
*/

// --- USER LOGIN & REGISTER ---
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserWebController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserWebController::class, 'register']);
    
    Route::get('/login', [UserWebController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [UserWebController::class, 'login']);
});

// --- ADMIN LOGIN (Separate Controller) ---
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [WebAuthController::class, 'showLoginForm'])->name('login'); // Admin Login Form
    Route::post('/admin/login', [WebAuthController::class, 'login'])->name('admin.login.submit');
});

// --- LOGOUT (Shared) ---
Route::post('/logout', [UserWebController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 3. USER PORTAL (Normal Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('user')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [UserWebController::class, 'dashboard'])->name('user.dashboard');
    
    // Profile Management
    Route::get('/profile', [UserWebController::class, 'showProfile'])->name('user.profile');
    Route::put('/profile', [UserWebController::class, 'updateProfile'])->name('user.profile.update');

    // Donation Logic
    Route::get('/donate', [UserWebController::class, 'showDonateForm'])->name('user.donate');
    Route::post('/donate', [UserWebController::class, 'storeDonation'])->name('user.donate.store');
    Route::get('/donation/{id}/certificate', [UserWebController::class, 'certificate'])->name('user.certificate');

    // Request Logic
    Route::get('/requests/create', [UserWebController::class, 'showCreateRequestForm'])->name('user.requests.create');
    Route::post('/requests', [UserWebController::class, 'storeRequest'])->name('user.requests.store');
    Route::put('/requests/{id}/complete', [UserWebController::class, 'markRequestAsComplete'])->name('user.requests.complete');
});


/*
|--------------------------------------------------------------------------
| 4. ADMIN PANEL (Super Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Request Management
    Route::get('/requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::post('/requests/{id}/status', [AdminController::class, 'updateRequestStatus'])->name('admin.requests.status');
    Route::delete('/requests/{id}', [AdminController::class, 'deleteRequest'])->name('admin.requests.delete');

    // Donation Invoice Management
    Route::get('/donations', [AdminController::class, 'donations'])->name('admin.donations');
    Route::post('/donations/{id}/status', [AdminController::class, 'updateDonationStatus'])->name('admin.donations.status');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/verify', [AdminController::class, 'verifyUser'])->name('admin.users.verify');
    Route::post('/users/{id}/toggle', [AdminController::class, 'toggleBlockUser'])->name('admin.users.toggle'); // Block/Unblock
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete'); // Delete
    Route::post('/users/{id}/update-blood', [AdminController::class, 'updateUserBloodType'])->name('admin.users.update_blood');

    // Configuration & Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // Localization Settings
    Route::get('/settings/localization', [AdminController::class, 'localization'])->name('admin.settings.localization');
    Route::post('/settings/localization', [AdminController::class, 'updateLocalization'])->name('admin.settings.localization.update');
});