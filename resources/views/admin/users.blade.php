@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
        Total Users: {{ $users->total() }}
    </span>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">User Info</th>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Blood & KYC</th>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50 transition">
                
                {{-- 1. USER INFO (Avatar + Name) --}}
                <td class="p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($user->avatar)
                                <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ asset('storage/' . $user->avatar) }}" alt="">
                            @else
                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-500 font-bold text-lg">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">Joined {{ $user->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </td>

                {{-- 2. CONTACT (Phone + Email) --}}
                <td class="p-4">
                    <div class="text-sm text-gray-900"><i class="fa-solid fa-phone text-gray-400 mr-1"></i> {{ $user->phone }}</div>
                    @if($user->email)
                        <div class="text-xs text-gray-500 mt-1">{{ $user->email }}</div>
                    @endif
                </td>

                {{-- 3. BLOOD & KYC --}}
                <td class="p-4">
                    <div class="flex flex-col space-y-1">
                        <span class="text-sm font-bold text-red-600">
                            <i class="fa-solid fa-droplet mr-1"></i> {{ $user->blood_type ?? 'Unknown' }}
                        </span>
                        
                        @if($user->kyc_status == 'verified')
                            <span class="text-xs text-blue-600 font-semibold flex items-center">
                                <i class="fa-solid fa-circle-check mr-1"></i> Verified
                            </span>
                        @else
                            <form action="{{ route('admin.users.verify', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button class="text-xs text-gray-400 hover:text-blue-600 underline">
                                    Mark Verified
                                </button>
                            </form>
                        @endif
                    </div>
                </td>

                {{-- 4. STATUS (Active/Blocked) --}}
                <td class="p-4">
                    @if($user->status == 'active')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Blocked
                        </span>
                    @endif
                </td>

                {{-- 5. ACTIONS --}}
                <td class="p-4 text-right whitespace-nowrap text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                        
                        {{-- Block / Unblock Button --}}
                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                            @csrf
                            @if($user->status == 'active')
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 px-3 py-1 rounded transition border border-yellow-200" title="Block User">
                                    <i class="fa-solid fa-ban"></i> Block
                                </button>
                            @else
                                <button type="submit" class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded transition border border-green-200" title="Unblock User">
                                    <i class="fa-solid fa-check"></i> Activate
                                </button>
                            @endif
                        </form>

                        {{-- Delete Button --}}
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Deleting a user will also delete all their requests and donations. Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition border border-red-200" title="Delete User">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <div class="p-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
</div>
@endsection