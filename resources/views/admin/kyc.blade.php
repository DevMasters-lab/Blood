@extends('layouts.admin')

@section('content')
@php
    $adminUser = auth('admin')->user();

    $isSuperAdmin = $adminUser
        ? $adminUser->hasRole('Super Admin', 'web')
        : false;

    $can = fn (string $permission): bool => $adminUser && (
        $isSuperAdmin || $adminUser->checkPermissionTo($permission, 'web')
    );
@endphp
<div class="space-y-6 animate-fade-in px-8 py-8"">
    {{-- Header with count --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('ui.kyc_verifications') }}</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">{{ __('ui.kyc_header_desc') }}</p>
        </div>
                    <form action="{{ route('admin.kyc') }}" method="GET" class="relative shrink-0">
                <i class="fa-solid fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none z-10"></i>
                <select name="filter" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl pl-10 pr-10 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F]/20 shadow-sm cursor-pointer appearance-none hover:border-gray-300 transition-colors">
                    <option value="all"     {{ ($filter ?? 'all') === 'all'      ? 'selected' : '' }}>All ({{ $allCount ?? 0 }})</option>
                    <option value="pending"  {{ ($filter ?? '') === 'pending'   ? 'selected' : '' }}>Pending ({{ $pendingCount ?? 0 }})</option>
                    <option value="verified" {{ ($filter ?? '') === 'verified'  ? 'selected' : '' }}>Success ({{ $verifiedCount ?? 0 }})</option>
                    <option value="rejected" {{ ($filter ?? '') === 'rejected'  ? 'selected' : '' }}>Fail ({{ $rejectedCount ?? 0 }})</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs z-10"></i>
            </form>
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
    {{-- Data Table --}}
    <div
        x-data="{
            search: '',
            userSearchIndex: @js($pendingUsers->map(fn ($user) => strtolower(trim($user->name . ' ' . ($user->phone ?? '') . ' ' . ($user->id_number ?? ''))))->values()),
            matchesSearch(value) {
                const term = this.search.trim().toLowerCase();
                return term === '' || value.includes(term);
            },
            visibleRows() {
                return this.userSearchIndex.filter((value) => this.matchesSearch(value)).length;
            }
        }"
        class="overflow-hidden rounded-[1.75rem] border border-gray-200 bg-white shadow-sm"
    >
        <div class="flex flex-col gap-4 border-b border-gray-100 bg-gray-50/70 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-xl">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                    <input x-model="search" type="text" placeholder="Search KYC user..." class="w-full rounded-xl border border-gray-200 bg-white py-3 pl-10 pr-20 text-sm font-medium text-gray-700 outline-none transition-all focus:border-[#D32F2F] focus:ring-2 focus:ring-[#D32F2F]/15">
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
            <table class="w-full min-w-[980px] text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.user_details') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.phone_number') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.id_passport_info') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.document_proof') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @if($pendingUsers->isNotEmpty())
                    @foreach($pendingUsers as $user)
                        <tr x-show="matchesSearch(@js(strtolower(trim($user->name . ' ' . ($user->phone ?? '') . ' ' . ($user->id_number ?? '')))))" class="group transition-colors hover:bg-gray-50/50">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 font-black text-gray-500 flex items-center justify-center">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-900">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-bold text-black">{{ $user->phone ?? __('ui.not_available') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-gray-900">{{ $user->id_number }}</p>
                            </td>
                            <td class="px-8 py-6">
                                @if($user->proofFiles->isNotEmpty())
                                    @php
                                        $docUrl = $user->proofFiles->first()->url;
                                        $docExt = strtolower(pathinfo(parse_url($docUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                                        $isImg  = in_array($docExt, ['jpg','jpeg','png','gif','webp','bmp','svg']);
                                    @endphp
                                    @if($isImg)
                                        <button type="button" onclick="openDocPreview('{{ $docUrl }}')" class="block group relative w-20 h-14 rounded-xl overflow-hidden border border-gray-200 hover:border-blue-400 transition-colors shadow-sm hover:shadow-md">
                                            <img src="{{ $docUrl }}" alt="proof" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 flex items-center justify-center transition-all duration-200">
                                                <i class="fa-solid fa-expand text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                            </div>
                                        </button>
                                    @else
                                        <button type="button" onclick="openDocPreview('{{ $docUrl }}')" class="inline-flex items-center gap-2 bg-red-50 text-red-500 px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-500 hover:text-white transition-colors border border-red-100">
                                            <i class="fa-solid fa-file-pdf"></i> {{ __('ui.view_document') }}
                                        </button>
                                    @endif
                                @else
                                    <span class="text-xs font-bold text-gray-400 italic">{{ __('ui.no_document') }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $kycLabel = $user->kyc_status === 'verified' ? 'Success' : ($user->kyc_status === 'rejected' ? 'Fail' : 'Pending');
                                    $kycClass = $user->kyc_status === 'verified'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : ($user->kyc_status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700');
                                @endphp
                                <span class="inline-flex items-center rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-wider {{ $kycClass }}">
                                    {{ $kycLabel }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Approve Form --}}
                                    @if($can('accept_kyc') && $user->kyc_status === 'pending')
                                        <form action="{{ route('admin.kyc.approve', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors border border-green-100" title="{{ __('ui.verify_user') }}">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- Reject Form --}}
                                    @if($can('reject_kyc') && $user->kyc_status === 'pending')
                                        <form action="{{ route('admin.kyc.reject', $user->id) }}" method="POST" data-confirm="{{ __('ui.confirm_reject_user') }}" onsubmit="return confirm(this.dataset.confirm);">
                                            @csrf
                                            <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors border border-red-100" title="{{ __('ui.reject_user') }}">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if((!$can('accept_kyc') && !$can('reject_kyc')) || $user->kyc_status !== 'pending')
                                        <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold text-gray-400">No actions</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                        <tr x-show="visibleRows() === 0" style="display: none;">
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                                        <i class="fa-solid fa-magnifying-glass text-2xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-lg font-black text-gray-800">No Matching Users</h3>
                                    <p class="mt-1 text-sm font-medium text-gray-500">Try a different search term.</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                                        <i class="fa-solid fa-circle-check text-2xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-lg font-black text-gray-800">All caught up! No pending verifications.</h3>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Document Preview Modal --}}
