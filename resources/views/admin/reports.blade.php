@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- Header & Date Filter --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Analytics & Reports</h2>
            <p class="text-sm text-gray-500 mt-1.5 font-medium">System performance, donation metrics, and request fulfillment.</p>
        </div>
        
        <div class="flex gap-3">
            <select class="bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl px-5 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F]/20 shadow-sm cursor-pointer appearance-none">
                <option value="today">Today</option>
                <option value="7days" selected>Last 7 Days</option>
                <option value="30days">Last 30 Days</option>
                <option value="all">All Time</option>
            </select>
            <button class="bg-[#D32F2F] text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-red-900/20 hover:bg-red-700 transition-all flex items-center gap-2">
                <i class="fa-solid fa-download"></i> Export PDF
            </button>
        </div>
    </div>

    {{-- Top KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- KPI 1 --}}
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-4"><i class="fa-solid fa-hand-holding-medical"></i></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Requests</p>
                <div class="flex items-end gap-3 mt-1">
                    <p class="text-3xl font-black text-gray-900">124</p>
                    <p class="text-xs font-bold text-green-500 mb-1.5"><i class="fa-solid fa-arrow-trend-up"></i> 12%</p>
                </div>
            </div>
        </div>

        {{-- KPI 2 --}}
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-xl mb-4"><i class="fa-solid fa-check-double"></i></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Completed Requests</p>
                <div class="flex items-end gap-3 mt-1">
                    <p class="text-3xl font-black text-gray-900">89</p>
                    <p class="text-xs font-bold text-gray-400 mb-1.5">71% Success</p>
                </div>
            </div>
        </div>

        {{-- KPI 3 --}}
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-xl mb-4"><i class="fa-solid fa-hourglass-end"></i></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Expired Requests</p>
                <div class="flex items-end gap-3 mt-1">
                    <p class="text-3xl font-black text-gray-900">12</p>
                    <p class="text-xs font-bold text-red-500 mb-1.5"><i class="fa-solid fa-arrow-trend-up"></i> 2%</p>
                </div>
            </div>
        </div>

        {{-- KPI 4 --}}
        <div class="bg-[#1A1C1E] rounded-3xl p-6 border border-gray-800 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-[#D32F2F] text-white flex items-center justify-center text-xl mb-4 shadow-lg shadow-red-900/50"><i class="fa-solid fa-droplet"></i></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Verified Donations</p>
                <div class="flex items-end gap-3 mt-1">
                    <p class="text-3xl font-black text-white">342</p>
                    <p class="text-xs font-bold text-[#D32F2F] mb-1.5">Units Donated</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Visual Status Breakdown --}}
        <div class="lg:col-span-2 bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-lg font-black text-gray-900">Request Fulfillment Status</h3>
                <button class="text-sm font-bold text-gray-400 hover:text-[#D32F2F] transition-colors"><i class="fa-solid fa-ellipsis"></i></button>
            </div>
            
            {{-- CSS Progress Bars representing data --}}
            <div class="space-y-6">
                <div>
                    <div class="flex justify-between text-sm font-bold text-gray-700 mb-2">
                        <span>Completed (Received Blood)</span>
                        <span>71%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: 71%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm font-bold text-gray-700 mb-2">
                        <span>Reserved (Donors on the way)</span>
                        <span>18%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-orange-400 h-3 rounded-full" style="width: 18%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm font-bold text-gray-700 mb-2">
                        <span>Open (Urgently seeking donors)</span>
                        <span>8%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-[#D32F2F] h-3 rounded-full animate-pulse" style="width: 8%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm font-bold text-gray-700 mb-2">
                        <span>Expired / Cancelled</span>
                        <span>3%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-gray-300 h-3 rounded-full" style="width: 3%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Popular Blood Types --}}
        <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-black text-gray-900 mb-6">Most Requested Blood Types</h3>
            
            <div class="space-y-4">
                {{-- O+ --}}
                <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-red-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white text-[#D32F2F] font-black flex items-center justify-center shadow-sm group-hover:bg-[#D32F2F] group-hover:text-white transition-colors">O+</div>
                        <span class="text-sm font-bold text-gray-700">Highest Demand</span>
                    </div>
                    <span class="text-lg font-black text-gray-900">42%</span>
                </div>

                {{-- A+ --}}
                <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-red-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white text-[#D32F2F] font-black flex items-center justify-center shadow-sm group-hover:bg-[#D32F2F] group-hover:text-white transition-colors">A+</div>
                        <span class="text-sm font-bold text-gray-700">High Demand</span>
                    </div>
                    <span class="text-lg font-black text-gray-900">28%</span>
                </div>

                {{-- B+ --}}
                <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-red-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white text-[#D32F2F] font-black flex items-center justify-center shadow-sm group-hover:bg-[#D32F2F] group-hover:text-white transition-colors">B+</div>
                        <span class="text-sm font-bold text-gray-700">Medium Demand</span>
                    </div>
                    <span class="text-lg font-black text-gray-900">19%</span>
                </div>

                {{-- AB- --}}
                <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-red-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white text-[#D32F2F] font-black flex items-center justify-center shadow-sm group-hover:bg-[#D32F2F] group-hover:text-white transition-colors text-xs">AB-</div>
                        <span class="text-sm font-bold text-gray-700">Rare Need</span>
                    </div>
                    <span class="text-lg font-black text-gray-900">3%</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection