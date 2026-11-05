<x-guest-layout>
    <div class="mb-6 flex flex-col items-center text-center">
        <div class="flex justify-center mb-3">
            <picture class="inline-block">
                <source srcset="{{ asset('images/logo-v2.webp') }}" type="image/webp">
                <img src="{{ asset('images/logo-v2.png') }}"
                     alt="{{ config('app.name', 'BookLedger') }}"
                     class="h-7.5 w-auto object-contain mx-auto"
                     style="height: 30px;" />
            </picture>
        </div>

        <p class="text-[11px] font-semibold tracking-wider uppercase text-red-600 mb-1.5">
            Sistema Bibliotecario
        </p>

        <h1 class="text-2xl font-bold tracking-tight text-zinc-950">
            Iniciar sesión
        </h1>
        <p class="mt-1.5 text-sm text-zinc-500">
            Ingresa tus credenciales para acceder al panel.
        </p>
    </div>

    <!-- Errores de validación -->
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50/70 border border-red-200/80 p-3 text-xs text-red-800 flex items-start gap-2.5">
            <svg class="size-4 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div class="space-y-0.5">
                <p class="font-medium text-red-900">No se pudo iniciar sesión:</p>
                <ul class="space-y-0.5 text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Estado de sesión -->
    @session('status')
        <div class="mb-4 rounded-lg bg-emerald-50/70 border border-emerald-200/80 p-3 text-xs font-medium text-emerald-800 flex items-center gap-2">
            <svg class="size-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
            </svg>
            <span>{{ $value }}</span>
        </div>
    @endsession

    <!-- Formulario -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-medium text-zinc-700 mb-1.5">
                Correo electrónico
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </div>
                <input id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    autocorrect="off"
                    autocapitalize="off"
                    spellcheck="false"
                    enterkeyhint="next"
                    placeholder="tu@correo.com"
                    class="w-full pl-9 pr-3.5 py-2 text-sm rounded-lg border border-zinc-200 bg-white text-zinc-900 placeholder:text-zinc-400 shadow-[0_1px_2px_rgba(0,0,0,0.04)] transition-all duration-150 hover:border-zinc-300 focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 focus:outline-none" />
            </div>
        </div>

        <!-- Contraseña -->
        <div x-data="{ show: false }">
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-medium text-zinc-700">
                    Contraseña
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-zinc-500 hover:text-zinc-900 font-medium transition-colors" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <input id="password"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    enterkeyhint="done"
                    placeholder="••••••••"
                    class="w-full pl-9 pr-10 py-2 text-sm rounded-lg border border-zinc-200 bg-white text-zinc-900 placeholder:text-zinc-400 shadow-[0_1px_2px_rgba(0,0,0,0.04)] transition-all duration-150 hover:border-zinc-300 focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 focus:outline-none" />
                <button type="button"
                    @click="show = !show"
                    :aria-label="show ? 'Ocultar contraseña' : 'Ver contraseña'"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-700 transition-colors focus:outline-none">
                    <svg x-show="!show" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg x-show="show" x-cloak class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Recordarme -->
        <div class="flex items-center">
            <label for="remember_me" class="flex items-center gap-2 cursor-pointer select-none group">
                <input id="remember_me" type="checkbox" name="remember"
                    class="size-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900 focus:ring-offset-0 transition cursor-pointer" />
                <span class="text-xs text-zinc-600 group-hover:text-zinc-900 transition-colors">Mantener sesión activa</span>
            </label>
        </div>

        <!-- Botón Enviar -->
        <div class="pt-1">
            <button type="submit"
                class="w-full h-10 px-4 rounded-lg bg-zinc-900 hover:bg-zinc-800 active:bg-zinc-950 text-white font-medium text-sm transition-all duration-150 shadow-[0_1px_2px_rgba(0,0,0,0.08),0_0_0_1px_rgba(0,0,0,0.06)] hover:shadow-md focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 flex items-center justify-center gap-2 group cursor-pointer">
                <span>Iniciar sesión</span>
                <svg class="size-4 shrink-0 text-zinc-400 group-hover:text-white group-hover:translate-x-0.5 transition-all duration-150" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>
</x-guest-layout>