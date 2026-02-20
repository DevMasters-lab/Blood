<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
    // 4. DONATION INVOICE MANAGEMENT
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

    public function updateUserBloodType(Request $request, $id)
    {
        // 1. Validate the incoming blood type selection
        $request->validate([
            'blood_type' => 'required|string|max:3'
        ]);

        // 2. Find the user and update their record silently
        $user = User::findOrFail($id);
        $user->blood_type = $request->blood_type;
        $user->save(); // No Mail::to() triggered here

        return back()->with('success', 'User blood group corrected silently.');
    }

    /**
     * Show the Frontend Settings page.
     */
    public function settings()
    {
        // If you create a Settings table later, you can fetch data here:
        // $settings = Setting::pluck('value', 'key')->toArray();
        // return view('admin.settings', compact('settings'));

        return view('admin.settings'); // Make sure your blade file is named settings.blade.php in the admin folder
    }

    /**
     * Update the Frontend Settings.
     */
    public function updateSettings(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',
            'contact_email' => 'required|email',
            'contact_location' => 'required|string',
            'facebook_url' => 'nullable|url',
            'telegram_url' => 'nullable|url',
        ]);

        // 2. Save the settings to the database
        // (Note: You will need a Setting model/table to actually save these to the database permanently)
        // Example:
        // foreach($request->except('_token') as $key => $value) {
        //     Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        // }

        // 3. Redirect back with a success message
        return redirect()->back()->with('success', 'Frontend settings updated successfully!');
    }

    public function localization()
    {
        return view('admin.localization'); // You will create resources/views/admin/localization.blade.php next!
    }

    /**
     * Update the Localization Settings.
     */
    public function updateLocalization(Request $request)
    {
        // 1. Validate the data
        $request->validate([
            'default_language' => 'required|string',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'phone_code' => 'required|string',
        ]);

        // 2. Save settings to your database/config here...

        // 3. Redirect back with success message
        return redirect()->back()->with('success', 'Localization settings saved successfully!');
    }
}