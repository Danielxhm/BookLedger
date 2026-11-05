@php $c = $categoria ?? null; @endphp

<div class="space-y-6">
    <div>
        <label for="NombreCategoria" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
            Nombre de la Categoría <span class="text-rose-500">*</span>
        </label>
        <input 
            type="text" 
            id="NombreCategoria"
            name="NombreCategoria" 
            value="{{ old('NombreCategoria', $c->NombreCategoria ?? '') }}" 
            placeholder="Ej. Ciencia Ficción, Historia, Filosofía"
            required
            class="w-full rounded-xl border @error('NombreCategoria') border-rose-400 bg-rose-50/20 @else border-gray-300/80 bg-white @enderror px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
        >
        @error('NombreCategoria')
            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="Codigo_categoriaPadre" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
            Categoría Padre <span class="text-xs text-gray-400 font-normal lowercase">(opcional para subcategorías)</span>
        </label>
        <select 
            id="Codigo_categoriaPadre"
            name="Codigo_categoriaPadre" 
            class="w-full rounded-xl border border-gray-300/80 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
        >
            <option value="">-- Ninguna (Categoría Principal) --</option>
            @foreach ($padres as $p)
                <option value="{{ $p->Codigo_Categoria }}" @selected(old('Codigo_categoriaPadre', $c->Codigo_categoriaPadre ?? '') == $p->Codigo_Categoria)>
                    {{ $p->NombreCategoria }}
                </option>
            @endforeach
        </select>
        @error('Codigo_categoriaPadre')
            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="Descripcion" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
            Descripción de la Categoría <span class="text-xs text-gray-400 font-normal lowercase">(opcional)</span>
        </label>
        <textarea 
            id="Descripcion"
            name="Descripcion" 
            rows="3"
            placeholder="Breve reseña sobre el tipo de obras que agrupa esta categoría..."
            class="w-full rounded-xl border border-gray-300/80 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition shadow-2xs"
        >{{ old('Descripcion', $c->Descripcion ?? '') }}</textarea>
        @error('Descripcion')
            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>
