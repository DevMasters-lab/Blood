@extends('layouts.admin')

@section('content')
<div class="space-y-6 animate-fade-in px-8 py-8">
    
    {{-- Header & Filters --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Donation Invoices</h2>
            <p class="text-sm text-gray-500 mt-2 font-medium">Review and manage submitted donation records.</p>
        </div>
        
        <div class="flex gap-3">
            {{-- DYNAMIC FILTER FORM --}}
            <form action="{{ route('admin.donations') }}" method="GET" class="relative">
                <i class="fa-solid fa-filter absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 z-10 pointer-events-none text-sm"></i>
                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl pl-10 pr-10 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F]/20 shadow-sm cursor-pointer appearance-none relative z-0 hover:border-gray-300 transition-colors">
                    <option value="all" {{ ($currentStatus ?? '') == 'all' ? 'selected' : '' }}>
                        All ({{ $allCount ?? 0 }})
                    </option>
                    <option value="pending" {{ ($currentStatus ?? 'pending') == 'pending' ? 'selected' : '' }}>
                        Pending ({{ $pendingCount ?? 0 }})
                    </option>
                    <option value="approved" {{ ($currentStatus ?? '') == 'approved' ? 'selected' : '' }}>
                        Approved ({{ $approvedCount ?? 0 }})
                    </option>
                    <option value="rejected" {{ ($currentStatus ?? '') == 'rejected' ? 'selected' : '' }}>
                        Rejected ({{ $rejectedCount ?? 0 }})
                    </option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-xs z-10"></i>
            </form>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 mb-6 shadow-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 mb-6 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Invoices Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        @forelse($donations as $invoice)
        
        {{-- Invoice Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col sm:flex-row hover:border-gray-200">
            
            {{-- Left Side: Document Preview (UPDATED FOR IMAGES) --}}
            <div class="sm:w-2/5 bg-gradient-to-br from-gray-50 to-gray-100 relative group cursor-pointer border-b sm:border-b-0 sm:border-r border-gray-100 flex-shrink-0 min-h-[240px] overflow-hidden">
                @if($invoice->proofFile)
                    @php
                        // Check if the file is an image
                        $extension = pathinfo($invoice->proofFile->path, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    @endphp

                    <a href="{{ asset('storage/' . $invoice->proofFile->path) }}" target="_blank" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 transition-colors">
                        @if($isImage)
                            {{-- Render Image Preview --}}
                            <img src="{{ asset('storage/' . $invoice->proofFile->path) }}" alt="Proof Document" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity duration-300">
                            
                            {{-- Hover Overlay for Images --}}
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <span class="text-white text-xs font-bold uppercase tracking-widest bg-black/50 px-4 py-2 rounded-xl backdrop-blur-sm shadow-lg">
                                    <i class="fa-solid fa-expand mr-1"></i> View Full Image
                                </span>
                            </div>
                        @else
                            {{-- Render PDF/Generic Icon --}}
                            <div class="absolute inset-0 group-hover:bg-black/5 transition-colors flex flex-col items-center justify-center">
                                <i class="fa-solid fa-file-pdf text-5xl mb-3 text-[#D32F2F]/70 group-hover:text-[#D32F2F] transition-colors z-10"></i>
                                <span class="text-xs font-bold uppercase tracking-widest group-hover:text-[#D32F2F] transition-colors z-10">View Document</span>
                            </div>
                        @endif
                    </a>
                @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                        <i class="fa-solid fa-file-circle-xmark text-5xl mb-3 text-gray-300"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">No Proof</span>
                    </div>
                @endif
                
                {{-- Status Badge over image --}}
                <div class="absolute top-4 left-4 z-20">
                    @if($invoice->status == 'active')
                        <span class="bg-green-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-lg">Approved</span>
                    @elseif($invoice->status == 'rejected')
                        <span class="bg-red-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-lg">Rejected</span>
                    @else
                        <span class="bg-orange-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-lg">Pending</span>
                    @endif
                </div>
            </div>

            {{-- Right Side: Details & Actions --}}
            <div class="p-8 sm:w-3/5 flex flex-col">
                {{-- Invoice Context --}}
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] font-black text-[#D32F2F] uppercase tracking-widest">
                            {{ $invoice->invoice_code ? 'Invoice #' . $invoice->invoice_code : 'Invoice #' . $invoice->id }}
                        </p>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 leading-tight">{{ $invoice->blood_bank_name }}</h3>
                    <div class="flex flex-col gap-1 mt-2">
                        <p class="text-xs font-bold text-gray-500">Donation Date: <strong class="text-gray-700">{{ \Carbon\Carbon::parse($invoice->donation_date)->format('d M Y') }}</strong></p>
                        <p class="text-xs font-bold text-gray-500">Expiry Date: <strong class="text-red-600">{{ \Carbon\Carbon::parse($invoice->expiry_date)->format('d M Y') }}</strong></p>
                    </div>
                </div>

                {{-- Donor Info --}}
                <div class="mb-8 flex-grow">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Donor Information</p>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#D32F2F]/20 to-[#D32F2F]/5 flex items-center justify-center text-[#D32F2F] font-bold shrink-0 text-lg">
                            {{ substr($invoice->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-gray-900">{{ $invoice->user->name }}</p>
                            @if($invoice->user && $invoice->user->phone)
                                <a href="tel:{{ $invoice->user->phone }}" class="text-xs font-bold text-[#D32F2F] hover:text-red-700 transition-colors flex items-center gap-1 mt-1">
                                    <i class="fa-solid fa-phone text-[10px]"></i> {{ $invoice->user->phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                    @if($invoice->user && $invoice->user->email)
                        <div class="flex items-center gap-2 text-xs">
                            <i class="fa-solid fa-envelope text-gray-400"></i>
                            <span class="text-gray-600 font-medium">{{ $invoice->user->email }}</span>
                        </div>
                    @endif
                </div>

                {{-- Action Buttons (Only show if pending) --}}
                @if($invoice->status == 'pending')
                    <div class="flex gap-3 mt-auto">
                        <form action="{{ route('admin.donations.status', $invoice->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="active">
                            <button onclick="return confirm('Approve this donation?')" class="w-full bg-green-50 text-green-600 border border-green-200 hover:bg-green-600 hover:text-white hover:border-green-600 text-sm font-black py-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow-lg">
                                <i class="fa-solid fa-check"></i> Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.donations.status', $invoice->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button onclick="return confirm('Reject this donation?')" class="w-full bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white hover:border-red-600 text-sm font-black py-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow-lg">
                                <i class="fa-solid fa-xmark"></i> Reject
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-auto pt-4 border-t border-gray-100">
                        <p class="text-xs font-bold text-gray-400 text-center">Status is locked</p>
                    </div>
                @endif
            </div>
        </div>

        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-20 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-50 to-gray-100 rounded-full flex items-center justify-center text-gray-300 text-4xl mx-auto mb-6 border border-gray-100 shadow-inner">
                    <i class="fa-solid fa-inbox"></i>
                </div>
                @if($currentStatus === 'all')
                    <p class="text-lg font-black text-gray-900 mb-2">No invoices to review</p>
                    <p class="text-sm font-medium text-gray-500">All donation invoices have been processed.</p>
                @else
                    <p class="text-lg font-black text-gray-900 mb-2">No {{ $currentStatus }} invoices</p>
                    <p class="text-sm font-medium text-gray-500">Try changing your filter to see other invoices.</p>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination (Preserves the filter parameter) --}}
    @if(method_exists($donations, 'hasPages') && $donations->hasPages())
    <div class="mt-12">
        {{ $donations->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection