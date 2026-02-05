@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Blood Requests Management</h2>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr>
                <th class="border-b p-2">Patient / Hospital</th>
                <th class="border-b p-2">Blood Type</th>
                <th class="border-b p-2">Needed Date</th>
                <th class="border-b p-2">Status</th>
                <th class="border-b p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
            <tr class="hover:bg-gray-50">
                <td class="border-b p-2">
                    {{ $req->patient_name ?? 'Unknown' }}<br>
                    <span class="text-xs text-gray-500">{{ $req->hospital_name }}</span>
                </td>
                <td class="border-b p-2 font-bold text-red-600">{{ $req->blood_type }}</td>
                <td class="border-b p-2">{{ $req->needed_date->format('d M Y') }}</td>
                <td class="border-b p-2">
                    <span class="px-2 py-1 rounded text-xs 
                        {{ $req->status == 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-200' }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </td>
                <td class="border-b p-2">
                    @if($req->status == 'open')
                    <form action="{{ route('admin.requests.status', $req->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button class="text-blue-600 hover:underline text-sm">Mark Complete</button>
                    </form>
                    @else
                        <span class="text-gray-400 text-sm">No Action</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection