@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Account Settings</h2>
            <p class="text-sm text-gray-500 mt-1">Update your contact details and login password.</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white text-gray-600 font-bold rounded-xl shadow-sm border border-gray-100 hover:bg-red-50 hover:text-red-600 transition-colors">
            <i class="fa-solid fa-arrow-left text-sm"></i> Dashboard
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check text-xl mr-3 text-green-500"></i>
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 shadow-sm">
            <div class="flex items-center mb-2">
                <i class="fa-solid fa-triangle-exclamation text-xl mr-3 text-red-500"></i>
                <span class="font-bold text-lg">Oops! Please check your inputs.</span>
            </div>
            <ul class="ml-9 space-y-1 text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Profile Form Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
            @csrf
            @method('PUT')

            {{-- Avatar Section --}}
            <div class="mb-10 flex items-center bg-gray-50 p-6 rounded-2xl border border-gray-100">
                <div class="mr-6 relative group">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                    @else
                        <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center text-gray-400 text-3xl shadow-md">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <i class="fa-solid fa-camera text-white"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Profile Photo</label>
                    <input type="file" name="avatar" class="text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-red-50 file:text-red-600 hover:file:bg-red-100 transition-colors cursor-pointer">
                    <p class="text-xs text-gray-400 mt-2 font-medium"><i class="fa-solid fa-circle-info mr-1"></i> JPG or PNG. Max 2MB.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800">
                </div>

                {{-- NEW: Email Address --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800">
                    <p class="text-[11px] text-gray-500 mt-2 font-medium"><i class="fa-solid fa-lock text-gray-400 mr-1"></i> This number is shown to donors when you request blood.</p>
                </div>

                {{-- Spacer to push passwords to next row cleanly --}}
                <div class="hidden md:block"></div>

                {{-- Password Section Divider --}}
                <div class="md:col-span-2 mt-4 pt-8 border-t border-gray-100">
                    <h4 class="text-lg font-black text-gray-900 flex items-center">
                        <i class="fa-solid fa-shield-halved text-red-500 mr-2"></i> Security
                    </h4>
                    <p class="text-sm text-gray-500 mt-1 mb-6">Change your password here. Leave it blank if you want to keep your current password.</p>
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">New Password</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800">
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800">
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-red-600 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection