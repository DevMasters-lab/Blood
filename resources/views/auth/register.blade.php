<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | BloodShare KH</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Google Fonts: Kantumruy Pro (Loaded but only applied via .font-km) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">

    <style>
        html, body { overflow: hidden !important; height: 100% !important; margin: 0; padding: 0; background-color: #ffffff; }
        #register-root { position: fixed; inset: 0; width: 100%; height: 100%; display: flex; z-index: 40; overflow: hidden; }
        @keyframes heartbeat { 0%, 100% { transform: scale(1); } 14% { transform: scale(1.22); } 28% { transform: scale(1); } 42% { transform: scale(1.14); } 56% { transform: scale(1); } }
        @keyframes ekg-draw { 0% { stroke-dashoffset: 1200; } 100% { stroke-dashoffset: 0; } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-14px); } }
        @keyframes pulse-ring { 0% { transform: scale(0.85); opacity: 0.6; } 70% { transform: scale(1.6); opacity: 0; } 100% { transform: scale(0.85); opacity: 0; } }
        @keyframes drift1 { 0%,100%{transform:translateY(0) rotate(0deg);opacity:.25} 50%{transform:translateY(-40px) rotate(180deg);opacity:.08} }
        @keyframes drift2 { 0%,100%{transform:translateY(0) rotate(0deg);opacity:.15} 50%{transform:translateY(-60px) rotate(-120deg);opacity:.05} }
        @keyframes drift3 { 0%,100%{transform:translateY(0) rotate(0deg);opacity:.2} 50%{transform:translateY(-30px) rotate(90deg);opacity:.07} }
        @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .anim-beat { animation: heartbeat 1.8s ease-in-out infinite; }
        .ekg-line { stroke-dasharray: 1200; stroke-dashoffset: 1200; animation: ekg-draw 2.8s linear infinite; }
        .anim-float { animation: float 4s ease-in-out infinite; }
        .anim-ring { animation: pulse-ring 2.2s ease-out infinite; }
        .anim-ring-delay { animation: pulse-ring 2.2s ease-out 1.1s infinite; }
        .p1 { animation: drift1 7s ease-in-out infinite; }
        .p2 { animation: drift2 9s ease-in-out 2s infinite; }
        .p3 { animation: drift3 6s ease-in-out 1s infinite; }
        .anim-slide-up { animation: slide-up 0.5s cubic-bezier(.22,.68,0,1.2) both; }
        .delay-1 { animation-delay: .05s; }
        .delay-2 { animation-delay: .12s; }
        .delay-3 { animation-delay: .19s; }
        .delay-4 { animation-delay: .26s; }
        .delay-5 { animation-delay: .33s; }
        .delay-6 { animation-delay: .40s; }
        .delay-7 { animation-delay: .47s; }
        .delay-8 { animation-delay: .54s; }
        .inp:focus { box-shadow: 0 0 0 4px rgba(198,40,40,.12); }
        
        .font-km { font-family: 'Kantumruy Pro', sans-serif !important; }
    </style>
</head>
<body class="font-sans {{ app()->getLocale() === 'km' ? 'font-km' : '' }}">

