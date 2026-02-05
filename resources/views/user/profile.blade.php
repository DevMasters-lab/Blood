@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Account Settings</h2>
        <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-gray-900">
            &larr; Back to Dashboard
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <div class="p-6 bg-gray-50 border-b">
            <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
            <p class="mt-1 text-sm text-gray-500">Update your contact details and login password.</p>
        </div>

        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            {{-- Avatar Section --}}
            <div class="mb-6 flex items-center">
                <div class="mr-4">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-full object-cover border-2 border-red-500">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-2xl border-2 border-white shadow">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                    <input type="file" name="avatar" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    <p class="text-xs text-gray-500 mt-1">JPG or PNG. Max 2MB.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded shadow-sm p-2 border focus:ring-red-500 focus:border-red-500">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border-gray-300 rounded shadow-sm p-2 border focus:ring-red-500 focus:border-red-500">
                    <p class="text-xs text-gray-500 mt-1">This number will be shown to donors when you request blood.</p>
                </div>

                {{-- Password Section --}}
                <div class="md:col-span-2 mt-4 border-t pt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Change Password <span class="text-sm font-normal text-gray-500">(Leave blank to keep current)</span></h4>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" class="w-full border-gray-300 rounded shadow-sm p-2 border focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded shadow-sm p-2 border focus:ring-red-500 focus:border-red-500">
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-red-600 text-white font-bold py-2 px-6 rounded shadow hover:bg-red-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection