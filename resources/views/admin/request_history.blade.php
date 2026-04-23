@extends('layouts.admin')

@section('content')
@php
    $adminUser = auth('admin')->user();

    $isSuperAdmin = $adminUser
        ? $adminUser->hasRole('Super Admin', 'web')
        : false;

    $can = fn (string $permission): bool => $adminUser && (
        $isSuperAdmin || $adminUser->checkPermissionTo($permission, 'web')
    );
@endphp
<div class="space-y-6 animate-fade-in px-8 py-8"">

    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Blood Requested History</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Full archive of all blood requests submitted on the platform.</p>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        {{-- Filters --}}
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <div class="{{ request()->hasAny(['search', 'status', 'blood_type', 'from_date', 'to_date']) ? 'mb-5 ' : '' }}flex flex-wrap items-center gap-2 text-xs font-bold text-gray-500">
                @if(request()->hasAny(['search', 'status', 'blood_type', 'from_date', 'to_date']))
                    <span class="inline-flex items-center gap-2 rounded-lg border border-red-100 bg-red-50 px-3 py-1.5 text-red-600">
                        <i class="fa-solid fa-filter"></i>
                        Filter active
                    </span>
                @endif
            </div>

            <form method="GET" action="{{ route('admin.requests.history') }}" class="space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-center rounded-xl border border-gray-200 bg-white overflow-hidden">
                    <div class="relative flex-1 min-w-0 border-b lg:border-b-0 lg:border-r border-gray-200">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by requester, hospital, or patient..."
                               class="w-full bg-transparent pl-9 pr-4 py-3 text-sm font-semibold text-gray-800 outline-none placeholder:text-gray-400">
                    </div>

                    <div class="flex-1 min-w-[180px] border-b lg:border-b-0 lg:border-r border-gray-200">
                        <select name="status" class="w-full bg-transparent px-4 py-3 text-sm font-semibold text-gray-700 outline-none cursor-pointer">
                            <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Status: All</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Status: Open</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Status: Completed</option>
                            <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Status: Reserved</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Status: Cancelled</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Status: Expired</option>
                        </select>
                    </div>

                    <div class="flex-1 min-w-[160px] border-b lg:border-b-0 lg:border-r border-gray-200">
                        <select name="blood_type" class="w-full bg-transparent px-4 py-3 text-sm font-semibold text-gray-700 outline-none cursor-pointer">
                            <option value="">Blood Type: All</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $type)
                                <option value="{{ $type }}" {{ request('blood_type') === $type ? 'selected' : '' }}>Blood Type: {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2 p-2 lg:p-1.5">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-700 transition-colors shadow-sm">
                            <i class="fa-solid fa-filter text-xs"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'status', 'blood_type', 'from_date', 'to_date']))
                            <a href="{{ route('admin.requests.history') }}" class="inline-flex items-center justify-center rounded-lg bg-gray-50 border border-gray-200 px-3 py-2.5 text-sm font-bold text-gray-500 hover:bg-gray-100 transition-colors" title="Clear filters">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="rounded-xl border border-gray-200 bg-white px-3 py-2.5 flex items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">From</span>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full bg-transparent text-sm font-semibold text-gray-700 outline-none">
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white px-3 py-2.5 flex items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">To</span>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full bg-transparent text-sm font-semibold text-gray-700 outline-none">
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">#</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Requested By</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Hospital</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Patient</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Type</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Date Needed</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Submitted</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-5 text-sm text-gray-400 font-bold text-center">
                            {{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-100 font-black text-gray-500 flex items-center justify-center text-sm flex-shrink-0">
                                    {{ substr($req->requester->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800 text-sm">{{ $req->requester->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">{{ $req->requester->phone ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-800 text-sm">{{ $req->hospital_name }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm text-gray-500 font-medium">{{ $req->patient_name ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-10 rounded-xl bg-red-50 text-red-600 font-black text-sm border border-red-100/50">
                                {{ $req->blood_type }}
                            </span>
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
                                $statusMap = [
                                    'open'      => 'bg-emerald-100 text-emerald-700',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                    'reserved'  => 'bg-orange-100 text-orange-700',
                                    'cancelled' => 'bg-gray-100 text-gray-500',
                                    'expired'   => 'bg-rose-100 text-rose-600',
                                ];
                                $cls = $statusMap[$req->status] ?? 'bg-gray-100 text-gray-500';
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $cls }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex justify-center items-center gap-2">
                                {{-- Mark as Done (open only) --}}
                                @if($req->status === 'open' && $can('accept_requests'))
                                    <form action="{{ route('admin.requests.status', $req->id) }}" method="POST"
                                          onsubmit="return confirm('Mark this request as completed?')">
                                        @csrf
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit"
                                                class="w-9 h-9 rounded-xl bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition-all shadow-sm"
                                                title="Mark as Done">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Delete --}}
                                @if($can('reject_requests'))
                                    <form action="{{ route('admin.requests.delete', $req->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this request permanently?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm"
                                                title="Delete">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i class="fa-solid fa-droplet-slash text-5xl opacity-30"></i>
                                <p class="font-black text-lg text-gray-500">No requests found</p>
                                <p class="text-sm font-medium">
                                    @if(request()->hasAny(['search', 'status', 'blood_type']))
                                        Try adjusting your filters or
                                        <a href="{{ route('admin.requests.history') }}" class="text-red-500 underline">clear all filters</a>.
                                    @else
                                        No blood requests have been submitted yet.
                                    @endif
                                </p>
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
