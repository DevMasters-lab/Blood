@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">My Dashboard</h2>
    
    <div class="flex space-x-3">
        {{-- Submit Donation Button --}}
        <a href="{{ route('user.donate') }}" class="bg-white border border-red-600 text-red-600 px-4 py-2 rounded shadow hover:bg-red-50 transition flex items-center">
            <i class="fa-solid fa-file-invoice mr-2"></i> Submit Donation
        </a>

        {{-- Create Request Button --}}
        <a href="{{ route('user.requests.create') }}" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 transition flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Create Blood Request
        </a>
    </div>
</div>

<div class="bg-white rounded shadow p-6">
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
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($myRequests as $req)
                <tr class="border-b">
                    <td class="p-3">{{ $req->hospital_name }}</td>
                    <td class="p-3 font-bold text-red-600">{{ $req->blood_type }}</td>
                    <td class="p-3">{{ $req->needed_date->format('d M Y') }}</td>
                    <td class="p-3">{{ ucfirst($req->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection