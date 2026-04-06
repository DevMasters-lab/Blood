@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto animate-fade-in px-4 sm:px-8 py-8">
    
    {{-- Header --}}
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Add New User</h2>
            <p class="mt-1 text-sm font-medium text-gray-500">Create a user account and assign system access.</p>
        </div>
        <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-600 shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900">
            <i class="fa-solid fa-arrow-left"></i> Back to Directory
        </a>
    </div>

    {{-- Error Alerts --}}
    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 shadow-sm flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation mt-0.5 text-red-500"></i>
            <ul class="space-y-1 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- The Form --}}
    <form action="{{ route('admin.users.store') }}" method="POST" class="rounded-[1.75rem] border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col gap-0">
        @csrf

        <div class="p-8 border-b border-gray-100 flex flex-col gap-6">
            
            {{-- Row 1: Name Input --}}
            <div>
                <label class="mb-2 block text-sm font-black text-gray-900">Full Name <span class="text-[#D32F2F]">*</span></label>
                <div class="relative group">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fa-solid fa-user text-gray-300 group-focus-within:text-[#D32F2F] transition-colors"></i>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., Sokha Developer" class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3.5 pl-11 pr-4 font-bold text-gray-800 outline-none transition-all focus:border-[#D32F2F] focus:bg-white focus:ring-4 focus:ring-red-500/10" required>
                </div>
            </div>

            {{-- Row 2: Contact & Security (3 Columns) --}}
            <div class="flex flex-col md:flex-row gap-6">
                
                {{-- Phone Input --}}
                <div class="flex-1">
                    <label class="mb-2 block text-sm font-black text-gray-900">Phone Number <span class="text-[#D32F2F]">*</span></label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fa-solid fa-phone text-gray-300 group-focus-within:text-[#D32F2F] transition-colors"></i>
                        </div>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="012 345 678" class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3.5 pl-11 pr-4 font-bold text-gray-800 outline-none transition-all focus:border-[#D32F2F] focus:bg-white focus:ring-4 focus:ring-red-500/10" required>
                    </div>
                </div>

                {{-- Email Input --}}
                <div class="flex-1">
                    <label class="mb-2 block text-sm font-black text-gray-900">Email Address <span class="text-[#D32F2F]">*</span></label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fa-solid fa-envelope text-gray-300 group-focus-within:text-[#D32F2F] transition-colors"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@bloodshare.kh" class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3.5 pl-11 pr-4 font-bold text-gray-800 outline-none transition-all focus:border-[#D32F2F] focus:bg-white focus:ring-4 focus:ring-red-500/10" required>
                    </div>
                </div>

                {{-- Password Input --}}
                <div class="flex-1">
                    <label class="mb-2 block text-sm font-black text-gray-900">Initial Password <span class="text-[#D32F2F]">*</span></label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fa-solid fa-lock text-gray-300 group-focus-within:text-[#D32F2F] transition-colors"></i>
                        </div>
                        <input type="password" name="password" placeholder="Minimum 6 characters" class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3.5 pl-11 pr-4 font-bold text-gray-800 outline-none transition-all focus:border-[#D32F2F] focus:bg-white focus:ring-4 focus:ring-red-500/10" required>
                    </div>
                </div>
            </div>

            {{-- Row 3: Role Assignment --}}
            <div class="mt-4 pt-6 border-t border-gray-100">
                <label class="mb-4 flex items-center gap-2 text-sm font-black text-gray-900">
                    <i class="fa-solid fa-user-shield text-[#D32F2F]"></i> Assign Admin Role (Optional)
                </label>

                <p class="mb-4 text-xs font-semibold text-gray-500">
                    Select an admin role to create an <span class="font-black text-gray-700">admin-panel account</span>. By default, this account will use the standard <span class="font-black text-gray-700">user</span> role.
                </p>
                
                {{-- 🌟 Alpine.js wrapper handles the live UI updates seamlessly 🌟 --}}
                <div x-data="{ selectedRole: '{{ old('role', '') }}' }" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    
                    {{-- Explicit 'Standard User' Card --}}
                    <label class="relative flex cursor-pointer items-center rounded-xl border-2 p-4 transition-all group"
                           :class="selectedRole === '' ? 'border-[#D32F2F] bg-red-50' : 'border-gray-200 bg-white hover:border-red-200 hover:bg-gray-50'">
                        <input type="radio" name="role" value="" x-model="selectedRole" class="peer absolute appearance-none">
                        
                        <div class="mr-3 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-colors"
                             :class="selectedRole === '' ? 'border-[#D32F2F]' : 'border-gray-300'">
                            <div class="h-2.5 w-2.5 rounded-full bg-[#D32F2F] transition-transform"
                                 :class="selectedRole === '' ? 'scale-100' : 'scale-0'"></div>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm font-black text-gray-900">Standard User</span>
                            <span class="mt-0.5 text-[10px] font-bold uppercase tracking-wider text-gray-500">
                                Front-end Access Only
                            </span>
                        </div>
                    </label>

                    {{-- Admin Roles from Database --}}
                    @foreach($roles as $role)
                        <label class="relative flex cursor-pointer items-center rounded-xl border-2 p-4 transition-all group"
                               :class="selectedRole === '{{ $role->name }}' ? 'border-[#D32F2F] bg-red-50' : 'border-gray-200 bg-white hover:border-red-200 hover:bg-gray-50'">
                            <input type="radio" name="role" value="{{ $role->name }}" x-model="selectedRole" class="peer absolute appearance-none">
                            
                            <div class="mr-3 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-colors"
                                 :class="selectedRole === '{{ $role->name }}' ? 'border-[#D32F2F]' : 'border-gray-300'">
                                <div class="h-2.5 w-2.5 rounded-full bg-[#D32F2F] transition-transform"
                                     :class="selectedRole === '{{ $role->name }}' ? 'scale-100' : 'scale-0'"></div>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm font-black text-gray-900">{{ $role->name }}</span>
                                <span class="mt-0.5 text-[10px] font-bold uppercase tracking-wider text-gray-500">
                                    {{ $role->permissions_count ?? 0 }} Permissions
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="flex justify-end bg-gray-50/80 px-8 py-5">
            <button type="submit" class="flex items-center gap-2 rounded-xl bg-[#D32F2F] px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-red-900/20 transition-all hover:-translate-y-0.5 hover:bg-[#B71C1C]">
                <i class="fa-solid fa-user-check"></i> Create User
            </button>
        </div>
    </form>
</div>
@endsection