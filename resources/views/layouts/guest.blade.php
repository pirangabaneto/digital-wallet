<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="grid grid-cols-1 md:grid-cols-2 min-h-screen sm:justify-center items-center bg-gray-100 dark:bg-gray-900 relative">
    
            <!-- conteúdo principal -->
            <div class="w-full h-full px-6 pt-6 pb-40 md:pb-0 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <div class="w-full text-center mt-12">
                    <h1 class="text-3xl font-bold text-[#034B5E] pb-20">
                        Hi, welcome To
                        <span class="text-[#F8AD15]">PirasWallet</span>
                    </h1>
                </div>
                <div class="w-2/3 mx-auto">
                    {{ $slot }}
                </div>
            </div>
        
            <div class="hidden md:flex w-full h-screen flex flex-col justify-center bg-[#034B5E] py-6">
                <div class="w-full text-center text-white text-3xl font-bold mt-12">
                    <h1>
                        More Than a Wallet, <br> Your Financial World
                    </h1>
                </div>
            
                <!-- fundo verde à direita -->
                <div class="flex justify-center items-center flex-grow">
                    <img src="{{ asset('images/phones.png') }}" alt="phones" class="w-192">
                </div>
            
                <div class="absolute bottom-4 right-4">
                    <img src="{{ asset('images/white-logo.png') }}" alt="Logo" class="w-48">
                </div>
            </div>
        
            <!-- barra inferior verde quando small -->
            <div class="block md:hidden w-full text-center text-white bg-[#034B5E] py-2 absolute bottom-0 left-0">
                <img src="{{ asset('images/white-logo.png') }}" alt="Logo" class="w-24 justify-self-end mr-4">
            </div>
        
        </div>
        
    </body>
</html>
