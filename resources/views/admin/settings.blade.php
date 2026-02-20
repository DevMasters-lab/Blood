@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    
    {{-- Header & Save Button Actions --}}
    <div class="flex justify-between items-center mb-10">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Frontend Settings</h2>
            <p class="text-sm text-gray-500 mt-1">Manage homepage content, contact information, and platform variables.</p>
        </div>
        
        {{-- Binds to the form below using form="settings-form" --}}
        <button type="submit" form="settings-form" class="bg-[#D32F2F] text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-700 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up"></i> Save Settings
        </button>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check text-xl mr-3 text-green-500"></i>
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Settings Form --}}
    <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- COLUMN 1 & 2: Main Content Settings --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Hero / Banner Section Card --}}
                <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center">
                        <i class="fa-solid fa-image text-[#D32F2F] mr-3"></i> Homepage Hero Banner
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Main Headline</label>
                            <input type="text" name="hero_title" value="Donate Blood, Save a Life Today" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F] focus:bg-white transition-all font-medium text-gray-800">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Subtitle / Description</label>
                            <textarea name="hero_subtitle" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F] focus:bg-white transition-all font-medium text-gray-800 resize-none">Urgent blood requests in Cambodia need your help. Connect directly with patients and be a hero.</textarea>
                        </div>
                    </div>
                </div>

                {{-- Contact Information Card --}}
                <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center">
                        <i class="fa-solid fa-address-book text-[#D32F2F] mr-3"></i> Footer Contact Info
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Support Email</label>
                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="email" name="contact_email" value="support@bloodshare.kh" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F] focus:bg-white transition-all font-medium text-gray-800">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Office Location</label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="contact_location" value="Phnom Penh, Cambodia" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F] focus:bg-white transition-all font-medium text-gray-800">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Facebook Link</label>
                            <div class="relative">
                                <i class="fa-brands fa-facebook absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="url" name="facebook_url" placeholder="https://facebook.com/..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F] focus:bg-white transition-all font-medium text-gray-800">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Telegram Link</label>
                            <div class="relative">
                                <i class="fa-brands fa-telegram absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="url" name="telegram_url" placeholder="https://t.me/..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 outline-none focus:ring-2 focus:ring-[#D32F2F] focus:bg-white transition-all font-medium text-gray-800">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- COLUMN 3: Toggles & Status --}}
            <div class="space-y-8">
                
                {{-- Platform Status Card --}}
                <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center">
                        <i class="fa-solid fa-sliders text-[#D32F2F] mr-3"></i> System Toggles
                    </h3>
                    
                    <div class="space-y-5">
                        {{-- Toggle 1 --}}
                        <label class="flex items-center justify-between cursor-pointer p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Maintenance Mode</p>
                                <p class="text-[11px] text-gray-500 font-medium mt-0.5">Disable public access</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="maintenance_mode" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#D32F2F]"></div>
                            </div>
                        </label>

                        {{-- Toggle 2 --}}
                        <label class="flex items-center justify-between cursor-pointer p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Allow Guest Requests</p>
                                <p class="text-[11px] text-gray-500 font-medium mt-0.5">Let non-users request</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="allow_guest_requests" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection