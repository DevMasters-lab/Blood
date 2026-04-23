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
    
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">User Directory</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Manage all registered donors and their account access.</p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            @if($can('view_users'))
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#D32F2F] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-red-900/15 transition-all hover:-translate-y-0.5 hover:bg-[#B71C1C]">
                    <i class="fa-solid fa-user-plus"></i>
                    Add User
                </a>
            @endif

            <form action="{{ route('admin.users') }}" method="GET" class="flex items-center gap-2">
                {{-- KYC Status filter --}}
                <div class="relative">
                    <i class="fa-solid fa-filter absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 z-10 pointer-events-none text-sm"></i>
                    <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl pl-10 pr-10 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F]/20 shadow-sm cursor-pointer appearance-none hover:border-gray-300 transition-colors">
                        <option value="all"      {{ ($currentStatus ?? 'all') === 'all'      ? 'selected' : '' }}>All ({{ $allCount ?? 0 }})</option>
                        <option value="verified" {{ ($currentStatus ?? '') === 'verified'    ? 'selected' : '' }}>Verified ({{ $verifiedCount ?? 0 }})</option>
                        <option value="rejected" {{ ($currentStatus ?? '') === 'rejected'    ? 'selected' : '' }}>Rejected ({{ $rejectedCount ?? 0 }})</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-xs z-10"></i>
                </div>
                {{-- Role filter --}}
                <div class="relative">
                    <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 z-10 pointer-events-none text-sm"></i>
                    <select name="role" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl pl-10 pr-10 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F]/20 shadow-sm cursor-pointer appearance-none hover:border-gray-300 transition-colors">
                        <option value="all" {{ ($currentRole ?? 'all') === 'all' ? 'selected' : '' }}>All Roles</option>
                        @foreach($roles ?? [] as $role)
                            <option value="{{ $role }}" {{ ($currentRole ?? '') === $role ? 'selected' : '' }}>{{ Str::headline($role) }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-xs z-10"></i>
                </div>
            </form>

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

    {{-- Users Table --}}
    <div
        x-data="{
            search: '',
            userSearchIndex: @js($users->map(fn ($user) => strtolower(trim($user->name . ' ' . ($user->phone ?? '') . ' ' . ($user->kyc_status ?? '') . ' ' . ($user->status ?? ''))))->values()),
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
                    <input x-model="search" type="text" placeholder="Search donor..." class="w-full rounded-xl border border-gray-200 bg-white py-3 pl-10 pr-20 text-sm font-medium text-gray-700 outline-none transition-all focus:border-[#D32F2F] focus:ring-2 focus:ring-[#D32F2F]/15">
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
            <table class="w-full min-w-[920px] text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Donor</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Roles</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Verified</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Account</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @if($users->isNotEmpty())
                    @foreach($users as $user)
                    <tr x-show="matchesSearch(@js(strtolower(trim($user->name . ' ' . ($user->phone ?? '') . ' ' . ($user->kyc_status ?? '') . ' ' . ($user->status ?? '')))))" class="group transition-colors hover:bg-gray-50/50">
                        
                        {{-- Donor Info --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-black text-gray-900">{{ $user->name }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Contact --}}
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-700">{{ $user->phone ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email ?? 'No email' }}</p>
                        </td>

                        {{-- Roles --}}
                        <td class="px-6 py-4">
                            @if($user->roles->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center rounded-lg border border-red-100 bg-red-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-[#D32F2F]">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-100 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-gray-500">
                                    No Role
                                </span>
                            @endif
                        </td>

                        {{-- KYC Status --}}
                        <td class="px-6 py-4 text-center">
                            @if($user->kyc_status == 'verified')
                                <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"><i class="fa-solid fa-shield-check mr-1"></i> Verified</span>
                            @elseif($user->kyc_status == 'rejected')
                                <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"><i class="fa-solid fa-xmark mr-1"></i> Rejected</span>
                            @else
                                <span class="bg-yellow-50 text-yellow-600 border border-yellow-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"><i class="fa-solid fa-clock mr-1"></i> Pending</span>
                            @endif
                        </td>

                        {{-- Account Status (Active/Blocked) --}}
                        <td class="px-6 py-4 text-center">
                            @if($user->status == 'active')
                                <span class="inline-flex items-center gap-1 rounded-full border border-green-100 bg-green-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-green-600"><i class="fa-solid fa-circle text-[7px]"></i> Active</span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full border border-red-100 bg-red-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-red-600"><i class="fa-solid fa-ban text-[8px]"></i> Blocked</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-right">
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
                                        const menuWidth = 208;
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
                                @if($can('view_users') || $can('reset_users') || $can('block_users') || $can('delete_users'))
                                    <button
                                        type="button"
                                        @click="toggleMenu($event)"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600 transition-colors hover:bg-gray-900 hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-300"
                                        title="Actions"
                                        aria-label="Actions Menu"
                                    >
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                @else
                                    <span class="inline-flex px-2 py-1 text-[10px] font-bold text-gray-400">No actions</span>
                                @endif

                                <template x-teleport="body">
                                    <div x-show="open" x-transition.origin.top.right @click.outside="closeMenu()" style="display: none;" :style="menuStyles" class="fixed z-[70] space-y-1 rounded-xl border border-gray-100 bg-white p-2 shadow-lg">
                                        @if($can('view_users'))
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-bold text-blue-600 hover:bg-blue-50">
                                                <i class="fa-solid fa-eye w-4"></i> View Detail
                                            </a>
                                        @endif

                                        @if($can('reset_users'))
                                            <form action="{{ route('admin.users.reset_password', $user->id) }}" method="POST" onsubmit="return confirm('Reset this user password to 123456?');">
                                                @csrf
                                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-bold text-amber-600 hover:bg-amber-50">
                                                    <i class="fa-solid fa-key w-4"></i> Reset Password
                                                </button>
                                            </form>
                                        @endif

                                        @if($can('block_users'))
                                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-bold {{ $user->status == 'active' ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }}">
                                                    <i class="fa-solid {{ $user->status == 'active' ? 'fa-lock' : 'fa-unlock' }} w-4"></i> {{ $user->status == 'active' ? 'Block User' : 'Unblock User' }}
                                                </button>
                                            </form>
                                        @endif

                                        @if($can('delete_users'))
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this user? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-bold text-red-600 hover:bg-red-50">
                                                    <i class="fa-solid fa-trash-can w-4"></i> Delete User
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    <tr x-show="visibleRows() === 0" style="display: none;">
                        <td colspan="6" class="px-6 py-12 text-center">
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
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                                    <i class="fa-solid fa-users text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-800">No Users Found</h3>
                                <p class="mt-1 text-sm font-medium text-gray-500">Create your first user to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-50">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection