<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\DonationApproved;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard()
    {
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

    public function requests()
    {
        // Get all requests, newest first
        $requests = BloodRequest::with('requester')->latest()->paginate(10);
        return view('admin.requests', compact('requests'));
    }

    public function deleteRequest($id)
    {
        $request = BloodRequest::findOrFail($id);
        $request->delete();

        return back()->with('success', 'Request has been deleted successfully.');
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        $bloodRequest->update(['status' => $request->status]);
        return back()->with('success', 'Status updated successfully!');
    }

    public function users()
    {
        $users = User::where('usertype', 'user')->latest()->paginate(10);
        
        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id == auth()->id()) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }
        $user->delete();

        return back()->with('success', 'User account deleted successfully.');
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
            $donation->status = 'active';
            
            if (!$donation->invoice_code) {
                $donation->invoice_code = 'INV-' . date('Ym') . '-' . str_pad($donation->id, 4, '0', STR_PAD_LEFT);
            }

            if ($donation->user && !empty($donation->user->email)) {
                try {
                    Mail::to($donation->user->email)
                        ->send(new \App\Mail\DonationApproved($donation->user));
                } catch (\Exception $e) {
                    Log::error('Email sending failed: ' . $e->getMessage());
                }
            }
        } elseif ($request->status == 'rejected') {
            $donation->status = 'rejected';
        }

        $donation->save();

        return back()->with('success', 'Donation status updated successfully!');
    }
    
}