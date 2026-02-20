@extends('layouts.user')

@section('content')
<div class="space-y-10 animate-fade-in">
    
    {{-- 1. VIBRANT ANALYTICS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Total Requests Card --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500">
            <div class="flex justify-between items-start mb-6">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-[#D32F2F] group-hover:bg-[#D32F2F] group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fa-solid fa-droplet text-2xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Active Status</span>
            </div>
            <p class="text-4xl font-black text-gray-900">{{ $myRequests->where('status', 'open')->count() }}</p>
            <p class="text-xs text-gray-400 mt-2 font-medium italic">Current requests waiting</p>
        </div>

        {{-- Verified Badges Card --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500">
            <div class="flex justify-between items-start mb-6">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-inner">
                    <i class="fa-solid fa-shield-heart text-2xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Verified</span>
            </div>
            <p class="text-4xl font-black text-gray-900">{{ $myDonations->where('status', 'active')->count() }}</p>
            <p class="text-xs text-gray-400 mt-2 font-medium">Verified donation badges</p>
        </div>

        {{-- THE MASTER BLOOD CARD (Custom Gradient & Glow) --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-[#E53935] via-[#D32F2F] to-[#B71C1C] p-8 rounded-[2.5rem] shadow-2xl shadow-red-900/20 text-white group">
            <div class="relative z-10">
                <p class="text-[11px] font-black uppercase tracking-[0.3em] opacity-70">Your Blood Group</p>
                <p class="text-7xl font-black mt-4 drop-shadow-lg group-hover:scale-110 transition-transform duration-500">{{ auth()->user()->blood_type ?? 'N/A' }}</p>
                <div class="mt-8">
                    <span class="bg-white/20 backdrop-blur-md px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-wider border border-white/10">
                        Verified Donor Status
                    </span>
                </div>
            </div>
            {{-- Floating Decorative Heart --}}
            <i class="fa-solid fa-heart-pulse absolute -right-8 -bottom-8 text-[12rem] text-white/10 -rotate-12 group-hover:rotate-0 transition-transform duration-700"></i>
        </div>
    </div>

    {{-- 2. COLORFUL ACTIVITY SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        {{-- RECENT REQUESTS --}}
        <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-gray-50">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">Recent Requests</h3>
                <a href="{{ route('user.requests.create') }}" class="w-12 h-12 bg-[#FAFAFA] rounded-2xl flex items-center justify-center text-gray-400 hover:bg-[#D32F2F] hover:text-white transition-all shadow-inner">
                    <i class="fa-solid fa-plus text-lg"></i>
                </a>
            </div>

            <div class="space-y-8">
                @forelse($myRequests as $req)
                <div class="flex items-center p-6 rounded-[2rem] hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-[#D32F2F] mr-6 font-black text-lg shadow-sm border border-red-100/50">
                        {{ $req->blood_type }}
                    </div>
                    <div class="flex-1">
                        <p class="text-base font-black text-gray-800">{{ $req->hospital_name }}</p>
                        <p class="text-xs text-gray-400 font-bold tracking-wide mt-0.5">{{ $req->needed_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        @if($req->status == 'open')
                            <form action="{{ route('user.requests.complete', $req->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button class="text-[10px] font-black bg-[#2ECC71] text-white px-5 py-2.5 rounded-xl shadow-lg shadow-green-900/20 hover:scale-105 active:scale-95 transition-all">DONE</button>
                            </form>
                        @else
                            <span class="text-[10px] font-black py-2.5 px-5 rounded-xl bg-gray-100 text-gray-400 border border-gray-200 uppercase tracking-widest">Closed</span>
                        @endif
                    </div>
                </div>
                @empty
                    <p class="text-center py-10 text-gray-400 font-medium italic">No recent requests.</p>
                @endforelse
            </div>
            
            <div class="mt-10 pt-6 border-t border-gray-50">
                {{ $myRequests->appends(['donations_page' => $myDonations->currentPage()])->links() }}
            </div>
        </div>

        {{-- DONATION HISTORY --}}
        <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-gray-50">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">Donation History</h3>
                <i class="fa-solid fa-certificate text-blue-100 text-4xl"></i>
            </div>

            <div class="space-y-8">
                @forelse($myDonations as $donation)
                <div class="flex items-center p-6 rounded-[2rem] bg-[#FAFAFA] border border-gray-100/50 hover:border-blue-100 transition-all">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-blue-500 shadow-sm mr-6 border border-blue-50">
                        <i class="fa-solid fa-medal text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-base font-black text-gray-800">{{ $donation->blood_bank_name }}</p>
                        <p class="text-[11px] font-mono text-blue-600 font-bold uppercase tracking-widest mt-0.5">{{ $donation->invoice_code ?? 'Processing...' }}</p>
                    </div>
                    <div>
                        @if($donation->status == 'active')
                            <a href="{{ route('user.certificate', $donation->id) }}" target="_blank" class="w-12 h-12 bg-white border border-gray-100 rounded-2xl flex items-center justify-center text-gray-600 hover:text-blue-600 shadow-sm transition-all hover:scale-105 active:scale-95">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        @else
                            <div class="flex items-center gap-1.5 bg-yellow-50 px-3 py-1.5 rounded-full border border-yellow-100">
                                <div class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></div>
                                <span class="text-[9px] font-black text-yellow-700 uppercase">Pending</span>
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                    <p class="text-center py-10 text-gray-400 font-medium italic">No history available.</p>
                @endforelse
            </div>

            <div class="mt-10 pt-6 border-t border-gray-50">
                {{ $myDonations->appends(['requests_page' => $myRequests->currentPage()])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection