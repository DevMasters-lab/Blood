@extends('layouts.user')

@section('content')
<div class="flex items-center justify-center min-h-[70vh] relative py-12">
    
    {{-- Decorative Background Elements --}}
    <div class="absolute top-10 left-10 md:left-1/4 w-72 h-72 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
    <div class="absolute bottom-10 right-10 md:right-1/4 w-72 h-72 bg-red-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>

    {{-- Login Card --}}
    <div class="bg-white p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-md relative z-10 border border-gray-100">
        
        {{-- Header Icon & Title --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm border border-red-100">
                <i class="fa-solid fa-right-to-bracket text-2xl pr-1"></i>
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Welcome Back</h2>
            <p class="text-gray-500 mt-2 font-medium text-sm">Log in to request blood or manage your profile.</p>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-6 shadow-sm text-sm flex items-start">
                <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5 mr-3"></i>
                <ul class="list-disc list-inside font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Login Form --}}
        <form action="{{ route('user.login') }}" method="POST">
            @csrf
            
            {{-- Phone Input --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-900 mb-2">Phone Number</label>
                <div class="relative">
                    <i class="fa-solid fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="phone" placeholder="Enter your phone number" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800" required>
                </div>
            </div>
            
            {{-- Password Input --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-900 mb-2">Password</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="password" name="password" placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800" required>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-red-600 text-white font-black py-4 px-8 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                Log In <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
        
        {{-- Register Link --}}
        <div class="mt-8 text-center border-t border-gray-100 pt-6">
            <p class="text-gray-500 font-medium text-sm">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-red-600 font-bold hover:text-red-700 transition-colors ml-1 hover:underline decoration-2 underline-offset-4">Register here</a>
            </p>
        </div>
    </div>
</div>
@endsection