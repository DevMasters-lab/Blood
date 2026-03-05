@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto pb-12">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter leading-none mb-2">Verify Invoices</h2>
            <p class="text-sm text-slate-500 font-medium">Review hospital proofs and generate official invoice codes.</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Active Invoices</p>
            <p class="text-2xl font-black text-emerald-600">{{ $activeInvoicesCount }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 text-emerald-700 font-bold rounded-xl border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Donor</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Donation Details</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Proof</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingInvoices as $invoice)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900">{{ $invoice->user->name }}</p>
                                <p class="text-xs font-bold text-slate-500 mt-1">{{ $invoice->user->phone }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900">{{ $invoice->blood_bank_name }}</p>
                                <p class="text-xs font-bold text-slate-500 mt-1">Donated: {{ $invoice->donation_date->format('M d, Y') }}</p>
                                <p class="text-xs font-bold text-orange-500">Expires: {{ $invoice->expiry_date->format('M d, Y') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                @if($invoice->proofFile)
                                    <a href="{{ $invoice->proofFile->url }}" target="_blank" class="inline-flex items-center gap-2 bg-white text-blue-600 px-4 py-2.5 rounded-xl text-xs font-black shadow-sm border border-slate-200 hover:border-blue-300 hover:ring-4 hover:ring-blue-50 transition-all">
                                        <i class="fa-solid fa-file-invoice"></i> View Proof
                                    </a>
                                @else
                                    <span class="text-xs text-red-500 font-bold">No Proof Uploaded</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <form action="{{ route('admin.invoices.approve', $invoice->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 rounded-xl bg-white text-emerald-600 shadow-sm border border-emerald-100 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all" title="Approve & Generate Code">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.invoices.reject', $invoice->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 rounded-xl bg-white text-rose-600 shadow-sm border border-rose-100 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-3xl mx-auto mb-4 border border-slate-100">
                                    <i class="fa-solid fa-check-double"></i>
                                </div>
                                <p class="text-sm font-black text-slate-900">No pending invoices.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection