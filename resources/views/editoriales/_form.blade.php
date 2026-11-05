@php $e = $editorial ?? null; @endphp

<div class="space-y-6">
    <div>
        <label for="NombreEditorial" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
            Nombre de la Editorial <span class="text-rose-500">*</span>
        </label>
        <input 
            type="text" 
            id="NombreEditorial"
            name="NombreEditorial" 
            value="{{ old('NombreEditorial', $e->NombreEditorial ?? '') }}" 
            placeholder="Ej. Penguin Random House, Planeta, Fondo de Cultura Económica"
            required
            class="w-full rounded-xl border @error('NombreEditorial') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
        >
        @error('NombreEditorial')
            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="Pais" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
            País de Origen <span class="text-rose-500">*</span>
        </label>
        <input 
            type="text" 
            id="Pais"
            name="Pais" 
            value="{{ old('Pais', $e->Pais ?? '') }}" 
            placeholder="Ej. España, México, Colombia, Argentina"
            required
            class="w-full rounded-xl border @error('Pais') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
        >
        @error('Pais')
            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>
