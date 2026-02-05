@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">User Verification (KYC)</h2>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr>
                <th class="border-b p-2">Name</th>
                <th class="border-b p-2">Phone</th>
                <th class="border-b p-2">ID Number</th>
                <th class="border-b p-2">Status</th>
                <th class="border-b p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="border-b p-2">{{ $user->name }}</td>
                <td class="border-b p-2">{{ $user->phone }}</td>
                <td class="border-b p-2">{{ $user->id_number ?? '-' }}</td>
                <td class="border-b p-2">
                    @if($user->kyc_status == 'verified')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Verified</span>
                    @elseif($user->kyc_status == 'pending')
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Rejected</span>
                    @endif
                </td>
                <td class="border-b p-2">
                    @if($user->kyc_status == 'pending')
                    <form action="{{ route('admin.users.verify', $user->id) }}" method="POST">
                        @csrf
                        <button class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                            Verify ID
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection