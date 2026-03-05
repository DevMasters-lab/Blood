@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- Form wrapping the entire page so the top button works --}}
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Frontend Settings</h2>
                <p class="text-sm text-gray-500 mt-1 font-medium">Manage homepage content, contact information, and platform variables.</p>
            </div>
            
            <button type="submit" class="bg-[#D32F2F] text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-700 transition-all flex items-center gap-2 active:scale-95">
                <i class="fa-solid fa-cloud-arrow-up"></i> Save Settings
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column (Banners & Contact) --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Homepage Hero Banner --}}
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                        <i class="fa-solid fa-image text-[#D32F2F]"></i> Homepage Hero Banner
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Main Headline</label>
                            <input type="text" name="main_headline" value="{{ $settings['main_headline'] ?? 'Donate Blood, Save a Life Today' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 outline-none focus:bg-white focus:border-[#D32F2F] focus:ring-4 focus:ring-[#D32F2F]/10 transition-all font-bold text-gray-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Subtitle / Description</label>
                            <textarea name="hero_subtitle" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 outline-none focus:bg-white focus:border-[#D32F2F] focus:ring-4 focus:ring-[#D32F2F]/10 transition-all font-medium text-gray-800 resize-none">{{ $settings['hero_subtitle'] ?? 'Urgent blood requests in Cambodia need your help. Connect directly with patients and be a hero.' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Footer Contact Info --}}
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                        <i class="fa-solid fa-address-book text-[#D32F2F]"></i> Footer Contact Info
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Support Email</label>
                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'support@bloodshare.kh' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 outline-none focus:bg-white focus:border-[#D32F2F] focus:ring-4 focus:ring-[#D32F2F]/10 transition-all font-bold text-gray-800">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Office Location</label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="office_location" value="{{ $settings['office_location'] ?? 'Phnom Penh, Cambodia' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 outline-none focus:bg-white focus:border-[#D32F2F] focus:ring-4 focus:ring-[#D32F2F]/10 transition-all font-bold text-gray-800">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Facebook Link</label>
                            <div class="relative">
                                <i class="fa-brands fa-facebook absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="url" name="facebook_link" value="{{ $settings['facebook_link'] ?? '' }}" placeholder="https://facebook.com/..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 outline-none focus:bg-white focus:border-[#D32F2F] focus:ring-4 focus:ring-[#D32F2F]/10 transition-all font-bold text-gray-800">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Telegram Link</label>
                            <div class="relative">
                                <i class="fa-brands fa-telegram absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="url" name="telegram_link" value="{{ $settings['telegram_link'] ?? '' }}" placeholder="https://t.me/..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 outline-none focus:bg-white focus:border-[#D32F2F] focus:ring-4 focus:ring-[#D32F2F]/10 transition-all font-bold text-gray-800">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column (Toggles) --}}
            <div class="space-y-8">
                
                {{-- System Toggles --}}
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                        <i class="fa-solid fa-sliders text-[#D32F2F]"></i> System Toggles
                    </h3>
                    
                    <div class="space-y-6">
                        {{-- Toggle 1: Maintenance Mode --}}
                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl bg-gray-50/50">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Maintenance Mode</p>
                                <p class="text-xs text-gray-500 font-medium">Disable public access</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                            </label>
                        </div>

                        <!-- {{-- Toggle 2: Allow Guest Requests --}}
                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl bg-gray-50/50">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Allow Guest Requests</p>
                                <p class="text-xs text-gray-500 font-medium">Let non-users request</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="allow_guest_requests" value="1" class="sr-only peer" {{ ($settings['allow_guest_requests'] ?? '0') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                        </div> -->
                        
                        {{-- Notice from Brief --}}
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded-xl">
                            <p class="text-[10px] text-blue-700 font-bold leading-tight">
                                [cite_start]<i class="fa-solid fa-circle-info mr-1"></i> Note: The Project Brief recommends preventing Guests from creating requests to avoid abuse [cite: 1133-1135].
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection