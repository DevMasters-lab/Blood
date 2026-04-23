<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Control | BloodShare KH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        .font-km { font-family: 'Kantumruy Pro', sans-serif !important; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#FAFAFA] font-sans text-[#1A1C1E] {{ app()->getLocale() === 'km' ? 'font-km' : '' }}">
    @php
    $adminUser = auth('admin')->user();

    $isSuperAdmin = $adminUser
        ? $adminUser->hasRole('Super Admin', 'web')
        : false;

    $can = function (string $permission) use ($adminUser, $isSuperAdmin): bool {
        if (!$adminUser) {
            return false;
        }

        if ($isSuperAdmin) {
            return true;
        }

        return $adminUser->checkPermissionTo($permission, 'web');
    };

    $isRequestsActive = request()->routeIs('admin.requests')
        || request()->routeIs('admin.requests.status')
        || request()->routeIs('admin.requests.delete');

    $isRequestHistoryActive = request()->routeIs('admin.requests.history');

    $adminSidebarVisible = (bool) $adminUser;
@endphp

    <div class="flex h-screen overflow-hidden">
        @auth('admin')
            @if($adminSidebarVisible)
                <aside class="flex flex-col w-72 h-full px-6 py-10 bg-[#1A1C1E] shadow-2xl z-20 overflow-y-auto">
                    <div class="flex items-center mb-12 px-2">
                        <div class="w-12 h-12 bg-[#D32F2F] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-900/50 mr-4 shrink-0">
                            <i class="fa-solid fa-heart-pulse text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-black text-white leading-none tracking-tight">BloodShare</h1>
                            <p class="text-[10px] font-bold text-[#D32F2F] tracking-[0.3em] mt-1 uppercase">Kingdom</p>
                        </div>
                    </div>

                    <nav class="flex flex-col justify-between flex-1">
                        <div class="space-y-2">
                            <p class="px-5 text-[10px] font-black text-gray-600 uppercase mt-10 mb-4 tracking-[0.2em]">{{ __('ui.overview') }}</p>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-5 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-[#D32F2F] text-white shadow-xl shadow-red-900/20 translate-x-2' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-chart-pie w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">{{ __('ui.dashboard') }}</span>
                                </a>

                                <a href="{{ route('admin.reports') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.reports*') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-chart-line w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">{{ __('ui.analytics_reports') }}</span>
                                </a>

                            <p class="px-5 text-[10px] font-black text-gray-600 uppercase mt-10 mb-4 tracking-[0.2em]">{{ __('ui.management') }}</p>

                            @if($can('view_requests'))
                                <a href="{{ route('admin.requests') }}" class="flex items-center px-5 py-4 rounded-2xl {{ $isRequestsActive ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-hand-holding-medical w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">{{ __('ui.blood_requests') }}</span>
                                </a>
                            @endif

                            @if($can('view_history'))
                                <a href="{{ route('admin.requests.history') }}" class="flex items-center px-5 py-4 rounded-2xl {{ $isRequestHistoryActive ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-clock-rotate-left w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">Requested History</span>
                                </a>
                            @endif

                            @if($can('view_invoices'))
                                <a href="{{ route('admin.invoices') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.invoices*') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-file-invoice w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">{{ __('ui.verify_invoices') }}</span>
                                </a>
                            @endif

                            @if($can('view_users'))
                                <a href="{{ route('admin.users') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.users*') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-users-gear w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">{{ __('ui.manage_users') }}</span>
                                </a>
                            @endif

                            @if($can('view_kyc'))
                                <a href="{{ route('admin.kyc') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.kyc*') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-id-card w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">{{ __('ui.kyc_verifications') }}</span>
                                </a>
                            @endif

                            <p class="px-5 text-[10px] font-black text-gray-600 uppercase mt-10 mb-4 tracking-[0.2em]">{{ __('ui.configuration') }}</p>

                            @if($can('view_account'))
                                <a href="{{ route('admin.profile') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.profile*') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-user-shield w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">{{ __('ui.account_settings') }}</span>
                                </a>
                            @endif

                            @if($can('view_settings'))
                                <a href="{{ route('admin.settings') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.settings*') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-gear w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">{{ __('ui.platform_settings') }}</span>
                                </a>
                            @endif

                            @if($can('view_roles'))
                                <a href="{{ route('admin.roles.index') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.roles.*') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                    <i class="fa-solid fa-user-lock w-6 text-center"></i>
                                    <span class="ml-4 font-bold text-sm">Roles & Permissions</span>
                                </a>
                            @endif
                        </div>

                        <form action="{{ route('admin.logout') }}" method="POST" class="mt-10 pt-6 border-t border-white/5">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-5 py-4 text-xs font-black text-gray-500 hover:text-[#D32F2F] transition-colors uppercase tracking-widest">
                                <i class="fa-solid fa-power-off w-6 text-center"></i>
                                <span class="ml-4 font-bold">{{ __('ui.logout') }}</span>
                            </button>
                        </form>
                    </nav>
                </aside>
            @endif
        @endauth

        <div class="flex-1 flex flex-col overflow-hidden relative">
            @auth('admin')
                {{-- 🌟 ALPINE NOTIFICATION SYSTEM IN HEADER 🌟 --}}
                <header class="flex items-center justify-between px-10 py-6 bg-transparent backdrop-blur-xl border-b border-transparent z-10" x-data="notificationSystem()" x-init="initNotifications()">
                    <div class="flex-1 flex flex-col justify-center">
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight leading-none">
                            {{ __('ui.welcome_back', ['name' => explode(' ', auth('admin')->user()->name)[0]]) }}
                        </h2>
                        <div class="flex items-center mt-2.5 text-sm font-medium text-gray-500">
                            <i class="fa-regular fa-calendar text-[#D32F2F] mr-2"></i>
                            {{ now()->format('l, F jS, Y') }}
                            <span class="mx-3 text-gray-300">|</span>
                            <div class="flex items-center text-green-600 bg-green-50 px-2.5 py-1 rounded-md text-xs font-bold border border-green-100 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse mr-1.5"></span>
                                {{ __('ui.system_online') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 ml-10">
                        <div class="text-right hidden xl:block">
                            <p class="text-sm font-black text-[#1A1C1E] leading-tight">{{ auth('admin')->user()->name }}</p>
                            <div class="flex items-center justify-end mt-1.5">
                                <div class="flex gap-0.5 mr-2">
                                    <span class="w-1 h-3 bg-green-500 rounded-full animate-pulse"></span>
                                    <span class="w-1 h-3 bg-green-500 rounded-full animate-pulse" style="animation-delay: 0.2s"></span>
                                </div>
                                <p class="text-[9px] text-[#D32F2F] uppercase font-black tracking-[0.25em]">{{ __('ui.master_control') }}</p>
                            </div>
                        </div>

                        {{-- DYNAMIC NOTIFICATION BELL DROPDOWN --}}
                        <div class="relative inline-block text-left" @click.away="open = false">
                            
                            {{-- Bell Button --}}
                            <div class="relative group cursor-pointer block">
                                <div class="absolute -inset-1 bg-gradient-to-tr from-[#D32F2F] to-[#FF5252] rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-300"></div>
                                <button @click="open = !open" type="button" class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-white border border-gray-100 text-[#D32F2F] shadow-sm active:scale-95 transition-transform hover:border-red-200 focus:outline-none">
                                    <i class="fa-solid fa-bell text-xl group-hover:scale-110 transition-transform"></i>
                                    
                                    {{-- Unread Badge Counter (Dynamic) --}}
                                    <span x-show="unreadCount > 0" class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full border-[3px] border-white bg-[#D32F2F] text-[10px] font-black text-white shadow-sm z-10" x-text="unreadCount" style="display: none;"></span>
                                </button>
                            </div>

                            {{-- Dropdown Panel --}}
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 style="display: none;" 
                                 class="absolute right-0 top-full mt-4 w-80 sm:w-96 rounded-2xl border border-gray-100 bg-white shadow-xl z-50 overflow-hidden flex flex-col">
                                
                                {{-- Dropdown Header --}}
                                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 px-5 py-4">
                                    <h3 class="text-sm font-black text-gray-900">Notifications</h3>
                                    <button x-show="unreadCount > 0" @click="markAllAsRead()" class="text-[10px] font-bold text-[#D32F2F] hover:text-[#B71C1C] uppercase tracking-wider transition-colors">Mark all read</button>
                                </div>

                                {{-- Notifications List (Dynamic Alpine Loop) --}}
                                <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
                                    <template x-for="notif in notifications" :key="notif.id">
                                        <a :href="'/notifications/' + notif.id + '/read'" class="flex items-start gap-4 px-5 py-4 transition-colors hover:bg-gray-50/80 group">
                                            
                                            {{-- Icon Styling: Gray if read, Red if unread --}}
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full transition-colors" 
                                                 :class="notif.is_read ? 'bg-gray-100 text-gray-400 group-hover:bg-gray-200' : 'bg-red-50 text-[#D32F2F] group-hover:bg-[#D32F2F] group-hover:text-white'">
                                                <i class="fa-solid" :class="notif.icon"></i>
                                            </div>
                                            
                                            <div class="flex-1 space-y-1">
                                                {{-- Text Styling: Gray if read --}}
                                                <p class="text-sm font-bold" :class="notif.is_read ? 'text-gray-500' : 'text-gray-900'" x-text="notif.title"></p>
                                                <p class="text-xs font-medium text-gray-500 line-clamp-2" x-text="notif.message"></p>
                                                <p class="text-[10px] font-bold mt-2" :class="notif.is_read ? 'text-gray-300' : 'text-gray-400'">
                                                    <i class="fa-regular fa-clock mr-1"></i> <span x-text="notif.time"></span>
                                                </p>
                                            </div>

                                            {{-- Unread Red Dot --}}
                                            <div x-show="!notif.is_read" class="w-2.5 h-2.5 rounded-full bg-[#D32F2F] mt-1.5 shrink-0 border-2 border-white shadow-sm"></div>
                                        </a>
                                    </template>
                                    
                                    {{-- Empty State --}}
                                    <div x-show="notifications.length === 0" class="px-5 py-10 text-center flex flex-col items-center" style="display: none;">
                                        <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fa-regular fa-bell-slash text-gray-400 text-xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-gray-900">All caught up!</p>
                                        <p class="text-xs text-gray-500 font-medium mt-1">You have no new notifications.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.profile') }}" class="relative group cursor-pointer block" title="{{ __('ui.go_to_account_settings') }}">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-[#D32F2F] to-[#FF5252] rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-300"></div>
                            <div class="relative w-14 h-14 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-[#D32F2F] shadow-sm active:scale-95 transition-transform overflow-hidden hover:border-red-200">
                                @if(auth('admin')->user()->avatar)
                                    <img src="{{ asset('storage/' . auth('admin')->user()->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user-shield text-xl group-hover:scale-110 transition-transform"></i>
                                @endif
                            </div>
                        </a>

                        <details class="relative group">
                            <summary class="list-none cursor-pointer inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white pl-2 pr-3 py-1.5 shadow-sm hover:border-red-200 hover:bg-red-50/40 transition">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-red-50 text-red-500">
                                    <i class="fa-solid fa-globe text-xs"></i>
                                </span>
                                <span class="text-xs font-black text-gray-700 uppercase">{{ app()->getLocale() }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform group-open:rotate-180"></i>
                            </summary>

                            <div class="absolute right-0 mt-2 w-44 rounded-xl border border-gray-100 bg-white p-2 shadow-xl z-50">
                                <a href="{{ route('admin.language.switch', 'en') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm font-bold transition {{ app()->getLocale() === 'en' ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="inline-flex items-center gap-2"><span>{{ __('ui.english') }}</span><span>🇬🇧</span></span>
                                    <span class="text-[11px] font-black">EN</span>
                                </a>
                                <a href="{{ route('admin.language.switch', 'km') }}" class="mt-1 flex items-center justify-between rounded-lg px-3 py-2 text-sm font-bold transition {{ app()->getLocale() === 'km' ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="inline-flex items-center gap-2"><span>{{ __('ui.khmer') }}</span><span>🇰🇭</span></span>
                                    <span class="text-[11px] font-black">KM</span>
                                </a>
                            </div>
                        </details>
                    </div>
                </header>
            @endauth

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-10 bg-[#FAFAFA] {{ !auth('admin')->check() ? 'flex items-center justify-center' : '' }}">
                <div class="{{ !auth('admin')->check() ? 'w-full max-w-md' : 'max-w-[1400px] mx-auto' }} animate-fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- 🌟 ALPINE DYNAMIC NOTIFICATIONS ENGINE (Admin) 🌟 --}}
    <script>
        function notificationSystem() {
            return {
                open: false,
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
                        this.notifications.forEach(notif => notif.is_read = true);
                        this.unreadCount = 0;
                    });
                }
            }
        }
    </script>
</body>
</html>