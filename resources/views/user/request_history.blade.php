@extends('layouts.user')

@section('content')
<div class="space-y-8 animate-fade-in">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Blood Requested History</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">A full record of all your blood requests.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('user.requests.create') }}" class="flex items-center gap-2 bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md hover:bg-red-700 transition-all hover:-translate-y-0.5">
                <i class="fa-solid fa-plus"></i> New Request
            </a>
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white text-gray-600 font-bold rounded-xl shadow-sm border border-gray-100 hover:bg-red-50 hover:text-red-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-sm"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- Success / Error Alerts --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-black text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Total</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-green-100 shadow-sm text-center">
            <p class="text-3xl font-black text-green-600">{{ $stats['open'] }}</p>
            <p class="text-xs font-bold text-green-400 uppercase tracking-widest mt-1">Active / Open</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-blue-100 shadow-sm text-center">
            <p class="text-3xl font-black text-blue-600">{{ $stats['completed'] }}</p>
            <p class="text-xs font-bold text-blue-400 uppercase tracking-widest mt-1">Completed</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-black text-gray-400">{{ $stats['cancelled'] }}</p>
            <p class="text-xs font-bold text-gray-300 uppercase tracking-widest mt-1">Cancelled / Expired</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="GET" action="{{ route('user.requests.history') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            
            {{-- Status Filter --}}
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Status</label>
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-800 outline-none focus:ring-2 focus:ring-red-400 cursor-pointer">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="open"      {{ request('status') === 'open'      ? 'selected' : '' }}>Open</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="reserved"  {{ request('status') === 'reserved'  ? 'selected' : '' }}>Reserved</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            {{-- Blood Type Filter --}}
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Blood Type</label>
                <select name="blood_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-800 outline-none focus:ring-2 focus:ring-red-400 cursor-pointer">
                    <option value="">All Types</option>
                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $type)
                        <option value="{{ $type }}" {{ request('blood_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            {{-- From Date --}}
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-800 outline-none focus:ring-2 focus:ring-red-400 cursor-pointer">
            </div>

            {{-- To Date + Submit --}}
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">To Date</label>
                <div class="flex gap-2">
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                           class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-800 outline-none focus:ring-2 focus:ring-red-400 cursor-pointer">
                    <button type="submit" class="px-4 py-2.5 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition-all shadow-sm flex-shrink-0">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    @if(request()->hasAny(['status', 'blood_type', 'from_date', 'to_date']))
                        <a href="{{ route('user.requests.history') }}" class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all flex-shrink-0" title="Clear filters">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </div>

        </form>
    </div>

    {{-- Request History Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">#</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Blood Type</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Hospital</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Patient</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Quantity</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Date Needed</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Submitted</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-5 text-sm text-gray-400 font-bold">{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-red-50 text-red-600 font-black text-sm border border-red-100/50">
                                {{ $req->blood_type }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-800 text-sm">{{ $req->hospital_name }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm text-gray-500 font-medium">{{ $req->patient_name ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-sm font-bold text-gray-700">{{ $req->quantity }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-sm font-bold text-gray-700">{{ $req->needed_date->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-xs text-gray-400 font-medium">{{ $req->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusConfig = [
                                    'open'      => ['bg-emerald-100 text-emerald-700', 'Open'],
                                    'completed' => ['bg-blue-100 text-blue-700',       'Completed'],
                                    'reserved'  => ['bg-orange-100 text-orange-700',   'Reserved'],
                                    'cancelled' => ['bg-gray-100 text-gray-500',       'Cancelled'],
                                    'expired'   => ['bg-rose-100 text-rose-600',       'Expired'],
                                ];
                                [$cls, $label] = $statusConfig[$req->status] ?? ['bg-gray-100 text-gray-500', ucfirst($req->status)];
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $cls }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($req->status === 'open')
                                <form action="{{ route('user.requests.complete', $req->id) }}" method="POST"
                                      onsubmit="return confirm('Mark this request as completed?')">
                                    @csrf @method('PUT')
                                    <button type="submit"
                                            class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm"
                                            title="Mark as Done">
                                        <i class="fa-solid fa-check mr-1"></i> Done
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-300 font-bold">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4 text-gray-400">
                                <i class="fa-solid fa-droplet-slash text-5xl opacity-30"></i>
                                <div>
                                    <p class="font-black text-lg text-gray-500">No requests found</p>
                                    <p class="text-sm font-medium mt-1">
                                        @if(request()->hasAny(['status', 'blood_type', 'from_date', 'to_date']))
                                            Try adjusting your filters or <a href="{{ route('user.requests.history') }}" class="text-red-500 underline">clear all filters</a>.
                                        @else
                                            You haven't submitted any blood requests yet.
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('user.requests.create') }}" class="mt-2 inline-flex items-center gap-2 bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md hover:bg-red-700 transition-all">
                                    <i class="fa-solid fa-plus"></i> Make your first request
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
        <div class="p-6 border-t border-gray-50">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
