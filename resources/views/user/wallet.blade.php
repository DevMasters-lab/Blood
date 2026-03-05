@extends('layouts.user')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4">
    <div class="mb-10 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">My Invoice Wallet</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Manage your active and expired donation records.</p>
        </div>
        <a href="{{ route('user.donate') }}" class="bg-red-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-md hover:bg-red-700 transition-all">
            + New Submission
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($invoices as $invoice)
            @php
                // Countdown Logic
                $now = \Carbon\Carbon::now();
                $expiry = \Carbon\Carbon::parse($invoice->expiry_date);
                $daysLeft = $now->diffInDays($expiry, false); // false allows negative numbers if expired
            @endphp

            <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm relative overflow-hidden flex flex-col">
                {{-- Status Badge --}}
                <div class="flex justify-between items-start mb-6">
                    <div>
                        @if($invoice->status == 'active')
                            <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest">Active</span>
                        @elseif($invoice->status == 'pending')
                            <span class="bg-orange-100 text-orange-700 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest">Pending Review</span>
                        @elseif($invoice->status == 'rejected')
                            <span class="bg-rose-100 text-rose-700 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest">Rejected</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest">Expired</span>
                        @endif
                    </div>
                    @if($invoice->status == 'active')
                        <i class="fa-solid fa-certificate text-emerald-400 text-2xl opacity-50"></i>
                    @endif
                </div>

                {{-- Details --}}
                <h3 class="text-lg font-black text-gray-900 mb-1">{{ $invoice->blood_bank_name }}</h3>
                <p class="text-xs font-bold text-gray-500 mb-4">Donated: {{ $invoice->donation_date->format('M d, Y') }}</p>

                {{-- Invoice Code (Only if active) --}}
                @if($invoice->status == 'active' && $invoice->invoice_code)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 mb-4 text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Official Invoice Code</p>
                        <p class="text-lg font-mono font-black text-slate-800 tracking-widest">{{ $invoice->invoice_code }}</p>
                    </div>
                @endif

                {{-- Countdown Footer --}}
                <div class="mt-auto pt-4 border-t border-gray-100">
                    @if($invoice->status == 'active')
                        @if($daysLeft > 0)
                            <p class="text-sm font-bold text-gray-800"><i class="fa-regular fa-clock text-orange-500 mr-1"></i> {{ floor($daysLeft) }} days until expiry</p>
                        @else
                            <p class="text-sm font-bold text-rose-600"><i class="fa-solid fa-circle-exclamation mr-1"></i> Blood unit has expired.</p>
                        @endif
                    @elseif($invoice->status == 'pending')
                        <p class="text-xs font-bold text-gray-400 italic">Code will generate upon approval.</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center border-2 border-dashed border-gray-200 rounded-[2rem]">
                <i class="fa-solid fa-wallet text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-bold">Your wallet is empty.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection