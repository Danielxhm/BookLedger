@extends('install.layout', ['currentStep' => 1])

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="px-8 sm:px-10 py-7 border-b border-gray-200 bg-[#fbfbfb]">
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900">
            Comprobación de Requisitos del Servidor
        </h1>
        <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
            Verificación de entorno técnico, extensiones y permisos antes de continuar.
        </p>
    </div>

    <div class="p-8 sm:p-10 space-y-10">

        <!-- 1. Versión de PHP -->
        <div class="pb-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Versión de PHP</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Se requiere PHP 8.2 o superior &bull; Detectado: <span class="font-mono font-semibold text-gray-900">{{ $phpVersion }}</span></p>
                </div>
                <div>
                    @if ($phpSatisfied)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-900 border border-gray-200">
                            <svg class="w-3.5 h-3.5 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Correcto
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-gray-900 text-white">
                            Incompatible
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Extensiones PHP -->
        <div>
            <div class="mb-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500">Extensiones de PHP Requeridas</h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($extensions as $ext => $loaded)
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 bg-white shadow-2xs">
                        <span class="text-xs font-mono font-semibold text-gray-900">{{ $ext }}</span>
                        @if ($loaded)
                            <span class="text-gray-900 font-semibold text-xs inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Activo
                            </span>
                        @else
                            <span class="text-red-600 font-semibold text-xs inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                No instalado
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. Permisos de Directorios -->
        <div>
            <div class="mb-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500">Permisos de Escritura en Directorios</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($directories as $dir => $writable)
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 bg-white shadow-2xs">
                        <span class="text-xs font-mono font-semibold text-gray-900">{{ $dir }}</span>
                        @if ($writable)
                            <span class="text-gray-900 font-semibold text-xs inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Escribible
                            </span>
                        @else
                            <span class="text-red-600 font-semibold text-xs inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Solo lectura
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 4. Archivo SQL de Base de Datos -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-2xs">
            <div class="flex items-start gap-4">
                <div class="size-10 rounded-xl bg-gray-900 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <h3 class="text-sm font-bold text-gray-900">
                            Archivo SQL de Importación
                        </h3>
                        @if ($sqlExists)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-gray-100 text-gray-900 border border-gray-200 self-start sm:self-auto">
                                Detectado ({{ $sqlSize }} KB)
                            </span>
                        @else
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 border border-gray-200 self-start sm:self-auto">
                                Modo Migraciones Automáticas
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Ubicación configurada: <code class="px-2 py-0.5 rounded bg-gray-100 border border-gray-200 font-mono text-gray-900 font-semibold text-[11px]">database/install.sql</code>
                    </p>
                    @if ($sqlExists)
                        <p class="text-xs text-gray-700 mt-2 font-medium">
                            ✓ Archivo listo. La base de datos y los registros se importarán automáticamente en el siguiente paso.
                        </p>
                    @else
                        <p class="text-xs text-gray-500 mt-2 font-medium">
                            ℹ No se encontró el archivo. Se ejecutarán las migraciones nativas de Laravel como respaldo.
                        </p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Footer con Botón de Acción -->
    <div class="px-8 sm:px-10 py-6 border-t border-gray-200 bg-[#fbfbfb] flex items-center justify-between gap-4">
        <span class="text-xs font-medium text-gray-500">Paso 1 de 3</span>

        @if ($canProceed)
            <a href="{{ route('install.database') }}"
               class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-gray-900 hover:bg-black text-white text-sm font-bold shadow-sm transition active:scale-[0.98] cursor-pointer"
               style="background-color: #111827 !important; color: #ffffff !important;">
                <span>Continuar a Base de Datos</span>
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        @else
            <button disabled
                    class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-gray-300 text-gray-500 text-sm font-bold cursor-not-allowed"
                    style="background-color: #e5e7eb !important; color: #6b7280 !important;">
                <span>Requisitos pendientes</span>
            </button>
        @endif
    </div>
</div>
@endsection
