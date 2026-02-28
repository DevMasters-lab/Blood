@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">KYC Verifications</h2>
            <p class="text-sm text-gray-500 mt-1.5 font-medium">Review official IDs to verify donors and secure the platform.</p>
        </div>
        
        <div class="flex gap-3">
            <select class="bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl px-5 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F]/20 shadow-sm cursor-pointer appearance-none">
                <option value="pending">Pending Review (3)</option>
                <option value="verified">Verified Users</option>
                <option value="rejected">Rejected Submissions</option>
            </select>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl mr-4"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pending</p>
                <p class="text-2xl font-black text-gray-900">3 Submissions</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center">
            <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-2xl mr-4"><i class="fa-solid fa-shield-check"></i></div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Verified Today</p>
                <p class="text-2xl font-black text-gray-900">12 Users</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center">
            <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center text-2xl mr-4"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rejected</p>
                <p class="text-2xl font-black text-gray-900">2 Users</p>
            </div>
        </div>
    </div>

    {{-- KYC Submissions List --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">User Details</th>
                        <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">ID / Passport Info</th>
                        <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Document Proof</th>
                        <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    {{-- Sample Row 1 --}}
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="py-5 px-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold mr-4">S</div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Sokha Developer</p>
                                    <p class="text-xs text-gray-500 mt-0.5">+855 12 345 678</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <p class="text-sm font-bold text-gray-800">NID-123456789</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-md">Pending</span>
                        </td>
                        <td class="py-5 px-6">
                            <button class="flex items-center gap-2 text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fa-solid fa-image"></i> View ID Card
                            </button>
                        </td>
                        <td class="py-5 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors" title="Approve">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <button class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors" title="Reject">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Sample Row 2 --}}
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="py-5 px-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold mr-4">M</div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Minea Chan</p>
                                    <p class="text-xs text-gray-500 mt-0.5">+855 98 765 432</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <p class="text-sm font-bold text-gray-800">PASS-N483921</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-md">Pending</span>
                        </td>
                        <td class="py-5 px-6">
                            <button class="flex items-center gap-2 text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fa-solid fa-file-pdf"></i> View Passport
                            </button>
                        </td>
                        <td class="py-5 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors" title="Approve">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <button class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors" title="Reject">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection