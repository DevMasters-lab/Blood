@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manage Users</h2>
    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
        Total: {{ $users->total() }}
    </span>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-4 text-gray-600 font-semibold">Name</th>
                <th class="p-4 text-gray-600 font-semibold">Email</th>
                <th class="p-4 text-gray-600 font-semibold">Phone</th>
                <th class="p-4 text-gray-600 font-semibold">Joined Date</th>
                <th class="p-4 text-gray-600 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="p-4 font-bold text-gray-800">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-500">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        {{ $user->name }}
                    </div>
                </td>
                <td class="p-4 text-gray-600">{{ $user->email }}</td>
                <td class="p-4 text-gray-600">{{ $user->phone ?? 'N/A' }}</td>
                <td class="p-4 text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                <td class="p-4 text-right">
                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Deleting a user will also delete all their requests and donations. Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-sm border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">
                            <i class="fa-solid fa-trash-can mr-1"></i> Ban User
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="p-4">
        {{ $users->links() }}
    </div>
</div>
@endsection