<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Cambodia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .font-km {
            font-family: 'Kantumruy Pro', sans-serif !important;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans {{ app()->getLocale() === 'km' ? 'font-km' : '' }}" x-data="{ mobileMenuOpen: false }">
    
    {{-- FETCH SETTINGS FROM DATABASE ONCE FOR THE WHOLE PAGE --}}
    @php
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
    @endphp

    {{-- 🌟 NAVBAR 🌟 --}}
    <nav class="bg-white shadow-md fixed w-full z-50 top-0 transition-all duration-300" x-data="notificationSystem()" x-init="initNotifications()">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                {{-- Logo --}}
                <a class="text-2xl font-black text-red-600 flex items-center tracking-tight" href="{{ route('home') }}">
                    <i class="fa-solid fa-heart-pulse mr-2 animate-pulse"></i> BloodShare KH
                </a>

                {{-- Desktop Menu (Main Links) --}}
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600 font-medium transition">
                        {{ __('ui.home') }}
                    </a>
                    <a href="{{ route('home') }}#requests" class="text-gray-600 hover:text-red-600 font-medium transition">
                        {{ __('ui.urgent_requests') }}
                    </a>
                </div>
                
                {{-- Desktop Actions & Profile Dropdown --}}
                <div class="hidden md:flex items-center">
                    
                    @auth
                        @if(auth()->user()->usertype === 'admin')
                            {{-- Admin Dashboard Button --}}
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center bg-slate-900 text-white px-5 py-2.5 rounded-full font-bold text-sm shadow-sm hover:bg-black transition-all mr-6">
                                <i class="fa-solid fa-shield-halved mr-2"></i> {{ __('ui.admin_portal') }}
                            </a>
                        @else
                            {{-- Request Blood Button --}}
                            <a href="{{ route('user.requests.create') }}" class="flex items-center bg-red-50 text-red-600 px-5 py-2.5 rounded-full font-bold text-sm shadow-sm hover:bg-red-600 hover:text-white transition-all border border-red-100 mr-6">
                                <i class="fa-solid fa-hand-holding-medical mr-2"></i> {{ __('ui.request_blood') }}
                            </a>
                        @endif

                        {{-- AUTHENTICATED PROFILE AREA --}}
                        <div class="relative pl-6 border-l border-gray-200" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                            <button @click="profileOpen = !profileOpen" class="flex items-center focus:outline-none transition-transform hover:scale-105">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-10 h-10 rounded-full object-cover mr-3 border-2 border-red-100 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3 text-gray-400 shadow-sm">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                                <div class="flex flex-col text-left mr-2">
                                    <span class="text-sm font-bold text-gray-800 leading-tight">{{ auth()->user()->name }}</span>
                                    <span class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ auth()->user()->blood_type ?? __('ui.not_available') }} {{ __('ui.donor') }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="profileOpen ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="profileOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 border border-gray-100 z-50" style="display: none;">
                                
                                @if(auth()->user()->usertype === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                        <i class="fa-solid fa-gauge mr-2 w-4 text-center"></i> Admin Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                        <i class="fa-solid fa-house mr-2 w-4 text-center"></i> {{ __('ui.my_dashboard') }}
                                    </a>
                                    <a href="{{ route('user.requests.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                        <i class="fa-solid fa-clock-rotate-left mr-2 w-4 text-center"></i> Request History
                                    </a>
                                    <a href="{{ route('user.wallet') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                        <i class="fa-solid fa-wallet mr-2 w-4 text-center"></i> {{ __('ui.my_wallet') }}
                                    </a>
                                    <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                        <i class="fa-solid fa-user-gear mr-2 w-4 text-center"></i> {{ __('ui.profile_settings') }}
                                    </a>
                                @endif
                                
                                <div class="border-t border-gray-100 my-1"></div>
                                
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                        <i class="fa-solid fa-power-off mr-2 w-4 text-center"></i> {{ __('ui.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- GUEST BUTTONS --}}
                        <a href="{{ route('user.requests.create') }}" class="flex items-center bg-red-50 text-red-600 px-5 py-2.5 rounded-full font-bold text-sm shadow-sm hover:bg-red-600 hover:text-white transition-all border border-red-100 mr-6">
                            <i class="fa-solid fa-hand-holding-medical mr-2"></i> {{ __('ui.request_blood') }}
                        </a>
                        <div class="flex items-center pl-6 border-l border-gray-200">
                            {{-- 🌟 RESTORED DESKTOP LOGIN ICON 🌟 --}}
                            <a href="{{ route('user.login') }}" class="flex items-center text-gray-600 hover:text-[#D32F2F] mr-5 font-bold transition">
                                <i class="fa-solid fa-right-to-bracket mr-2"></i> {{ __('ui.login') }}
                            </a>
                            <a href="{{ route('register') }}" class="bg-red-600 text-white px-5 py-2 rounded-full font-bold text-sm shadow-sm hover:bg-red-700 transition">
                                {{ __('ui.register') }}
                            </a>
                        </div>
                    @endauth

                    {{-- 🌟 DYNAMIC DESKTOP NOTIFICATION BELL 🌟 --}}
                    <div class="relative inline-block text-left ml-6 pl-6 border-l border-gray-200" @click.away="desktopNotifOpen = false">
                        <div class="relative group cursor-pointer block">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-[#D32F2F] to-[#FF5252] rounded-xl blur opacity-20 group-hover:opacity-40 transition duration-300"></div>
                            <button @click="desktopNotifOpen = !desktopNotifOpen" type="button" class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-gray-100 text-[#D32F2F] shadow-sm active:scale-95 transition-transform hover:border-red-200 focus:outline-none">
                                <i class="fa-solid fa-bell group-hover:scale-110 transition-transform"></i>
                                <span x-show="unreadCount > 0" class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-[#D32F2F] text-[9px] font-black text-white shadow-sm z-10" x-text="unreadCount" style="display: none;"></span>
                            </button>
                        </div>

                        <div x-show="desktopNotifOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             style="display: none;" 
                             class="absolute right-0 top-full mt-4 w-80 sm:w-96 rounded-2xl border border-gray-100 bg-white shadow-xl z-50 overflow-hidden flex flex-col">
                            
                            <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 px-5 py-4">
                                <h3 class="text-sm font-black text-gray-900">Notifications</h3>
                                <button x-show="unreadCount > 0" @click="markAllAsRead()" class="text-[10px] font-bold text-[#D32F2F] hover:text-[#B71C1C] uppercase tracking-wider transition-colors">Mark all read</button>
                            </div>

                            <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
                                <template x-for="notif in notifications" :key="notif.id">
                                    <a :href="'/notifications/' + notif.id + '/read'" class="flex items-start gap-4 px-5 py-4 transition-colors hover:bg-gray-50/80 group">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full transition-colors" 
                                             :class="notif.is_read ? 'bg-gray-100 text-gray-400 group-hover:bg-gray-200' : 'bg-red-50 text-[#D32F2F] group-hover:bg-[#D32F2F] group-hover:text-white'">
                                            <i class="fa-solid" :class="notif.icon"></i>
                                        </div>
                                        <div class="flex-1 space-y-1">
                                            <p class="text-sm font-bold" :class="notif.is_read ? 'text-gray-500' : 'text-gray-900'" x-text="notif.title"></p>
                                            <p class="text-xs font-medium text-gray-500 line-clamp-2" x-text="notif.message"></p>
                                            <p class="text-[10px] font-bold mt-2" :class="notif.is_read ? 'text-gray-300' : 'text-gray-400'">
                                                <i class="fa-regular fa-clock mr-1"></i> <span x-text="notif.time"></span>
                                            </p>
                                        </div>
                                        <div x-show="!notif.is_read" class="w-2 h-2 rounded-full bg-[#D32F2F] mt-1.5 shrink-0"></div>
                                    </a>
                                </template>

                                <div x-show="notifications.length === 0" class="px-5 py-10 text-center flex flex-col items-center">
                                    <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fa-regular fa-bell-slash text-gray-400 text-xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900">All caught up!</p>
                                    <p class="text-xs text-gray-500 font-medium mt-1">You have no new notifications.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- LANGUAGE SWITCHER --}}
                    @if(($settings['enable_language_switcher'] ?? '1') === '1')
                        <div class="relative ml-6 pl-6 border-l border-gray-200" x-data="{ langOpen: false }" @click.away="langOpen = false">
                            <button @click="langOpen = !langOpen" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white pl-2 pr-3 py-1.5 shadow-sm hover:border-red-200 hover:bg-red-50/40 transition">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-red-50 text-red-500">
                                    <i class="fa-solid fa-globe text-xs"></i>
                                </span>
                                
                                <span class="text-xs font-black text-gray-700">
                                    @if(app()->getLocale() === 'km')
                                        <span class="font-km tracking-wide">ភាសាខ្មែរ</span>
                                    @else
                                        ENGLISH
                                    @endif
                                </span>
                                
                                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform" :class="langOpen ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="langOpen"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-44 rounded-xl border border-gray-100 bg-white p-2 shadow-xl z-50" style="display: none;">
                                
                                <a href="{{ route('language.switch', 'en') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm font-bold transition {{ app()->getLocale() === 'en' ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="inline-flex items-center gap-2"><span>🇬🇧</span><span>English</span></span>
                                </a>
                                
                                <a href="{{ route('language.switch', 'km') }}" class="mt-1 flex items-center justify-between rounded-lg px-3 py-2 text-sm font-bold transition {{ app()->getLocale() === 'km' ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="inline-flex items-center gap-2"><span>🇰🇭</span><span class="font-km">ភាសាខ្មែរ</span></span>
                                </a>
                            </div>
                        </div>
                    @endif
                    
                </div>
            </div>

            {{-- Mobile Menu Button & Notification --}}
            <div class="md:hidden flex items-center gap-5">
                
                {{-- DYNAMIC MOBILE NOTIFICATION BELL --}}
                <div class="relative" @click.away="mobileNotifOpen = false">
                    <button @click="mobileNotifOpen = !mobileNotifOpen" class="relative text-gray-500 hover:text-red-600 focus:outline-none transition-colors">
                        <i class="fa-solid fa-bell text-xl"></i>
                        <span x-show="unreadCount > 0" class="absolute -right-1.5 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full border-2 border-white bg-[#D32F2F] text-[8px] font-black text-white shadow-sm" x-text="unreadCount" style="display: none;"></span>
                    </button>
                    
                    <div x-show="mobileNotifOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         style="display: none;" 
                         class="absolute right-0 top-full mt-4 w-72 rounded-2xl border border-gray-100 bg-white shadow-xl z-50 overflow-hidden flex flex-col">
                        
                        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 px-4 py-3">
                            <h3 class="text-sm font-black text-gray-900">Notifications</h3>
                            <button x-show="unreadCount > 0" @click="markAllAsRead()" class="text-[9px] font-bold text-[#D32F2F] uppercase tracking-wider">Mark all read</button>
                        </div>

                        <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                            <template x-for="notif in notifications" :key="notif.id">
                                <a :href="'/notifications/' + notif.id + '/read'" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full transition-colors"
                                         :class="notif.is_read ? 'bg-gray-100 text-gray-400' : 'bg-red-50 text-[#D32F2F]'">
                                        <i class="fa-solid" :class="notif.icon"></i>
                                    </div>
                                    <div class="flex-1 space-y-0.5">
                                        <p class="text-xs font-bold" :class="notif.is_read ? 'text-gray-500' : 'text-gray-900'" x-text="notif.title"></p>
                                        <p class="text-[10px] text-gray-500 line-clamp-2" x-text="notif.message"></p>
                                        <p class="text-[9px] font-bold mt-1" :class="notif.is_read ? 'text-gray-300' : 'text-gray-400'" x-text="notif.time"></p>
                                    </div>
                                    <div x-show="!notif.is_read" class="w-1.5 h-1.5 rounded-full bg-[#D32F2F] mt-1 shrink-0"></div>
                                </a>
                            </template>
                            <div x-show="notifications.length === 0" class="px-4 py-6 text-center">
                                <p class="text-xs font-bold text-gray-500">No new notifications.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-red-600 focus:outline-none transition-colors">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden mt-4 pb-4 border-t border-gray-100" style="display: none;">
            <a href="{{ route('home') }}" class="block py-3 px-6 text-gray-600 hover:text-red-600 font-medium">{{ __('ui.home') }}</a>
            <a href="{{ route('home') }}#requests" class="block py-3 px-6 text-gray-600 hover:text-red-600 font-medium">{{ __('ui.urgent_requests') }}</a>

            @if(($settings['enable_language_switcher'] ?? '1') === '1')
                <div class="flex flex-col gap-2 py-3 px-6 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Language</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('language.switch', 'en') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition {{ app()->getLocale() === 'en' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600' }}"><span>🇬🇧</span><span>English</span></a>
                        <a href="{{ route('language.switch', 'km') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition {{ app()->getLocale() === 'km' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600' }}"><span>🇰🇭</span><span class="font-km">ភាសាខ្មែរ</span></a>
                    </div>
                </div>
            @endif
            
            @auth
                @if(auth()->user()->usertype === 'admin')
                    <div class="py-3 mt-2 px-6">
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 text-slate-900 font-black"><i class="fa-solid fa-shield-halved mr-2"></i> {{ __('ui.admin_portal') }}</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="text-gray-500 hover:text-red-600 font-medium w-full text-left py-2">{{ __('ui.logout') }}</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('user.requests.create') }}" class="block py-3 px-6 text-red-600 font-bold">{{ __('ui.request_blood') }}</a>
                    <div class="py-3 mt-2 px-6">
                        <div class="flex items-center mb-3">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover mr-2 border border-red-100">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-2 text-gray-400"><i class="fa-solid fa-user"></i></div>
                            @endif
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 leading-tight">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ auth()->user()->blood_type ?? __('ui.not_available') }} {{ __('ui.donor') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('user.dashboard') }}" class="block py-2 text-gray-600 hover:text-red-600 font-medium">{{ __('ui.my_dashboard') }}</a>
                        <a href="{{ route('user.profile') }}" class="block py-2 text-gray-600 hover:text-red-600 font-medium">{{ __('ui.profile_settings') }}</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="text-gray-500 hover:text-red-600 font-medium w-full text-left py-2">{{ __('ui.logout') }}</button>
                        </form>
                    </div>
                @endif
            @else
                <a href="{{ route('user.requests.create') }}" class="block py-3 px-6 text-red-600 font-bold">{{ __('ui.request_blood') }}</a>
                <div class="flex flex-col gap-3 mt-4 border-t border-gray-100 pt-4 px-6">
                    {{-- 🌟 RESTORED MOBILE LOGIN ICON 🌟 --}}
                    <a href="{{ route('user.login') }}" class="flex justify-center items-center gap-2 w-full py-3 border border-gray-200 rounded-xl font-bold text-gray-600">
                        <i class="fa-solid fa-right-to-bracket"></i> {{ __('ui.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-center w-full py-3 bg-red-600 text-white rounded-xl font-bold shadow-md">{{ __('ui.register') }}</a>
                </div>
            @endauth
        </div>
    </nav>

    {{-- HERO SECTION WITH DYNAMIC SETTINGS --}}
    @php
        $heroBanner = $settings['hero_banner_image'] ?? null;
        $locale = app()->getLocale();
        $headline = $locale === 'km'
            ? ($settings['main_headline_km'] ?? $settings['main_headline_en'] ?? $settings['main_headline'] ?? 'Donate Blood, Save a Life Today')
            : ($settings['main_headline_en'] ?? $settings['main_headline'] ?? 'Donate Blood, Save a Life Today');
        $subtitle = $locale === 'km'
            ? ($settings['hero_subtitle_km'] ?? $settings['hero_subtitle_en'] ?? $settings['hero_subtitle'] ?? 'Urgent blood requests in Cambodia need your help. Connect directly with patients and be a hero.')
            : ($settings['hero_subtitle_en'] ?? $settings['hero_subtitle'] ?? 'Urgent blood requests in Cambodia need your help. Connect directly with patients and be a hero.');
    @endphp
        <div class="relative pt-32 pb-20 px-6 text-center text-white overflow-hidden bg-gradient-to-br from-red-700 to-red-600">
            @if($heroBanner)
                <img src="{{ asset('storage/' . $heroBanner) }}" alt="Hero Banner" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-br from-red-900/75 to-red-600/75"></div>
            @endif
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <i class="fa-solid fa-heart-pulse absolute top-10 left-10 text-9xl"></i>
            <i class="fa-solid fa-hand-holding-medical absolute bottom-10 right-10 text-9xl"></i>
        </div>
        <div class="relative z-10 max-w-3xl mx-auto">
            <span class="bg-white/20 backdrop-blur-md text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-6 inline-block">
                {{ __('ui.emergency_response') }}
            </span>
            
            <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                {{ $headline }}
            </h1>
            
            <p class="text-lg md:text-xl mb-10 text-red-100 font-medium">
                {{ $subtitle }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#requests" class="bg-white text-red-600 font-black py-4 px-10 rounded-full shadow-xl hover:bg-gray-50 transition transform hover:scale-105">
                    {{ __('ui.find_requests') }}
                </a>
                @guest
                <a href="{{ route('register') }}" class="bg-red-800 text-white font-bold py-4 px-10 rounded-full shadow-xl hover:bg-red-900 transition">
                    {{ __('ui.register_as_donor') }}
                </a>
                @endguest
            </div>
        </div>
    </div>

    {{-- REQUESTS SECTION (AJAX ENABLED) --}}
    <div id="requests" class="container mx-auto px-6 py-16 flex-grow" 
         x-data="{ 
            isLoading: false,
            hasFilter: new URLSearchParams(window.location.search).has('blood_type'),
            async performSearch(event) {
                this.isLoading = true;
                const form = event.target;
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                const url = `{{ route('home') }}?${params.toString()}`;
                try {
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    document.getElementById('requests-grid').innerHTML = doc.getElementById('requests-grid').innerHTML;
                    window.history.pushState({}, '', url);
                    
                    this.hasFilter = true;
                } catch (error) {
                    console.error('Search failed:', error);
                } finally {
                    this.isLoading = false;
                }
            },
            async clearFilters() {
                this.isLoading = true;
                const url = `{{ route('home') }}`;
                try {
                    const response = await fetch(url);
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    document.getElementById('requests-grid').innerHTML = doc.getElementById('requests-grid').innerHTML;
                    window.history.pushState({}, '', url);
                    
                    document.querySelector('select[name=\'blood_type\']').value = '';
                    this.hasFilter = false;
                } finally {
                    this.isLoading = false;
                }
            }
         }">
        
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 tracking-tight">
                {{ __('ui.urgent_blood_needed_short') }}
            </h2>
            <div class="flex items-center justify-center gap-3">
                <div class="h-1 w-12 md:w-24 bg-gradient-to-r from-transparent via-red-400 to-red-600 rounded-full opacity-80"></div>
                <div class="relative">
                    <div class="absolute inset-0 bg-red-600 rounded-full blur opacity-20 animate-pulse"></div>
                    <div class="w-8 h-8 bg-white border-2 border-red-50 rounded-full flex items-center justify-center relative z-10 shadow-sm">
                        <i class="fa-solid fa-heart-pulse text-red-600 text-xs"></i>
                    </div>
                </div>
                <div class="h-1 w-12 md:w-24 bg-gradient-to-l from-transparent via-red-400 to-red-600 rounded-full opacity-80"></div>
            </div>
        </div>

        {{-- SEARCH FILTER FORM --}}
        <div class="max-w-2xl mx-auto mb-16">
            <form @submit.prevent="performSearch" action="{{ route('home') }}" method="GET" class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-red-600 to-red-400 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative flex bg-white rounded-xl shadow-lg overflow-hidden p-2">
                    
                    <div class="flex-grow relative border-r border-gray-100">
                        <i class="fa-solid fa-droplet text-red-500 absolute left-4 top-1/2 transform -translate-y-1/2 text-lg"></i>
                        <select name="blood_type" class="w-full bg-transparent border-none pl-12 pr-10 py-3 outline-none appearance-none font-bold text-gray-700 cursor-pointer h-full">
                            <option value="">{{ __('ui.filter_by_blood_type') }}</option>
                            <option value="Any" {{ request('blood_type') == 'Any' ? 'selected' : '' }}>Any</option>
                            <option value="A+" {{ request('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ request('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ request('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ request('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="O+" {{ request('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ request('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                            <option value="AB+" {{ request('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ request('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>
                    
                    <button type="submit" :disabled="isLoading" class="bg-red-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition flex items-center ml-2 shadow-md disabled:opacity-75 disabled:cursor-not-allowed">
                        <span x-show="!isLoading">{{ __('ui.search') }}</span>
                        <span x-show="isLoading" class="flex items-center" style="display: none;">
                            <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> ...
                        </span>
                    </button>
                </div>
            </form>
            
            <div class="text-center mt-4" x-show="hasFilter" style="display: none;">
                <button @click="clearFilters" :disabled="isLoading" class="text-sm font-bold text-gray-400 hover:text-red-600 transition flex items-center justify-center gap-2 mx-auto disabled:opacity-50">
                    <i class="fa-solid fa-times-circle"></i> Clear Filters
                </button>
            </div>
        </div>

        {{-- RESULTS GRID WRAPPER --}}
        <div id="requests-grid">
            @if($requests->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-6">
                        <i class="fa-regular fa-folder-open text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">{{ __('ui.no_requests_found') }}</h3>
                    <p class="text-gray-500 mt-2 max-w-sm text-center">{{ __('ui.no_requests_match') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($requests as $req)
                    <div class="bg-white rounded-[1.5rem] p-6 border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.12)] transition-all duration-300 flex flex-col h-full relative overflow-hidden group">
                        
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-red-600"></div>

                        <div class="flex justify-between items-start mb-6 pt-2">
                            <div class="pr-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider mb-2 border border-red-100/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                    {{ __('ui.urgent_need') }}
                                </span>
                                <h3 class="text-xl font-black text-gray-900 leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">{{ $req->hospital_name }}</h3>
                            </div>
                            <div class="w-12 h-12 shrink-0 bg-red-600 text-white rounded-xl flex items-center justify-center font-black text-lg shadow-sm transform group-hover:scale-105 transition-transform">
                                {{ $req->blood_type }}
                            </div>
                        </div>

                        <div class="space-y-4 mb-6 flex-grow">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 shrink-0 border border-gray-100 mt-0.5 group-hover:bg-red-50 group-hover:text-red-500 transition-colors">
                                    <i class="fa-solid fa-user-injured text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('ui.patient') }}</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $req->patient_name ?? 'Anonymous' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 shrink-0 border border-gray-100 mt-0.5 group-hover:bg-red-50 group-hover:text-red-500 transition-colors">
                                    <i class="fa-solid fa-phone text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('ui.contact_phone') }}</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $req->contact_phone ?? 'No number' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-6">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-50 text-gray-700 text-xs font-bold border border-gray-100">
                                <i class="fa-solid fa-droplet text-red-500"></i> {{ $req->units_needed ?? '1' }} Bag(s)
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-50 text-gray-700 text-xs font-bold border border-gray-100">
                                <i class="fa-solid fa-location-dot text-red-500"></i> <span class="truncate max-w-[120px]">{{ $req->location ?? 'Phnom Penh' }}</span>
                            </div>
                        </div>

                        <div class="pt-5 border-t border-gray-100 mt-auto">
                            
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('ui.needed_by') }}</span>
                                    <span class="text-xs font-black text-red-600 bg-red-50 px-2.5 py-1 rounded-md border border-red-100">{{ $req->needed_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('ui.requested_by') }}</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $req->requester->name ?? __('ui.default_user') }}</span>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <a href="tel:{{ $req->contact_phone ?? '' }}" class="px-5 h-12 shrink-0 bg-white border border-gray-200 text-gray-700 rounded-xl flex items-center justify-center gap-2 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors shadow-sm font-bold text-sm">
                                    <i class="fa-solid fa-phone"></i> {{ __('ui.call') }}
                                </a>
                                
                                <a href="{{ auth()->check() ? route('user.donate') : route('user.login') }}" class="flex-1 bg-red-600 text-white flex items-center justify-center gap-2 rounded-xl font-bold text-sm hover:bg-red-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 border border-transparent">
                                    <i class="fa-solid fa-hand-holding-medical"></i> {{ __('ui.i_can_donate') }}
                                </a>
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
                
                <div class="mt-12 flex justify-end">
                    {{ $requests->appends(request()->query())->fragment('requests')->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- FOOTER WITH DYNAMIC SETTINGS --}}
    <footer class="bg-gray-900 text-white py-16 mt-auto">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <a class="text-2xl font-black text-white flex items-center mb-6" href="/">
                        <i class="fa-solid fa-heart-pulse mr-3 text-red-500"></i> BloodShare KH
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                        {{ __('ui.about_platform') }}
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-6">{{ __('ui.platform') }}</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="/" class="hover:text-red-500 transition">{{ __('ui.home') }}</a></li>
                        <li><a href="#requests" class="hover:text-red-500 transition">{{ __('ui.urgent_requests') }}</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-red-500 transition">{{ __('ui.become_a_donor') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-6">{{ __('ui.contact') }}</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-start">
                            <i class="fa-solid fa-envelope mt-1 mr-3 text-red-500"></i> 
                            {{ $settings['support_email'] ?? 'support@bloodshare.kh' }}
                        </li>
                        <li class="flex items-start">
                            <i class="fa-solid fa-location-dot mt-1 mr-3 text-red-500"></i> 
                            {{ $settings['office_location'] ?? 'Phnom Penh, Cambodia' }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-xs text-gray-500">&copy; 2026 BloodShare KH. {{ __('ui.all_rights_reserved') }}</p>
                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="{{ $settings['facebook_link'] ?? '#' }}" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-red-600 hover:text-white transition" target="_blank">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="{{ $settings['telegram_link'] ?? '#' }}" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-red-600 hover:text-white transition" target="_blank">
                        <i class="fa-brands fa-telegram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    {{-- 🌟 ALPINE DYNAMIC NOTIFICATIONS ENGINE 🌟 --}}
    <script>
        function notificationSystem() {
            return {
                desktopNotifOpen: false,
                mobileNotifOpen: false,
                notifications: [],
                unreadCount: 0,

                initNotifications() {
                    let deviceUuid = localStorage.getItem('device_uuid');
                    if (!deviceUuid) {
                        deviceUuid = crypto.randomUUID();
                        localStorage.setItem('device_uuid', deviceUuid);
                    }

                    fetch(`/api/notifications?device_uuid=${deviceUuid}`)
                        .then(res => res.json())
                        .then(data => {
                            this.notifications = data.notifications || [];
                            this.unreadCount = data.count || 0;
                        })
                        .catch(err => console.error('Error fetching notifications:', err));
                },

                markAllAsRead() {
                    let deviceUuid = localStorage.getItem('device_uuid');
                    fetch(`/api/notifications/read-all`, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                        },
                        body: JSON.stringify({ device_uuid: deviceUuid })
                    }).then(() => {
                        this.notifications.forEach(notif => {
                            notif.is_read = true;
                        });
                        this.unreadCount = 0;
                    });
                }
            }
        }
    </script>
</body>
</html>