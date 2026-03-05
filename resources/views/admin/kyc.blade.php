@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">KYC Verifications</h2>
            <p class="text-sm text-gray-500 mt-1 font-medium">Review official IDs to verify donors and secure the platform.</p>
        </div>
        <div class="bg-white px-5 py-2.5 rounded-full border border-gray-200 text-sm font-bold text-gray-600 shadow-sm">
            Pending Review ({{ $pendingCount }})
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-200">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Pending --}}
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pending</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $pendingCount }} Submissions</h3>
            </div>
        </div>

        {{-- Verified Today --}}
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-shield-check"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Verified Today</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $verifiedTodayCount }} Users</h3>
            </div>
        </div>

        {{-- Rejected --}}
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rejected</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $rejectedCount }} Users</h3>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">User Details</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">ID / Passport Info</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Document Proof</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pendingUsers as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 font-black text-gray-500 flex items-center justify-center">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs font-bold text-gray-500 mt-0.5">{{ $user->phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-gray-900">{{ $user->id_number }}</p>
                                <span class="inline-block mt-1 px-2.5 py-1 bg-orange-100 text-orange-600 text-[9px] font-black uppercase tracking-widest rounded-md">Pending</span>
                            </td>
                            <td class="px-8 py-6">
                                @if($user->proofFiles->isNotEmpty())
                                    <a href="{{ $user->proofFiles->first()->url }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition-colors border border-blue-100">
                                        <i class="fa-solid fa-image"></i> View Document
                                    </a>
                                @else
                                    <span class="text-xs font-bold text-gray-400 italic">No Document</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                {{-- Removed hover opacity classes here --}}
                                <div class="flex justify-end gap-2">
                                    {{-- Approve Form --}}
                                    <form action="{{ route('admin.kyc.approve', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors border border-green-100" title="Verify User">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    {{-- Reject Form --}}
                                    <form action="{{ route('admin.kyc.reject', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to REJECT this user? They will not be able to log in.');">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors border border-red-100" title="Reject User">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center text-gray-400 font-bold">
                                <i class="fa-solid fa-shield-check text-4xl mb-3 text-gray-300"></i>
                                <p>All caught up! No pending verifications.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection