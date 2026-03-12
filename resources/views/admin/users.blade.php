@extends('layouts.admin') {{-- Assuming you have an admin layout --}}

@section('content')
<div class="space-y-6 animate-fade-in px-8 py-8">
    
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">User Directory</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Manage all registered donors and their account access.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
            <i class="fa-solid fa-users text-blue-500"></i>
            <span class="text-sm font-bold text-gray-700">Total Users: {{ $users->total() }}</span>
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
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Blood Type</th>
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
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ID: {{ $user->id_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Contact --}}
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-700">{{ $user->phone }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email ?? 'No email' }}</p>
                        </td>

                        {{-- Blood Type (Click to edit) --}}
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 text-red-600 font-black border border-red-100 shadow-sm cursor-pointer hover:bg-red-600 hover:text-white transition-colors" onclick="openBloodModal({{ $user->id }}, '{{ $user->blood_type }}')">
                                {{ $user->blood_type ?? '?' }}
                            </div>
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
                            <div class="flex items-center justify-end gap-2">
                                {{-- Block/Unblock Button --}}
                                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ $user->status == 'active' ? 'bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white' : 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white' }}" title="{{ $user->status == 'active' ? 'Block User' : 'Unblock User' }}">
                                        <i class="fa-solid {{ $user->status == 'active' ? 'fa-lock' : 'fa-unlock' }}"></i>
                                    </button>
                                </form>

                                {{-- Delete Button --}}
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this user? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition-colors" title="Delete User">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium italic">
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

    {{-- Update Blood Type Modal (Hidden by default) --}}
    <div id="bloodModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-[2rem] p-8 w-full max-w-sm shadow-2xl transform scale-95 transition-transform">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900">Edit Blood Type</h3>
                <button onclick="closeBloodModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="bloodForm" method="POST" action="">
                @csrf
                <div class="mb-6">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Select Correct Blood Type</label>
                    <select name="blood_type" id="bloodSelect" class="w-full bg-gray-50 border border-gray-200 p-4 rounded-xl font-bold text-gray-800 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100">
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-red-600 text-white font-black uppercase tracking-widest py-4 rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-200">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

</div>

{{-- Script for handling the Blood Type Modal --}}
<script>
    function openBloodModal(userId, currentBloodType) {
        // Set the form action dynamically based on the user ID
        document.getElementById('bloodForm').action = `/admin/users/${userId}/update-blood`;
        
        // Set the current blood type in the dropdown
        let select = document.getElementById('bloodSelect');
        if(currentBloodType) {
            select.value = currentBloodType;
        } else {
            select.value = 'A+'; // fallback
        }

        // Show modal
        document.getElementById('bloodModal').classList.remove('hidden');
    }

    function closeBloodModal() {
        document.getElementById('bloodModal').classList.add('hidden');
    }
</script>
@endsection