@php
    $ej = $ejemplar ?? null;
@endphp

<div class="space-y-6">
    @if (!$ej)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <label for="Codigo_ISBN" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                    Libro correspondiente <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select id="Codigo_ISBN" name="Codigo_ISBN" required
                        class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Codigo_ISBN') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                        <option value="">-- Seleccionar libro del catálogo --</option>
                        @foreach ($libros as $l)
                            <option value="{{ $l->Codigo_ISBN }}" @selected(old('Codigo_ISBN') == $l->Codigo_ISBN)>
                                {{ $l->Titulo }} (ISBN: {{ $l->Codigo_ISBN }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="mt-1.5 text-xs text-gray-500">Selecciona el título al que se vincularán las copias físicas.</p>
                @error('Codigo_ISBN')
                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="cantidad" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                    Cantidad a generar <span class="text-rose-500">*</span>
                </label>
                <input type="number" id="cantidad" name="cantidad" min="1" max="100" value="{{ old('cantidad', 1) }}" required
                    class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('cantidad') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                <p class="mt-1.5 text-xs text-gray-500">Copias físicas a registrar (máx. 100).</p>
                @error('cantidad')
                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    @else
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-900 text-white flex items-center justify-center font-semibold text-xs shrink-0">
                #{{ $ej->Codigo_Ejemplar }}
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Libro vinculado</p>
                <p class="text-sm font-semibold text-gray-900">{{ $ej->libro->Titulo ?? 'Libro no encontrado' }}</p>
                <p class="text-xs text-gray-500 font-mono">ISBN: {{ $ej->Codigo_ISBN }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="Ubicacion_Estante" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Ubicación / Estante <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="Ubicacion_Estante" name="Ubicacion_Estante" value="{{ old('Ubicacion_Estante', $ej->Ubicacion_Estante ?? '') }}" required
                placeholder="Ej. Pasillo 3 - Estante B2"
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Ubicacion_Estante') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
            <p class="mt-1.5 text-xs text-gray-500">Sección, pasillo o estante físico donde se encuentra la copia.</p>
            @error('Ubicacion_Estante')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        @php
            $prestado = $ej && $ej->Estado_Ejemplar === 'Prestado';
        @endphp
        <div>
            <label for="Estado_Ejemplar" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Estado del ejemplar <span class="text-rose-500">*</span>
            </label>
            @if ($prestado)
                <input type="hidden" name="Estado_Ejemplar" value="Prestado">
                <div class="flex items-center min-h-[44px] px-3.5 py-2.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-sm font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                    Prestado (No modificable)
                </div>
                <p class="mt-1.5 text-xs text-amber-700">Este ejemplar está actualmente en préstamo activo. Su estado cambiará al registrar su devolución.</p>
            @else
                <select id="Estado_Ejemplar" name="Estado_Ejemplar" required
                    class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Estado_Ejemplar') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                    @foreach (['Disponible', 'Mantenimiento'] as $estado)
                        <option value="{{ $estado }}" @selected(old('Estado_Ejemplar', $ej->Estado_Ejemplar ?? 'Disponible') == $estado)>
                            {{ $estado }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1.5 text-xs text-gray-500">Disponible para circular o retirado temporalmente para restauración.</p>
            @endif
            @error('Estado_Ejemplar')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>
</div>
