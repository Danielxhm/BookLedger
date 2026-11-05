@php $d = $devolucion ?? null; @endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="Codigo_Bibliotecario" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Bibliotecario receptor <span class="text-rose-500">*</span>
            </label>
            <select id="Codigo_Bibliotecario" name="Codigo_Bibliotecario" required
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Codigo_Bibliotecario') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                <option value="">-- Seleccionar bibliotecario --</option>
                @foreach ($bibliotecarios as $bibliotecario)
                    <option value="{{ $bibliotecario->Codigo_Bibliotecario }}" @selected(old('Codigo_Bibliotecario', $d->Codigo_Bibliotecario ?? '') == $bibliotecario->Codigo_Bibliotecario)>
                        {{ $bibliotecario->Nombres }} {{ $bibliotecario->Apellidos }}
                    </option>
                @endforeach
            </select>
            @error('Codigo_Bibliotecario')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="Fecha_Devolucion" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Fecha de recepción <span class="text-rose-500">*</span>
            </label>
            <input type="date" id="Fecha_Devolucion" name="Fecha_Devolucion" value="{{ old('Fecha_Devolucion', isset($d->Fecha_Devolucion) ? $d->Fecha_Devolucion->format('Y-m-d') : date('Y-m-d')) }}" required
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Fecha_Devolucion') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
            @error('Fecha_Devolucion')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div>
        <label for="Observaciones" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
            Observaciones <span class="text-gray-400 font-normal lowercase">(opcional)</span>
        </label>
        <textarea id="Observaciones" name="Observaciones" rows="3" maxlength="200"
            class="block w-full px-3.5 py-2.5 bg-white border @error('Observaciones') border-rose-300 @else border-gray-300 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">{{ old('Observaciones', $d->Observaciones ?? '') }}</textarea>
        @error('Observaciones')
            <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>
</div>
