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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    public function users(Request $request)
    {
        $currentStatus = $request->get('status', 'all');

        $allCount = User::where('usertype', 'user')->count();
        $pendingCount = User::where('usertype', 'user')->where('kyc_status', 'pending')->count();
        $verifiedCount = User::where('usertype', 'user')->where('kyc_status', 'verified')->count();

        $query = User::where('usertype', 'user');

        if (in_array($currentStatus, ['pending', 'verified'], true)) {
            $query->where('kyc_status', $currentStatus);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users', compact('users', 'currentStatus', 'allCount', 'pendingCount', 'verifiedCount'));
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

    public function showUser($id)
    {
        $user = User::where('usertype', 'user')->findOrFail($id);
        return view('admin.user-view', compact('user'));
    }

    public function resetUserPassword($id)
    {
        $user = User::where('usertype', 'user')->findOrFail($id);
        $user->password = Hash::make('123456');
        $user->save();

        return back()->with('success', 'Password reset successfully. New password is 123456.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Security: Prevent deleting yourself
        if ($user->id == Auth::id()) {
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

    public function requestHistory(Request $request)
    {
        $query = BloodRequest::with('requester')->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('hospital_name', 'like', "%{$search}%")
                  ->orWhere('patient_name', 'like', "%{$search}%")
                  ->orWhereHas('requester', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $requests = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => BloodRequest::count(),
            'open'      => BloodRequest::where('status', 'open')->count(),
            'completed' => BloodRequest::where('status', 'completed')->count(),
            'cancelled' => BloodRequest::whereIn('status', ['cancelled', 'expired'])->count(),
        ];

        return view('admin.request_history', compact('requests', 'stats'));
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
        $request->validate([
            'hero_banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $request->except(['_token']);

        // Handle the Toggle Switches
        $data['maintenance_mode'] = $request->has('maintenance_mode') ? '1' : '0';
        $data['allow_guest_requests'] = $request->has('allow_guest_requests') ? '1' : '0';

        // Keep existing banner unless a new file is uploaded.
        unset($data['hero_banner_image']);
        $oldBanner = Setting::where('key', 'hero_banner_image')->value('value');
        $shouldRemoveBanner = $request->has('remove_hero_banner');

        if ($request->hasFile('hero_banner_image')) {
            if (!empty($oldBanner)) {
                Storage::disk('public')->delete($oldBanner);
            }

            $data['hero_banner_image'] = $request->file('hero_banner_image')->store('settings/banners', 'public');
        } elseif ($shouldRemoveBanner) {
            if (!empty($oldBanner)) {
                Storage::disk('public')->delete($oldBanner);
            }

            // Persist an empty value to disable the custom banner on homepage.
            $data['hero_banner_image'] = '';
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Platform settings updated successfully.');
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

    public function reports(Request $request)
    {
        $allowedRanges = ['today', '7days', '30days', 'all'];
        $selectedRange = $request->query('range', '7days');
        if (!in_array($selectedRange, $allowedRanges, true)) {
            $selectedRange = '7days';
        }

        $fromDate = null;
        if ($selectedRange === 'today') {
            $fromDate = Carbon::today();
        } elseif ($selectedRange === '7days') {
            $fromDate = Carbon::now()->subDays(6)->startOfDay();
        } elseif ($selectedRange === '30days') {
            $fromDate = Carbon::now()->subDays(29)->startOfDay();
        }

        $applyDateFilter = function ($query) use ($fromDate) {
            if ($fromDate !== null) {
                $query->where('created_at', '>=', $fromDate);
            }

            return $query;
        };

        // 1. Get Total Counts
        $requestsQuery = $applyDateFilter(BloodRequest::query());
        $totalRequests = (clone $requestsQuery)->count();
        $completedRequests = (clone $requestsQuery)->where('status', 'completed')->count();
        $expiredRequests = (clone $requestsQuery)->where('status', 'expired')->count();
        $cancelledRequests = (clone $requestsQuery)->where('status', 'cancelled')->count();
        $reservedRequests = (clone $requestsQuery)->where('status', 'reserved')->count();
        $openRequests = (clone $requestsQuery)->where('status', 'open')->count();

        // 2. Count Verified Donations (Approved Invoices + Verified Responses)
        $verifiedInvoices = $applyDateFilter(DonationInvoice::query())
            ->whereIn('status', ['active', 'used', 'expired'])
            ->count();
        $verifiedResponses = $applyDateFilter(\App\Models\RequestResponse::query())
            ->where('proof_status', 'verified')
            ->count();
        $verifiedDonations = $verifiedInvoices + $verifiedResponses;

        // 3. Calculate Percentages (Preventing division by zero)
        $completedPct = $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100) : 0;
        $reservedPct = $totalRequests > 0 ? round(($reservedRequests / $totalRequests) * 100) : 0;
        $openPct = $totalRequests > 0 ? round(($openRequests / $totalRequests) * 100) : 0;
        $expiredCancelledPct = $totalRequests > 0 ? round((($expiredRequests + $cancelledRequests) / $totalRequests) * 100) : 0;

        // 4. Get Most Popular Blood Types (Top 4)
        $popularBloodTypes = $applyDateFilter(BloodRequest::query())
            ->select('blood_type', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
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
            'completedPct', 'reservedPct', 'openPct', 'expiredCancelledPct', 'popularBloodTypes', 'selectedRange'
        ));
    }

    // =========================================================================
    // 9. ADMIN ACCOUNT SETTINGS
    // =========================================================================
    public function profile()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Admin profile updated successfully.');
    }
}
