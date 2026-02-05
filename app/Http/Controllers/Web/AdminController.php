<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationInvoice;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'requests' => BloodRequest::where('status', 'open')->count(),
            'donations' => DonationInvoice::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function requests()
    {
        $requests = BloodRequest::with('requester')->latest()->paginate(10);
        return view('admin.requests', compact('requests'));
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        $bloodRequest->update(['status' => $request->status]);
        return back()->with('success', 'Status updated successfully!');
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function verifyUser($id)
    {
        User::where('id', $id)->update(['kyc_status' => 'verified', 'kyc_verified_at' => now()]);
        return back()->with('success', 'User Verified!');
    }

    public function donations()
    {
        // Get all invoices with user data, newest first
        $donations = DonationInvoice::with('user')->latest()->paginate(10);
        return view('admin.donations', compact('donations'));
    }

    public function updateDonationStatus(Request $request, $id)
    {
        $donation = DonationInvoice::findOrFail($id);
        
        if ($request->status == 'active') {
            // Approve: Generate Invoice Code and Set Active
            $donation->update([
                'status' => 'active',
                'invoice_code' => 'INV-' . date('Ym') . '-' . str_pad($donation->id, 4, '0', STR_PAD_LEFT),
            ]);
            // Optional: Send Notification to User here
        } else {
            // Reject
            $donation->update(['status' => 'rejected']);
        }

        return back()->with('success', 'Donation status updated successfully!');
    }
}