<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\WebAuthController;
use App\Models\BloodRequest;
use App\Http\Controllers\Web\UserWebController;
use Illuminate\Http\Request;

// --- PUBLIC HOME PAGE ---
Route::get('/', function (Request $request) {
    $query = BloodRequest::where('status', 'open');

    if ($request->filled('blood_type')) {
        $query->where('blood_type', $request->blood_type);
    }

    $requests = $query->latest()->paginate(6);
    return view('welcome', compact('requests'));
})->name('home');

Route::get('/request/{id}', function ($id) {
    $request = BloodRequest::with('requester')->findOrFail($id);
    return view('request_details', compact('request'));
})->name('request.show');

Route::get('/about', function () {
    return view('about');
})->name('about');

// 2. Admin Authentication
Route::get('/admin/login', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [WebAuthController::class, 'login']);
Route::post('/admin/logout', [WebAuthController::class, 'logout'])->name('logout');


// 3. Admin Dashboard (Protected)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::post('/requests/{id}/status', [AdminController::class, 'updateRequestStatus'])->name('admin.requests.status');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/users/{id}/verify', [AdminController::class, 'verifyUser'])->name('admin.users.verify');
});
Route::get('/donations', [AdminController::class, 'donations'])->name('admin.donations');
Route::post('/donations/{id}/status', [AdminController::class, 'updateDonationStatus'])->name('admin.donations.status');
Route::get('/requests', [AdminController::class, 'requests'])->name('admin.requests');
Route::delete('/requests/{id}', [AdminController::class, 'deleteRequest'])->name('admin.requests.delete');


// ==========================
// PUBLIC USER AUTH (Web)
// ==========================
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserWebController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserWebController::class, 'register']);
    
    Route::get('/login', [UserWebController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [UserWebController::class, 'login']);
});


// ==========================
// USER DASHBOARD (Protected)
// ==========================
Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/dashboard', [UserWebController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/donate', [UserWebController::class, 'showDonateForm'])->name('user.donate');
    Route::post('/donate', [UserWebController::class, 'storeDonation'])->name('user.donate.store');
    Route::get('/requests/create', [UserWebController::class, 'showCreateRequestForm'])->name('user.requests.create');
    Route::post('/requests', [UserWebController::class, 'storeRequest'])->name('user.requests.store');
});


// ==========================
// USER DASHBOARD (Protected)
// ==========================
Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/dashboard', [UserWebController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/requests/create', [UserWebController::class, 'showCreateRequestForm'])->name('user.requests.create');
    Route::post('/requests', [UserWebController::class, 'storeRequest'])->name('user.requests.store');
});
Route::post('/logout', [UserWebController::class, 'logout'])->name('user.logout');
Route::put('/portal/requests/{id}/complete', [UserWebController::class, 'markRequestAsComplete'])->name('user.requests.complete');
Route::get('/profile', [UserWebController::class, 'showProfile'])->name('user.profile');
Route::put('/profile', [UserWebController::class, 'updateProfile'])->name('user.profile.update');
Route::get('/donation/{id}/certificate', [UserWebController::class, 'certificate'])->name('user.certificate');

