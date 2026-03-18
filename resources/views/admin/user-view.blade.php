@extends('layouts.admin')

@section('content')
<div class="space-y-6 animate-fade-in px-8 py-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">User Details</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">View donor account details.</p>
        </div>
        <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Users
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 sm:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Name</p>
                <p class="text-sm font-bold text-gray-900 mt-1">{{ $user->name ?? 'N/A' }}</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Phone</p>
                <p class="text-sm font-bold text-gray-900 mt-1">{{ $user->phone ?? 'N/A' }}</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</p>
                <p class="text-sm font-bold text-gray-900 mt-1">{{ $user->email ?? 'N/A' }}</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Blood Type</p>
                <p class="text-sm font-bold text-gray-900 mt-1">{{ $user->blood_type ?? 'N/A' }}</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">KYC Status</p>
                <p class="text-sm font-bold text-gray-900 mt-1">{{ ucfirst($user->kyc_status ?? 'pending') }}</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Account Status</p>
                <p class="text-sm font-bold text-gray-900 mt-1">{{ ucfirst($user->status ?? 'active') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
