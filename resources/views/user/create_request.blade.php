@extends('layouts.user')

@section('content')
<div class="max-w-3xl mx-auto">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ __('ui.request_blood') }}</h2>
        </div>
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white text-gray-600 font-bold rounded-xl shadow-sm border border-gray-100 hover:bg-red-50 hover:text-red-600 transition-colors">
            <i class="fa-solid fa-arrow-left text-sm"></i> {{ __('ui.dashboard') }}
        </a>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 shadow-sm">
            <div class="flex items-center mb-2">
                <i class="fa-solid fa-triangle-exclamation text-xl mr-3 text-red-500"></i>
                <span class="font-bold text-lg">{{ __('ui.oops_check_inputs') }}</span>
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
            
            {{-- 🌟 NEW: Hidden input to send the device UUID to the controller 🌟 --}}
            <input type="hidden" name="device_uuid" id="device_uuid_input">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Blood Type --}}
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('ui.blood_type_needed') }}</label>
                    <div class="relative">
                        <select name="blood_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none appearance-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-bold text-gray-800 cursor-pointer">
                            <option value="" disabled selected>{{ __('ui.select_blood_type') }}</option>
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
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('ui.quantity') }}</label>
                    <input type="text" name="quantity" placeholder="{{ __('ui.quantity_example') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800" required>
                </div>

                {{-- Hospital Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('ui.hospital_name') }}</label>
                    <div class="relative">
                        <i class="fa-regular fa-hospital absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="hospital_name" placeholder="{{ __('ui.where_blood_needed') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800" required>
                    </div>
                </div>

                {{-- Date Needed --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('ui.date_needed') }}</label>
                    <input type="date" name="needed_date" min="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all font-medium text-gray-800 cursor-pointer" required>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-100">
                <button type="submit" class="w-full bg-red-600 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> {{ __('ui.submit_request') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 🌟 NEW: Script to grab the UUID from memory and put it in the hidden input 🌟 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let uuid = localStorage.getItem('device_uuid');
        if (uuid) {
            document.getElementById('device_uuid_input').value = uuid;
        }
    });
</script>
@endsection