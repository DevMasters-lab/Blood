<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.about_us') }} - BloodShare KH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .font-km { font-family: 'Kantumruy Pro', sans-serif !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen {{ app()->getLocale() === 'km' ? 'font-km' : '' }}">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-md fixed w-full z-10 top-0">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a class="text-2xl font-bold text-red-600 flex items-center" href="/">
                <i class="fa-solid fa-heart-pulse mr-2"></i> BloodShare KH
            </a>
            
            <div class="hidden md:flex space-x-6">
                <a href="/" class="hover:text-red-600 font-medium">{{ __('ui.home') }}</a>
                <a href="{{ route('about') }}" class="text-red-600 font-bold">{{ __('ui.about_us') }}</a>
                @auth
                    <a href="{{ route('user.dashboard') }}" class="hover:text-red-600 font-medium">{{ __('ui.dashboard') }}</a>
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
                            {{ __('ui.logout') }}
                        </button>
                    </form>
                @else
                    {{-- IF GUEST: Show Login --}}
                    <a href="{{ route('user.login') }}" class="text-gray-600 hover:text-red-600 mr-4 font-semibold">
                        {{ __('ui.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition shadow">
                        {{ __('ui.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <div class="container mx-auto px-6 py-24 flex-grow">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden p-8">
            
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ __('ui.about_bloodshare') }}</h1>
                <p class="text-lg text-gray-600">{{ __('ui.about_tagline') }}</p>
                <div class="w-24 h-1 bg-red-600 mx-auto mt-4 rounded"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div>
                    <img src="{{ asset('images/OIP.jpg') }}" alt="Blood Donation" class="rounded-lg shadow-md mb-4 transform hover:scale-105 transition duration-500 w-full h-64 object-cover">
                </div>
                <div class="flex flex-col justify-center">
                    <h2 class="text-2xl font-bold text-red-600 mb-4">{{ __('ui.our_mission') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        {{ __('ui.about_mission_desc') }}
                    </p>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-center"><i class="fa-solid fa-circle-check text-green-500 mr-3"></i> {{ __('ui.real_time_requests') }}</li>
                        <li class="flex items-center"><i class="fa-solid fa-circle-check text-green-500 mr-3"></i> {{ __('ui.direct_contact_patients') }}</li>
                        <li class="flex items-center"><i class="fa-solid fa-circle-check text-green-500 mr-3"></i> {{ __('ui.verified_donation_tracking') }}</li>
                    </ul>
                </div>
            </div>

            <div class="mt-16 bg-red-50 rounded-xl p-8 text-center border border-red-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-3">{{ __('ui.become_hero') }}</h2>
                <p class="text-gray-600 mb-6">{{ __('ui.become_hero_desc') }}</p>
                <a href="{{ route('register') }}" class="inline-block bg-red-600 text-white font-bold py-3 px-8 rounded-full shadow hover:bg-red-700 transition transform hover:-translate-y-1">
                    {{ __('ui.register_as_a_donor') }}
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
                <h3 class="text-lg font-bold mb-4">{{ __('ui.quick_links') }}</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="/" class="hover:text-white transition">{{ __('ui.home') }}</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">{{ __('ui.about_us') }}</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition">{{ __('ui.register_as_donor') }}</a></li>
                </ul>
            </div>

            {{-- Column 3 --}}
            <div>
                <h3 class="text-lg font-bold mb-4">{{ __('ui.contact') }}</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fa-solid fa-envelope mr-2"></i> help@bloodshare.kh</li>
                    <li><i class="fa-solid fa-phone mr-2"></i> +855 12 345 678</li>
                    <li><i class="fa-solid fa-location-dot mr-2"></i> Phnom Penh, Cambodia</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
            &copy; 2026 BloodShare KH. {{ __('ui.all_rights_reserved') }}
        </div>
    </footer>

</body>
</html>