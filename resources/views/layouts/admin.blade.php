<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodShare KH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-100 font-sans">

    {{-- TOP NAVIGATION --}}
    <nav class="bg-red-700 text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            
            {{-- Logo --}}
            <a href="/" class="text-xl font-bold flex items-center hover:text-red-100 transition">
                <i class="fa-solid fa-heart-pulse mr-2"></i> BloodShare KH
            </a>
            
            {{-- Menu --}}
            <div class="flex items-center space-x-6">
                @auth
                    {{-- LOGGED IN LINKS --}}
                    @if(auth()->user()->usertype == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-200">Dashboard</a>
                        <a href="{{ route('admin.requests') }}" class="hover:text-red-200">Requests</a>
                        <a href="{{ route('admin.donations') }}" class="hover:text-red-200">Donations</a>
                        <a href="{{ route('admin.users') }}" class="hover:text-red-200">Users</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="hover:text-red-200">Dashboard</a>
                    @endif

                    {{-- Profile & Logout --}}
                    <div class="flex items-center border-l border-red-600 pl-6 ml-6">
                        <a href="{{ route('user.profile') }}" class="flex items-center hover:text-red-200">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover border-2 border-red-400 mr-2">
                            @else
                                <div class="w-8 h-8 rounded-full bg-red-800 flex items-center justify-center border-2 border-red-500 mr-2">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </div>
                            @endif
                            <span class="font-semibold text-sm">{{ auth()->user()->name }}</span>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="inline ml-4">
                            @csrf
                            <button type="submit" class="text-red-200 hover:text-white text-sm">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                @else
                    {{-- GUEST LINKS --}}
                    <a href="{{ route('user.login') }}" class="hover:text-red-200 font-semibold">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-red-700 px-4 py-2 rounded font-bold hover:bg-gray-100 transition">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- CONTENT AREA --}}
    <div class="container mx-auto mt-8 px-4 pb-12">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        
        {{-- THIS IS WHERE THE DASHBOARD CONTENT WILL LOAD --}}
        @yield('content')
    </div>

</body>
</html>