@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-red-600">Request Blood</h2>

    {{-- START: Error Message Block --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">Please check your input:</span>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- END: Error Message Block --}}

    <form action="{{ route('user.requests.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Blood Type</label>
            <select name="blood_type" class="w-full border p-2 rounded">
                <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                <option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Quantity (e.g. 1 Bag)</label>
            <input type="text" name="quantity" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Hospital Name</label>
            <input type="text" name="hospital_name" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-1">Date Needed</label>
            {{-- Note: min="{{ date('Y-m-d') }}" helps prevent picking past dates in the UI --}}
            <input type="date" name="needed_date" min="{{ date('Y-m-d') }}" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="w-full bg-red-600 text-white p-2 rounded hover:bg-red-700">Submit Request</button>
    </form>
</div>
@endsection