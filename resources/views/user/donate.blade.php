@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded shadow border-t-4 border-red-600">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
        <i class="fa-solid fa-file-invoice text-red-600 mr-2"></i> Submit Donation Slip
    </h2>

    {{-- Error Display --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Whoops!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.donate.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Blood Bank / Hospital</label>
            <input type="text" name="blood_bank_name" placeholder="Where did you donate?" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Date of Donation</label>
            <input type="date" name="donation_date" max="{{ date('Y-m-d') }}" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Blood Type (Optional)</label>
            <select name="blood_type" class="w-full border p-2 rounded">
                <option value="">-- Select --</option>
                <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                <option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-1">Upload Proof (Image/PDF)</label>
            <input type="file" name="proof_file" class="w-full border p-2 rounded bg-gray-50" required>
            <p class="text-xs text-gray-500 mt-1">Max size: 5MB</p>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('user.dashboard') }}" class="text-gray-500 hover:text-gray-700">Cancel</a>
            <button type="submit" class="bg-red-600 text-white font-bold py-2 px-6 rounded hover:bg-red-700 transition shadow">
                Submit Donation
            </button>
        </div>
    </form>
</div>
@endsection