<div id="register-root" class="flex-col lg:flex-row">
    <div class="hidden lg:flex w-[48%] flex-col justify-center items-center relative overflow-hidden" style="background: linear-gradient(150deg, #d32f2f 0%, #b71c1c 55%, #7f0000 100%);">
        <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(circle,rgba(255,255,255,.07) 1px,transparent 1px);background-size:28px 28px;"></div>
        <i class="fa-solid fa-heart absolute pointer-events-none select-none text-white/[.04]" style="font-size:34rem;top:-3rem;left:-4rem;transform:rotate(-14deg);"></i>
        <i class="fa-solid fa-droplet absolute pointer-events-none select-none text-white/[.04]" style="font-size:24rem;bottom:-4rem;right:-3rem;transform:rotate(12deg);"></i>
        <div class="absolute w-4 h-4 bg-white/20 rounded-full p1" style="top:18%;left:14%;"></div>
        <div class="absolute w-2.5 h-2.5 bg-white/15 rounded-full p2" style="top:62%;left:8%;"></div>
        <div class="absolute w-3 h-3 bg-white/20 rounded-full p3" style="top:35%;right:12%;"></div>
        <div class="absolute w-2 h-2 bg-white/10 rounded-full p1" style="bottom:20%;right:22%;"></div>

        <div class="relative z-10 text-center px-16 max-w-md">
            <div class="flex items-center justify-center gap-3 mb-10">
                <div class="w-12 h-12 rounded-2xl bg-white/15 border border-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-heart-pulse text-xl text-white"></i>
                </div>
                <span class="text-[1.6rem] font-black text-white tracking-tight">BloodShare KH</span>
            </div>

            <h1 class="text-[2.8rem] font-black text-white leading-[1.1] tracking-tight mb-3">Join the Network.<br>Save More Lives.</h1>
            <p class="text-sm text-red-100/75 font-medium leading-relaxed mb-12">Create your donor account and complete identity verification<br>to request or donate blood safely.</p>

            <div class="relative w-72 h-28 mx-auto mb-12 flex justify-center items-center anim-float">
                <div class="absolute w-20 h-20 rounded-full border-2 border-white/30 anim-ring"></div>
                <div class="absolute w-20 h-20 rounded-full border-2 border-white/20 anim-ring-delay"></div>
                <div class="absolute w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
                <svg class="absolute w-[4.5rem] z-10 anim-beat" viewBox="0 0 24 24" fill="none">
                    <path fill="rgba(255,255,255,0.3)" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <svg class="absolute w-full h-full z-20" viewBox="0 0 260 100" preserveAspectRatio="none">
                    <path class="ekg-line fill-none stroke-white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M 0,50 L 55,50 L 72,28 L 88,74 L 106,10 L 124,85 L 142,50 L 162,50 L 260,50"/>
                </svg>
            </div>

            <div class="flex justify-center gap-6">
                <div class="text-center">
                    <p class="text-[1.6rem] font-black text-white leading-none">500+</p>
                    <p class="text-[10px] font-bold text-red-200/60 uppercase tracking-widest mt-1">Donors</p>
                </div>
                <div class="w-px bg-white/10 self-stretch"></div>
                <div class="text-center">
                    <p class="text-[1.6rem] font-black text-white leading-none">1.2k</p>
                    <p class="text-[10px] font-bold text-red-200/60 uppercase tracking-widest mt-1">Lives Saved</p>
                </div>
                <div class="w-px bg-white/10 self-stretch"></div>
                <div class="text-center">
                    <p class="text-[1.6rem] font-black text-white leading-none">24/7</p>
                    <p class="text-[10px] font-bold text-red-200/60 uppercase tracking-widest mt-1">Support</p>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-[52%] flex flex-col items-center justify-center bg-white overflow-y-auto px-6 py-10 sm:px-12 lg:px-16 xl:px-20 relative">
        <div class="absolute top-6 right-6 z-50 flex items-center gap-4">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-[#c62828] font-bold transition-colors flex items-center gap-2 text-sm pr-4 border-r border-gray-200">
                <i class="fa-solid fa-arrow-left"></i> {{ __('ui.home') ?? 'Home' }}
            </a>

            {{-- 🌟 UPDATED LANGUAGE SWITCHER 🌟 --}}
            <div class="relative" x-data="{ langOpen: false }" @click.away="langOpen = false">
                <button @click="langOpen = !langOpen" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white pl-2 pr-3 py-1.5 shadow-sm hover:border-red-200 hover:bg-red-50/40 transition">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-red-50 text-red-500">
                        <i class="fa-solid fa-globe text-xs"></i>
                    </span>
                    
                    {{-- Text Display: English / ភាសាខ្មែរ --}}
                    <span class="text-xs font-black text-gray-700">
                        @if(app()->getLocale() === 'km')
                            <span class="font-km tracking-wide">ភាសាខ្មែរ</span>
                        @else
                            ENGLISH
                        @endif
                    </span>
                    
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform" :class="langOpen ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="langOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-44 rounded-xl border border-gray-100 bg-white p-2 shadow-xl z-50" style="display: none;">
                    
                    <a href="{{ route('language.switch', 'en') }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm font-bold transition {{ app()->getLocale() === 'en' ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="inline-flex items-center gap-2"><span>🇬🇧</span><span>English</span></span>
                    </a>
                    
                    <a href="{{ route('language.switch', 'km') }}" class="mt-1 flex items-center justify-between rounded-lg px-3 py-2 text-sm font-bold transition {{ app()->getLocale() === 'km' ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span class="inline-flex items-center gap-2"><span>🇰🇭</span><span class="font-km">ភាសាខ្មែរ</span></span>
                    </a>
                    
                </div>
            </div>
        </div>

        <div class="w-full max-w-[460px]">
            <div class="flex lg:hidden justify-center items-center gap-2 mb-8 anim-slide-up delay-1">
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-heart-pulse text-[#c62828] text-sm"></i>
                </div>
                <span class="text-xl font-black text-gray-900">BloodShare <span class="text-[#c62828]">KH</span></span>
            </div>

            <div class="mb-8 anim-slide-up delay-1">
                <p class="text-[10px] font-black text-[#c62828] uppercase tracking-[.2em] mb-1.5">CREATE ACCOUNT</p>
                <h2 class="text-[2rem] font-black text-gray-900 tracking-tight leading-tight">Register as User</h2>
                <p class="text-gray-400 mt-1.5 text-sm font-medium">Verify your identity to request or donate blood.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-[#c62828] px-4 py-3.5 rounded-2xl mb-6 text-sm flex items-start gap-3 anim-slide-up delay-2">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0"></i>
                    <ul class="font-semibold space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="anim-slide-up delay-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-300 group-focus-within:text-[#c62828] transition-colors text-sm"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" class="inp w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl pl-11 pr-4 py-3.5 outline-none focus:border-[#c62828] focus:bg-white transition-all font-medium placeholder-gray-300 @error('name') border-red-300 @enderror" required>
                    </div>
                </div>

                <div class="anim-slide-up delay-3">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-phone text-gray-300 group-focus-within:text-[#c62828] transition-colors text-sm"></i>
                        </div>
                        <input type="text" name="phone" value="{{ old('phone') }}" inputmode="numeric" placeholder="e.g. 012345678" class="inp w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl pl-11 pr-4 py-3.5 outline-none focus:border-[#c62828] focus:bg-white transition-all font-medium placeholder-gray-300 @error('phone') border-red-300 @enderror" required>
                    </div>
                    <p class="mt-2 ml-1 text-[11px] font-medium text-gray-400">Must start with 0</p>
                </div>

                <div class="anim-slide-up delay-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">ID / Passport Number</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-id-card text-gray-300 group-focus-within:text-[#c62828] transition-colors text-sm"></i>
                        </div>
                        <input type="text" name="id_number" value="{{ old('id_number') }}" placeholder="Enter your ID or passport number" class="inp w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl pl-11 pr-4 py-3.5 outline-none focus:border-[#c62828] focus:bg-white transition-all font-medium placeholder-gray-300 @error('id_number') border-red-300 @enderror" required>
                    </div>
                </div>

                <div class="anim-slide-up delay-5 rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-4 py-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload ID or Passport Photo</label>
                    <input type="file" name="id_photo" accept="image/*" class="block w-full text-sm font-medium text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-red-50 file:px-4 file:py-2 file:text-sm file:font-bold file:text-red-700 hover:file:bg-red-100 @error('id_photo') text-red-500 @enderror" required>
                    <p class="mt-2 text-[11px] font-medium text-gray-400">Image must be clear and readable for verification.</p>
                </div>

                <div class="anim-slide-up delay-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-300 group-focus-within:text-[#c62828] transition-colors text-sm"></i>
                        </div>
                        <input type="password" name="password" placeholder="Min. 6 characters" class="inp w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl pl-11 pr-4 py-3.5 outline-none focus:border-[#c62828] focus:bg-white transition-all font-medium placeholder-gray-300 @error('password') border-red-300 @enderror" required>
                    </div>
                </div>

                <div class="anim-slide-up delay-7">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-shield-heart text-gray-300 group-focus-within:text-[#c62828] transition-colors text-sm"></i>
                        </div>
                        <input type="password" name="password_confirmation" placeholder="Repeat your password" class="inp w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl pl-11 pr-4 py-3.5 outline-none focus:border-[#c62828] focus:bg-white transition-all font-medium placeholder-gray-300" required>
                    </div>
                </div>

                <div class="anim-slide-up delay-8 pt-1">
                    <button type="submit" class="w-full bg-[#c62828] hover:bg-[#b71c1c] active:scale-[0.97] text-white font-black text-base py-4 rounded-2xl transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2.5" style="box-shadow:0 10px 28px -6px rgba(198,40,40,.45);">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Register Account</span>
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-4 my-6 anim-slide-up delay-8">
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">or</span>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <div class="anim-slide-up delay-8">
                <a href="{{ route('user.login') }}" class="w-full flex items-center justify-center gap-2 border-2 border-gray-100 hover:border-[#c62828]/25 hover:bg-red-50 text-gray-500 hover:text-[#c62828] font-bold py-3.5 rounded-2xl transition-all text-sm">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    Already have an account?
                    <span class="text-[#c62828] ml-0.5">Login</span>
                </a>

                <p class="text-center text-[11px] text-gray-300 font-medium mt-6">© {{ date('Y') }} BloodShare KH · Saving lives together</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>