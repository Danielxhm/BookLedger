<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('devoluciones.index') }}" class="hover:text-gray-900 transition-colors">Devoluciones</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-semibold">Registrar devolución</span>
        </nav>

        <div class="max-w-5xl mx-auto space-y-6">
            {{-- Filter Box --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6">
                <form method="GET" action="{{ route('devoluciones.create') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    <div class="sm:col-span-2">
                        <label for="filtro_socio" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Filtrar por socio con préstamos activos
                        </label>
                        <select id="filtro_socio" name="socio" onchange="this.form.submit()"
                            class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                            <option value="">-- Todos los socios con entregas pendientes --</option>
                            @foreach ($socios as $s)
                                <option value="{{ $s->Codigo_Socio }}" @selected(request('socio') == $s->Codigo_Socio)>
                                    {{ $s->Apellidos }}, {{ $s->Nombres }} (Doc: {{ $s->Numero_Documento }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(request('socio'))
                        <div class="flex items-center">
                            <a href="{{ route('devoluciones.create') }}" class="min-h-[44px] px-4 py-2.5 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Limpiar filtro
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            @error('detalles')
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs font-medium text-rose-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            {{-- Reception Form Card --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-gray-900">Seleccionar ejemplares a devolver</h1>
                        <p class="text-xs text-gray-500 mt-1">Marca las casillas de los libros recibidos y califica el estado físico del ejemplar entregado.</p>
                    </div>
                    <a href="{{ route('devoluciones.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Volver
                    </a>
                </div>

                <form method="POST" action="{{ route('devoluciones.store') }}" class="p-6 space-y-6" onsubmit="bloquearBoton(this)">
                    @csrf

                    <div class="overflow-x-auto rounded-xl border border-gray-200/80" role="region" aria-label="Tabla de ejemplares pendientes" tabindex="0">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200/80 bg-gray-50/75 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                                    <th scope="col" class="py-3 px-4 w-12 text-center">Devolver</th>
                                    <th scope="col" class="py-3 px-4">Libro y ejemplar</th>
                                    <th scope="col" class="py-3 px-4">Socio prestatario</th>
                                    <th scope="col" class="py-3 px-4">Fecha límite / Mora</th>
                                    <th scope="col" class="py-3 px-4">Estado físico al recibir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($detalles as $d)
                                    @php
                                        $mora = max(0, (int) $d->prestamo->FechaLimite_Devolucion->diffInDays(today(), false));
                                        $key = "detalles.{$d->Codigo_Detalle}";
                                    @endphp
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="py-3 px-4 text-center">
                                            <input type="checkbox" name="detalles[{{ $d->Codigo_Detalle }}][devolver]" value="1" @checked(old("$key.devolver"))
                                                class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 w-4 h-4 cursor-pointer">
                                        </td>
                                        <td class="py-3 px-4">
                                            <p class="font-semibold text-gray-900 text-xs">{{ $d->ejemplar->libro->Titulo ?? '—' }}</p>
                                            <p class="text-[11px] text-gray-500 font-mono">Ejemplar #{{ $d->Codigo_Ejemplar }} (Estante: {{ $d->ejemplar->Ubicacion_Estante ?? 'N/A' }})</p>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <p class="font-medium text-gray-900">{{ $d->prestamo->socio->Nombres }} {{ $d->prestamo->socio->Apellidos }}</p>
                                            <p class="text-[11px] text-gray-500">Doc: {{ $d->prestamo->socio->Numero_Documento }}</p>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <span class="font-medium text-gray-800">{{ $d->prestamo->FechaLimite_Devolucion->format('d/m/Y') }}</span>
                                            @if ($mora > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                    {{ $mora }} día(s) mora · ${{ number_format($mora * config('biblioteca.multa_por_dia'), 2) }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 mt-1 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700">
                                                    A tiempo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <select name="detalles[{{ $d->Codigo_Detalle }}][estado]"
                                                class="rounded-lg border-gray-300 text-xs py-1.5 px-3 bg-white focus:ring-gray-900 focus:border-gray-900">
                                                @foreach (['Buen estado', 'Dañado', 'Perdido'] as $est)
                                                    <option value="{{ $est }}" @selected(old("$key.estado", 'Buen estado') == $est)>{{ $est }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 px-4 text-center">
                                            <div class="max-w-xs mx-auto text-center space-y-1">
                                                <p class="text-xs font-semibold text-gray-900">No hay ejemplares pendientes</p>
                                                <p class="text-xs text-gray-500">Todos los préstamos activos están al día o no coinciden con el filtro seleccionado.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <label for="Observaciones" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Observaciones de recepción <span class="text-gray-400 font-normal lowercase">(opcional)</span>
                        </label>
                        <textarea id="Observaciones" name="Observaciones" rows="3" maxlength="200" placeholder="Comentarios sobre el estado del material, entrega fuera de plazo u observaciones del bibliotecario..."
                            class="block w-full px-3.5 py-2.5 bg-white border @error('Observaciones') border-rose-300 @else border-gray-300 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">{{ old('Observaciones') }}</textarea>
                        @error('Observaciones')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-600 flex items-start gap-3">
                        <svg class="w-4 h-4 text-gray-700 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <span class="font-semibold text-gray-800">Cálculo de sanciones:</span>
                            La entrega con estado <em>Dañado</em> o <em>Perdido</em>, o con retraso de mora, genera una multa automática y el estado del socio cambiará a <strong>Sancionado</strong> hasta que el pago de la multa sea registrado.
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('devoluciones.index') }}" class="min-h-[44px] px-5 py-2.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center">
                            Cancelar
                        </a>
                        <button type="submit" class="min-h-[44px] px-6 py-2.5 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-xs flex items-center justify-center">
                            Registrar devolución y procesar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
