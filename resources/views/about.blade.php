<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - BloodShare KH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-md fixed w-full z-10 top-0">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a class="text-2xl font-bold text-red-600 flex items-center" href="/">
                <i class="fa-solid fa-heart-pulse mr-2"></i> BloodShare KH
            </a>
            
            <div class="hidden md:flex space-x-6">
                <a href="/" class="hover:text-red-600 font-medium">Home</a>
                <a href="{{ route('about') }}" class="text-red-600 font-bold">About Us</a>
                @auth
                    <a href="{{ route('user.dashboard') }}" class="hover:text-red-600 font-medium">Dashboard</a>
                @endauth
            </div>
            
            <div class="flex items-center">
                @auth
                    {{-- IF LOGGED IN: Show Avatar --}}
                    <div class="hidden md:flex items-center mr-4">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover mr-2 border border-gray-300">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2 text-gray-500 text-xs">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <span class="text-gray-700 font-bold">{{ auth()->user()->name }}</span>
                    </div>
                    
                    <form action="{{ route('user.logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300 text-sm">
                            Logout
                        </button>
                    </form>
                @else
                    {{-- IF GUEST: Show Login --}}
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

    {{-- MAIN CONTENT --}}
    <div class="container mx-auto px-6 py-24 flex-grow">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden p-8">
            
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">About BloodShare KH</h1>
                <p class="text-lg text-gray-600">Connecting donors with those in need, one drop at a time.</p>
                <div class="w-24 h-1 bg-red-600 mx-auto mt-4 rounded"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div>
                    <img src="{{ asset('images/OIP.jpg') }}" alt="Blood Donation" class="rounded-lg shadow-md mb-4 transform hover:scale-105 transition duration-500 w-full h-64 object-cover">
                </div>
                <div class="flex flex-col justify-center">
                    <h2 class="text-2xl font-bold text-red-600 mb-4">Our Mission</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        BloodShare KH was built to solve a critical problem: the delay in finding blood donors during emergencies. 
                        We provide a platform where hospitals and individuals can post urgent requests, and local heroes can step up to save lives.
                    </p>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-center"><i class="fa-solid fa-circle-check text-green-500 mr-3"></i> Real-time urgent requests</li>
                        <li class="flex items-center"><i class="fa-solid fa-circle-check text-green-500 mr-3"></i> Direct contact with patients</li>
                        <li class="flex items-center"><i class="fa-solid fa-circle-check text-green-500 mr-3"></i> Verified donation tracking</li>
                    </ul>
                </div>
            </div>

            <div class="mt-16 bg-red-50 rounded-xl p-8 text-center border border-red-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-3">Want to become a hero?</h2>
                <p class="text-gray-600 mb-6">You don't need to be a doctor to save a life. It only takes 15 minutes.</p>
                <a href="{{ route('register') }}" class="inline-block bg-red-600 text-white font-bold py-3 px-8 rounded-full shadow hover:bg-red-700 transition transform hover:-translate-y-1">
                    Register as a Donor
                </a>
            </div>

        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-white py-10 mt-auto">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            
            {{-- Column 1 --}}
            <div>
                <h3 class="text-lg font-bold mb-4 text-red-400">BloodShare KH</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Saving lives by connecting donors with patients in real-time. Join our community today.
                </p>
            </div>

            {{-- Column 2 --}}
            <div>
                <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="/" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition">Register as Donor</a></li>
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