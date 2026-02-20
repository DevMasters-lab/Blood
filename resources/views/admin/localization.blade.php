@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    
    {{-- Header & Save Button Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Localization</h2>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Manage platform languages, timezones, and regional formats.</p>
        </div>
        
        {{-- Binds to the form below using form="localization-form" --}}
        <button type="submit" form="localization-form" class="bg-red-600 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-red-500/30 hover:bg-red-700 hover:shadow-red-600/40 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up"></i> Save Changes
        </button>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl mb-8 flex items-center shadow-sm animate-fade-in">
            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-4 shrink-0">
                <i class="fa-solid fa-check text-emerald-600"></i>
            </div>
            <div>
                <p class="font-bold text-sm">Update Successful</p>
                <p class="text-xs font-medium text-emerald-600 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Localization Form --}}
    <form id="localization-form" action="{{ route('admin.settings.localization.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- COLUMN 1 & 2: Main Content Settings --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Language Settings Card --}}
                <div class="bg-white rounded-[1.5rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-8 pb-0">
                        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
                            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shadow-inner border border-red-100">
                                <i class="fa-solid fa-language"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Language Preferences</h3>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Control how users experience the platform</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 pt-0 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">System Default Language</label>
                            <div class="relative">
                                <select name="default_language" class="w-full bg-slate-50 hover:bg-slate-100/50 border border-slate-200 rounded-xl px-4 py-3.5 outline-none appearance-none focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all font-semibold text-slate-700 cursor-pointer">
                                    <option value="en" selected>English (EN)</option>
                                    <option value="km">Khmer (KM)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                            </div>
                        </div>

                        {{-- Toggle: Allow Users to switch language --}}
                        <label class="flex items-center justify-between cursor-pointer p-5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition-colors group">
                            <div>
                                <p class="text-sm font-bold text-slate-800 group-hover:text-red-600 transition-colors">Enable Language Switcher</p>
                                <p class="text-xs text-slate-500 font-medium mt-1">Show the translation dropdown on the frontend</p>
                            </div>
                            <div class="relative ml-4 shrink-0">
                                <input type="checkbox" name="enable_language_switcher" class="sr-only peer" checked>
                                <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600 shadow-inner"></div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Date & Time Settings Card --}}
                <div class="bg-white rounded-[1.5rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-8 pb-0">
                        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
                            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shadow-inner border border-red-100">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Date & Time Formats</h3>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Set how time is displayed across the app</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 pt-0 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Default Timezone</label>
                            <div class="relative">
                                <select name="timezone" class="w-full bg-slate-50 hover:bg-slate-100/50 border border-slate-200 rounded-xl px-4 py-3.5 outline-none appearance-none focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all font-semibold text-slate-700 cursor-pointer">
                                    <option value="Asia/Phnom_Penh" selected>(GMT+07:00) Asia/Phnom_Penh</option>
                                    <option value="Asia/Bangkok">(GMT+07:00) Asia/Bangkok</option>
                                    <option value="UTC">UTC (Universal Coordinated Time)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Date Format</label>
                            <div class="relative">
                                <select name="date_format" class="w-full bg-slate-50 hover:bg-slate-100/50 border border-slate-200 rounded-xl px-4 py-3.5 outline-none appearance-none focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all font-semibold text-slate-700 cursor-pointer">
                                    <option value="d/m/Y" selected>DD/MM/YYYY (31/12/2026)</option>
                                    <option value="Y-m-d">YYYY-MM-DD (2026-12-31)</option>
                                    <option value="M d, Y">MMM DD, YYYY (Dec 31, 2026)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Time Format</label>
                            <div class="relative">
                                <select name="time_format" class="w-full bg-slate-50 hover:bg-slate-100/50 border border-slate-200 rounded-xl px-4 py-3.5 outline-none appearance-none focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all font-semibold text-slate-700 cursor-pointer">
                                    <option value="H:i" selected>24-Hour (14:30)</option>
                                    <option value="h:i A">12-Hour (02:30 PM)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- COLUMN 3: Regional Settings --}}
            <div class="space-y-8">
                
                {{-- Region Card --}}
                <div class="bg-white rounded-[1.5rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-8 pb-0">
                        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
                            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shadow-inner border border-red-100">
                                <i class="fa-solid fa-location-crosshairs"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Region Details</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 pt-0 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Base Country</label>
                            <div class="relative">
                                <input type="text" value="Cambodia" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3.5 text-slate-400 font-bold cursor-not-allowed select-none" disabled>
                                <i class="fa-solid fa-lock absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-300"></i>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium mt-2 leading-relaxed">System is locked to local operations for blood tracking accuracy.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Default Phone Code</label>
                            <div class="relative">
                                <i class="fa-solid fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="phone_code" value="+855" class="w-full bg-slate-50 hover:bg-slate-100/50 border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 outline-none focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all font-bold text-slate-800">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection