<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\UserWebController;
use App\Http\Controllers\Web\NotificationController;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\GoogleAuthController;


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

// Frontend Language Switch (EN/KM)
Route::get('/language/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'km'], true)) {
        abort(404);
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('language.switch');


/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES (Login/Register)
|--------------------------------------------------------------------------
*/

// --- USER LOGIN & REGISTER ---
Route::middleware('guest:web')->group(function () {
    Route::get('/register', [UserWebController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserWebController::class, 'register']);
    
    Route::get('/login', [UserWebController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [UserWebController::class, 'login']);
    Route::get('/auth/google/redirect', [UserWebController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [UserWebController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::post('/auth/telegram/callback', [UserWebController::class, 'handleTelegramCallback'])->name('auth.telegram.callback');
});

// --- ADMIN LOGIN (Separate Controller) ---
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [WebAuthController::class, 'showLoginForm'])->name('login'); // Admin Login Form
    Route::post('/admin/login', [WebAuthController::class, 'login'])->name('admin.login.submit');
});

// --- LOGOUT (Shared) ---
Route::post('/logout', [UserWebController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [WebAuthController::class, 'logout'])->name('admin.logout');


/*
|--------------------------------------------------------------------------
| 3. DEVICE & NOTIFICATION ROUTES (Guests & Users)
|--------------------------------------------------------------------------
*/

// 🌟 NEW: Device Registration API (Triggered by Alpine.js in the background)
Route::post('/api/register-device', [UserWebController::class, 'registerDevice']);

// 🌟 NEW: Fetch dynamic notifications for the Alpine.js dropdown
Route::get('/api/notifications', [NotificationController::class, 'fetchDynamic']);
Route::post('/api/notifications/read-all', [NotificationController::class, 'markAllDynamic']);

// Click to read a specific notification (Moved OUT of auth so guests can click it!)
Route::get('/notifications/{id}/read', [NotificationController::class, 'readAndRedirect'])->name('notifications.read');

// Standard Blade Form "Mark All Read" (Still used by the Admin Panel)
Route::middleware(['auth:web,admin'])->group(function () {
    Route::post('/notifications/read-all-admin', [NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');
});


/*
|--------------------------------------------------------------------------
| 4. USER PORTAL (Normal Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('user')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [UserWebController::class, 'dashboard'])->name('user.dashboard');
    
    // Profile Management
    Route::get('/profile', [UserWebController::class, 'showProfile'])->name('user.profile');
    Route::put('/profile', [UserWebController::class, 'updateProfile'])->name('user.profile.update');

    // Invoice Wallet
    Route::get('/wallet', [UserWebController::class, 'wallet'])->name('user.wallet');

    // Donation Logic
    Route::get('/donate', [UserWebController::class, 'showDonateForm'])->name('user.donate');
    Route::post('/donate', [UserWebController::class, 'storeDonation'])->name('user.donate.store');
    Route::get('/donation/{id}/certificate', [UserWebController::class, 'certificate'])->name('user.certificate');

    // Request Logic 
    Route::get('/requests/create', [UserWebController::class, 'showCreateRequestForm'])->name('user.requests.create');
    Route::post('/requests', [UserWebController::class, 'storeRequest'])->name('user.requests.store');
    Route::put('/requests/{id}/complete', [UserWebController::class, 'markRequestAsComplete'])->name('user.requests.complete');

    // Blood Requested History
    Route::get('/requests/history', [UserWebController::class, 'requestHistory'])->name('user.requests.history');
});


/*
|--------------------------------------------------------------------------
| 5. ADMIN PANEL (Super Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->group(function () {
    // Admin Language Switch (isolated from frontend)
    Route::get('/language/{locale}', function ($locale) {
        if (!in_array($locale, ['en', 'km'], true)) {
            abort(404);
        }

        session(['admin_locale' => $locale]);

        return redirect()->back();
    })->name('admin.language.switch');

    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Request Management
    Route::get('/requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::get('/requests/history', [AdminController::class, 'requestHistory'])->name('admin.requests.history');
    Route::post('/requests/{id}/status', [AdminController::class, 'updateRequestStatus'])->name('admin.requests.status');
    Route::delete('/requests/{id}', [AdminController::class, 'deleteRequest'])->name('admin.requests.delete');

    // Old Donation Records Management
    Route::get('/donations', [AdminController::class, 'donations'])->name('admin.donations');
    Route::post('/donations/{id}/status', [AdminController::class, 'updateDonationStatus'])->name('admin.donations.status');

    // Verify Invoices
    Route::get('/invoices', [AdminController::class, 'verifyInvoices'])->name('admin.invoices');
    Route::post('/invoices/{id}/approve', [AdminController::class, 'approveInvoice'])->name('admin.invoices.approve');
    Route::post('/invoices/{id}/reject', [AdminController::class, 'rejectInvoice'])->name('admin.invoices.reject');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');       
    Route::post('/users/{id}/verify', [AdminController::class, 'verifyUser'])->name('admin.users.verify');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::post('/users/{id}/toggle', [AdminController::class, 'toggleBlockUser'])->name('admin.users.toggle'); // Block/Unblock
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('admin.users.reset_password');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete'); // Delete
    Route::post('/users/{id}/update-blood', [AdminController::class, 'updateUserBloodType'])->name('admin.users.update_blood');

    // Configuration & Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // Admin Management Additions (KYC & Reports)
    Route::get('/kyc', [AdminController::class, 'kyc'])->name('admin.kyc');
    Route::post('/kyc/{id}/approve', [AdminController::class, 'approveKyc'])->name('admin.kyc.approve');
    Route::post('/kyc/{id}/reject', [AdminController::class, 'rejectKyc'])->name('admin.kyc.reject');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // Admin Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Role Management
    Route::resource('roles', \App\Http\Controllers\Web\RoleController::class)->names('admin.roles');
});

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
    ->name('auth.google.redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('auth.google.callback');