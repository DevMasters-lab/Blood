<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Cambodia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-md fixed w-full z-10 top-0">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a class="text-2xl font-bold text-red-600 flex items-center" href="/">
                <i class="fa-solid fa-heart-pulse mr-2"></i> BloodShare KH
            </a>
            <div class="hidden md:flex space-x-6">
                <a href="/" class="hover:text-red-600">Home</a>
                <a href="#requests" class="hover:text-red-600">Urgent Requests</a>
                @auth
                    <a href="{{ route('user.dashboard') }}" class="hover:text-red-600 font-semibold">My Dashboard</a>
                @endauth
            </div>
            
            <div class="flex items-center">
                @auth
                    {{-- IF USER IS LOGGED IN --}}
                    
                    {{-- NEW AVATAR SECTION --}}
                    <div class="hidden md:flex items-center mr-4">
                        @if(auth()->user()->avatar)
                            {{-- Show Uploaded Image --}}
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover mr-2 border border-gray-300">
                        @else
                            {{-- Show Default Icon --}}
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2 text-gray-500 text-xs">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <span class="text-gray-700 font-bold">{{ auth()->user()->name }}</span>
                    </div>
                    {{-- END AVATAR SECTION --}}
                    
                    <form action="{{ route('user.logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300 text-sm">
                            Logout
                        </button>
                    </form>
                @else
                    {{-- IF GUEST (NOT LOGGED IN) --}}
                    <a href="{{ route('user.login') }}" class="text-gray-600 hover:text-red-600 mr-4 font-semibold">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition shadow">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <div class="relative bg-red-600 h-96 flex items-center justify-center mt-16 text-center text-white px-6">
        <div>
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Donate Blood, Save a Life</h1>
            <p class="text-lg md:text-xl mb-8">Urgent blood requests in Cambodia need your help today.</p>
            <a href="#requests" class="bg-white text-red-600 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition">
                Find Requests
            </a>
        </div>
    </div>

    {{-- REQUESTS SECTION --}}
    <div id="requests" class="container mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-center mb-8 border-b-4 border-red-600 inline-block pb-2">
            Urgent Blood Needed
        </h2>

        {{-- SEARCH FILTER FORM --}}
        <div class="max-w-xl mx-auto mb-10">
            <form action="{{ route('home') }}" method="GET" class="flex shadow-md rounded-lg overflow-hidden border border-gray-200">
                <div class="flex-grow relative">
                    <i class="fa-solid fa-droplet text-red-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    <select name="blood_type" class="w-full bg-white border-none pl-10 pr-4 py-3 outline-none appearance-none focus:ring-2 focus:ring-red-500 text-gray-700 font-semibold cursor-pointer">
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
                </div>
                
                <button type="submit" class="bg-red-600 text-white px-6 font-bold hover:bg-red-700 transition flex items-center">
                    <i class="fa-solid fa-filter mr-2"></i> Search
                </button>
                
                @if(request('blood_type'))
                    <a href="{{ route('home') }}" class="bg-gray-200 text-gray-600 px-4 flex items-center hover:bg-gray-300 transition border-l border-gray-300" title="Clear Filter">
                        <i class="fa-solid fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- REQUEST CARDS --}}
        @if($requests->isEmpty())
            <div class="text-center py-12 bg-white rounded shadow-sm border border-gray-100">
                <div class="text-gray-400 mb-4 text-5xl">
                    <i class="fa-regular fa-folder-open"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-600">No Requests Found</h3>
                <p class="text-gray-500 mt-2">There are no urgent requests matching your search right now.</p>
                @if(request('blood_type'))
                    <a href="{{ route('home') }}" class="mt-4 inline-block text-red-600 font-bold hover:underline">Clear Filters</a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($requests as $req)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition transform hover:-translate-y-1">
                    <div class="bg-red-50 p-4 flex justify-between items-center border-b border-red-100">
                        <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded uppercase animate-pulse">
                            Urgent
                        </span>
                        <span class="text-red-600 font-bold text-lg">
                            <i class="fa-solid fa-droplet"></i> {{ $req->blood_type }}
                        </span>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800">{{ $req->hospital_name }}</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            <i class="fa-solid fa-user-injured mr-1 text-red-400"></i> Patient: {{ $req->patient_name ?? 'Anonymous' }}
                        </p>
                        
                        <div class="flex justify-between items-center text-sm text-gray-500 mb-4 bg-gray-50 p-2 rounded">
                            <span><i class="fa-regular fa-calendar mr-1"></i> Needed:</span>
                            <span class="font-bold text-red-600">
                                {{ $req->needed_date->format('d M Y') }}
                            </span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                             <div class="text-xs text-gray-400 font-medium">
                                 Posted by {{ $req->requester->name }}
                             </div>
                             
                             <a href="{{ route('request.show', $req->id) }}" class="text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-sm font-bold shadow transition flex items-center">
                                 Contact <i class="fa-solid fa-phone ml-2"></i>
                             </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{-- Make sure to append the query string so filters persist across pages --}}
                {{ $requests->appends(request()->query())->fragment('requests')->links() }}
            </div>
        @endif
    </div>

    <footer class="bg-gray-800 text-white py-10 mt-auto">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
        
        {{-- Column 1 --}}
        <div>
            <h3 class="text-lg font-bold mb-4 text-red-400">BloodShare KH</h3>
            <p class="text-gray-400 text-sm">
                Saving lives by connecting donors with patients in real-time. Join our community today.
            </p>
        </div>

        {{-- Column 2 --}}
        <div>
            <h3 class="text-lg font-bold mb-4">Quick Links</h3>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><a href="/" class="hover:text-white">Home</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-white">About Us</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-white">Register as Donor</a></li>
            </ul>
        </div>

        {{-- Column 3 --}}
        <div>
            <h3 class="text-lg font-bold mb-4">Contact</h3>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><i class="fa-solid fa-envelope mr-2"></i> help@bloodshare.kh</li>
                <li><i class="fa-solid fa-phone mr-2"></i> +855 12 345 678</li>
                <li><i class="fa-solid fa-location-dot mr-2"></i> Phnom Penh, Cambodia</li>
            </ul>
        </div>
    </div>

    <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
        &copy; 2026 BloodShare KH. All rights reserved.
    </div>
</footer>

</body>
</html>