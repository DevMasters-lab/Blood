<!DOCTYPE html>
<html lang="en">
<head>
    <title>Certificate of Appreciation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white border-8 border-red-600 p-12 max-w-4xl w-full text-center shadow-2xl relative">
        <div class="text-red-600 text-6xl mb-4">
            <i class="fa-solid fa-heart-pulse"></i>
        </div>

        <h1 class="text-5xl font-serif font-bold text-gray-800 uppercase tracking-widest mb-4">Certificate</h1>
        <h2 class="text-2xl font-light text-gray-500 uppercase mb-8">of Appreciation</h2>

        <p class="text-xl text-gray-600 mb-2">This is proudly presented to</p>
        <h3 class="text-4xl font-bold text-red-600 mb-6 italic">{{ $donation->user->name }}</h3>

        <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto mb-8">
            For your selfless act of donating <strong>{{ $donation->blood_type }}</strong> blood to save a life. 
            Your contribution makes our community stronger and healthier.
        </p>

        <div class="flex justify-between items-end mt-12 px-10">
            <div class="text-center">
                <p class="text-gray-500 text-sm border-t border-gray-400 pt-2 px-8">Date</p>
                <p class="font-bold">{{ $donation->created_at->format('d M Y') }}</p>
            </div>
            
            <div class="w-24 h-24 bg-red-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-lg">
                OFFICIAL<br>DONOR
            </div>

            <div class="text-center">
                <p class="text-gray-500 text-sm border-t border-gray-400 pt-2 px-8">Director Signature</p>
                <p class="font-bold font-serif italic">Dr. Sokha</p>
            </div>
        </div>

        <button onclick="window.print()" class="no-print mt-12 bg-gray-800 text-white px-8 py-3 rounded-full hover:bg-black transition font-bold cursor-pointer">
            Download / Print
        </button>
        
        <a href="{{ route('user.dashboard') }}" class="no-print block mt-4 text-gray-500 hover:underline text-sm">Back to Dashboard</a>
    </div>

</body>
</html>