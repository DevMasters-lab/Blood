@extends('layouts.admin')

@section('content')

@php
    // Define the sidebar features so your resource counting logic works perfectly
    $sidebarFeatures = [
        ['keywords' => ['request']],
        ['keywords' => ['history']],
        ['keywords' => ['donation', 'invoice']],
        ['keywords' => ['user']],
        ['keywords' => ['kyc']],
        ['keywords' => ['profile', 'account']],
        ['keywords' => ['setting']],
        ['keywords' => ['role', 'permission']],
    ];
@endphp

@php
    $adminUser = auth('admin')->user();

    $isSuperAdminUser = $adminUser
        ? $adminUser->hasRole('Super Admin', 'web')
        : false;

    $can = fn (string $permission): bool => $adminUser && (
        $isSuperAdminUser || $adminUser->checkPermissionTo($permission, 'web')
    );
@endphp

<div class="space-y-6 animate-fade-in px-8 py-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Roles & Permissions</h2>
        </div>

        @if($can('create_roles'))
            <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#D32F2F] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-red-900/15 transition-all hover:-translate-y-0.5 hover:bg-[#B71C1C]">
                <i class="fa-solid fa-user-shield"></i>
                Add New Role
            </a>
        @endif
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-2xl text-sm font-bold flex items-center gap-3 mb-8 shadow-sm">
            <i class="fa-solid fa-circle-check text-green-500 text-lg"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl text-sm font-bold flex items-center gap-3 mb-8 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-lg"></i> {{ session('error') }}
        </div>
    @endif

    <div
        x-data="{
            search: '',
            roleSearchIndex: @js($roles->map(fn ($role) => strtolower($role->name))->values()),
            matchesSearch(value) {
                const term = this.search.trim().toLowerCase();
                return term === '' || value.includes(term);
            },
            visibleRows() {
                return this.roleSearchIndex.filter((value) => this.matchesSearch(value)).length;
            }
        }"
        class="overflow-hidden rounded-[1.75rem] border border-gray-200 bg-white shadow-sm"
    >
        {{-- Search Bar --}}
        <div class="flex flex-col gap-4 border-b border-gray-100 bg-gray-50/70 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-xl">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input x-model="search" type="text" placeholder="Search role..." class="w-full rounded-xl border border-gray-200 bg-white py-3 pl-10 pr-20 text-sm font-medium text-gray-700 outline-none transition-all focus:border-[#D32F2F] focus:ring-2 focus:ring-[#D32F2F]/15">
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
            <table class="w-full min-w-[940px] border-collapse text-left">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/60">
                        <th class="py-4 px-6 text-[11px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Role</th>
                        <th class="py-4 px-6 text-[11px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Status</th>
                        <th class="py-4 px-6 text-[11px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap text-center">Permissions</th>
                        <th class="py-4 px-6 text-[11px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap text-center">Resources</th>
                        <th class="py-4 px-6 text-[11px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Updated</th>
                        <th class="py-4 px-6 text-[11px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($roles as $role)
                    @php
                        // 🌟 Detect Super Admin
                        $isSuperAdmin = $role->name === 'Super Admin';

                        // Calculate resource coverage
                        $permissionNames = $role->permissions->pluck('name')->map(fn ($name) => strtolower($name));
                        $enabledResourceCount = $isSuperAdmin
                            ? count($sidebarFeatures)
                            : collect($sidebarFeatures)->filter(function ($feature) use ($permissionNames) {
                                foreach ($feature['keywords'] as $keyword) {
                                    if ($permissionNames->contains(fn ($permissionName) => str_contains($permissionName, strtolower($keyword)))) {
                                        return true;
                                    }
                                }
                                return false;
                            })->count();
                    @endphp

                    {{-- Apply locked styles if Super Admin --}}
                    <tr x-show="matchesSearch(@js(strtolower($role->name)))" class="{{ $isSuperAdmin ? 'bg-gray-50/80 opacity-60 select-none' : 'group transition-colors hover:bg-gray-50/60' }}">
                        
                        {{-- Role Name --}}
                        <td class="py-4 px-6">
                            <p class="text-sm font-black {{ $isSuperAdmin ? 'text-gray-500' : 'text-gray-900' }}">{{ $role->name }}</p>
                        </td>

                        {{-- Status --}}
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border text-[10px] font-black uppercase tracking-wider {{ $isSuperAdmin ? 'bg-gray-100 text-gray-500 border-gray-200' : 'bg-green-50 text-green-700 border-green-100' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isSuperAdmin ? 'bg-gray-400' : 'bg-green-500' }}"></span> Active
                            </span>
                        </td>

                        {{-- Permissions Count --}}
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl border text-xs font-black {{ $isSuperAdmin ? 'border-gray-200 bg-gray-100 text-gray-500' : 'border-red-100 bg-red-50 text-[#D32F2F]' }}">
                                {{ $role->permissions->count() }}
                            </span>
                        </td>

                        {{-- Resources Count --}}
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl border text-xs font-black {{ $isSuperAdmin ? 'border-gray-200 bg-gray-100 text-gray-500' : 'border-red-100 bg-red-50 text-[#D32F2F]' }}">
                                {{ $enabledResourceCount }}
                            </span>
                        </td>

                        {{-- Updated Date --}}
                        <td class="py-4 px-6">
                            <span class="text-xs font-medium {{ $isSuperAdmin ? 'text-gray-400' : 'text-gray-500' }}">
                                {{ $role->updated_at ? $role->updated_at->format('l d M Y') : 'Unknown' }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="py-4 px-6 text-right">
                            @if($isSuperAdmin)
                                {{-- Locked Padlock for Super Admin --}}
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-200/50 text-gray-400 cursor-not-allowed" title="System Role (Locked)">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                            @else
                                {{-- Your Custom Teleport Dropdown --}}
                                <div
                                    x-data="{
                                        open: false,
                                        menuStyles: '',
                                        toggleMenu(event) {
                                            if (this.open) {
                                                this.open = false;
                                                return;
                                            }
                                            const triggerRect = event.currentTarget.getBoundingClientRect();
                                            const menuWidth = 160;
                                            const viewportPadding = 16;
                                            const left = Math.min(
                                                Math.max(triggerRect.right - menuWidth, viewportPadding),
                                                window.innerWidth - menuWidth - viewportPadding
                                            );
                                            this.menuStyles = `top: ${triggerRect.bottom + 8}px; left: ${left}px; width: ${menuWidth}px;`;
                                            this.open = true;
                                        },
                                        closeMenu() {
                                            this.open = false;
                                        }
                                    }"
                                    @keydown.escape.window="closeMenu()"
                                    @scroll.window="closeMenu()"
                                    @resize.window="closeMenu()"
                                    class="relative inline-block text-left"
                                >
                                    <button
                                        type="button"
                                        @click="toggleMenu($event)"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600 transition-colors hover:bg-gray-900 hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-300"
                                    >
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <template x-teleport="body">
                                        <div x-show="open" x-transition.origin.top.right @click.outside="closeMenu()" style="display: none;" :style="menuStyles" class="fixed z-[70] overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 border border-gray-100">
                                            <div class="py-1">
                                                @if($can('edit_roles'))
                                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm font-bold text-gray-700 transition-colors hover:bg-blue-50 hover:text-blue-600">
                                                        <i class="fa-solid fa-pen-to-square text-blue-400 w-4"></i> Edit
                                                    </a>
                                                @endif

                                                @if($can('delete_roles'))
                                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="block m-0" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm font-bold text-gray-700 transition-colors hover:bg-red-50 hover:text-[#D32F2F]">
                                                            <i class="fa-solid fa-trash-can text-red-400 w-4"></i> Delete
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!$can('edit_roles') && !$can('delete_roles'))
                                                    <div class="px-4 py-2.5 text-xs font-bold text-gray-400">No actions</div>
                                                @endif
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                                    <i class="fa-solid fa-shield-halved text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-800">No Roles Found</h3>
                                <p class="text-sm text-gray-500 font-medium mt-1">Get started by creating a new role.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    {{-- 🌟 Search Empty State (Moved outside the loop!) 🌟 --}}
                    @if($roles->isNotEmpty())
                    <tr x-show="visibleRows() === 0" style="display: none;">
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                                    <i class="fa-solid fa-magnifying-glass text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-800">No Matching Roles</h3>
                                <p class="mt-1 text-sm font-medium text-gray-500">Try a different search term.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection