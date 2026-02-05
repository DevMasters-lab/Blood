@extends('layouts.admin')

@section('content')

{{-- 1. WELCOME HERO SECTION --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-8 flex flex-col md:flex-row items-center justify-between border-l-4 border-red-600">
    <div class="flex items-center mb-4 md:mb-0">
        {{-- Big Avatar --}}
        <div class="mr-6">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-20 h-20 rounded-full object-cover border-4 border-gray-100 shadow-sm">
            @else
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-3xl shadow-sm">
                    <i class="fa-solid fa-user"></i>
                </div>
            @endif
        </div>
        
        {{-- Welcome Text --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-500 text-sm mt-1">
                <i class="fa-solid fa-phone mr-1"></i> {{ auth()->user()->phone }} 
                <span class="mx-2">•</span> 
                <a href="{{ route('user.profile') }}" class="text-red-600 hover:underline">Edit Profile</a>
            </p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex space-x-3">
        <a href="{{ route('user.donate') }}" class="bg-white border border-red-600 text-red-600 px-5 py-2 rounded-full font-bold shadow-sm hover:bg-red-50 transition flex items-center">
            <i class="fa-solid fa-file-invoice mr-2"></i> Submit Donation
        </a>

        <a href="{{ route('user.requests.create') }}" class="bg-red-600 text-white px-5 py-2 rounded-full font-bold shadow-md hover:bg-red-700 transition flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Request Blood
        </a>
    </div>
</div>

{{-- SECTION 2: REQUESTS HISTORY --}}
<div class="bg-white rounded shadow p-6 mb-8">
    <h3 class="text-xl font-bold mb-4">My Requests History</h3>
    @if($myRequests->isEmpty())
        <p class="text-gray-500">You haven't requested blood yet.</p>
    @else
        <table class="w-full text-left">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="p-3">Hospital</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Action / Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($myRequests as $req)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $req->hospital_name }}</td>
                    <td class="p-3 font-bold text-red-600">{{ $req->blood_type }}</td>
                    <td class="p-3">{{ $req->needed_date->format('d M Y') }}</td>
                    
                    <td class="p-3">
                        @if($req->status == 'open')
                            {{-- Button to Close Request --}}
                            <form action="{{ route('user.requests.complete', $req->id) }}" method="POST" onsubmit="return confirm('Did you receive the blood? This will remove the request from the home page.')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-green-600 text-white text-xs px-3 py-1 rounded shadow hover:bg-green-700 transition flex items-center">
                                    <i class="fa-solid fa-check mr-1"></i> Mark Completed
                                </button>
                            </form>
                        @else
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $req->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- SECTION 3: DONATION HISTORY --}}
<div class="bg-white rounded shadow p-6 border-t-4 border-blue-500">
    <h3 class="text-xl font-bold mb-4 flex items-center">
        <i class="fa-solid fa-hand-holding-medical text-blue-500 mr-2"></i> My Donation History
    </h3>

    @if($myDonations->isEmpty())
        <p class="text-gray-500">You haven't submitted any donation proofs yet.</p>
    @else
        <table class="w-full text-left">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="p-3">Bank / Hospital</th>
                    <th class="p-3">Donation Date</th>
                    <th class="p-3">Invoice Code</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($myDonations as $donation)
                <tr class="hover:bg-gray-50">
                    <td class="p-3">{{ $donation->blood_bank_name }}</td>
                    <td class="p-3">{{ $donation->donation_date->format('d M Y') }}</td>
                    <td class="p-3">
                        @if($donation->invoice_code)
                            <span class="font-mono text-xs bg-gray-200 px-2 py-1 rounded">
                                {{ $donation->invoice_code }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs italic">Processing...</span>
                        @endif
                    </td>
                    <td class="p-3">
                        @if($donation->status == 'active')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Approved</span>
                            
                            {{-- NEW CERTIFICATE LINK --}}
                            <a href="{{ route('user.certificate', $donation->id) }}" target="_blank" class="text-blue-600 hover:underline text-xs block mt-1 font-semibold">
                                <i class="fa-solid fa-certificate"></i> Get Certificate
                            </a>

                        @elseif($donation->status == 'rejected')
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold">Rejected</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection