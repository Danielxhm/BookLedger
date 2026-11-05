<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('bibliotecarios.index') }}" class="hover:text-gray-900 transition-colors">Bibliotecarios</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-semibold">Editar bibliotecario</span>
        </nav>

        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-gray-900">Editar bibliotecario: {{ $bibliotecario->Nombres }} {{ $bibliotecario->Apellidos }}</h1>
                        <p class="text-xs text-gray-500 mt-1">Actualiza información personal, supervisor jerárquico o estado del empleado.</p>
                    </div>
                    <a href="{{ route('bibliotecarios.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Volver
                    </a>
                </div>

                <form method="POST" action="{{ route('bibliotecarios.update', $bibliotecario) }}"
                    x-data="{
                        aviso: { doc: false, nombres: false, apellidos: false },
                        soloLetras(campo, e) {
                            const original = e.target.value;
                            const limpio = original.replace(/[^\p{L}\s'-]/gu, '');
                            this.aviso[campo] = original !== limpio;
                            e.target.value = limpio;
                        },
                        formatoDocumento(e) {
                            const original = e.target.value;
                            this.aviso.doc = /[^\d-]/.test(original);
                            const d = original.replace(/\D/g, '').slice(0, 9);
                            e.target.value = d.length > 8 ? d.slice(0, 8) + '-' + d.slice(8) : d;
                        }
                    }"
                    class="p-6 space-y-6" onsubmit="bloquearBoton(this)">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-xs font-medium text-rose-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Hay datos por corregir. Revisa los campos marcados en rojo.</span>
                        </div>
                    @endif

                    {{-- Documento e Identificador --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="Num_documento" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Documento de identidad <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="Num_documento" name="Num_documento" value="{{ old('Num_documento', $bibliotecario->Num_documento) }}" inputmode="numeric" maxlength="10"
                                placeholder="00000000-0" autocomplete="off" @input="formatoDocumento($event)" required
                                class="block w-full min-h-[44px] px-3.5 py-2.5 font-mono text-sm bg-white border @error('Num_documento') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                            <p x-show="aviso.doc" x-cloak class="text-rose-600 text-xs mt-1.5 font-medium">Solo se permiten números.</p>
                            @error('Num_documento')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="NombreUsuario" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Nombre de usuario (login interno) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="NombreUsuario" name="NombreUsuario" value="{{ old('NombreUsuario', $bibliotecario->NombreUsuario) }}" required
                                class="block w-full min-h-[44px] px-3.5 py-2.5 font-mono text-sm bg-white border @error('NombreUsuario') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                            @error('NombreUsuario')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Nombres y Apellidos --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="Nombres" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Nombres <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="Nombres" name="Nombres" value="{{ old('Nombres', $bibliotecario->Nombres) }}" maxlength="50" autocomplete="off" required
                                @input="soloLetras('nombres', $event)"
                                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Nombres') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                            <p x-show="aviso.nombres" x-cloak class="text-rose-600 text-xs mt-1.5 font-medium">Solo se permiten letras.</p>
                            @error('Nombres')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="Apellidos" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Apellidos <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="Apellidos" name="Apellidos" value="{{ old('Apellidos', $bibliotecario->Apellidos) }}" maxlength="50" autocomplete="off" required
                                @input="soloLetras('apellidos', $event)"
                                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Apellidos') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                            <p x-show="aviso.apellidos" x-cloak class="text-rose-600 text-xs mt-1.5 font-medium">Solo se permiten letras.</p>
                            @error('Apellidos')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Supervisor y Estado --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="Codigo_Supervisor" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Supervisor jerárquico <span class="text-gray-400 font-normal lowercase">(opcional)</span>
                            </label>
                            <select id="Codigo_Supervisor" name="Codigo_Supervisor"
                                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Codigo_Supervisor') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                                <option value="">— Ninguno —</option>
                                @foreach ($supervisores as $s)
                                    <option value="{{ $s->Codigo_Bibliotecario }}" @selected(old('Codigo_Supervisor', $bibliotecario->Codigo_Supervisor) == $s->Codigo_Bibliotecario)>
                                        {{ $s->Nombres }} {{ $s->Apellidos }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Codigo_Supervisor')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="EstadoEmpleado" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Estado laboral <span class="text-rose-500">*</span>
                            </label>
                            <select id="EstadoEmpleado" name="EstadoEmpleado" required
                                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('EstadoEmpleado') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                                <option value="Activo" @selected(old('EstadoEmpleado', $bibliotecario->EstadoEmpleado) === 'Activo')>Activo</option>
                                <option value="Inactivo" @selected(old('EstadoEmpleado', $bibliotecario->EstadoEmpleado) === 'Inactivo')>Inactivo</option>
                            </select>
                            @error('EstadoEmpleado')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Cuenta vinculada --}}
                    @if ($bibliotecario->user)
                        <div class="pt-4 border-t border-gray-100 space-y-4">
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Cuenta de acceso vinculada</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $bibliotecario->user->email }}</p>
                            </div>

                            <div>
                                <label for="rol" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Rol en el sistema <span class="text-rose-500">*</span>
                                </label>
                                <select id="rol" name="rol" required
                                    class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('rol') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                                    <option value="Bibliotecario" @selected(old('rol', $bibliotecario->user->hasRole('Administrador') ? 'Administrador' : 'Bibliotecario') === 'Bibliotecario')>Bibliotecario</option>
                                    <option value="Administrador" @selected(old('rol', $bibliotecario->user->hasRole('Administrador') ? 'Administrador' : 'Bibliotecario') === 'Administrador')>Administrador</option>
                                </select>
                                @error('rol')
                                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('bibliotecarios.index') }}" class="min-h-[44px] px-5 py-2.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center">
                            Cancelar
                        </a>
                        <button type="submit" class="min-h-[44px] px-6 py-2.5 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-xs flex items-center justify-center">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
