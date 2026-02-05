@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manage Blood Requests</h2>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-4 text-gray-600 font-semibold">Posted By</th>
                <th class="p-4 text-gray-600 font-semibold">Hospital</th>
                <th class="p-4 text-gray-600 font-semibold">Type</th>
                <th class="p-4 text-gray-600 font-semibold">Date Needed</th>
                <th class="p-4 text-gray-600 font-semibold">Status</th>
                <th class="p-4 text-gray-600 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($requests as $req)
            <tr class="hover:bg-gray-50">
                <td class="p-4">
                    <div class="font-bold text-gray-800">{{ $req->requester->name }}</div>
                    <div class="text-xs text-gray-500">{{ $req->requester->phone }}</div>
                </td>
                <td class="p-4">{{ $req->hospital_name }}</td>
                <td class="p-4 font-bold text-red-600">{{ $req->blood_type }}</td>
                <td class="p-4">{{ $req->needed_date->format('d M Y') }}</td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $req->status == 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </td>
                <td class="p-4 text-right">
                    <form action="{{ route('admin.requests.delete', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure? This will remove the request permanently.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-sm">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <div class="p-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection