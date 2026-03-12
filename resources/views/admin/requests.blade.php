@extends('layouts.admin') {{-- Assuming you have an admin layout --}}

@section('content')
<div class="space-y-6 animate-fade-in px-8 py-8">
<!-- <div class="space-y-6 animate-fade-in"> -->
    {{-- Header section with total count --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Manage Blood Requests</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Review and remove incoming blood allocation needs.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
            <i class="fa-solid fa-notes-medical text-red-500"></i>
            <span class="text-sm font-bold text-gray-700">Total Requests: {{ $requests->total() }}</span>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Requests Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Requested By</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Hospital</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Type</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Date Needed</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 font-black text-gray-500 flex items-center justify-center text-sm flex-shrink-0">
                                    {{ substr($req->requester->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800">{{ $req->requester->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $req->requester->phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="font-bold text-blue-600">{{ $req->requester->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-8 py-6">{{ $req->hospital_name }}</td>
                        <td class="px-8 py-6 text-center font-bold text-red-600">{{ $req->blood_type }}</td>
                        <td class="px-8 py-6 text-center">{{ $req->needed_date->format('d M Y') }}</td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $req->status == 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-center items-center gap-2">
                            {{-- Mark as Done Button (Only show if open) --}}
                            @if($req->status == 'open')
                                <form action="{{ route('admin.requests.status', $req->id) }}" method="POST" onsubmit="return confirm('Mark this request as completed?')">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Mark as Done">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Delete Button --}}
                            <form action="{{ route('admin.requests.delete', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure? This will remove the request permanently.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Delete Request">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-16 text-center text-gray-400 font-medium italic">
                            No blood requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-50">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection