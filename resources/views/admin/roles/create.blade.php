@extends('layouts.admin')

@section('content')

@php
    $sidebarFeatureMap = [
        'Blood Requests'     => ['keywords' => ['request'],              'icon' => 'fa-hand-holding-medical'],
        'Requested History'  => ['keywords' => ['history'],              'icon' => 'fa-clock-rotate-left'],
        'Verify Invoices'    => ['keywords' => ['donation', 'invoice'],  'icon' => 'fa-file-invoice'],
        'Manage Users'       => ['keywords' => ['user'],                 'icon' => 'fa-users-gear'],
        'KYC Verifications'  => ['keywords' => ['kyc'],                  'icon' => 'fa-id-card'],
        'Account Settings'   => ['keywords' => ['profile', 'account'],  'icon' => 'fa-user-shield'],
        'Platform Settings'  => ['keywords' => ['setting'],             'icon' => 'fa-gear'],
        'Roles & Permissions'=> ['keywords' => ['role', 'permission'],  'icon' => 'fa-user-lock'],
    ];

    $cols = ['view', 'create', 'accept', 'reject', 'edit', 'delete', 'reset'];

    $colPatterns = [
        'view'   => '/^(view|show|read|index|list)([._:-]|$)/',
        'create' => '/^(create|add|store|make|insert)([._:-]|$)/',
        'accept' => '/^(accept|approve)([._:-]|$)/',
        'reject' => '/^(reject)([._:-]|$)/',
        'edit'   => '/^(edit|update|modify|change|toggle|block|unblock|verify)([._:-]|$)/',
        'delete' => '/^(delete|destroy|remove|trash)([._:-]|$)/',
        'reset'  => '/^(reset)([._:-]|$)/',
    ];

    $grouped = [];
    foreach ($sidebarFeatureMap as $label => $meta) {
        $grouped[$label] = ['icon' => $meta['icon'], 'actions' => array_fill_keys($cols, [])];
    }
    $grouped['Other Features'] = ['icon' => 'fa-puzzle-piece', 'actions' => array_fill_keys($cols, [])];

    foreach ($permissions as $perm) {
        $name = strtolower($perm->name);

        // Match resource row
        $rowKey = 'Other Features';
        foreach ($sidebarFeatureMap as $label => $meta) {
            foreach ($meta['keywords'] as $kw) {
                if (str_contains($name, $kw)) { $rowKey = $label; break 2; }
            }
        }

        // Match action column — check in priority order
        $col = 'edit'; // fallback
        foreach ($colPatterns as $colName => $pattern) {
            if (preg_match($pattern, $name)) { $col = $colName; break; }
        }

        $grouped[$rowKey]['actions'][$col][] = $perm;
    }

    // Remove Other Features if empty
    if (collect($grouped['Other Features']['actions'])->flatten()->isEmpty()) {
        unset($grouped['Other Features']);
    }
@endphp

<div
    x-data="{
        permissionOptions: @js($permissions->pluck('name')->values()),
        selectedPermissions: @js(old('permissions', [])),
        selectedCount() { return this.selectedPermissions.length; },
        selectAllPermissions() {
            this.selectedPermissions = this.selectedPermissions.length === this.permissionOptions.length
                ? [] : [...this.permissionOptions];
        },
        isRowSelected(perms) {
            return perms.length > 0 && perms.every(p => this.selectedPermissions.includes(p));
        },
        toggleRow(perms) {
            if (this.isRowSelected(perms)) {
                this.selectedPermissions = this.selectedPermissions.filter(p => !perms.includes(p));
            } else {
                this.selectedPermissions = [...new Set([...this.selectedPermissions, ...perms])];
            }
        }
    }"
    class="max-w-7xl mx-auto animate-fade-in px-4 sm:px-8 py-8"
