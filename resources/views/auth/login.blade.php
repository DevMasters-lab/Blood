<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Control | BloodShare Kingdom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @keyframes scan { 0% { top: -10%; } 100% { top: 110%; } }
        @keyframes pulse-red { 0%, 100% { box-shadow: 0 0 20px rgba(211, 47, 47, 0.2); } 50% { box-shadow: 0 0 40px rgba(211, 47, 47, 0.4); } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        
        .scan-line {
            height: 10px;
            background: linear-gradient(to bottom, transparent, rgba(211, 47, 47, 0.5), transparent);
            position: absolute;
            width: 100%;
            z-index: 50;
            animation: scan 4s linear infinite;
        }
        
        .circuit-bg {
            background-image: radial-gradient(rgba(211, 47, 47, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .glass-premium {
            background: rgba(15, 17, 19, 0.7);
            backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .font-km { font-family: 'Kantumruy Pro', sans-serif !important; }
    </style>
</head>
<body class="bg-[#050505] h-screen flex items-center justify-center overflow-hidden font-sans selection:bg-red-500 selection:text-white {{ app()->getLocale() === 'km' ? 'font-km' : '' }}">

    {{-- CYBERNETIC BACKGROUND --}}
    <div class="absolute inset-0 circuit-bg pointer-events-none opacity-40"></div>
    <div class="scan-line opacity-20"></div>

    {{-- AMBIENT DEPTH GLOWS --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-900/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-red-900/10 rounded-full blur-[120px]"></div>

    <div class="relative w-full max-w-[480px] px-8 py-12">
        
        {{-- BRANDING SECTION --}}
        <div class="flex flex-col items-center mb-12">
            <div class="relative">
                {{-- Glowing Halo --}}
                <div class="absolute inset-0 bg-red-600 rounded-[2.5rem] blur-3xl opacity-20 animate-pulse"></div>
                
                {{-- Main Logo Container --}}
                <div class="relative w-24 h-24 bg-gradient-to-br from-[#D32F2F] to-[#7F1D1D] rounded-[2.5rem] flex items-center justify-center text-white border border-white/10 shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500 group cursor-default">
                    <i class="fa-solid fa-shield-heart text-5xl group-hover:scale-110 transition-transform"></i>
                </div>
            </div>

            <div class="mt-8 text-center">
                <h1 class="text-5xl font-black text-white tracking-tighter uppercase italic">
                    Admin<span class="text-[#D32F2F] tracking-normal not-italic px-1">X</span>
                </h1>
                <p class="text-[9px] font-black text-gray-500 tracking-[0.6em] uppercase mt-4 flex items-center justify-center gap-2">
                    <span class="w-10 h-[1px] bg-gray-800"></span>
                    {{ __('ui.secure_management_terminal') }}
                    <span class="w-10 h-[1px] bg-gray-800"></span>
                </p>
            </div>
        </div>

        {{-- LOGIN CARD --}}
        <div class="glass-premium p-10 rounded-[3.5rem] relative overflow-hidden">
            {{-- Internal Decorative Corner --}}
            <div class="absolute top-0 right-0 w-24 h-24 border-t-2 border-r-2 border-red-500/20 rounded-tr-[3.5rem]"></div>
            
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-black text-white">{{ __('ui.authorization') }}</h2>
                    <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">{{ __('ui.level5_clearance') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-[10px] font-bold text-green-500 flex items-center justify-end gap-1.5 bg-green-500/5 px-2 py-1 rounded-md border border-green-500/20">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                        {{ __('ui.encrypted') }}
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-950/30 border border-red-500/30 rounded-2xl flex items-center gap-4 animate-bounce">
                    <i class="fa-solid fa-lock text-red-500"></i>
                    <span class="text-[10px] font-black text-red-500 uppercase">{{ __('ui.warning_unidentified_login') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-8">
                @csrf
                
                {{-- PHONE INPUT --}}
                <div class="relative group">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-4 mb-2 block">{{ __('ui.admin_identifier') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-fingerprint text-gray-600 group-focus-within:text-red-500 transition-colors"></i>
                        </div>
                        <input type="text" name="phone" required
                            class="w-full bg-black/60 border border-white/5 rounded-2xl pl-14 pr-6 py-4.5 text-white font-bold placeholder:text-gray-700 outline-none focus:ring-1 focus:ring-red-500/50 transition-all"
                            placeholder="0XX XXX XXXX">
                    </div>
                </div>

                {{-- PASSWORD INPUT --}}
                <div class="relative group">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-4 mb-2 block">{{ __('ui.security_token') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user-lock text-gray-600 group-focus-within:text-red-500 transition-colors"></i>
                        </div>
                        <input type="password" name="password" required
                            class="w-full bg-black/60 border border-white/5 rounded-2xl pl-14 pr-6 py-4.5 text-white font-bold placeholder:text-gray-700 outline-none focus:ring-1 focus:ring-red-500/50 transition-all"
                            placeholder="••••••••••••">
                    </div>
                </div>

                {{-- ACTION BUTTON --}}
                <button type="submit" class="w-full relative group overflow-hidden bg-[#D32F2F] text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.4em] shadow-xl hover:shadow-red-500/20 active:scale-95 transition-all">
                    {{-- Shimmer Effect --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent skew-x-[-20deg] animate-[shimmer_2s_infinite]" style="background-size: 200% 100%;"></div>
                    
                    <span class="relative z-10 flex items-center justify-center gap-3">
                        {{ __('ui.authorize_access') }}
                        <i class="fa-solid fa-satellite-dish animate-pulse"></i>
                    </span>
                </button>
            </form>

            <div class="mt-12 text-center">
                <a href="/" class="group text-[10px] font-black text-gray-600 hover:text-white transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fa-solid fa-power-off group-hover:text-red-500"></i>
                    {{ __('ui.go_to_homepage') }}
                </a>
            </div>
        </div>

        {{-- VERSIONING INFO --}}
        <div class="mt-12 flex justify-between items-center px-10">
            <p class="text-[9px] font-black text-gray-800 uppercase tracking-widest">&copy; 2026 BLOODSHARE</p>
            <div class="flex gap-4">
                <span class="w-2 h-2 rounded-full bg-red-900/30"></span>
                <span class="w-2 h-2 rounded-full bg-red-600 animate-ping"></span>
                <span class="w-2 h-2 rounded-full bg-red-900/30"></span>
            </div>
            <p class="text-[9px] font-black text-gray-800 uppercase tracking-widest">SYS v4.0.1</p>
        </div>
    </div>

</body>
</html>