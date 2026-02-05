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
                    <span class="hidden md:inline text-gray-700 font-bold mr-4">Hi, {{ auth()->user()->name }}</span>
                    
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

    <div class="relative bg-red-600 h-96 flex items-center justify-center mt-16 text-center text-white px-6">
        <div>
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Donate Blood, Save a Life</h1>
            <p class="text-lg md:text-xl mb-8">Urgent blood requests in Cambodia need your help today.</p>
            <a href="#requests" class="bg-white text-red-600 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition">
                Find Requests
            </a>
        </div>
    </div>

    <div id="requests" class="container mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-center mb-8 border-b-4 border-red-600 inline-block pb-2">
            Urgent Blood Needed
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($requests as $req)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition">
                <div class="bg-red-50 p-4 flex justify-between items-center border-b border-red-100">
                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded uppercase">
                        Urgent
                    </span>
                    <span class="text-red-600 font-bold text-lg">
                        <i class="fa-solid fa-droplet"></i> {{ $req->blood_type }}
                    </span>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">{{ $req->hospital_name }}</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        <i class="fa-solid fa-user-injured mr-1"></i> Patient: {{ $req->patient_name ?? 'Anonymous' }}
                    </p>
                    
                    <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                        <span><i class="fa-regular fa-calendar mr-1"></i> Needed:</span>
                        <span class="font-semibold text-red-500">
                            {{ $req->needed_date->format('d M Y') }}
                        </span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                         <div class="text-xs text-gray-400">
                             Posted by {{ $req->requester->name }}
                         </div>
                         <button class="text-red-600 font-bold hover:underline text-sm">
                             Contact Now <i class="fa-solid fa-arrow-right ml-1"></i>
                         </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $requests->fragment('requests')->links() }}
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-8 text-center">
        <p>&copy; 2026 BloodShare KH. All rights reserved.</p>
    </footer>

</body>
</html>