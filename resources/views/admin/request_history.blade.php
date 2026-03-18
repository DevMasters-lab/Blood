@extends('layouts.admin')

@section('content')
<div class="space-y-6 animate-fade-in px-8 py-8">

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

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-black text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Total</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-emerald-100 shadow-sm text-center">
            <p class="text-3xl font-black text-emerald-600">{{ $stats['open'] }}</p>
            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mt-1">Open</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-blue-100 shadow-sm text-center">
            <p class="text-3xl font-black text-blue-600">{{ $stats['completed'] }}</p>
            <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mt-1">Completed</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-black text-gray-400">{{ $stats['cancelled'] }}</p>
            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mt-1">Cancelled / Expired</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="GET" action="{{ route('admin.requests.history') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">

            {{-- Search --}}
            <div class="lg:col-span-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Search</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Name, hospital, patient..."
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm font-medium text-gray-800 outline-none focus:ring-2 focus:ring-red-400">
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Status</label>
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-800 outline-none focus:ring-2 focus:ring-red-400 cursor-pointer">
                    <option value="all"      {{ request('status', 'all') === 'all'      ? 'selected' : '' }}>All Statuses</option>
                    <option value="open"      {{ request('status') === 'open'      ? 'selected' : '' }}>Open</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="reserved"  {{ request('status') === 'reserved'  ? 'selected' : '' }}>Reserved</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            {{-- Blood Type --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Blood Type</label>
                <select name="blood_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-800 outline-none focus:ring-2 focus:ring-red-400 cursor-pointer">
                    <option value="">All Types</option>
                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $type)
                        <option value="{{ $type }}" {{ request('blood_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Actions --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">&nbsp;</label>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition-all shadow-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-filter text-xs"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'blood_type', 'from_date', 'to_date']))
                        <a href="{{ route('admin.requests.history') }}" class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all flex-shrink-0" title="Clear filters">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </div>

        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">#</th>
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
                        <td class="px-6 py-5 text-sm text-gray-400 font-bold">
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
                                @if($req->status === 'open')
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
                                <form action="{{ route('admin.requests.delete', $req->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this request permanently?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm"
                                            title="Delete">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
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
