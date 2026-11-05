<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f8f9fa]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instalación del Sistema - {{ config('app.name', 'BookLedger') }}</title>

    <link rel="icon" type="image/webp" href="{{ asset('images/logo-v2.webp') }}">

    <!-- Fonts:  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts y Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 min-h-screen flex flex-col justify-between bg-[#f8f9fa] selection:bg-gray-900 selection:text-white">

    <!-- Header -->
    <header class="w-full border-b border-gray-200 bg-white sticky top-0 z-30">
        <div class="max-w-4xl mx-auto px-6 sm:px-8 h-18 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-application-mark class="h-9 w-auto" />
                <span class="text-xs font-semibold tracking-wider px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 border border-gray-200">
                    Instalador del Sistema
                </span>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-gray-500">
                <span class="size-2 rounded-full bg-gray-900"></span>
                <span>Asistente v1.0</span>
            </div>
        </div>
    </header>

    <!-- Contenedor Principal -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-6 sm:px-8 py-10 sm:py-14">
        <!-- Indicador de Pasos -->
        <div class="mb-10">
            <div class="flex items-center justify-between max-w-md mx-auto relative">
             
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 w-full bg-gray-200 -z-10" aria-hidden="true"></div>
                
                @php
                    $step = $currentStep ?? 1;
                @endphp

                <!-- Paso 1 -->
                <div class="flex flex-col items-center gap-2 bg-[#f8f9fa] px-3">
                    <div class="size-10 rounded-full flex items-center justify-center font-bold text-xs {{ $step >= 1 ? 'bg-gray-900 text-white shadow-sm ring-4 ring-gray-100' : 'bg-gray-200 text-gray-500' }}">
                        @if ($step > 1)
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @else
                            1
                        @endif
                    </div>
                    <span class="text-xs font-bold {{ $step >= 1 ? 'text-gray-900' : 'text-gray-400' }}">Requisitos</span>
                </div>

                <!-- Paso 2 -->
                <div class="flex flex-col items-center gap-2 bg-[#f8f9fa] px-3">
                    <div class="size-10 rounded-full flex items-center justify-center font-bold text-xs {{ $step >= 2 ? 'bg-gray-900 text-white shadow-sm ring-4 ring-gray-100' : 'bg-gray-200 text-gray-500' }}">
                        @if ($step > 2)
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @else
                            2
                        @endif
                    </div>
                    <span class="text-xs font-bold {{ $step >= 2 ? 'text-gray-900' : 'text-gray-400' }}">Base de Datos</span>
                </div>

                <!-- Paso 3 -->
                <div class="flex flex-col items-center gap-2 bg-[#f8f9fa] px-3">
                    <div class="size-10 rounded-full flex items-center justify-center font-bold text-xs {{ $step >= 3 ? 'bg-gray-900 text-white shadow-sm ring-4 ring-gray-100' : 'bg-gray-200 text-gray-500' }}">
                        3
                    </div>
                    <span class="text-xs font-bold {{ $step >= 3 ? 'text-gray-900' : 'text-gray-400' }}">Finalizado</span>
                </div>
            </div>
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-gray-200 py-8 text-center text-xs text-gray-500 bg-white">
        <p>{{ config('app.name', 'BookLedger') }} &bull; Sistema de Gestión de Biblioteca &bull; Configuración Inicial</p>
    </footer>

</body>
</html>
