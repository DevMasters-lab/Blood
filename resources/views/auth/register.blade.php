@extends('layouts.admin')

@section('content')
<div class="flex justify-center items-center min-h-[80vh]">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <h2 class="text-3xl font-extrabold mb-6 text-center text-red-700 tracking-tight">Create Account</h2>
        
        {{-- 1. ERROR MESSAGE BLOCK --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <ul class="list-disc list-inside text-sm text-red-600 font-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            {{-- Name --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none @error('name') border-red-500 @enderror" placeholder="Enter name" required>
            </div>

            {{-- Phone --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Phone Number</label>
                <input type="text" 
                    name="phone" 
                    value="{{ old('phone') }}" 
                    inputmode="numeric"
                    class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none @error('phone') border-red-500 @enderror" 
                    placeholder="e.g. 012345678" 
                    required>
                <p class="text-[10px] text-gray-400 mt-1 ml-1 font-medium italic">Must start with 0 (e.g., 012...)</p>
            </div>

            {{-- Blood Type Selection --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Blood Type</label>
                <select name="blood_type" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-white @error('blood_type') border-red-500 @enderror" required>
                    <option value="" disabled selected>Select your blood group</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                        <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none @error('password') border-red-500 @enderror" placeholder="Min. 6 characters" required>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none" placeholder="Repeat your password" required>
            </div>

            <button type="submit" class="w-full bg-red-600 text-white p-3 rounded-xl font-bold hover:bg-red-700 transition shadow-lg shadow-red-100">
                Register Account
            </button>
        </form>
    </div>
</div>
@endsection