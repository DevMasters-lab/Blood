<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Cambodia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js for Mobile Menu, AJAX Search & Profile Dropdown --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">
    {{-- NAVBAR --}}
    <nav class="bg-white shadow-md fixed w-full z-50 top-0 transition-all duration-300">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                {{-- Logo --}}
                <a class="text-2xl font-black text-red-600 flex items-center tracking-tight" href="{{ route('home') }}">
                    <i class="fa-solid fa-heart-pulse mr-2 animate-pulse"></i> BloodShare KH
                </a>
                
                {{-- Desktop Menu --}}
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600 font-medium transition">Home</a>
                    <a href="{{ route('home') }}#requests" class="text-gray-600 hover:text-red-600 font-medium transition">Urgent Requests</a>
                </div>
                
                {{-- Desktop Actions & Profile Dropdown --}}
                <div class="hidden md:flex items-center">
                    @auth
                        {{-- Request Blood Button --}}
                        <a href="{{ route('user.requests.create') }}" class="flex items-center bg-red-50 text-red-600 px-5 py-2.5 rounded-full font-bold text-sm shadow-sm hover:bg-red-600 hover:text-white transition-all border border-red-100 mr-6">
                            <i class="fa-solid fa-hand-holding-medical mr-2"></i> Request Blood
                        </a>

                        {{-- PROFILE DROPDOWN (with vertical divider) --}}
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
                            
                            {{-- Dropdown Menu --}}
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
                    @else
                        {{-- Guests Request Blood Button --}}
                        <a href="{{ route('user.login') }}" class="flex items-center bg-red-50 text-red-600 px-5 py-2.5 rounded-full font-bold text-sm shadow-sm hover:bg-red-600 hover:text-white transition-all border border-red-100 mr-6">
                            <i class="fa-solid fa-hand-holding-medical mr-2"></i> Request Blood
                        </a>
                        
                        {{-- Guest Auth Buttons (with vertical divider) --}}
                        <div class="pl-6 border-l border-gray-200 flex items-center">
                            <a href="{{ route('user.login') }}" class="text-gray-600 hover:text-red-600 mr-6 font-bold transition">Login</a>
                            <a href="{{ route('register') }}" class="bg-red-600 text-white px-6 py-2.5 rounded-full font-bold shadow-lg shadow-red-200 hover:bg-red-700 hover:shadow-xl transition transform hover:-translate-y-0.5">
                                Register 
                            </a>
                        </div>
                    @endauth
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
                <a href="{{ route('home') }}" class="block py-3 text-gray-600 hover:text-red-600 font-medium">Home</a>
                <a href="{{ route('home') }}#requests" class="block py-3 text-gray-600 hover:text-red-600 font-medium">Urgent Requests</a>
                <a href="{{ route('user.requests.create') }}" class="block py-3 text-red-600 font-bold">Request Blood</a>
                
                @auth
                    {{-- Mobile Profile Options --}}
                    <div class="py-3 border-t border-gray-100 mt-2">
                        <div class="flex items-center mb-3">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover mr-2 border border-red-100">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-2 text-gray-400"><i class="fa-solid fa-user"></i></div>
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
                @else
                    <div class="flex flex-col gap-3 mt-4 border-t border-gray-100 pt-4">
                        <a href="{{ route('user.login') }}" class="text-center w-full py-3 border border-gray-200 rounded-xl font-bold text-gray-600">Login</a>
                        <a href="{{ route('register') }}" class="text-center w-full py-3 bg-red-600 text-white rounded-xl font-bold shadow-md">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <div class="relative bg-gradient-to-br from-red-700 to-red-600 pt-32 pb-20 px-6 text-center text-white overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <i class="fa-solid fa-heart-pulse absolute top-10 left-10 text-9xl"></i>
            <i class="fa-solid fa-hand-holding-medical absolute bottom-10 right-10 text-9xl"></i>
        </div>
        <div class="relative z-10 max-w-3xl mx-auto">
            <span class="bg-white/20 backdrop-blur-md text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-6 inline-block">
                Emergency Response
            </span>
            <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">Donate Blood,<br>Save a Life Today</h1>
            <p class="text-lg md:text-xl mb-10 text-red-100 font-medium">Urgent blood requests in Cambodia need your help. Connect directly with patients and be a hero.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#requests" class="bg-white text-red-600 font-black py-4 px-10 rounded-full shadow-xl hover:bg-gray-50 transition transform hover:scale-105">
                    Find Requests
                </a>
                @guest
                <a href="{{ route('register') }}" class="bg-red-800 text-white font-bold py-4 px-10 rounded-full shadow-xl hover:bg-red-900 transition">
                    Register as Donor
                </a>
                @endguest
            </div>
        </div>
    </div>

    {{-- REQUESTS SECTION (AJAX ENABLED) --}}
    <div id="requests" class="container mx-auto px-6 py-16 flex-grow" 
         x-data="{ 
            isLoading: false,
            // Check URL on load to show/hide clear button
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
                    
                    // Show Clear Button
                    this.hasFilter = true;
                } catch (error) {
                    console.error('Search failed:', error);
                } finally {
                    this.isLoading = false;
                }
            },
            async clearFilters() {
                this.isLoading = true;
                const url = `{{ route('home') }}`; // Reset to clean home URL
                try {
                    const response = await fetch(url);
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    document.getElementById('requests-grid').innerHTML = doc.getElementById('requests-grid').innerHTML;
                    window.history.pushState({}, '', url);
                    
                    // Reset UI
                    document.querySelector('select[name=\'blood_type\']').value = '';
                    this.hasFilter = false;
                } finally {
                    this.isLoading = false;
                }
            }
         }">
        
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 tracking-tight">
                Urgent Blood Needed
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
                    
                    {{-- Dropdown Wrapper --}}
                    <div class="flex-grow relative border-r border-gray-100">
                        <i class="fa-solid fa-droplet text-red-500 absolute left-4 top-1/2 transform -translate-y-1/2 text-lg"></i>
                        <select name="blood_type" class="w-full bg-transparent border-none pl-12 pr-10 py-3 outline-none appearance-none font-bold text-gray-700 cursor-pointer h-full">
                            <option value="">Filter by Blood Type...</option>
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
                    
                    {{-- Search Button --}}
                    <button type="submit" :disabled="isLoading" class="bg-red-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition flex items-center ml-2 shadow-md disabled:opacity-75 disabled:cursor-not-allowed">
                        <span x-show="!isLoading">Search</span>
                        <span x-show="isLoading" class="flex items-center" style="display: none;">
                            <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> ...
                        </span>
                    </button>
                </div>
            </form>
            
            {{-- CLEAR FILTERS BUTTON --}}
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
                    <h3 class="text-xl font-bold text-gray-800">No Requests Found</h3>
                    <p class="text-gray-500 mt-2 max-w-sm text-center">There are no urgent requests matching your search criteria right now.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($requests as $req)
                    <div class="bg-white rounded-[1.5rem] p-6 border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.12)] transition-all duration-300 flex flex-col h-full relative overflow-hidden group">
                        
                        {{-- Top Accent Line --}}
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-red-600"></div>

                        {{-- Header: Badge, Hospital & Blood Type --}}
                        <div class="flex justify-between items-start mb-6 pt-2">
                            <div class="pr-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider mb-2 border border-red-100/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                    Urgent Need
                                </span>
                                <h3 class="text-xl font-black text-gray-900 leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">{{ $req->hospital_name }}</h3>
                            </div>
                            <div class="w-12 h-12 shrink-0 bg-red-600 text-white rounded-xl flex items-center justify-center font-black text-lg shadow-sm transform group-hover:scale-105 transition-transform">
                                {{ $req->blood_type }}
                            </div>
                        </div>

                        {{-- Patient & Contact Info --}}
                        <div class="space-y-4 mb-6 flex-grow">
                            {{-- Patient --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 shrink-0 border border-gray-100 mt-0.5 group-hover:bg-red-50 group-hover:text-red-500 transition-colors">
                                    <i class="fa-solid fa-user-injured text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Patient</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $req->patient_name ?? 'Anonymous' }}</p>
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 shrink-0 border border-gray-100 mt-0.5 group-hover:bg-red-50 group-hover:text-red-500 transition-colors">
                                    <i class="fa-solid fa-phone text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Contact Phone</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $req->contact_phone ?? 'No number' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Info Pills (Location & Units) --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-50 text-gray-700 text-xs font-bold border border-gray-100">
                                <i class="fa-solid fa-droplet text-red-500"></i> {{ $req->units_needed ?? '1' }} Bag(s)
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-50 text-gray-700 text-xs font-bold border border-gray-100">
                                <i class="fa-solid fa-location-dot text-red-500"></i> <span class="truncate max-w-[120px]">{{ $req->location ?? 'Phnom Penh' }}</span>
                            </div>
                        </div>

                        {{-- Footer: Dates, Requester & Buttons --}}
                        <div class="pt-5 border-t border-gray-100 mt-auto">
                            
                            {{-- Added Requested By under Needed By --}}
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Needed By</span>
                                    <span class="text-xs font-black text-red-600 bg-red-50 px-2.5 py-1 rounded-md border border-red-100">{{ $req->needed_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Requested By</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $req->requester->name ?? 'User' }}</span>
                                </div>
                            </div>
                            {{-- <div class="px-6 pb-6 mt-2">
                                <a href="{{ route('request.show', $req->id) }}" class="block w-full bg-gray-900 text-white text-center py-3.5 rounded-2xl font-bold text-sm hover:bg-red-600 transition-colors shadow-lg group-hover:shadow-red-500/30">
                                    View Contact Details
                                </a>
                            </div> --}}
                            <div class="flex gap-3">
                                {{-- Fixed Call Button (Added padding, moved text outside of <i> tag) --}}
                                <a href="tel:{{ $req->contact_phone ?? '' }}" class="px-5 h-12 shrink-0 bg-white border border-gray-200 text-gray-700 rounded-xl flex items-center justify-center gap-2 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors shadow-sm font-bold text-sm">
                                    <i class="fa-solid fa-phone"></i> Call
                                </a>
                                
                                
                                {{-- Primary Action Button --}}
                                <a href="{{ auth()->check() ? route('user.donate') : route('user.login') }}" class="flex-1 bg-red-600 text-white flex items-center justify-center gap-2 rounded-xl font-bold text-sm hover:bg-red-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 border border-transparent">
                                    <i class="fa-solid fa-hand-holding-medical"></i> I Can Donate
                                </a>
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="mt-12 flex justify-end">
                    {{ $requests->appends(request()->query())->fragment('requests')->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white py-16 mt-auto">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <a class="text-2xl font-black text-white flex items-center mb-6" href="/">
                        <i class="fa-solid fa-heart-pulse mr-3 text-red-500"></i> BloodShare KH
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                        A centralized platform connecting generous blood donors with patients in critical need across Cambodia. Every donation counts.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-6">Platform</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="/" class="hover:text-red-500 transition">Home</a></li>
                        <li><a href="#requests" class="hover:text-red-500 transition">Urgent Requests</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-red-500 transition">Become a Donor</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-6">Contact</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-start"><i class="fa-solid fa-envelope mt-1 mr-3 text-red-500"></i> support@bloodshare.kh</li>
                        <li class="flex items-start"><i class="fa-solid fa-location-dot mt-1 mr-3 text-red-500"></i> Phnom Penh, Cambodia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-xs text-gray-500">&copy; 2026 BloodShare KH. All rights reserved.</p>
                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-red-600 hover:text-white transition"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-red-600 hover:text-white transition"><i class="fa-brands fa-telegram"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>