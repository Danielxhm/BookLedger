@extends('install.layout', ['currentStep' => 2])

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="px-8 sm:px-10 py-7 border-b border-gray-200 bg-[#fbfbfb]">
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900">
            Configuración de Base de Datos y Super Administrador
        </h1>
        <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
            Ingresa las credenciales del servidor MySQL y crea la cuenta principal de acceso al sistema.
        </p>
    </div>

    <!-- Errores si los hay -->
    @if ($errors->any())
        <div class="p-8 sm:p-10 pb-0">
            <div class="rounded-xl bg-gray-50 border border-red-300 p-5 text-sm text-red-900 flex items-start gap-3.5">
                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <div class="space-y-1">
                    <p class="font-bold">Por favor revisa los siguientes campos:</p>
                    @foreach ($errors->all() as $error)
                        <p class="text-xs sm:text-sm text-red-700">&bull; {{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('install.process') }}" class="p-8 sm:p-10 space-y-10" id="install-form">
        @csrf

        <!-- Campos Ocultos (Se ejecutan por defecto en segundo plano) -->
        <input type="hidden" name="auto_create_db" value="1">
        <input type="hidden" name="auto_destruct" value="1">

        <!-- Bloque 1: Datos de la Aplicación -->
        <div>
            <div class="mb-5">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500">1. Información del Sistema</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="app_name" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Nombre de la Aplicación <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="app_name" name="app_name" value="{{ old('app_name', 'BookLedger') }}" required
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                </div>

                <div>
                    <label for="app_url" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        URL de la Aplicación <span class="text-red-500">*</span>
                    </label>
                    <input type="url" id="app_url" name="app_url" value="{{ old('app_url', $defaultAppUrl) }}" required
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm font-mono text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- Bloque 2: Parámetros de Conexión MySQL -->
        <div class="pt-2 border-t border-gray-100">
            <div class="mb-5 pt-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500">2. Servidor MySQL</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <div class="sm:col-span-2">
                    <label for="db_host" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Host del Servidor <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="db_host" name="db_host" value="{{ old('db_host', $defaultHost) }}" required placeholder="127.0.0.1 o localhost"
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm font-mono text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                </div>

                <div>
                    <label for="db_port" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Puerto <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="db_port" name="db_port" value="{{ old('db_port', $defaultPort) }}" required
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm font-mono text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="db_database" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Nombre de la BD <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="db_database" name="db_database" value="{{ old('db_database', $defaultDatabase) }}" required placeholder="sistema_biblioteca"
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm font-mono text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                    <p class="text-[11px] text-gray-400 mt-1.5">Si no existe, se creará sola.</p>
                </div>

                <div>
                    <label for="db_username" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Usuario MySQL <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="db_username" name="db_username" value="{{ old('db_username', $defaultUsername) }}" required placeholder="root"
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm font-mono text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                </div>

                <div>
                    <label for="db_password" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Contraseña MySQL
                    </label>
                    <input type="password" id="db_password" name="db_password" value="{{ old('db_password', $defaultPassword) }}" placeholder="Opcional / Vacía"
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm font-mono text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                    <p class="text-[11px] text-gray-400 mt-1.5">En XAMPP normalmente es vacía.</p>
                </div>
            </div>
        </div>

        <!-- Bloque 3: Creador del Usuario Super Admin -->
        <div class="pt-2 border-t border-gray-100">
            <div class="mb-5 pt-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500">3. Cuenta del Super Administrador</h2>
                <p class="text-xs text-gray-400 mt-0.5">Esta cuenta tendrá acceso total e irrestricto al panel del sistema</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="admin_name" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Nombre Completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name', 'Administrador Principal') }}" required
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                </div>

                <div>
                    <label for="admin_email" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email', 'admin@biblioteca.com') }}" required placeholder="admin@tudominio.com"
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm font-mono text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="admin_password" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Contraseña del Administrador <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="admin_password" name="admin_password" required minlength="8" placeholder="Mínimo 8 caracteres"
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm font-mono text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                </div>

                <div>
                    <label for="admin_password_confirmation" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Confirmar Contraseña <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="admin_password_confirmation" name="admin_password_confirmation" required minlength="8" placeholder="Repite la contraseña"
                           class="block w-full min-h-[46px] px-4 py-3 rounded-xl border border-gray-300 text-sm font-mono text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- Footer con Acciones -->
        <div class="pt-6 border-t border-gray-200 bg-[#fbfbfb] -mx-8 sm:-mx-10 -mb-8 sm:-mb-10 px-8 sm:px-10 py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="{{ route('install.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-gray-900 transition py-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Volver a requisitos</span>
            </a>

            <button type="submit" id="btn-submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-xl bg-gray-900 hover:bg-black text-white text-sm font-bold shadow-sm transition active:scale-[0.98] cursor-pointer"
                    style="background-color: #111827 !important; color: #ffffff !important;">
                <svg id="btn-icon" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span id="btn-text">Instalar y Crear Super Admin</span>
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('install-form')?.addEventListener('submit', function() {
        const btn = document.getElementById('btn-submit');
        const text = document.getElementById('btn-text');
        const icon = document.getElementById('btn-icon');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            if (text) text.innerText = 'Instalando e Importando BD...';
            if (icon) {
                icon.outerHTML = '<svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>';
            }
        }
    });
</script>
@endsection
