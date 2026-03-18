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

    <div class="flex h-screen overflow-hidden">
        
        {{-- 1. SIDEBAR: STRICT ROLE CHECK --}}
        @auth
            @if(auth()->user()->usertype == 'admin') {{-- Only show sidebar for real admins --}}
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

                        {{-- 1. Dashboard --}}
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-5 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-[#D32F2F] text-white shadow-xl shadow-red-900/20 translate-x-2' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-chart-pie w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">{{ __('ui.dashboard') }}</span>
                        </a>

                        {{-- 2. Analytics --}}
                        <a href="{{ route('admin.reports') ?? '#' }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.reports') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-chart-line w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">{{ __('ui.analytics_reports') }}</span>
                        </a>

                        <p class="px-5 text-[10px] font-black text-gray-600 uppercase mt-10 mb-4 tracking-[0.2em]">{{ __('ui.management') }}</p>
                        
                        {{-- 3. Blood Requests --}}
                        <a href="{{ route('admin.requests') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.requests') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-hand-holding-medical w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">{{ __('ui.blood_requests') }}</span>
                        </a>

                        {{-- Blood Requested History --}}
                        <a href="{{ route('admin.requests.history') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.requests.history') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-clock-rotate-left w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">Requested History</span>
                        </a>

                        {{-- 4. Verify Invoices --}}
                        <a href="{{ route('admin.donations') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.donations') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-file-invoice w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">{{ __('ui.verify_invoices') }}</span>
                        </a>

                        {{-- 5. Manage Users --}}
                        <a href="{{ route('admin.users') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.users') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-users-gear w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">{{ __('ui.manage_users') }}</span>
                        </a>

                        {{-- 6. KYC Verifications --}}
                        <a href="{{ route('admin.kyc') ?? '#' }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.kyc') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-id-card w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">{{ __('ui.kyc_verifications') }}</span>
                        </a>

                        <p class="px-5 text-[10px] font-black text-gray-600 uppercase mt-10 mb-4 tracking-[0.2em]">{{ __('ui.configuration') }}</p>

                        {{-- 7. Account Settings --}}
                        <a href="{{ route('admin.profile') ?? '#' }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.profile') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-user-shield w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">{{ __('ui.account_settings') }}</span>
                        </a>
                        {{-- 9. Platform Settings --}}
                        <a href="{{ route('admin.settings') ?? '#' }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.settings') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-gear w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">{{ __('ui.platform_settings') }}</span>
                        </a>

                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="mt-10 pt-6 border-t border-white/5">
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

        {{-- 2. MAIN WORKSPACE --}}
        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            {{-- HEADER: Protected Profile Info --}}
            @auth
            <header class="flex items-center justify-between px-10 py-6 bg-white/70 backdrop-blur-xl border-b border-gray-100 z-10">
                
                {{-- Greeting & Status --}}
                <div class="flex-1 flex flex-col justify-center">
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight leading-none">
                        {{ __('ui.welcome_back', ['name' => explode(' ', auth()->user()->name)[0]]) }}
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

                {{-- Profile Section --}}
                <div class="flex items-center gap-6 ml-10">
                    <div class="text-right hidden xl:block">
                        <p class="text-sm font-black text-[#1A1C1E] leading-tight">{{ auth()->user()->name }}</p>
                        <div class="flex items-center justify-end mt-1.5">
                            <div class="flex gap-0.5 mr-2">
                                <span class="w-1 h-3 bg-green-500 rounded-full animate-pulse"></span>
                                <span class="w-1 h-3 bg-green-500 rounded-full animate-pulse" style="animation-delay: 0.2s"></span>
                            </div>
                            <p class="text-[9px] text-[#D32F2F] uppercase font-black tracking-[0.25em]">{{ __('ui.master_control') }}</p>
                        </div>
                    </div>
                    
                    {{-- NEW: Made the Avatar a clickable link to Account Settings --}}
                    <a href="{{ route('admin.profile') ?? '#' }}" class="relative group cursor-pointer block" title="{{ __('ui.go_to_account_settings') }}">
                        <div class="absolute -inset-1 bg-gradient-to-tr from-[#D32F2F] to-[#FF5252] rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-300"></div>
                        <div class="relative w-14 h-14 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-[#D32F2F] shadow-sm active:scale-95 transition-transform overflow-hidden hover:border-red-200">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
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

            {{-- 3. CONTENT AREA --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-10 {{ !auth()->check() ? 'flex items-center justify-center' : '' }}">
                <div class="{{ !auth()->check() ? 'w-full max-w-md' : 'max-w-[1400px] mx-auto' }} animate-fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>