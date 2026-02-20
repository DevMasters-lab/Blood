@extends('layouts.admin')

@section('content')
<div class="flex justify-center items-center min-h-[80vh]">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <h2 class="text-3xl font-extrabold mb-6 text-center text-red-700 tracking-tight">Create Account</h2>
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            {{-- Name --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none" placeholder="Enter name" required>
            </div>

            {{-- Phone --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none" placeholder="012345678" required>
            </div>

            {{-- Blood Type Selection (FIX FOR UNKNOWN ERROR) --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Blood Type</label>
                <select name="blood_type" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-white" required>
                    <option value="" disabled selected>Select your blood group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>

            {{-- Password --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border-gray-200 border p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 outline-none" placeholder="Min. 6 characters" required>
            </div>

            <button type="submit" class="w-full bg-red-600 text-white p-3 rounded-xl font-bold hover:bg-red-700 transition shadow-lg shadow-red-100">
                Register Account
            </button>
        </form>
    </div>
</div>
@endsection