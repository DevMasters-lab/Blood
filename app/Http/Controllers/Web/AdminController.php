<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // <-- ADDED: Required for generating Invoice Codes

class AdminController extends Controller
{
    // =========================================================================
    // 1. ADMIN DASHBOARD
    // =========================================================================
    public function dashboard()
    {
        // Calculate stats for the dashboard cards
        $totalUsers = User::where('usertype', 'user')->count();
        $pendingRequests = BloodRequest::where('status', 'open')->count();
        $pendingDonations = DonationInvoice::where('status', 'pending')->count();
        $totalDonated = DonationInvoice::where('status', 'active')->count();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'pendingRequests', 
            'pendingDonations', 
            'totalDonated'
        ));
    }

    // =========================================================================
    // 2. USER MANAGEMENT (Verify, Block, Delete)
    // =========================================================================
    public function users()
    {
        // Get all users, newest first, 10 per page
        $users = User::where('usertype', 'user')->latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function toggleBlockUser($id) 
    {
        $user = User::findOrFail($id);
        
        if($user->status == 'active') {
            $user->status = 'blocked';
            $message = 'User has been blocked.';
        } else {
            $user->status = 'active';
            $message = 'User has been activated.';
        }
        
        $user->save();
        return back()->with('success', $message);
    }

    public function verifyUser($id)
    {
        User::where('id', $id)->update([
            'kyc_status' => 'verified', 
            'kyc_verified_at' => now()
        ]);
        return back()->with('success', 'User Verified successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Security: Prevent deleting yourself
        if ($user->id == auth()->id()) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }
        
        $user->delete();
        return back()->with('success', 'User account deleted successfully.');
    }

    public function updateUserBloodType(Request $request, $id)
    {
        // 1. Validate the incoming blood type selection
        $request->validate([
            'blood_type' => 'required|string|max:3'
        ]);

        // 2. Find the user and update their record silently
        $user = User::findOrFail($id);
        $user->blood_type = $request->blood_type;
        $user->save(); 

        return back()->with('success', 'User blood group corrected silently.');
    }

    // =========================================================================
    // 3. BLOOD REQUEST MANAGEMENT
    // =========================================================================
    public function requests()
    {
        $requests = BloodRequest::with('requester')->latest()->paginate(10);
        return view('admin.requests', compact('requests'));
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        $bloodRequest->update(['status' => $request->status]);
        return back()->with('success', 'Request status updated!');
    }

    public function deleteRequest($id)
    {
        $request = BloodRequest::findOrFail($id);
        $request->delete();
        return back()->with('success', 'Request deleted successfully.');
    }

    // =========================================================================
    // 4. OLD DONATIONS MANAGEMENT (If keeping legacy module)
    // =========================================================================
    public function donations()
    {
        $donations = DonationInvoice::with('user')->latest()->paginate(10);
        return view('admin.donations', compact('donations'));
    }

    public function updateDonationStatus(Request $request, $id)
    {
        $donation = DonationInvoice::findOrFail($id);
        
        if ($request->status == 'active') {
            $donation->status = 'active';
            
            if (!$donation->invoice_code) {
                $donation->invoice_code = 'INV-' . date('Ym') . '-' . str_pad($donation->id, 4, '0', STR_PAD_LEFT);
            }
            
        } 
        elseif ($request->status == 'rejected') {
            $donation->status = 'rejected';
        }

        $donation->save();
        return back()->with('success', 'Status updated silently in the Master Database.');
    }

    // =========================================================================
    // 5. NEW INVOICE VERIFICATION MODULE
    // =========================================================================
    public function verifyInvoices()
    {
        $pendingInvoices = DonationInvoice::with(['user', 'proofFile'])
            ->where('status', 'pending')
            ->latest()
            ->get();
            
        $activeInvoicesCount = DonationInvoice::where('status', 'active')->count();

        return view('admin.verify_invoices', compact('pendingInvoices', 'activeInvoicesCount'));
    }

    public function approveInvoice($id)
    {
        $invoice = DonationInvoice::findOrFail($id);
        
        // Generate Unique Invoice Code (e.g., INV-8A9B2C)
        $invoiceCode = 'INV-' . strtoupper(Str::random(6));

        $invoice->update([
            'status' => 'active',
            'invoice_code' => $invoiceCode
        ]);

        // Increment user's verified stats
        $invoice->user->increment('donation_invoice_count');

        return back()->with('success', 'Invoice Approved! Code ' . $invoiceCode . ' generated.');
    }

    public function rejectInvoice($id)
    {
        $invoice = DonationInvoice::findOrFail($id);
        $invoice->update(['status' => 'rejected']);

        return back()->with('error', 'Donation submission rejected.');
    }


    // =========================================================================
    // 6. PLATFORM CONFIGURATION & SETTINGS
    // =========================================================================
    public function settings()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token']);

        // Handle the Toggle Switches
        $data['maintenance_mode'] = $request->has('maintenance_mode') ? '1' : '0';
        $data['allow_guest_requests'] = $request->has('allow_guest_requests') ? '1' : '0';

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Platform settings updated successfully.');
    }

    public function localization()
    {
        return view('admin.localization');
    }

    public function updateLocalization(Request $request)
    {
        $request->validate([
            'default_language' => 'required|string',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'phone_code' => 'required|string',
        ]);

        return redirect()->back()->with('success', 'Localization settings saved successfully!');
    }

    // =========================================================================
    // 7. KYC & REPORTS
    // =========================================================================
    public function kyc()
    {
        $pendingUsers = User::where('kyc_status', 'pending')
            ->where('usertype', 'user')
            ->with(['proofFiles' => function($query) {
                $query->where('file_type', 'id_photo')->latest();
            }])
            ->latest()
            ->get();

        $pendingCount = $pendingUsers->count();
        $verifiedTodayCount = User::where('kyc_status', 'verified')->whereDate('updated_at', Carbon::today())->count();
        $rejectedCount = User::where('kyc_status', 'rejected')->count();

        return view('admin.kyc', compact('pendingUsers', 'pendingCount', 'verifiedTodayCount', 'rejectedCount'));
    }

    public function approveKyc($id)
    {
        $user = User::findOrFail($id);
        $user->update(['kyc_status' => 'verified']);

        return back()->with('success', $user->name . ' has been verified and added to the User Directory.');
    }

    public function rejectKyc($id)
    {
        $user = User::findOrFail($id);
        
        // Delete their invalid ID photo from your server storage
        foreach($user->proofFiles as $file) {
            Storage::disk('public')->delete($file->path);
            $file->delete();
        }

        // Delete the user entirely
        $userName = $user->name;
        $user->delete();

        return back()->with('error', $userName . ' was rejected and deleted. They must register again with valid documents.');
    }

    public function responses()
    {
        return view('admin.responses');
    }

    public function reports()
    {
        return view('admin.reports');
    }
}