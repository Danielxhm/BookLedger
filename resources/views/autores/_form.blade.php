@php $a = $autor ?? null; @endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="Nombres" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Nombres del Autor <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="Nombres"
                name="Nombres" 
                value="{{ old('Nombres', $a->Nombres ?? '') }}" 
                placeholder="Ej. Gabriel"
                required
                class="w-full rounded-xl border @error('Nombres') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Nombres')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="Apellidos" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Apellidos del Autor <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="Apellidos"
                name="Apellidos" 
                value="{{ old('Apellidos', $a->Apellidos ?? '') }}" 
                placeholder="Ej. García Márquez"
                required
                class="w-full rounded-xl border @error('Apellidos') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Apellidos')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="Seudonimo" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Seudónimo / Firma <span class="text-xs text-gray-400 font-normal lowercase">(opcional)</span>
            </label>
            <input 
                type="text" 
                id="Seudonimo"
                name="Seudonimo" 
                value="{{ old('Seudonimo', $a->Seudonimo ?? '') }}" 
                placeholder="Ej. Gabo, George Orwell"
                class="w-full rounded-xl border border-gray-300/80 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Seudonimo')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="Nacionalidad" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                Nacionalidad <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="Nacionalidad"
                name="Nacionalidad" 
                value="{{ old('Nacionalidad', $a->Nacionalidad ?? '') }}" 
                placeholder="Ej. Colombiana, Mexicana, Española"
                required
                class="w-full rounded-xl border @error('Nacionalidad') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
            >
            @error('Nacionalidad')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="Biografia" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
            Biografía y Trayectoria <span class="text-xs text-gray-400 font-normal lowercase">(opcional)</span>
        </label>
        <textarea 
            id="Biografia"
            name="Biografia" 
            rows="4"
            placeholder="Reseña biográfica, premios, época o corrientes literarias del autor..."
            class="w-full rounded-xl border border-gray-300/80 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
        >{{ old('Biografia', $a->Biografia ?? '') }}</textarea>
        @error('Biografia')
            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>
