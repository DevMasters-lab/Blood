@extends('layouts.user')

@section('content')
<div class="max-w-3xl mx-auto">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Request Blood</h2>
            <p class="text-sm text-gray-500 mt-1">Fill out the form below to create an urgent blood request.</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white text-gray-600 font-bold rounded-xl shadow-sm border border-gray-100 hover:bg-red-50 hover:text-red-600 transition-colors">
            <i class="fa-solid fa-arrow-left text-sm"></i> Dashboard
        </a>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
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

    {{-- Form Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('user.requests.store') }}" method="POST" class="p-8 md:p-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Blood Type --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Blood Type Needed</label>
                    <div class="relative">
                        <select name="blood_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none appearance-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-bold text-gray-800 cursor-pointer">
                            <option value="" disabled selected>Select blood type...</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-sm"></i>
                    </div>
                </div>

                {{-- Quantity --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Quantity</label>
                    <input type="text" name="quantity" placeholder="e.g. 1 Bag" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800" required>
                </div>

                {{-- Hospital Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Hospital Name</label>
                    <div class="relative">
                        <i class="fa-regular fa-hospital absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="hospital_name" placeholder="Where is the blood needed?" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800" required>
                    </div>
                </div>

                {{-- Date Needed --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Date Needed</label>
                    <input type="date" name="needed_date" min="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800 cursor-pointer" required>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-100">
                <button type="submit" class="w-full bg-red-600 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection