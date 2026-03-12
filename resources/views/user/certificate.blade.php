<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Appreciation | BloodShare KH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Great+Vibes&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'serif': ['Cinzel', 'serif'],
                        'script': ['Great Vibes', 'cursive'],
                        'sans': ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        'blood-red': '#D32F2F',
                        'blood-dark': '#B71C1C',
                        'gold-light': '#F9DF9F',
                        'gold': '#D4AF37',
                        'gold-dark': '#AA8111',
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }
            .print-shadow-none { box-shadow: none !important; }
        }
        
        /* Subtle background pattern */
        .certificate-bg {
            background-color: #ffffff;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='#d32f2f' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Shape for the ribbon tails under the seal */
        .seal-ribbon {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 80%, 0 100%);
        }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4 sm:p-8 font-sans">

    <div class="w-full max-w-4xl relative">
        
        {{-- Action Buttons (Moved to the top so they don't interfere with the design) --}}
        <div class="text-center mb-6 no-print">
            <button onclick="window.print()" class="bg-blood-red text-white px-8 py-3 rounded-xl hover:bg-blood-dark transition-all font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center gap-2 mx-auto">
                <i class="fa-solid fa-print"></i> Print Certificate
            </button>
        </div>

        {{-- Outer Certificate Container --}}
        <div class="bg-white p-3 sm:p-4 rounded-sm shadow-2xl print-shadow-none relative overflow-hidden">
            
            {{-- Inner Gold/Red Border --}}
            <div class="certificate-bg border-[3px] border-gold p-8 sm:p-12 relative h-full flex flex-col justify-center items-center text-center">
                
                {{-- Decorative Corner Accents --}}
                <div class="absolute top-2 left-2 w-16 h-16 border-t-[4px] border-l-[4px] border-blood-red"></div>
                <div class="absolute top-2 right-2 w-16 h-16 border-t-[4px] border-r-[4px] border-blood-red"></div>
                <div class="absolute bottom-2 left-2 w-16 h-16 border-b-[4px] border-l-[4px] border-blood-red"></div>
                <div class="absolute bottom-2 right-2 w-16 h-16 border-b-[4px] border-r-[4px] border-blood-red"></div>

                {{-- Giant Faint Watermark Logo --}}
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
                    <i class="fa-solid fa-heart-pulse text-[20rem]"></i>
                </div>

                {{-- Header --}}
                <div class="relative z-10 w-full">
                    <div class="flex items-center justify-center gap-4 mb-8">
                        <div class="h-px bg-gold w-16 sm:w-24"></div>
                        <div class="text-blood-red text-3xl drop-shadow-sm">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </div>
                        <div class="h-px bg-gold w-16 sm:w-24"></div>
                    </div>

                    <h1 class="font-serif text-4xl sm:text-5xl font-bold text-gray-900 tracking-widest mb-2 uppercase">Certificate</h1>
                    <h2 class="font-serif text-xl sm:text-2xl text-gold-dark tracking-[0.3em] mb-2 uppercase">of</h2>
                    <h3 class="font-serif text-3xl sm:text-4xl font-semibold text-blood-red tracking-widest mb-10 uppercase">Appreciation</h3>
                </div>

                {{-- Recipient Section --}}
                <div class="relative z-10 w-full mb-8">
                    <p class="text-xs sm:text-sm text-gray-500 uppercase tracking-[0.2em] mb-4 font-semibold">Proudly Presented To</p>
                    
                    {{-- Donor Name in elegant script font --}}
                    <h4 class="font-script text-6xl sm:text-7xl text-gray-900 mb-6 drop-shadow-sm px-4">
                        {{ $donation->user->name }}
                    </h4>
                    
                    {{-- Underline --}}
                    <div class="w-2/3 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent mx-auto mb-8"></div>

                    <div class="max-w-2xl mx-auto space-y-4">
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed font-light">
                            In profound recognition of your generous and selfless donation of
                        </p>
                        <p class="text-2xl sm:text-3xl font-serif font-bold text-blood-red">
                            Type {{ $donation->blood_type }} Blood
                        </p>
                        <p class="text-sm text-gray-500 leading-relaxed italic max-w-xl mx-auto mt-4">
                            "Your contribution has directly impacted and saved lives. Thank you for your unwavering commitment to strengthening our community's health and hope."
                        </p>
                    </div>
                </div>

                {{-- Footer / Signatures / Seals --}}
                <div class="relative z-10 w-full mt-8 sm:mt-12 flex flex-row items-end justify-between px-4 sm:px-12">
                    
                    {{-- Left: Date & ID --}}
                    <div class="text-center w-32">
                        <p class="text-sm font-bold text-gray-800 border-b border-gray-400 pb-1 mb-2">{{ $donation->created_at->format('M d, Y') }}</p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Date</p>
                        <p class="text-[9px] text-gray-400 mt-2 font-mono">ID: #{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    
                    {{-- Center: Gold Ribbon Seal --}}
                    <div class="flex-shrink-0 mx-4 relative transform translate-y-4">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gold-light via-gold to-gold-dark p-1 shadow-xl relative z-10 flex items-center justify-center border-2 border-white border-dashed">
                            <div class="w-full h-full rounded-full border border-gold-light flex flex-col items-center justify-center bg-white/10 backdrop-blur-sm">
                                <i class="fa-solid fa-shield-heart text-white text-2xl mb-1 drop-shadow-md"></i>
                                <span class="text-[8px] text-white font-bold tracking-widest uppercase drop-shadow-md">Verified</span>
                                <span class="text-[7px] text-white/90 uppercase tracking-wider mt-0.5">Donor</span>
                            </div>
                        </div>
                        {{-- Hanging Ribbons --}}
                        <div class="seal-ribbon w-6 h-12 bg-blood-red absolute -bottom-6 left-4 z-0 shadow-sm"></div>
                        <div class="seal-ribbon w-6 h-12 bg-blood-dark absolute -bottom-6 right-4 z-0 shadow-sm"></div>
                    </div>

                    {{-- Right: Signature --}}
                    <div class="text-center w-32">
                        <div class="h-10 mb-1 relative">
                            <span class="font-script text-3xl text-gray-800 absolute bottom-0 left-0 w-full opacity-80">BloodShare</span>
                        </div>
                        <p class="border-t border-gray-400 pt-2 text-[10px] text-gray-500 uppercase tracking-widest font-bold">Official Signature</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>