@extends('layouts.admin')

@section('content')
<div class="space-y-10 animate-fade-in">

    {{-- 1. MASTER STATUS HERO: Midnight Style --}}
    <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-gray-100 flex flex-col lg:flex-row items-center justify-between">
        <div class="flex items-center">
            <div class="relative group">
                <div class="absolute -inset-2 bg-gradient-to-r from-[#D32F2F] to-[#FF5252] rounded-full blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="relative w-24 h-24 rounded-[2rem] object-cover border-4 border-white shadow-xl">
                @else
                    <div class="relative w-24 h-24 rounded-[2rem] bg-gray-50 flex items-center justify-center text-[#D32F2F] text-4xl shadow-xl border-4 border-white">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                @endif
            </div>
            
            <div class="ml-8">
                <h2 class="text-4xl font-black text-[#1A1C1E] tracking-tight">System Authority</h2>
                <div class="flex items-center mt-2">
                    <span class="relative flex h-3 w-3 mr-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Administrator: {{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 lg:mt-0 flex gap-4">
            <a href="{{ route('admin.donations') }}" class="bg-[#D32F2F] text-white px-10 py-4 rounded-2xl font-black text-xs shadow-2xl shadow-red-900/20 hover:bg-[#B71C1C] transition-all hover:-translate-y-1 active:translate-y-0 uppercase tracking-widest">
                Review Invoices
            </a>
        </div>
    </div>

    {{-- 2. GLOBAL ANALYTICS: High-Contrast Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        {{-- Total Users --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-50 group hover:shadow-xl transition-all duration-500">
            <div class="flex justify-between items-start mb-6">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fa-solid fa-users text-2xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Database</span>
            </div>
            <p class="text-4xl font-black text-[#1A1C1E]">{{ $totalUsers }}</p>
            <p class="text-xs text-gray-400 mt-2 font-bold uppercase tracking-wider">Registered Users</p>
        </div>

        {{-- Open Requests --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-50 group hover:shadow-xl transition-all duration-500">
            <div class="flex justify-between items-start mb-6">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-[#D32F2F] group-hover:bg-[#D32F2F] group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fa-solid fa-heart-pulse text-2xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Live</span>
            </div>
            <p class="text-4xl font-black text-[#D32F2F]">{{ $pendingRequests }}</p>
            <p class="text-xs text-gray-400 mt-2 font-bold uppercase tracking-wider">Active Requests</p>
        </div>

        {{-- Invoices --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-50 group hover:shadow-xl transition-all duration-500">
            <div class="flex justify-between items-start mb-6">
                <div class="w-16 h-16 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-600 group-hover:bg-yellow-500 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fa-solid fa-file-invoice text-2xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Pending</span>
            </div>
            <p class="text-4xl font-black text-gray-800">{{ $pendingDonations }}</p>
            <p class="text-xs text-gray-400 mt-2 font-bold uppercase tracking-wider">To Verify</p>
        </div>

        {{-- TOTAL SUCCESS: Midnight Card --}}
        <div class="bg-[#1A1C1E] p-8 rounded-[2.5rem] shadow-2xl shadow-gray-900/20 text-white relative overflow-hidden group">
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Verified Success</p>
                    <p class="text-5xl font-black mt-4 group-hover:scale-110 transition-transform duration-500">{{ $totalDonated }}</p>
                </div>
                <div class="mt-6">
                    <span class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border border-white/5">
                        Total Blood Units
                    </span>
                </div>
            </div>
            <i class="fa-solid fa-award absolute -right-6 -bottom-6 text-[10rem] text-white/5 -rotate-12 transition-transform duration-700 group-hover:rotate-0"></i>
        </div>
    </div>

    {{-- 3. ADMINISTRATIVE TIPS: Blue Glass Style --}}
    <div class="bg-[#D32F2F] rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-red-900/20">
        <div class="relative z-10 flex flex-col lg:flex-row items-center">
            <div class="w-20 h-20 bg-white/20 backdrop-blur-xl rounded-3xl flex items-center justify-center text-3xl mr-8 mb-6 lg:mb-0 shadow-inner">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="flex-1 text-center lg:text-left">
                <h3 class="text-2xl font-black tracking-tight mb-2">Security Overview</h3>
                <p class="text-white/80 text-sm font-bold tracking-wide">Actions here update the database silently. Use Global Search in the header to audit users or verify medical records instantly.</p>
            </div>
            <div class="mt-8 lg:mt-0 lg:ml-12">
                <a href="{{ route('admin.users') }}" class="inline-block bg-white text-[#D32F2F] px-8 py-3 rounded-2xl font-black text-xs hover:bg-gray-100 transition-all uppercase tracking-widest shadow-xl">
                    Manage Directory
                </a>
            </div>
        </div>
        <i class="fa-solid fa-fingerprint absolute -right-12 -top-12 text-[15rem] text-white/5 rotate-12"></i>
    </div>
</div>
@endsection