@extends('layouts.admin')

@section('content')
@php
    $adminUser = auth('admin')->user();
    $isSuperAdmin = $adminUser?->hasRole('Super Admin');
    $can = fn (string $permission): bool => $adminUser && ($isSuperAdmin || $adminUser->hasPermissionTo($permission, 'web'));
@endphp
<div class="space-y-6 animate-fade-in px-8 py-8">
    {{-- Header section with total count --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('ui.manage_blood_requests') }}</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">{{ __('ui.review_remove_requests') }}</p>
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
    <div
        x-data="{
            search: '',
            requestSearchIndex: @js($requests->map(fn ($req) => strtolower(trim(($req->requester->name ?? '') . ' ' . ($req->requester->phone ?? '') . ' ' . ($req->requester->email ?? '') . ' ' . ($req->hospital_name ?? '') . ' ' . ($req->blood_type ?? '') . ' ' . ($req->status ?? ''))))->values()),
            matchesSearch(value) {
                const term = this.search.trim().toLowerCase();
                return term === '' || value.includes(term);
            },
            visibleRows() {
                return this.requestSearchIndex.filter((value) => this.matchesSearch(value)).length;
            }
        }"
        class="overflow-hidden rounded-[1.75rem] border border-gray-200 bg-white shadow-sm"
    >
        <div class="flex flex-col gap-4 border-b border-gray-100 bg-gray-50/70 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-xl">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                    <input x-model="search" type="text" placeholder="Search request..." class="w-full rounded-xl border border-gray-200 bg-white py-3 pl-10 pr-20 text-sm font-medium text-gray-700 outline-none transition-all focus:border-[#D32F2F] focus:ring-2 focus:ring-[#D32F2F]/15">
                    <button
                        type="button"
                        @click="search = ''"
                        :class="search.trim().length > 0 ? 'opacity-100 scale-100 pointer-events-auto' : 'opacity-0 scale-95 pointer-events-none'"
                        class="absolute right-2 top-1/2 inline-flex -translate-y-1/2 items-center justify-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold text-gray-600 transition-all duration-200 ease-out hover:bg-gray-100 hover:text-gray-800"
                    >
                        <i class="fa-solid fa-rotate-left text-[10px]"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.requested_by') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.email') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.hospital') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('ui.type') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('ui.date_needed') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('ui.status') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @if($requests->isNotEmpty())
                    @foreach($requests as $req)
                    <tr x-show="matchesSearch(@js(strtolower(trim(($req->requester->name ?? '') . ' ' . ($req->requester->phone ?? '') . ' ' . ($req->requester->email ?? '') . ' ' . ($req->hospital_name ?? '') . ' ' . ($req->blood_type ?? '') . ' ' . ($req->status ?? '')))))" class="group transition-colors hover:bg-gray-50/50">
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
                            <div class="font-bold text-blue-600">{{ $req->requester->email ?? __('ui.not_available') }}</div>
                        </td>
                        <td class="px-8 py-6">{{ $req->hospital_name }}</td>
                        <td class="px-8 py-6 text-center font-bold text-red-600">{{ $req->blood_type }}</td>
                        <td class="px-8 py-6 text-center">{{ $req->needed_date->format('d M Y') }}</td>
                        <td class="px-8 py-6 text-center">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-black uppercase tracking-wider {{ $req->status == 'open' ? 'border-green-200 bg-green-50 text-green-700' : 'border-gray-200 bg-gray-50 text-gray-600' }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-center items-center gap-2">
                            {{-- Mark as Done Button (Only show if open) --}}
                            @if($req->status == 'open' && $can('accept_requests'))
                                <form action="{{ route('admin.requests.status', $req->id) }}" method="POST" data-confirm="{{ __('ui.confirm_mark_completed') }}" onsubmit="return confirm(this.dataset.confirm)">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="{{ __('ui.mark_done') }}">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Reject/Cancel Button with Custom Message --}}
                            @if($req->status == 'open' && $can('reject_requests'))
                                <form action="{{ route('admin.requests.status', $req->id) }}" method="POST" onsubmit="return askForReason(event, this, 'Why are you rejecting this request? (Optional message for the user):');">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <input type="hidden" name="rejection_reason" class="reason-input">
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Reject Request">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- 🌟 UPDATED: Delete Button with Custom Message 🌟 --}}
                            @if($can('reject_requests'))
                                <form action="{{ route('admin.requests.delete', $req->id) }}" method="POST" onsubmit="return askForReason(event, this, 'Why are you completely deleting this request? (Optional message for the user):');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="delete_reason" class="reason-input">
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="{{ __('ui.delete_request') }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                    </tr>
                    @endforeach
                    <tr x-show="visibleRows() === 0" style="display: none;">
                        <td colspan="7" class="px-8 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                                    <i class="fa-solid fa-magnifying-glass text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-800">No Matching Requests</h3>
                                <p class="mt-1 text-sm font-medium text-gray-500">Try a different search term.</p>
                            </div>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="7" class="px-8 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                                    <i class="fa-solid fa-notes-medical text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-800">{{ __('ui.no_blood_requests') }}</h3>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-50">
            {{ $requests->links() }}
        </div>
    </div>
</div>

{{-- 🌟 SCRIPT FOR DYNAMIC MESSAGE POPUPS 🌟 --}}
<script>
    function askForReason(event, form, promptMessage) {
        event.preventDefault(); 
        
        // Show the custom question based on which button was clicked
        let reason = prompt(promptMessage);
        
        // If they hit cancel on the prompt, stop the submission
        if (reason === null) {
            return false; 
        }
        
        // Find the hidden input inside the specific form they clicked and add the reason
        let input = form.querySelector('.reason-input');
        if(input) {
            input.value = reason;
        }
        
        // Send it to the controller!
        form.submit();
    }
</script>
@endsection