>
    {{-- Header --}}
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Create New Role</h2>
            <p class="mt-1 text-sm font-medium text-gray-500">Manage role and permission of user</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-600 shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900">
            <i class="fa-solid fa-arrow-left"></i> Back to Roles
        </a>
    </div>

    {{-- Error Alerts --}}
    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 shadow-sm flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation mt-0.5 text-red-500"></i>
            <ul class="space-y-1 font-medium">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.roles.store') }}" method="POST" class="flex flex-col gap-6">
        @csrf

        {{-- Role Name --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <label class="mb-2 block text-sm font-black text-gray-900">Role name <span class="text-[#D32F2F]">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter role name (e.g. Editor)"
                class="w-full md:w-1/2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-bold text-gray-800 outline-none transition-all focus:border-[#D32F2F] focus:bg-white focus:ring-4 focus:ring-red-500/10" required>
        </div>

        {{-- Resources Matrix --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

            {{-- Matrix Header --}}
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-black text-gray-900">Permission Matrix</h3>
                    <p class="text-xs font-medium text-gray-400 mt-0.5">Toggle permissions per feature below, or use Select All.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-500 bg-white border border-gray-200 px-3 py-1.5 rounded-lg shadow-sm">
                        <span x-text="selectedCount()" class="text-[#D32F2F]"></span> / {{ $permissions->count() }} active
                    </span>
                    <button type="button" @click="selectAllPermissions()"
                        class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-black uppercase tracking-wider text-gray-700 shadow-sm transition-colors hover:bg-gray-100 hover:text-gray-900 active:scale-95">
                        <span x-text="selectedCount() === permissionOptions.length ? 'Deselect All' : 'Select All'"></span>
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1060px]">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/60">
                            <th class="py-3 px-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Feature</th>
                            @foreach([
                                'view'   => ['label' => 'View',   'color' => 'text-blue-500'],
                                'create' => ['label' => 'Create', 'color' => 'text-green-500'],
                                'accept' => ['label' => 'Accept', 'color' => 'text-emerald-500'],
                                'reject' => ['label' => 'Reject', 'color' => 'text-orange-500'],
                                'edit'   => ['label' => 'Edit',   'color' => 'text-yellow-500'],
                                'delete' => ['label' => 'Delete', 'color' => 'text-red-500'],
                                'reset'  => ['label' => 'Reset',  'color' => 'text-purple-500'],
                            ] as $colKey => $colMeta)
                            <th class="py-3 px-4 text-[10px] font-black {{ $colMeta['color'] }} uppercase tracking-widest text-center">{{ $colMeta['label'] }}</th>
                            @endforeach
                            <th class="py-3 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">All</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($grouped as $featureName => $data)
                            @php
                                $rowPerms = collect($data['actions'])->flatten()->pluck('name')->toArray();
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors">

                                {{-- Feature label --}}
                                <td class="py-4 px-5 border-r border-gray-100 min-w-[190px]">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500 text-sm">
                                            <i class="fa-solid {{ $data['icon'] }}"></i>
                                        </div>
                                        <span class="text-sm font-black text-gray-800">{{ $featureName }}</span>
                                    </div>
                                </td>

                                {{-- Per-action cells --}}
                                @foreach($cols as $col)
                                <td class="py-4 px-4 text-center">
                                    @if(!empty($data['actions'][$col]))
                                        @foreach($data['actions'][$col] as $perm)
                                        <label class="inline-flex relative items-center cursor-pointer" title="{{ $perm->name }}">
                                            <input x-model="selectedPermissions" type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                                class="peer appearance-none w-5 h-5 border-2 border-gray-200 rounded-md hover:border-[#D32F2F] checked:bg-[#D32F2F] checked:border-[#D32F2F] transition-all cursor-pointer">
                                            <i class="fa-solid fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-[9px] opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></i>
                                        </label>
                                        @endforeach
                                    @else
                                        <div class="inline-flex w-5 h-5 rounded-md border-2 border-dashed border-gray-200 bg-gray-50"></div>
                                    @endif
                                </td>
                                @endforeach

                                {{-- Row toggle --}}
                                <td class="py-4 px-4 text-center">
                                    <label class="relative inline-flex items-center cursor-pointer" title="Toggle all in row">
                                        <input type="checkbox"
                                            :checked="isRowSelected({{ json_encode($rowPerms) }})"
                                            @change="toggleRow({{ json_encode($rowPerms) }})"
                                            {{ empty($rowPerms) ? 'disabled' : '' }}
                                            class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-md hover:border-gray-700 checked:bg-gray-800 checked:border-gray-800 disabled:opacity-25 disabled:cursor-not-allowed transition-all cursor-pointer">
                                        <i class="fa-solid fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-[9px] opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></i>
                                    </label>
                                </td>

                            </tr>
                        @endforeach

                        @if(empty($grouped))
                        <tr>
                            <td colspan="9" class="py-12 text-center text-sm font-medium text-gray-400">
                                No permissions found. Seed your permissions first.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end pt-2">
            <button type="submit" class="flex items-center gap-2 rounded-xl bg-[#D32F2F] px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-red-900/20 transition-all hover:-translate-y-0.5 hover:bg-[#B71C1C]">
                <i class="fa-solid fa-save"></i> Save New Role
            </button>
        </div>
    </form>
</div>
@endsection