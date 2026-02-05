@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 flex items-center">
        <i class="fa-solid fa-file-invoice text-red-600 mr-2"></i> Donation Invoices
    </h2>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-600 border-b">
                <th class="p-3">User</th>
                <th class="p-3">Hospital / Bank</th>
                <th class="p-3">Date</th>
                <th class="p-3">Proof</th>
                <th class="p-3">Status</th>
                <th class="p-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($donations as $invoice)
            <tr class="hover:bg-gray-50">
                <td class="p-3 font-semibold">{{ $invoice->user->name }}</td>
                <td class="p-3">{{ $invoice->blood_bank_name }}</td>
                <td class="p-3">{{ $invoice->donation_date->format('d M Y') }}</td>
                <td class="p-3">
                    @if($invoice->proofFile)
                        <a href="{{ asset('storage/' . $invoice->proofFile->path) }}" target="_blank" class="text-blue-500 underline text-sm">
                            View Proof
                        </a>
                    @else
                        <span class="text-gray-400 text-xs">No File</span>
                    @endif
                </td>
                <td class="p-3">
                    @if($invoice->status == 'active')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Approved</span>
                        <div class="text-xs text-gray-500 mt-1">{{ $invoice->invoice_code }}</div>
                    @elseif($invoice->status == 'pending')
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold">Pending</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold">Rejected</span>
                    @endif
                </td>
                <td class="p-3">
                    @if($invoice->status == 'pending')
                        <div class="flex space-x-2">
                            {{-- Approve Form --}}
                            <form action="{{ route('admin.donations.status', $invoice->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="active">
                                <button class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700" onclick="return confirm('Approve this donation?')">
                                    Approve
                                </button>
                            </form>
                            
                            {{-- Reject Form --}}
                            <form action="{{ route('admin.donations.status', $invoice->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700" onclick="return confirm('Reject this donation?')">
                                    Reject
                                </button>
                            </form>
                        </div>
                    @else
                        <span class="text-gray-400 text-sm">Locked</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $donations->links() }}
    </div>
</div>
@endsection