@extends('layouts.admin') {{-- Assuming you have an admin layout --}}

@section('content')
<div class="space-y-6 animate-fade-in px-8 py-8">
    
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">User Directory</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Manage all registered donors and their account access.</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.users') }}" method="GET" class="relative">
                <i class="fa-solid fa-filter absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 z-10 pointer-events-none text-sm"></i>
                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl pl-10 pr-10 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F]/20 shadow-sm cursor-pointer appearance-none relative z-0 hover:border-gray-300 transition-colors">
                    <option value="all" {{ ($currentStatus ?? 'all') == 'all' ? 'selected' : '' }}>All ({{ $allCount ?? 0 }})</option>
                    <option value="pending" {{ ($currentStatus ?? '') == 'pending' ? 'selected' : '' }}>Pending ({{ $pendingCount ?? 0 }})</option>
                    <option value="verified" {{ ($currentStatus ?? '') == 'verified' ? 'selected' : '' }}>Verified ({{ $verifiedCount ?? 0 }})</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-xs z-10"></i>
            </form>

            <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <i class="fa-solid fa-users text-blue-500"></i>
                <span class="text-sm font-bold text-gray-700">Total Users: {{ $users->total() }}</span>
            </div>
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

    {{-- Users Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Donor</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">KYC Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Account</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        
                        {{-- Donor Info --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-black text-gray-900">{{ $user->name }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Contact --}}
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-700">{{ $user->phone }}</p>
                            <!-- <p class="text-xs text-gray-400">{{ $user->email ?? 'No email' }}</p> -->
                        </td>

                        {{-- KYC Status --}}
                        <td class="px-6 py-4 text-center">
                            @if($user->kyc_status == 'verified')
                                <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"><i class="fa-solid fa-shield-check mr-1"></i> Verified</span>
                            @elseif($user->kyc_status == 'rejected')
                                <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"><i class="fa-solid fa-xmark mr-1"></i> Rejected</span>
                            @else
                                <span class="bg-yellow-50 text-yellow-600 border border-yellow-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"><i class="fa-solid fa-clock mr-1"></i> Pending</span>
                            @endif
                        </td>

                        {{-- Account Status (Active/Blocked) --}}
                        <td class="px-6 py-4 text-center">
                            @if($user->status == 'active')
                                <span class="text-green-500 font-bold text-xs"><i class="fa-solid fa-circle text-[8px] mr-1"></i> Active</span>
                            @else
                                <span class="text-red-500 font-bold text-xs"><i class="fa-solid fa-ban text-[8px] mr-1"></i> Blocked</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-right">
                            <details class="relative inline-block text-left">
                                <summary class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-900 hover:text-white flex items-center justify-center transition-colors cursor-pointer list-none" title="Actions" aria-label="Actions Menu">
                                    <i class="fa-solid fa-list"></i>
                                </summary>

                                <div class="absolute right-0 mt-2 w-52 bg-white border border-gray-100 rounded-xl shadow-lg z-20 p-2 space-y-1">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="w-full px-3 py-2 rounded-lg text-sm font-bold text-blue-600 hover:bg-blue-50 flex items-center gap-2">
                                        <i class="fa-solid fa-eye w-4"></i> View Detail
                                    </a>

                                    <form action="{{ route('admin.users.reset_password', $user->id) }}" method="POST" onsubmit="return confirm('Reset this user password to 123456?');">
                                        @csrf
                                        <button type="submit" class="w-full px-3 py-2 rounded-lg text-sm font-bold text-amber-600 hover:bg-amber-50 flex items-center gap-2 text-left">
                                            <i class="fa-solid fa-key w-4"></i> Reset Password
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-3 py-2 rounded-lg text-sm font-bold {{ $user->status == 'active' ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} flex items-center gap-2 text-left">
                                            <i class="fa-solid {{ $user->status == 'active' ? 'fa-lock' : 'fa-unlock' }} w-4"></i> {{ $user->status == 'active' ? 'Block User' : 'Unblock User' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this user? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-3 py-2 rounded-lg text-sm font-bold text-red-600 hover:bg-red-50 flex items-center gap-2 text-left">
                                            <i class="fa-solid fa-trash-can w-4"></i> Delete User
                                        </button>
                                    </form>
                                </div>
                            </details>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium italic">
                            No users found in the system.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-50">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection