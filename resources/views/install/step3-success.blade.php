@extends('install.layout', ['currentStep' => 3])

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden text-center p-8 sm:p-14">

    
    <div class="size-20 rounded-full bg-gray-100 border border-gray-200 text-gray-900 flex items-center justify-center mx-auto mb-6 shadow-2xs">
        <svg class="w-10 h-10 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
    </div>

    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 mb-2">
        ¡Instalación Completada con Éxito!
    </h1>
    <p class="text-sm text-gray-500 max-w-lg mx-auto mb-10 leading-relaxed">
        La base de datos se configuró, los datos fueron importados y tu cuenta de Super Administrador ha sido creada.
    </p>

    <!-- Caja de Credenciales del Super Admin Creado -->
    <div class="max-w-md mx-auto bg-[#fbfbfb] border border-gray-200 rounded-2xl p-6 text-left mb-8 space-y-4 shadow-2xs">
        <div class="flex items-center justify-between border-b border-gray-200 pb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-700">Tu Cuenta de Super Administrador</span>
            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded bg-gray-200 text-gray-800">Activa</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
            <div>
                <span class="block text-gray-500 font-sans text-[11px] mb-1">Correo Electrónico:</span>
                <span class="font-bold text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200 block truncate shadow-2xs">
                    {{ session('admin_email', 'admin@biblioteca.com') }}
                </span>
            </div>
            <div>
                <span class="block text-gray-500 font-sans text-[11px] mb-1">Contraseña:</span>
                <span class="font-bold text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200 block shadow-2xs truncate">
                    (La que acabas de definir)
                </span>
            </div>
        </div>
    </div>

  
    <div class="max-w-md mx-auto mb-10 p-5 rounded-2xl bg-[#fbfbfb] border border-gray-200 text-gray-900 text-xs flex items-start gap-3.5 text-left">
        <svg class="w-5 h-5 text-gray-900 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
        </svg>
        <div>
            <span class="font-bold block text-sm text-gray-900 mb-0.5">Instalador bloqueado (Uso Único)</span>
            <span class="text-gray-600 leading-relaxed">El archivo de bloqueo permanente se generó en <code class="font-mono bg-white px-1.5 py-0.5 rounded text-[11px] border border-gray-200">storage/installed</code>. Por seguridad, el instalador ha sido deshabilitado y nadie más podrá volver a ejecutarlo.</span>
        </div>
    </div>

    <!-- Botón de Inicio de Sesión -->
    <a href="{{ route('login') }}"
       class="inline-flex items-center justify-center gap-2.5 px-9 py-4 rounded-xl bg-gray-900 hover:bg-black text-white text-sm font-bold shadow-sm transition active:scale-[0.98]"
       style="background-color: #111827 !important; color: #ffffff !important;">
        <span>Iniciar Sesión en el Sistema</span>
        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
    </a>

</div>
@endsection
