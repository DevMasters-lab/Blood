@extends('layouts.user')

@section('content')
{{-- Added overflow-hidden and a very soft thematic gradient background --}}
<div class="flex items-center justify-center min-h-[85vh] relative py-12 bg-gradient-to-br from-slate-50 via-red-50/40 to-slate-100 overflow-hidden">
    
    {{-- THEMATIC BACKGROUND ELEMENTS --}}
    
    {{-- Faint floating background icons --}}
    <i class="fa-solid fa-droplet absolute text-red-600/[0.03] text-[15rem] top-10 left-10 -rotate-12 select-none pointer-events-none"></i>
    <i class="fa-solid fa-hand-holding-medical absolute text-red-600/[0.03] text-[20rem] -bottom-20 -right-10 rotate-12 select-none pointer-events-none"></i>
    <i class="fa-solid fa-heart-pulse absolute text-red-600/[0.02] text-[10rem] top-1/4 right-1/4 select-none pointer-events-none"></i>

    {{-- Richer Glowing Orbs (Blood Red Theme) --}}
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-pulse select-none pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-rose-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-pulse select-none pointer-events-none" style="animation-delay: 2s;"></div>

    {{-- LOGIN CARD (Frosted Glass Effect) --}}
    <div class="bg-white/90 backdrop-blur-xl p-8 md:p-10 rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(225,29,72,0.15)] w-full max-w-md relative z-10 border border-white/50">
        
        {{-- Header Icon & Title --}}
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-gradient-to-br from-red-50 to-red-100 text-red-600 rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-inner border border-red-200 transform rotate-3">
                <i class="fa-solid fa-right-to-bracket text-3xl pr-1 transform -rotate-3"></i>
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Welcome Back</h2>
            <p class="text-gray-500 mt-2 font-medium text-sm">Log in to request blood or pledge a donation.</p>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-8 shadow-sm text-sm flex items-start animate-fade-in">
                <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5 mr-3"></i>
                <ul class="list-disc list-inside font-medium space-y-1">
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
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-phone text-gray-400 group-focus-within:text-red-500 transition-colors"></i>
                    </div>
                    <input type="text" name="phone" placeholder="Enter your phone number" class="w-full bg-white/50 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 focus:bg-white transition-all font-bold text-gray-900 placeholder-gray-400" required>
                </div>
            </div>
            
            {{-- Password Input --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400 group-focus-within:text-red-500 transition-colors"></i>
                    </div>
                    <input type="password" name="password" placeholder="••••••••" class="w-full bg-white/50 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 focus:bg-white transition-all font-bold text-gray-900 placeholder-gray-400" required>
                </div>
                <div class="flex justify-end mt-2">
                    <a href="#" class="text-xs font-bold text-gray-500 hover:text-red-600 transition-colors">Forgot password?</a>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-500 text-white font-black py-4 px-8 rounded-xl shadow-lg shadow-red-500/30 hover:shadow-red-500/40 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 group border border-red-500">
                <span>Secure Login</span>
                <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
            </button>
        </form>
        
        {{-- Register Link --}}
        <div class="mt-8 text-center border-t border-gray-100 pt-6">
            <p class="text-gray-500 font-medium text-sm">
                New to the platform? 
                <a href="{{ route('register') }}" class="text-red-600 font-bold hover:text-red-700 transition-colors ml-1 hover:underline decoration-2 underline-offset-4">Register as a Donor</a>
            </p>
        </div>
    </div>
</div>
@endsection