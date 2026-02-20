<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Control | BloodShare KH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#FAFAFA] font-sans text-[#1A1C1E]">

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
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-5 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-[#D32F2F] text-white shadow-xl shadow-red-900/20 translate-x-2' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-chart-pie w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">Dashboard</span>
                        </a>

                        <p class="px-5 text-[10px] font-black text-gray-600 uppercase mt-10 mb-4 tracking-[0.2em]">Management</p>
                        
                        <a href="{{ route('admin.requests') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.requests') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-hand-holding-medical w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">Blood Requests</span>
                        </a>
                        <a href="{{ route('admin.donations') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.donations') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-file-invoice w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">Verify Invoices</span>
                        </a>
                        <a href="{{ route('admin.users') }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.users') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-users-gear w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">User Directory</span>
                        </a>

                        {{-- NEW: CONFIGURATION SECTION --}}
                        <p class="px-5 text-[10px] font-black text-gray-600 uppercase mt-10 mb-4 tracking-[0.2em]">Configuration</p>
                        
                        {{-- Make sure to create this route ('admin.settings') in your web.php --}}
                        <a href="{{ route('admin.settings') ?? '#' }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.settings') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-gear w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">Settings</span>
                        </a>
                        {{-- Localization Settings --}}
                        <a href="{{ route('admin.settings.localization') ?? '#' }}" class="flex items-center px-5 py-4 rounded-2xl {{ request()->routeIs('admin.settings.localization') ? 'bg-[#D32F2F] text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-earth-asia w-6 text-center"></i>
                            <span class="ml-4 font-bold text-sm">Localization</span>
                        </a>
                        

                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="mt-10 pt-6 border-t border-white/5">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-5 py-4 text-xs font-black text-gray-500 hover:text-[#D32F2F] transition-colors uppercase tracking-widest">
                            <i class="fa-solid fa-power-off w-6 text-center"></i>
                            <span class="ml-4 font-bold">Shutdown Session</span>
                        </button>
                    </form>
                </nav>
            </aside>
            @endif
        @endauth

        {{-- 2. MAIN WORKSPACE --}}
        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            {{-- HEADER: Protected Profile Info --}}
            {{-- HEADER: Protected Profile Info --}}
            @auth
            <header class="flex items-center justify-between px-10 py-6 bg-white/70 backdrop-blur-xl border-b border-gray-100 z-10">
                
                {{-- REPLACED SEARCH BAR: Greeting & Status --}}
                <div class="flex-1 flex flex-col justify-center">
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight leading-none">
                        Welcome Back, {{ explode(' ', auth()->user()->name)[0] }}!
                    </h2>
                    <div class="flex items-center mt-2.5 text-sm font-medium text-gray-500">
                        <i class="fa-regular fa-calendar text-[#D32F2F] mr-2"></i>
                        {{ now()->format('l, F jS, Y') }}
                        <span class="mx-3 text-gray-300">|</span>
                        <div class="flex items-center text-green-600 bg-green-50 px-2.5 py-1 rounded-md text-xs font-bold border border-green-100 shadow-sm">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse mr-1.5"></span>
                            System Online
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6 ml-10">
                    <div class="text-right hidden xl:block">
                        <p class="text-sm font-black text-[#1A1C1E] leading-tight">{{ auth()->user()->name }}</p>
                        <div class="flex items-center justify-end mt-1.5">
                            <div class="flex gap-0.5 mr-2">
                                <span class="w-1 h-3 bg-green-500 rounded-full animate-pulse"></span>
                                <span class="w-1 h-3 bg-green-500 rounded-full animate-pulse" style="animation-delay: 0.2s"></span>
                            </div>
                            <p class="text-[9px] text-[#D32F2F] uppercase font-black tracking-[0.25em]">Master Control</p>
                        </div>
                    </div>
                    
                    <div class="relative group cursor-pointer">
                        <div class="absolute -inset-1 bg-gradient-to-tr from-[#D32F2F] to-[#FF5252] rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-300"></div>
                        <div class="relative w-14 h-14 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-[#D32F2F] shadow-sm active:scale-95 transition-transform overflow-hidden">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user-shield text-xl"></i>
                            @endif
                        </div>
                    </div>
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