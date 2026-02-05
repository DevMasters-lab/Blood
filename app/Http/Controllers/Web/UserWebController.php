<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationInvoice;
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
        $credentials = $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // CORRECT: Always go to the User Portal
            return redirect()->route('user.dashboard'); 
        }

        return back()->withErrors(['phone' => 'Invalid credentials.']);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);

        Auth::login($user);
        return redirect()->route('user.dashboard');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }

    public function dashboard() {
        $user = Auth::user();
        
        $myRequests = BloodRequest::where('requester_id', $user->id)->latest()->get();
        
        $myDonations = DonationInvoice::where('user_id', $user->id)->latest()->get();

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
    public function showDonateForm() {
        return view('user.donate');
    }

    public function storeDonation(Request $request) {
        $request->validate([
            'blood_bank_name' => 'required',
            'donation_date' => 'required|date',
            'proof_file' => 'required|file|mimes:jpg,png,pdf|max:5120', // 5MB max
        ]);

        // 1. Calculate Expiry (1 Month rule)
        $donationDate = \Carbon\Carbon::parse($request->donation_date);
        $expiryDate = $donationDate->copy()->addMonth();

        // 2. Create Invoice Record
        $invoice = DonationInvoice::create([
            'user_id' => Auth::id(),
            'blood_bank_name' => $request->blood_bank_name,
            'donation_date' => $donationDate,
            'expiry_date' => $expiryDate,
            'blood_type' => $request->blood_type, // Optional
            'status' => 'pending'
        ]);

        // 3. Upload Proof File
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('proofs/invoices', 'public');
            
            // Link the file to the invoice
            $invoice->proofFile()->create([
                'path' => $path,
                'original_name' => $request->file('proof_file')->getClientOriginalName(),
                'file_type' => 'invoice_proof',
            ]);
        }

        // 4. Update User Stats (Optional)
        Auth::user()->increment('donation_invoice_count');

        return redirect()->route('user.dashboard')->with('success', 'Donation submitted for verification!');
    }
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
            'avatar' => 'nullable|image|max:2048', // Validate image
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