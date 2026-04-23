<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Portal | BloodShare KH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        
        /* Only apply Kantumruy Pro when the .font-km class is explicitly present */
        .font-km { font-family: 'Kantumruy Pro', sans-serif !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-900 flex flex-col min-h-screen {{ app()->getLocale() === 'km' ? 'font-km' : '' }}" x-data="{ mobileMenuOpen: false }">

    {{-- 🌟 NAVBAR (Solid White with Shadow like Welcome page) 🌟 --}}
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
                    {{-- Request Blood Button --}}
                    <a href="{{ route('user.requests.create') }}" class="flex items-center bg-red-50 text-red-600 px-5 py-2.5 rounded-full font-bold text-sm shadow-sm hover:bg-red-600 hover:text-white transition-all border border-red-100 mr-6">
                        <i class="fa-solid fa-hand-holding-medical mr-2"></i> {{ __('ui.request_blood') }}
                    </a>

                    {{-- AUTHENTICATED OR GUEST PROFILE AREA --}}
                    @auth
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
                        {{-- GUEST LOGIN BUTTON --}}
                        <a href="{{ route('user.login') }}" class="flex items-center text-gray-600 hover:text-[#D32F2F] font-bold transition ml-6 pl-6 border-l border-gray-200">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i> {{ __('ui.login') }}
                        </a>
                    @endauth

                    {{-- 🌟 DYNAMIC DESKTOP NOTIFICATION BELL 🌟 --}}
                    <div class="relative inline-block text-left ml-6 pl-6 border-l border-gray-200" @click.away="desktopNotifOpen = false">
                        <div class="relative group cursor-pointer block">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-[#D32F2F] to-[#FF5252] rounded-xl blur opacity-20 group-hover:opacity-40 transition duration-300"></div>
                            <button @click="desktopNotifOpen = !desktopNotifOpen" type="button" class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-gray-100 text-[#D32F2F] shadow-sm hover:border-red-200 active:scale-95 transition-transform focus:outline-none">
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

                    {{-- 🌟 UPDATED LANGUAGE SWITCHER 🌟 --}}
                    @if(($settings['enable_language_switcher'] ?? '1') === '1')
                        <div class="relative ml-6 pl-6 border-l border-gray-200" x-data="{ langOpen: false }" @click.away="langOpen = false">
                            <button @click="langOpen = !langOpen" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white pl-2 pr-3 py-1.5 shadow-sm hover:border-red-200 hover:bg-red-50/40 transition">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-red-50 text-red-500">
                                    <i class="fa-solid fa-globe text-xs"></i>
                                </span>
                                
                                {{-- Full Text on Button --}}
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

                {{-- Mobile Menu Button & Notification --}}
                <div class="md:hidden flex items-center gap-5">
                    {{-- 🌟 DYNAMIC MOBILE NOTIFICATION BELL 🌟 --}}
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
                
                <a href="{{ route('home') }}" class="block py-3 px-6 text-gray-600 hover:text-red-600 font-medium">{{ __('ui.home') ?? 'Home' }}</a>
                <a href="{{ route('home') }}#requests" class="block py-3 px-6 text-gray-600 hover:text-red-600 font-medium">{{ __('ui.urgent_requests') ?? 'Urgent Requests' }}</a>

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
                    {{-- ADMIN VIEW --}}
                    @if(auth()->user()->usertype === 'admin')
                        <div class="py-3 mt-2 px-6">
                            <a href="{{ route('admin.dashboard') }}" class="block py-2 text-slate-900 font-black"><i class="fa-solid fa-shield-halved mr-2"></i> {{ __('ui.admin_portal') ?? 'Admin Portal' }}</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="text-gray-500 hover:text-red-600 font-medium w-full text-left py-2">{{ __('ui.logout') ?? 'Logout' }}</button>
                            </form>
                        </div>
                    {{-- NORMAL USER VIEW --}}
                    @else
                        <a href="{{ route('user.requests.create') }}" class="block py-3 px-6 text-red-600 font-bold">{{ __('ui.request_blood') ?? 'Request Blood' }}</a>
                        <div class="py-3 mt-2 px-6">
                            <div class="flex items-center mb-3">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover mr-3 border border-red-100">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 text-gray-400">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800 leading-tight">{{ auth()->user()->name }}</span>
                                    <span class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ auth()->user()->blood_type ?? __('ui.not_available') }} {{ __('ui.donor') }}</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('user.dashboard') }}" class="block py-2 text-gray-600 hover:text-red-600 font-medium">{{ __('ui.my_dashboard') ?? 'My Dashboard' }}</a>
                            <a href="{{ route('user.profile') }}" class="block py-2 text-gray-600 hover:text-red-600 font-medium">{{ __('ui.profile_settings') ?? 'Profile Settings' }}</a>
                            
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="text-gray-500 hover:text-red-600 font-medium w-full text-left py-2">{{ __('ui.logout') ?? 'Logout' }}</button>
                            </form>
                        </div>
                    @endif
                @else
                    <a href="{{ route('user.requests.create') }}" class="block py-3 px-6 text-red-600 font-bold">{{ __('ui.request_blood') ?? 'Request Blood' }}</a>
                    <div class="flex flex-col gap-3 mt-4 border-t border-gray-100 pt-4 px-6">
                        <a href="{{ route('user.login') }}" class="flex items-center justify-center gap-2 w-full py-3 border border-gray-200 rounded-xl font-bold text-gray-600">
                            <i class="fa-solid fa-right-to-bracket"></i> {{ __('ui.login') ?? 'Login' }}
                        </a>
                        <a href="{{ route('register') }}" class="text-center w-full py-3 bg-red-600 text-white rounded-xl font-bold shadow-md">{{ __('ui.register') ?? 'Register' }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- 2. MAIN CONTENT --}}
    <main class="pt-28 pb-12 px-6 min-h-screen">
        <div class="max-w-[1600px] mx-auto animate-fade-in">
            @yield('content')
        </div>
    </main>

    {{-- 🌟 ALPINE.JS NOTIFICATION ENGINE 🌟 --}}
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