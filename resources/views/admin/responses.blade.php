@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- Header & Filters --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Donor Proof Verification</h2>
            <p class="text-sm text-gray-500 mt-1.5 font-medium">Review uploaded donation slips for urgent blood requests.</p>
        </div>
        
        <div class="flex gap-3">
            <div class="relative">
                <i class="fa-solid fa-filter absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select class="bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl pl-10 pr-8 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F]/20 shadow-sm cursor-pointer appearance-none">
                    <option value="pending" selected>Pending Proofs (4)</option>
                    <option value="verified">Verified Proofs</option>
                    <option value="rejected">Rejected Proofs</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
            </div>
        </div>
    </div>

    {{-- Proofs Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- SAMPLE CARD 1 --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col sm:flex-row">
            
            {{-- Left Side: Image Preview --}}
            <div class="sm:w-2/5 bg-gray-50 relative group cursor-pointer border-b sm:border-b-0 sm:border-r border-gray-100 flex-shrink-0 min-h-[200px]">
                {{-- Placeholder for actual uploaded slip --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 group-hover:bg-gray-800/5 transition-colors">
                    <i class="fa-solid fa-image text-4xl mb-2 text-gray-300"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">View Document</span>
                </div>
                {{-- Status Badge over image --}}
                <div class="absolute top-4 left-4 bg-orange-500 text-white text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm">
                    Pending
                </div>
            </div>

            {{-- Right Side: Details & Actions --}}
            <div class="p-6 sm:w-3/5 flex flex-col">
                {{-- Request Context --}}
                <div class="mb-4 pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-start mb-1">
                        <p class="text-[10px] font-black text-[#D32F2F] uppercase tracking-widest">Request #1042</p>
                        <span class="w-8 h-8 rounded-lg bg-[#D32F2F] text-white flex items-center justify-center font-black text-sm shadow-sm">A+</span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 leading-tight">Royal Phnom Penh Hospital</h3>
                    <p class="text-xs font-bold text-gray-500 mt-1">Needed by: 20 Feb 2026</p>
                </div>

                {{-- Donor Info --}}
                <div class="mb-6 flex-grow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold shrink-0">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Donor Responded</p>
                            <p class="text-sm font-bold text-gray-900">Sokha Developer</p>
                            <a href="tel:012345678" class="text-xs font-bold text-[#D32F2F] hover:underline flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-phone text-[10px]"></i> +855 12 345 678
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3 mt-auto">
                    <button class="flex-1 bg-green-50 text-green-600 border border-green-200 hover:bg-green-500 hover:text-white hover:border-green-500 text-sm font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Verify
                    </button>
                    <button class="flex-1 bg-red-50 text-red-600 border border-red-200 hover:bg-red-500 hover:text-white hover:border-red-500 text-sm font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-xmark"></i> Reject
                    </button>
                </div>
            </div>
        </div>

        {{-- SAMPLE CARD 2 --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col sm:flex-row">
            
            {{-- Left Side: Image Preview --}}
            <div class="sm:w-2/5 bg-gray-50 relative group cursor-pointer border-b sm:border-b-0 sm:border-r border-gray-100 flex-shrink-0 min-h-[200px]">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 group-hover:bg-gray-800/5 transition-colors">
                    <i class="fa-solid fa-file-pdf text-4xl mb-2 text-gray-300"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">View PDF</span>
                </div>
                <div class="absolute top-4 left-4 bg-orange-500 text-white text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm">
                    Pending
                </div>
            </div>

            {{-- Right Side: Details & Actions --}}
            <div class="p-6 sm:w-3/5 flex flex-col">
                {{-- Request Context --}}
                <div class="mb-4 pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-start mb-1">
                        <p class="text-[10px] font-black text-[#D32F2F] uppercase tracking-widest">Request #1038</p>
                        <span class="w-8 h-8 rounded-lg bg-[#D32F2F] text-white flex items-center justify-center font-black text-sm shadow-sm">O-</span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 leading-tight">Khmer-Soviet Hospital</h3>
                    <p class="text-xs font-bold text-gray-500 mt-1">Needed by: 19 Feb 2026</p>
                </div>

                {{-- Donor Info --}}
                <div class="mb-6 flex-grow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold shrink-0">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Donor Responded</p>
                            <p class="text-sm font-bold text-gray-900">Minea Chan</p>
                            <a href="tel:098765432" class="text-xs font-bold text-[#D32F2F] hover:underline flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-phone text-[10px]"></i> +855 98 765 432
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3 mt-auto">
                    <button class="flex-1 bg-green-50 text-green-600 border border-green-200 hover:bg-green-500 hover:text-white hover:border-green-500 text-sm font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Verify
                    </button>
                    <button class="flex-1 bg-red-50 text-red-600 border border-red-200 hover:bg-red-500 hover:text-white hover:border-red-500 text-sm font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-xmark"></i> Reject
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection