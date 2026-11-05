<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full overflow-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BookLedger') }} - Iniciar sesión</title>

        <link rel="icon" type="image/webp" href="{{ asset('images/logo-v2.webp') }}">

        <!-- Fonts: Geist -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased h-full bg-white text-zinc-900 overflow-hidden selection:bg-zinc-900 selection:text-white">
        <div class="h-screen w-full flex flex-col lg:flex-row overflow-hidden">

            <!-- Panel izquierdo: Portada directa actualizada con control de caché -->
            <div class="hidden lg:block lg:w-1/2 h-full relative bg-zinc-950 overflow-hidden">
                <img src="{{ asset('images/portada.webp') }}?v={{ file_exists(public_path('images/portada.webp')) ? filemtime(public_path('images/portada.webp')) : time() }}"
                     alt="Portada"
                     class="w-full h-full object-cover object-center block" />
            </div>

            <!-- Panel derecho: Formulario en pantalla completa fija sin scroll -->
            <div class="flex-1 h-full flex flex-col justify-center items-center px-6 sm:px-12 lg:px-16 py-8 bg-white lg:border-l lg:border-zinc-200/80 overflow-y-auto lg:overflow-hidden relative">
                <div class="w-full max-w-[430px] my-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
