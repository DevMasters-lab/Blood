<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationInvoice;
use App\Models\ProofFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Throwable;

class UserWebController extends Controller
{
    // --- Auth Screens ---
    public function showLoginForm() {
        return view('auth.user_login');
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    public function redirectToGoogle()
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret') || ! config('services.google.redirect')) {
            return redirect()->route('user.login')->withErrors([
                'identifier' => 'Google sign-in is not configured yet. Add your Google OAuth credentials first.',
            ]);
        }

        /** @var GoogleProvider $googleDriver */
        $googleDriver = Socialite::driver('google');

        return $googleDriver->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            /** @var GoogleProvider $googleDriver */
            $googleDriver = Socialite::driver('google');

            $googleUser = $googleDriver->user();
        } catch (Throwable $exception) {
            return redirect()->route('user.login')->withErrors([
                'identifier' => 'Google sign-in failed. Please try again.',
            ]);
        }

        $email = $googleUser->getEmail();

        $user = User::where('google_id', $googleUser->getId())
            ->when($email, fn ($query) => $query->orWhere('email', $email))
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                'email' => $email,
                'password' => Str::password(32),
                'usertype' => 'user',
                'kyc_status' => 'verified',
                'status' => 'active',
                'google_id' => $googleUser->getId(),
                'auth_provider' => 'google',
            ]);
        } else {
            $user->forceFill([
                'name' => $user->name ?: ($googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User'),
                'email' => $user->email ?: $email,
                'google_id' => $googleUser->getId(),
                'auth_provider' => $user->auth_provider ?: 'google',
            ])->save();
        }

        return $this->completeLogin($request, $user);
    }

    public function handleTelegramCallback(Request $request)
    {
        if (! config('services.telegram.bot_name') || ! config('services.telegram.bot_token')) {
            return redirect()->route('user.login')->withErrors([
                'identifier' => 'Telegram sign-in is not configured yet. Add your Telegram bot settings first.',
            ]);
        }

        $payload = $request->validate([
            'id' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'photo_url' => 'nullable|url|max:2048',
            'auth_date' => 'required|integer',
            'hash' => 'required|string',
        ]);

        if (! $this->isValidTelegramLogin($payload)) {
            return redirect()->route('user.login')->withErrors([
                'identifier' => 'Telegram sign-in could not be verified. Please try again.',
            ]);
        }

        $name = trim(implode(' ', array_filter([
            $payload['first_name'] ?? null,
            $payload['last_name'] ?? null,
        ]))) ?: ($payload['username'] ?? 'Telegram User');

        $user = User::where('telegram_id', $payload['id'])->first();

        if (! $user) {
            $user = User::create([
                'name' => $name,
                'password' => Str::password(32),
                'usertype' => 'user',
                'kyc_status' => 'verified',
                'status' => 'active',
                'telegram_id' => $payload['id'],
                'telegram_username' => $payload['username'] ?? null,
                'auth_provider' => 'telegram',
            ]);
        } else {
            $user->forceFill([
                'name' => $user->name ?: $name,
                'telegram_username' => $payload['username'] ?? $user->telegram_username,
                'auth_provider' => $user->auth_provider ?: 'telegram',
            ])->save();
        }

        return $this->completeLogin($request, $user);
    }

    // --- Auth Logic ---
    public function login(Request $request) {
        $request->validate([
            'identifier' => 'required',  // Can be phone or email
            'password' => 'required',
        ]);

        $identifier = $request->input('identifier');
        
        // Try to find user by phone or email
        $user = User::where('phone', $identifier)
                     ->orWhere('email', $identifier)
                     ->first();

        if ($user && $user->password && Hash::check($request->password, $user->password)) {
            return $this->completeLogin($request, $user);
        }

        return back()->withErrors(['identifier' => 'Invalid phone/email or password.']);
    }

    public function register(Request $request) {
        // 1. Validate all fields
        $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|unique:users|regex:/^0[0-9]{8,9}$/',
            'id_number' => 'required|string|max:50',
            'id_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', 
            'password' => 'required|string|min:6|confirmed',
        ], [
            'phone.regex' => 'Please enter a phone number that starts with 0 and contains only numbers.',
            'password.confirmed' => 'Password confirmation does not match. Please re-enter your password.',
            'id_photo.required' => 'You must upload an official ID or Passport photo to register.',
        ]);

        // 2. Create the User with Pending KYC Status
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'id_number' => $request->id_number,
            'kyc_status' => 'pending', 
            'password' => Hash::make($request->password),
            'status' => 'active',
            'usertype' => 'user', 
        ]);

        // 3. Handle ID Photo Upload
        if ($request->hasFile('id_photo')) {
            $path = $request->file('id_photo')->store('proofs/kyc', 'public');
            
            ProofFile::create([
                'fileable_type' => User::class,
                'fileable_id' => $user->id,
                'path' => $path,
                'original_name' => $request->file('id_photo')->getClientOriginalName(),
                'file_type' => 'id_photo',
                'status' => 'active'
            ]);
        }

        // 4. DO NOT log them in. Redirect to the login page with a message.
        return redirect()->route('user.login')->with('success', 'Registration submitted! You can log in once an Admin verifies your ID.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/'); 
    }

    public function dashboard()
    {
        $userId = Auth::id();

        $myRequests = \App\Models\BloodRequest::where('requester_id', $userId)
            ->latest()
            ->paginate(10, ['*'], 'requests_page');

        $myDonations = \App\Models\DonationInvoice::where('user_id', $userId)
            ->latest()
            ->paginate(10, ['*'], 'donations_page');

        return view('user.dashboard', compact('myRequests', 'myDonations'));
    }

    public function showCreateRequestForm() {
        return view('user.create_request');
    }

    public function storeRequest(Request $request) {
        $request->validate([
            'blood_type' => 'required',
            'quantity' => 'required',
            'hospital_name' => 'required',
            'needed_date' => 'required|date|after:today',
        ]);

        BloodRequest::create([
            'requester_id' => Auth::id(),
            'blood_type' => $request->blood_type,
            'quantity' => $request->quantity,
            'hospital_name' => $request->hospital_name,
            'needed_date' => $request->needed_date,
            'status' => 'open',
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Request Created Successfully!');
    }
    
    // --- DONATION INVOICE MODULE ---
    
    public function showDonateForm() {
        return view('user.donate');
    }

    public function storeDonation(Request $request) {
        // Added validation for the manual expiry date
        $request->validate([
            'blood_bank_name' => 'required|string|max:255',
            'donation_date' => 'required|date',
            'expiry_date' => 'required|date|after:donation_date', // Manual input from form
            'blood_type' => 'nullable|string',
            'proof_file' => 'required|file|mimes:jpg,png,pdf|max:5120', 
        ]);

        $invoice = DonationInvoice::create([
            'user_id' => Auth::id(),
            'blood_bank_name' => $request->blood_bank_name,
            'donation_date' => $request->donation_date,
            'expiry_date' => $request->expiry_date, // Saved manually
            'blood_type' => $request->blood_type,
            'status' => 'pending'
        ]);

        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('proofs/invoices', 'public');
            
            ProofFile::create([
                'fileable_type' => DonationInvoice::class,
                'fileable_id' => $invoice->id,
                'path' => $path,
                'original_name' => $request->file('proof_file')->getClientOriginalName(),
                'file_type' => 'invoice_proof',
                'status' => 'active'
            ]);
        }

        // Redirects to the new Wallet instead of the dashboard
        return redirect()->route('user.wallet')->with('success', 'Donation invoice submitted! Waiting for Admin verification.');
    }

    // THE MISSING WALLET METHOD
    public function wallet() {
        $invoices = DonationInvoice::where('user_id', Auth::id())->latest()->get();
        return view('user.wallet', compact('invoices'));
    }
    
    // -------------------------------
    
    public function markRequestAsComplete($id) {
        $request = BloodRequest::findOrFail($id);

        if ($request->requester_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->update(['status' => 'completed']);

        return back()->with('success', 'Great! Request marked as completed.');
    }

    public function requestHistory(Request $request)
    {
        $query = BloodRequest::where('requester_id', Auth::id())->latest();

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by blood type
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('needed_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('needed_date', '<=', $request->to_date);
        }

        $requests = $query->paginate(10)->withQueryString();

        // Stats for summary cards
        $stats = [
            'total'     => BloodRequest::where('requester_id', Auth::id())->count(),
            'open'      => BloodRequest::where('requester_id', Auth::id())->where('status', 'open')->count(),
            'completed' => BloodRequest::where('requester_id', Auth::id())->where('status', 'completed')->count(),
            'cancelled' => BloodRequest::where('requester_id', Auth::id())->whereIn('status', ['cancelled', 'expired'])->count(),
        ];

        return view('user.request_history', compact('requests', 'stats'));
    }
    
    public function showProfile() {
        return view('user.profile', ['user' => Auth::user()]);
    }
    
    public function updateProfile(Request $request) {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-,All',
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|max:2048', 
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->blood_type = $request->blood_type;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
    
    public function certificate($id)
    {
        $donation = DonationInvoice::where('user_id', Auth::id())
                    ->where('id', $id)
                    ->where('status', 'active')
                    ->firstOrFail();

        return view('user.certificate', compact('donation'));
    }

    private function completeLogin(Request $request, User $user)
    {
        if ($user->usertype === 'admin') {
            return redirect()->route('user.login')->withErrors([
                'identifier' => 'Administrators must login via the Admin Portal.',
            ]);
        }

        if ($user->status === 'blocked') {
            return redirect()->route('user.login')->withErrors([
                'identifier' => 'Your account is currently blocked. Please contact support.',
            ]);
        }

        if ($user->kyc_status === 'pending') {
            return redirect()->route('user.login')->withErrors([
                'identifier' => 'Your account is under review by an Admin. You cannot log in yet.',
            ]);
        }

        if ($user->kyc_status === 'rejected') {
            return redirect()->route('user.login')->withErrors([
                'identifier' => 'Your ID verification was rejected. Please register a new account.',
            ]);
        }

        Auth::loginUsingId($user->id);
        $request->session()->regenerate();

        $user->forceFill([
            'last_login_at' => now(),
        ])->save();

        return redirect()->route('user.dashboard');
    }

    private function isValidTelegramLogin(array $payload): bool
    {
        $providedHash = $payload['hash'];
        unset($payload['hash']);

        $payload = array_filter($payload, fn ($value) => $value !== null && $value !== '');
        ksort($payload);

        $dataCheckString = collect($payload)
            ->map(fn ($value, $key) => $key.'='.$value)
            ->implode("\n");

        $secretKey = hash('sha256', config('services.telegram.bot_token'), true);
        $calculatedHash = hash_hmac('sha256', $dataCheckString, $secretKey);

        if (! hash_equals($calculatedHash, $providedHash)) {
            return false;
        }

        return (int) ($payload['auth_date'] ?? 0) >= now()->subMinutes(10)->timestamp;
    }
}