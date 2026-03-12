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

class UserWebController extends Controller
{
    // --- Auth Screens ---
    public function showLoginForm() {
        return view('auth.user_login');
    }

    public function showRegisterForm() {
        return view('auth.register');
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

        if ($user && Hash::check($request->password, $user->password)) {
            
            // 1. Prevent Admins from entering the standard User Portal
            if ($user->usertype === 'admin') {
                return back()->withErrors(['identifier' => 'Administrators must login via the Admin Portal.']);
            }

            // 2. Block Pending Users
            if ($user->kyc_status === 'pending') {
                return back()->withErrors(['identifier' => 'Your account is under review by an Admin. You cannot log in yet.']);
            }

            // 3. Block Rejected Users
            if ($user->kyc_status === 'rejected') {
                return back()->withErrors(['identifier' => 'Your ID verification was rejected. Please register a new account.']);
            }
            
            // If they are Verified, let them in!
            Auth::loginUsingId($user->id);
            $request->session()->regenerate();
            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['identifier' => 'Invalid phone/email or password.']);
    }

    public function register(Request $request) {
        // 1. Validate all fields
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:255|unique:users',
            'phone' => 'required|string|unique:users|regex:/^0[0-9]{8,9}$/',
            'blood_type' => 'required|string', 
            'id_number' => 'required|string|max:50',
            'id_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', 
            'password' => 'required|string|min:6|confirmed',
        ], [
            'phone.regex' => 'Please enter a phone number that starts with 0 and contains only numbers.',
            'password.confirmed' => 'Password confirmation does not match. Please re-enter your password.',
            'id_photo.required' => 'You must upload an official ID or Passport photo to register.',
            'email.unique' => 'This email address is already registered.',
        ]);

        // 2. Create the User with Pending KYC Status
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'blood_type' => $request->blood_type, 
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
        $userId = auth()->id();

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
    
    public function showProfile() {
        return view('user.profile', ['user' => Auth::user()]);
    }
    
    public function updateProfile(Request $request) {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
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
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
    
    public function certificate($id)
    {
        $donation = DonationInvoice::where('user_id', auth()->id())
                    ->where('id', $id)
                    ->where('status', 'active')
                    ->firstOrFail();

        return view('user.certificate', compact('donation'));
    }
}