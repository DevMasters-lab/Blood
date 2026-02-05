<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    // 1. List Requests (Feed for Home Screen)
    public function index(Request $request)
    {
        $query = BloodRequest::with('requester:id,name,phone') // Eager load user
            ->where('status', 'open');

        // Optional: Filter by Blood Type
        if ($request->has('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        // Sort by Needed Date (Urgent first)
        $requests = $query->orderBy('needed_date', 'asc')
                          ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    // 2. Create Request (Protected)
    public function store(Request $request)
    {
        // 1. Validate
        $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity' => 'required|string',     // e.g. "2 Bags"
            'hospital_name' => 'required|string',
            'needed_date' => 'required|date|after_or_equal:today',
            'patient_name' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:jpg,png,pdf|max:5120'
        ]);

        // 2. Check KYC (Optional Rule: Only verified users can request)
        // if ($request->user()->kyc_status !== 'verified') {
        //     return response()->json(['message' => 'KYC must be verified'], 403);
        // }

        // 3. Create
        $bloodRequest = BloodRequest::create([
            'requester_id' => Auth::id(),
            'blood_type' => $request->blood_type,
            'quantity' => $request->quantity,
            'hospital_name' => $request->hospital_name,
            'needed_date' => $request->needed_date,
            'patient_name' => $request->patient_name,
            'status' => 'open'
        ]);

        // 4. Upload Doctor Note/Document
        if ($request->hasFile('document_file')) {
            $path = $request->file('document_file')->store('proofs/requests', 'public');
            $bloodRequest->proofFiles()->create([
                'file_type' => 'request_document',
                'path' => $path,
                'original_name' => $request->file('document_file')->getClientOriginalName(),
            ]);
        }

        // 5. Update User Stats
        $request->user()->increment('request_count');

        return response()->json([
            'success' => true,
            'message' => 'Blood request created successfully',
            'data' => $bloodRequest
        ], 201);
    }

    // 3. Show Single Request Detail
    public function show($id)
    {
        $request = BloodRequest::with(['requester', 'proofFiles'])->find($id);

        if (!$request) {
            return response()->json(['success' => false, 'message' => 'Not Found'], 404);
        }

        return response()->json(['success' => true, 'data' => $request]);
    }
}