@extends('layouts.admin')

@section('content')
<div class="flex justify-center items-center min-h-[80vh] py-10">
    {{-- Increased max-w-md to max-w-lg to comfortably fit the new fields --}}
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-lg border border-gray-100">
        <h2 class="text-3xl font-extrabold mb-2 text-center text-red-700 tracking-tight">Create Account</h2>
        <p class="text-center text-sm text-gray-500 font-medium mb-6">Verify your identity to request or donate blood.</p>
        
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
        
        {{-- IMPORTANT: Added enctype="multipart/form-data" for file uploads --}}
        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
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
                <p class="text-[10px] text-gray-400 mt-1 ml-1 font-medium italic">Must start with 0</p>
            </div>

            {{-- ID / Passport Number --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-1">ID / Passport No.</label>
                <input type="text" name="id_number" value="{{ old('id_number') }}" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none @error('id_number') border-red-500 @enderror" placeholder="Enter ID number" required>
            </div>

            {{-- Upload ID / Passport Photo --}}
            <div class="mb-6 p-4 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                <label class="block text-sm font-bold text-gray-700 mb-2">Upload ID or Passport Photo</label>
                <input type="file" name="id_photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 outline-none @error('id_photo') border-red-500 @enderror" required>
                <p class="text-[11px] text-gray-500 mt-2 font-medium italic">Image must be clear and readable for verification.</p>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none @error('password') border-red-500 @enderror" placeholder="Min. 6 characters" required>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none" placeholder="Repeat password" required>
            </div>

            <button type="submit" class="w-full bg-red-600 text-white p-3.5 rounded-xl font-bold hover:bg-red-700 transition shadow-lg shadow-red-100">
                Register Account
            </button>
        </form>
    </div>
</div>
@endsection