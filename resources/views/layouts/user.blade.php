<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Portal | BloodShare KH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-900 flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">

    {{-- NAVBAR --}}
    @auth
    <nav class="bg-white shadow-md fixed w-full z-50 top-0 transition-all duration-300">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                {{-- Logo --}}
                <a class="text-2xl font-black text-red-600 flex items-center tracking-tight" href="{{ route('user.dashboard') }}">
                    <i class="fa-solid fa-heart-pulse mr-2 animate-pulse"></i> BloodShare KH
                </a>

                {{-- Desktop Menu (Main Links) --}}
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600 font-medium transition">
                        Home
                    </a>
                    <a href="{{ route('home') }}#requests" class="text-gray-600 hover:text-red-600 font-medium transition">
                        Urgent Requests
                    </a>
                </div>
                
                {{-- Desktop Actions & Profile Dropdown --}}
                <div class="hidden md:flex items-center">
                    
                    {{-- NEW: Request Blood Button --}}
                    <a href="{{ route('user.requests.create') }}" class="flex items-center bg-red-50 text-red-600 px-5 py-2.5 rounded-full font-bold text-sm shadow-sm hover:bg-red-600 hover:text-white transition-all border border-red-100 mr-6">
                        <i class="fa-solid fa-hand-holding-medical mr-2"></i> Request Blood
                    </a>

                    {{-- PROFILE DROPDOWN --}}
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
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ auth()->user()->blood_type ?? 'N/A' }} Donor</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="profileOpen ? 'rotate-180' : ''"></i>
                        </button>

                        {{-- Dropdown Menu Container --}}
                        <div x-show="profileOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 border border-gray-100 z-50" style="display: none;">
                            
                            <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                <i class="fa-solid fa-house mr-2 w-4 text-center"></i> My Dashboard
                            </a>

                            <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                <i class="fa-solid fa-user-gear mr-2 w-4 text-center"></i> Profile Settings
                            </a>
                            
                            <div class="border-t border-gray-100 my-1"></div>
                            
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                    <i class="fa-solid fa-power-off mr-2 w-4 text-center"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-red-600 focus:outline-none">
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
                
                {{-- Mobile Links --}}
                <a href="{{ route('home') }}" class="block py-3 text-gray-600 hover:text-red-600 font-medium">Home</a>
                <a href="{{ route('home') }}#requests" class="block py-3 text-gray-600 hover:text-red-600 font-medium">Urgent Requests</a>
                
                {{-- Added Request Blood to Mobile Menu --}}
                <a href="{{ route('user.requests.create') }}" class="block py-3 text-red-600 font-bold">Request Blood</a>
                
                {{-- Mobile Profile Options --}}
                <div class="py-3 border-t border-gray-100 mt-2">
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
                            <span class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ auth()->user()->blood_type ?? 'N/A' }} Donor</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('user.dashboard') }}" class="block py-2 text-gray-600 hover:text-red-600 font-medium">My Dashboard</a>
                    <a href="{{ route('user.profile') }}" class="block py-2 text-gray-600 hover:text-red-600 font-medium">Profile Settings</a>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-gray-500 hover:text-red-600 font-medium w-full text-left py-2">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    {{-- 2. MAIN CONTENT --}}
    <main class="pt-28 pb-12 px-6 min-h-screen">
        <div class="max-w-[1600px] mx-auto animate-fade-in">
            @yield('content')
        </div>
    </main>

</body>
</html>