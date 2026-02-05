<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DonationInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationInvoiceController extends Controller
{
    // 1. Submit Invoice (Donate to Blood Bank)
    public function store(Request $request)
    {
        $request->validate([
            'blood_bank_name' => 'required|string',
            'donation_date' => 'required|date',
            'blood_type' => 'nullable|string',
            'proof_file' => 'required|file|mimes:jpg,png,pdf|max:5120'
        ]);

        // Auto-Calculate Expiry (1 Month rule)
        $donationDate = Carbon::parse($request->donation_date);
        $expiryDate = $donationDate->copy()->addMonth();

        // Create Invoice
        $invoice = DonationInvoice::create([
            'user_id' => Auth::id(),
            'blood_bank_name' => $request->blood_bank_name,
            'donation_date' => $donationDate,
            'expiry_date' => $expiryDate, // System sets this
            'blood_type' => $request->blood_type,
            'status' => 'pending'
        ]);

        // Upload Proof
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('proofs/invoices', 'public');
            $invoice->proofFile()->create([
                'file_type' => 'invoice_proof',
                'path' => $path,
                'original_name' => $request->file('proof_file')->getClientOriginalName(),
            ]);
        }

        // Update User Stats
        $request->user()->increment('donation_invoice_count');

        return response()->json([
            'success' => true,
            'message' => 'Donation invoice submitted for verification',
            'data' => $invoice
        ], 201);
    }

    // 2. My Invoice Wallet
    public function index(Request $request)
    {
        $query = DonationInvoice::where('user_id', Auth::id());

        // Filter by Status (active, expired, etc.)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('expiry_date', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }
}