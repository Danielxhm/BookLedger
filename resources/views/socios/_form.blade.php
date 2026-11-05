@php $s = $socio ?? null; @endphp

<div class="space-y-6">
    <!-- Documento y Teléfono -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="Num_Documento" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Número de documento <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="Num_Documento"
                name="Num_Documento" 
                value="{{ old('Num_Documento', $s->Num_Documento ?? '') }}" 
                placeholder="00000000-0"
                required
                class="w-full rounded-xl border @error('Num_Documento') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Num_Documento') 
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> 
            @enderror
        </div>

        <div>
            <label for="Telefono" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Teléfono <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="Telefono"
                name="Telefono" 
                value="{{ old('Telefono', $s->Telefono ?? '') }}" 
                placeholder="00000000"
                required
                class="w-full rounded-xl border @error('Telefono') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Telefono') 
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> 
            @enderror
        </div>
    </div>

    <!-- Nombres y Apellidos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="Nombres" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Nombres <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="Nombres"
                name="Nombres" 
                value="{{ old('Nombres', $s->Nombres ?? '') }}" 
                placeholder="Ej. Juan Carlos"
                required
                class="w-full rounded-xl border @error('Nombres') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Nombres') 
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> 
            @enderror
        </div>

        <div>
            <label for="Apellidos" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Apellidos <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="Apellidos"
                name="Apellidos" 
                value="{{ old('Apellidos', $s->Apellidos ?? '') }}" 
                placeholder="Ej. Pérez Gómez"
                required
                class="w-full rounded-xl border @error('Apellidos') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Apellidos') 
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> 
            @enderror
        </div>
    </div>

    <!-- Email y Fecha Nacimiento -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="Email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Correo Electrónico <span class="text-rose-500">*</span>
            </label>
            <input 
                type="email" 
                id="Email"
                name="Email" 
                value="{{ old('Email', $s->Email ?? '') }}" 
                placeholder="socio@ejemplo.com"
                required
                class="w-full rounded-xl border @error('Email') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Email') 
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> 
            @enderror
        </div>

        <div>
            <label for="Fecha_Nac" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Fecha de Nacimiento <span class="text-rose-500">*</span>
            </label>
            <input 
                type="date" 
                id="Fecha_Nac"
                name="Fecha_Nac" 
                value="{{ old('Fecha_Nac', isset($s->Fecha_Nac) ? $s->Fecha_Nac->format('Y-m-d') : '') }}" 
                required
                class="w-full rounded-xl border @error('Fecha_Nac') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Fecha_Nac') 
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> 
            @enderror
        </div>
    </div>

    <!-- Estado y Contraseña -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="EstadoSocio" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Estado de Membresía <span class="text-rose-500">*</span>
            </label>
            <select 
                id="EstadoSocio"
                name="EstadoSocio" 
                class="w-full rounded-xl border border-gray-300/80 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
                @foreach (['Activo', 'Sancionado', 'Inactivo'] as $estado)
                    <option value="{{ $estado }}" @selected(old('EstadoSocio', $s->EstadoSocio ?? 'Activo') == $estado)>
                        {{ $estado }}
                    </option>
                @endforeach
            </select>
            @error('EstadoSocio') 
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> 
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Contraseña de Acceso API <span class="text-xs text-gray-400 font-normal lowercase">(opcional)</span>
            </label>
            <input 
                type="password" 
                id="password"
                name="password" 
                placeholder="{{ $s ? 'Dejar en blanco para conservar actual' : 'Contraseña para la app móvil/API' }}"
                class="w-full rounded-xl border border-gray-300/80 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('password') 
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> 
            @enderror
        </div>
    </div>
</div>
