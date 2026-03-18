@extends('layouts.admin')

@section('content')
<div class="space-y-6 animate-fade-in px-8 py-8">
    {{-- Header with count --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('ui.kyc_verifications') }}</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">{{ __('ui.kyc_header_desc') }}</p>
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



    {{-- Data Table --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.user_details') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.phone_number') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.id_passport_info') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('ui.document_proof') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pendingUsers as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
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
                                <span class="inline-block mt-1 px-2.5 py-1 bg-orange-100 text-orange-600 text-[9px] font-black uppercase tracking-widest rounded-md">{{ __('ui.pending') }}</span>
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
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Approve Form --}}
                                    <form action="{{ route('admin.kyc.approve', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors border border-green-100" title="{{ __('ui.verify_user') }}">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    {{-- Reject Form --}}
                                    <form action="{{ route('admin.kyc.reject', $user->id) }}" method="POST" data-confirm="{{ __('ui.confirm_reject_user') }}" onsubmit="return confirm(this.dataset.confirm);">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors border border-red-100" title="{{ __('ui.reject_user') }}">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-gray-400 font-bold">
                                <i class="fa-solid fa-shield-check text-4xl mb-3 text-gray-300"></i>
                                <p>{{ __('ui.all_caught_up') }}</p>
                            </td>
                        </tr>
                    @endforelse
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