<div id="docPreviewModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-10" onclick="closeDocPreviewOutside(event)">
    <div class="absolute inset-0 bg-black/75 backdrop-blur-md"></div>
    <div id="docPreviewPanel" class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-2xl flex flex-col overflow-hidden transition-all duration-300 scale-95 opacity-0">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 shrink-0">
            <p class="text-sm font-black text-gray-800">{{ __('ui.document_proof') }}</p>
            <button type="button" onclick="closeDocPreview()"
                    class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-red-50 hover:text-red-500 text-gray-500 flex items-center justify-center transition-colors" title="Close (Esc)">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Image Body --}}
        <div class="relative flex items-center justify-center bg-gray-50 p-5" style="min-height:320px;">
            <div id="docPreviewSpinner" class="absolute inset-0 flex items-center justify-center bg-gray-50 z-10">
                <div class="w-9 h-9 rounded-full border-4 border-gray-200 border-t-blue-500 animate-spin"></div>
            </div>
            <img id="docPreviewImg" src="" alt="Document Preview"
                 class="hidden max-w-full rounded-2xl shadow-lg ring-1 ring-gray-200"
                 style="max-height:75vh; object-fit:contain;"
                 onload="hidePreviewSpinner()" onerror="hidePreviewSpinner()">
            <iframe id="docPreviewIframe" src="" frameborder="0"
                    class="hidden w-full rounded-2xl border border-gray-200 shadow-lg"
                    style="height:75vh;"
                    onload="hidePreviewSpinner()"></iframe>
        </div>

        {{-- ESC hint --}}
        <div class="px-5 py-3 border-t border-gray-100 flex justify-center shrink-0">
            <p class="text-[11px] text-gray-400 font-medium flex items-center gap-1.5">
                <kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-[10px] font-bold text-gray-500 border border-gray-200">Esc</kbd>
                to close &nbsp;·&nbsp; click outside to dismiss
            </p>
        </div>
    </div>
</div>

<style>
#docPreviewModal.open #docPreviewPanel { transform: scale(1); opacity: 1; }
</style>

<script>
function openDocPreview(url) {
    const modal = document.getElementById('docPreviewModal');
    const img   = document.getElementById('docPreviewImg');
    const frame = document.getElementById('docPreviewIframe');
    const ext   = url.split('?')[0].split('.').pop().toLowerCase();
    const isImg = ['jpg','jpeg','png','gif','webp','bmp','svg'].includes(ext);

    img.classList.add('hidden');   img.src   = '';
    frame.classList.add('hidden'); frame.src = '';
    document.getElementById('docPreviewSpinner').style.display = 'flex';

    if (isImg) { img.src = url; img.classList.remove('hidden'); }
    else       { frame.src = url; frame.classList.remove('hidden'); hidePreviewSpinner(); }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => requestAnimationFrame(() => modal.classList.add('open')));
}
function closeDocPreview() {
    const modal = document.getElementById('docPreviewModal');
    modal.classList.remove('open');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('docPreviewImg').src    = '';
        document.getElementById('docPreviewIframe').src = '';
        document.body.style.overflow = '';
    }, 220);
}
function hidePreviewSpinner() {
    document.getElementById('docPreviewSpinner').style.display = 'none';
}
function closeDocPreviewOutside(e) {
    if (e.target === document.getElementById('docPreviewModal')) closeDocPreview();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDocPreview(); });
</script>

@endsection