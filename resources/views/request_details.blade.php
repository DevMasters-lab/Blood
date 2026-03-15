<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.urgent_request_title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .font-km { font-family: 'Kantumruy Pro', sans-serif !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 {{ app()->getLocale() === 'km' ? 'font-km' : '' }}">

    {{-- Simple Navbar --}}
    <nav class="bg-white shadow-md w-full z-10 top-0 mb-8">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a class="text-2xl font-bold text-red-600 flex items-center" href="/">
                <i class="fa-solid fa-heart-pulse mr-2"></i> BloodShare KH
            </a>
            <a href="/" class="text-gray-600 hover:text-red-600 font-semibold">
                &larr; {{ __('ui.back_to_home') }}
            </a>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="min-h-screen flex items-start justify-center p-6">
        <div class="max-w-2xl w-full bg-white rounded-lg shadow-xl overflow-hidden border-t-8 border-red-600">
            
            {{-- Header --}}
            <div class="bg-gray-50 p-8 text-center border-b">
                <h1 class="text-3xl font-bold uppercase tracking-wide text-gray-800">{{ __('ui.urgent_blood_needed') }}</h1>
                <p class="mt-2 text-red-600 font-semibold">{{ __('ui.every_second_counts') }}</p>
            </div>

            {{-- Content --}}
            <div class="p-8">
                <div class="flex justify-between items-start border-b pb-6 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $request->hospital_name }}</h2>
                        <p class="text-gray-500 mt-1">
                            <i class="fa-solid fa-user-injured mr-1"></i> {{ __('ui.patient') }}: 
                            <span class="font-semibold">{{ $request->patient_name ?? __('ui.confidential') }}</span>
                        </p>
                    </div>
                    <div class="text-center bg-red-50 p-3 rounded-lg border border-red-100">
                        <span class="block text-4xl font-extrabold text-red-600">{{ $request->blood_type }}</span>
                        <span class="text-xs text-red-400 uppercase font-bold">Type</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-blue-50 p-4 rounded border border-blue-100">
                        <span class="text-blue-400 text-xs font-bold uppercase">{{ __('ui.needed_by') }}</span>
                        <p class="text-lg font-bold text-blue-900">
                            <i class="fa-regular fa-calendar mr-2"></i>
                            {{ $request->needed_date->format('l, d M Y') }}
                        </p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded border border-gray-200">
                        <span class="text-gray-400 text-xs font-bold uppercase">{{ __('ui.contact_person') }}</span>
                        <p class="text-lg font-semibold text-gray-800">
                            <i class="fa-solid fa-user text-gray-500 mr-2"></i>
                            {{ $request->requester->name }}
                        </p>
                    </div>
                </div>

                {{-- Call to Action --}}
                <div class="text-center mt-8">
                    <p class="text-gray-600 mb-4">{{ __('ui.please_contact_requester') }}</p>
                    
                    <a href="tel:{{ $request->requester->phone }}" class="inline-flex items-center justify-center w-full md:w-auto bg-green-600 text-white font-bold text-xl py-4 px-10 rounded-full hover:bg-green-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fa-solid fa-phone mr-3 animate-pulse"></i> 
                        {{ $request->requester->phone }}
                    </a>
                </div>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 p-4 text-center border-t text-sm text-gray-500">
                <p>BloodShare KH &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>

</body>
</html>