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
    public function donations(Request $request)
    {
        $allCount = \App\Models\DonationInvoice::count();
        $pendingCount = \App\Models\DonationInvoice::where('status', 'pending')->count();
        $approvedCount = \App\Models\DonationInvoice::where('status', 'active')->count();
        $rejectedCount = \App\Models\DonationInvoice::where('status', 'rejected')->count();

        $currentStatus = $request->get('status', 'all');
        
        $query = \App\Models\DonationInvoice::with(['user', 'proofFile']);

        if ($currentStatus !== 'all') {
            $dbStatus = ($currentStatus === 'approved') ? 'active' : $currentStatus;
            $query->where('status', $dbStatus);
        }

        $donations = $query->latest()->paginate(10);

        return view('admin.donations', compact(
            'donations',
            'allCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'currentStatus'
        ));
    }

    public function updateDonationStatus(Request $request, $id)
    {
        $donation = DonationInvoice::findOrFail($id);
        
        if ($request->status == 'active') {
            $donation->status = 'active';
            
            // Generate Modern Unique Invoice Code (e.g., INV-8A9B2C)
            if (!$donation->invoice_code) {
                $donation->invoice_code = 'INV-' . strtoupper(Str::random(6));
            }
            
            // Increment the user's trust stats so their public profile updates
            $donation->user->increment('donation_invoice_count');
            
            $message = 'Donation Approved! Code ' . $donation->invoice_code . ' generated.';
            
        } elseif ($request->status == 'rejected') {
            $donation->status = 'rejected';
            $message = 'Donation record rejected.';
        }

        $donation->save();
        
        // Redirect back to the SAME filter tab they were on
        return back()->with('success', $message);
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

    public function reports()
    {
        // 1. Get Total Counts
        $totalRequests = \App\Models\BloodRequest::count();
        $completedRequests = \App\Models\BloodRequest::where('status', 'completed')->count();
        $expiredRequests = \App\Models\BloodRequest::where('status', 'expired')->count();
        $cancelledRequests = \App\Models\BloodRequest::where('status', 'cancelled')->count();
        $reservedRequests = \App\Models\BloodRequest::where('status', 'reserved')->count();
        $openRequests = \App\Models\BloodRequest::where('status', 'open')->count();

        // 2. Count Verified Donations (Approved Invoices + Verified Responses)
        $verifiedInvoices = \App\Models\DonationInvoice::whereIn('status', ['active', 'used', 'expired'])->count();
        $verifiedResponses = \App\Models\RequestResponse::where('proof_status', 'verified')->count();
        $verifiedDonations = $verifiedInvoices + $verifiedResponses;

        // 3. Calculate Percentages (Preventing division by zero)
        $completedPct = $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100) : 0;
        $reservedPct = $totalRequests > 0 ? round(($reservedRequests / $totalRequests) * 100) : 0;
        $openPct = $totalRequests > 0 ? round(($openRequests / $totalRequests) * 100) : 0;
        $expiredCancelledPct = $totalRequests > 0 ? round((($expiredRequests + $cancelledRequests) / $totalRequests) * 100) : 0;

        // 4. Get Most Popular Blood Types (Top 4)
        $popularBloodTypes = \App\Models\BloodRequest::select('blood_type', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('blood_type')
            ->orderByDesc('count')
            ->take(4)
            ->get()
            ->map(function ($item) use ($totalRequests) {
                $item->percentage = $totalRequests > 0 ? round(($item->count / $totalRequests) * 100) : 0;
                
                // Assign a descriptive label based on the percentage
                if ($item->percentage > 30) {
                    $item->label = 'Highest Demand';
                } elseif ($item->percentage > 15) {
                    $item->label = 'High Demand';
                } elseif ($item->percentage > 5) {
                    $item->label = 'Medium Demand';
                } else {
                    $item->label = 'Rare Need';
                }
                return $item;
            });

        return view('admin.reports', compact(
            'totalRequests', 'completedRequests', 'expiredRequests', 'verifiedDonations',
            'completedPct', 'reservedPct', 'openPct', 'expiredCancelledPct', 'popularBloodTypes'
        ));
    }
}