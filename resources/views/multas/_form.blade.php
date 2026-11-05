@php
    $m = $multa ?? null;
    $listaDetalles = $detalles ?? collect();
@endphp

<div class="space-y-6">
    <div>
        <label for="Codigo_Detalle" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
            Préstamo / Ejemplar asociado <span class="text-rose-500">*</span>
        </label>
        @if($m && $m->detalle)
            <input type="hidden" name="Codigo_Detalle" value="{{ $m->Codigo_Detalle }}">
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Detalle #{{ $m->Codigo_Detalle }}</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $m->detalle->ejemplar->libro->Titulo ?? 'Libro sin título' }}</p>
                    <p class="text-xs text-gray-600">Socio: {{ $m->detalle->prestamo->socio->Nombres ?? '' }} {{ $m->detalle->prestamo->socio->Apellidos ?? '' }}</p>
                </div>
                <span class="text-xs font-mono bg-gray-200 px-2 py-1 rounded text-gray-700">Ejemplar #{{ $m->detalle->Codigo_Ejemplar }}</span>
            </div>
        @else
            <div x-data="{
                selectedId: '{{ old('Codigo_Detalle', request('Codigo_Detalle', '')) }}',
                selectedSocio: '',
                selectedLibro: '',
                selectedEjemplar: '',
                searchSocio: '',
                searchLibro: '',

                init() {
                    if (this.selectedId) {
                        this.$nextTick(() => {
                            const el = document.querySelector(`[data-id='${this.selectedId}']`);
                            if (el) {
                                this.selectItemFromElement(el);
                            }
                        });
                    }
                },
                selectItemFromElement(el) {
                    this.selectedId = el.dataset.id;
                    this.selectedSocio = el.dataset.socio;
                    this.selectedLibro = el.dataset.libro;
                    this.selectedEjemplar = el.dataset.ejemplar;
                },
                clearSelection() {
                    this.selectedId = '';
                    this.selectedSocio = '';
                    this.selectedLibro = '';
                    this.selectedEjemplar = '';
                },
                matches(el) {
                    const s = this.searchSocio.trim().toLowerCase();
                    const l = this.searchLibro.trim().toLowerCase();
                    const matchS = !s || el.dataset.searchSocio.includes(s);
                    const matchL = !l || el.dataset.searchLibro.includes(l);
                    return matchS && matchL;
                }
            }" class="space-y-3">

                <input type="hidden" name="Codigo_Detalle" x-model="selectedId" required id="Codigo_Detalle">

                {{-- Detalle seleccionado --}}
                <div x-show="selectedId" x-cloak class="p-4 bg-gray-50 border border-gray-300 rounded-xl flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-gray-900 text-white flex items-center justify-center shrink-0 mt-0.5 font-bold text-xs">
                            ✓
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono font-semibold text-gray-900" x-text="'Detalle #' + selectedId"></span>
                                <span class="text-xs text-gray-400">·</span>
                                <span class="text-xs font-mono bg-white px-2 py-0.5 rounded border border-gray-200 text-gray-800" x-text="'Ejemplar #' + selectedEjemplar"></span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900 truncate mt-0.5" x-text="selectedLibro"></p>
                            <p class="text-xs text-gray-600 mt-0.5 flex items-center gap-1.5">
                                <span class="font-medium text-gray-700">Socio:</span>
                                <span class="text-gray-900 font-semibold" x-text="selectedSocio"></span>
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="clearSelection()"
                        class="px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 rounded-lg transition-colors shrink-0 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Cambiar
                    </button>
                </div>

                {{-- Selector de préstamo o devolución a sancionar --}}
                <div x-show="!selectedId" class="border border-gray-200/90 rounded-2xl bg-white p-3.5 space-y-3 shadow-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input type="text" x-model="searchSocio" placeholder="Buscar por socio (nombre o doc)..."
                                class="w-full pl-9 pr-7 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:bg-white focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-all">
                            <button type="button" x-show="searchSocio" @click="searchSocio = ''" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </span>
                            <input type="text" x-model="searchLibro" placeholder="Buscar por libro o ejemplar..."
                                class="w-full pl-9 pr-7 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:bg-white focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-all">
                            <button type="button" x-show="searchLibro" @click="searchLibro = ''" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Lista de registros filtrables --}}
                    <div class="max-h-56 overflow-y-auto divide-y divide-gray-100 border border-gray-200/70 rounded-xl bg-gray-50/40">
                        @forelse ($listaDetalles as $d)
                            @php
                                $socioNombre = trim(($d->prestamo->socio->Nombres ?? '') . ' ' . ($d->prestamo->socio->Apellidos ?? ''));
                                if (!$socioNombre) $socioNombre = 'Socio no registrado';
                                $socioDoc = $d->prestamo->socio->Numero_Documento ?? $d->prestamo->socio->Num_Documento ?? '';
                                $socioFull = $socioNombre . ($socioDoc ? ' (Doc: ' . $socioDoc . ')' : '');
                                $libroTitulo = $d->ejemplar->libro->Titulo ?? 'Libro sin título';
                                $ejemplarNum = $d->Codigo_Ejemplar ?? 'N/A';
                            @endphp
                            <div
                                data-id="{{ $d->Codigo_Detalle }}"
                                data-socio="{{ $socioFull }}"
                                data-libro="{{ $libroTitulo }}"
                                data-ejemplar="{{ $ejemplarNum }}"
                                data-search-socio="{{ strtolower($socioFull) }}"
                                data-search-libro="{{ strtolower($libroTitulo . ' ' . $ejemplarNum . ' detalle #' . $d->Codigo_Detalle) }}"
                                x-show="matches($el)"
                                @click="selectItemFromElement($el)"
                                class="p-3 text-xs cursor-pointer hover:bg-gray-100/80 transition-colors flex items-center justify-between gap-3 group"
                            >
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <span class="font-mono text-[11px] font-semibold text-gray-600 bg-white px-1.5 py-0.5 rounded border border-gray-200">
                                            Detalle #{{ $d->Codigo_Detalle }}
                                        </span>
                                        <span class="font-semibold text-gray-900 truncate">
                                            {{ $libroTitulo }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-gray-600 flex items-center gap-1.5">
                                        <span class="text-gray-400">Socio:</span>
                                        <span class="font-medium text-gray-800">{{ $socioNombre }}</span>
                                        @if($socioDoc)
                                            <span class="text-gray-400 font-mono text-[10px]">[{{ $socioDoc }}]</span>
                                        @endif
                                        <span class="text-gray-300">·</span>
                                        <span class="text-gray-500 font-mono text-[10px]">Ejemplar #{{ $ejemplarNum }}</span>
                                    </p>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 group-hover:text-gray-900 group-hover:translate-x-0.5 transition-all shrink-0">
                                    Seleccionar →
                                </span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-xs text-gray-400">
                                No hay préstamos o entregas recientes disponibles para multar.
                            </div>
                        @endforelse
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-gray-500 px-1">
                        <span>Haz clic en un registro para seleccionarlo.</span>
                        <span x-show="searchSocio || searchLibro">
                            <button type="button" @click="searchSocio = ''; searchLibro = ''" class="text-gray-900 font-semibold hover:underline">
                                Limpiar filtros
                            </button>
                        </span>
                    </div>
                </div>

                <p class="text-xs text-gray-500">Selecciona el registro de préstamo o devolución al que se aplicará la sanción.</p>
            </div>
        @endif
        @error('Codigo_Detalle')
            <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <label for="Monto" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Monto de la multa ($) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 font-semibold text-sm">$</span>
                <input type="number" step="0.01" min="0" id="Monto" name="Monto" value="{{ old('Monto', $m->Monto ?? '') }}" required placeholder="0.00"
                    class="block w-full min-h-[44px] pl-8 pr-3.5 py-2.5 bg-white border @error('Monto') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
            </div>
            @error('Monto')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="Motivo" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Motivo de la penalización <span class="text-rose-500">*</span>
            </label>
            <select id="Motivo" name="Motivo" required
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Motivo') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                <option value="Mora" @selected(old('Motivo', $m->Motivo ?? 'Mora') === 'Mora')>Mora (Retraso en devolución: ${{ number_format(config('biblioteca.multa_por_dia', 0.25), 2) }}/día)</option>
                <option value="Daño" @selected(old('Motivo', $m->Motivo ?? '') === 'Daño')>Daño (Deterioro de material: ${{ number_format(config('biblioteca.multa_dano', 2.00), 2) }})</option>
                <option value="Perdida" @selected(old('Motivo', $m->Motivo ?? '') === 'Perdida')>Pérdida (Extravío de ejemplar: ${{ number_format(config('biblioteca.multa_perdida', 10.00), 2) }})</option>
            </select>
            @error('Motivo')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <label for="Estado_Pago" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Estado de pago <span class="text-rose-500">*</span>
            </label>
            <select id="Estado_Pago" name="Estado_Pago" required
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Estado_Pago') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                @foreach (['Pendiente', 'Pagado'] as $estado)
                    <option value="{{ $estado }}" @selected(old('Estado_Pago', $m->Estado_Pago ?? 'Pendiente') == $estado)>
                        {{ $estado }}
                    </option>
                @endforeach
            </select>
            @error('Estado_Pago')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="Fecha_Emision" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Fecha de emisión <span class="text-rose-500">*</span>
            </label>
            <input type="date" id="Fecha_Emision" name="Fecha_Emision" value="{{ old('Fecha_Emision', isset($m->Fecha_Emision) ? $m->Fecha_Emision->format('Y-m-d') : date('Y-m-d')) }}" required
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Fecha_Emision') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
            @error('Fecha_Emision')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>
</div>
