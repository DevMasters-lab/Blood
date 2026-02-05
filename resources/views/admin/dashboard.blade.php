@extends('layouts.admin')

@section('content')

    {{-- 1. WELCOME HERO SECTION --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-8 flex flex-col md:flex-row items-center justify-between border-l-4 border-red-800">
        <div class="flex items-center mb-4 md:mb-0">
            {{-- Admin Avatar --}}
            <div class="mr-6">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-20 h-20 rounded-full object-cover border-4 border-gray-100 shadow-sm">
                @else
                    <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center text-red-500 text-3xl shadow-sm">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                @endif
            </div>
            
            {{-- Welcome Text --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Welcome, Administrator {{ auth()->user()->name }}</h2>
                <p class="text-gray-500 text-sm mt-1">
                    <span class="text-green-600 font-bold"><i class="fa-solid fa-circle text-xs mr-1"></i> System Online</span>
                    <span class="mx-2">•</span> 
                    <a href="{{ route('user.profile') }}" class="text-red-600 hover:underline">Edit Profile</a>
                </p>
            </div>
        </div>

        {{-- Admin Quick Actions --}}
        <div class="flex space-x-3">
            <a href="{{ route('admin.donations') }}" class="bg-red-700 text-white px-5 py-2 rounded shadow hover:bg-red-800 transition flex items-center">
                <i class="fa-solid fa-check-double mr-2"></i> Review Donations
            </a>
        </div>
    </div>

    {{-- 2. STATS OVERVIEW --}}
    <h2 class="text-2xl font-bold mb-6 text-gray-800">System Overview</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Card 1: Total Users --}}
        <div class="bg-white p-6 rounded shadow border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-bold uppercase">Total Users</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                </div>
                <div class="text-blue-200 text-3xl">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>

        {{-- Card 2: Open Requests --}}
        <div class="bg-white p-6 rounded shadow border-l-4 border-red-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-bold uppercase">Open Requests</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $pendingRequests }}</p>
                </div>
                <div class="text-red-200 text-3xl">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
            </div>
        </div>

        {{-- Card 3: Pending Invoices --}}
        <div class="bg-white p-6 rounded shadow border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-bold uppercase">Invoices to Verify</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ $pendingDonations }}</p>
                </div>
                <div class="text-yellow-200 text-3xl">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
            </div>
        </div>

        {{-- Card 4: Total Donations --}}
        <div class="bg-white p-6 rounded shadow border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-bold uppercase">Total Donations</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $totalDonated }}</p>
                </div>
                <div class="text-green-200 text-3xl">
                    <i class="fa-solid fa-award"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Recent Activity Note --}}
    <div class="bg-white p-6 rounded shadow flex items-center">
        <div class="mr-4 bg-blue-100 text-blue-600 p-3 rounded-full">
            <i class="fa-solid fa-info-circle text-xl"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold">Admin Tips</h3>
            <p class="text-gray-600 text-sm">Use the top navigation bar to manage Users, Donations, and Requests. Remember to verify donation proofs carefully before approving them.</p>
        </div>
    </div>

@endsection