<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('prestamos.index') }}" class="hover:text-gray-900 transition-colors">Préstamos</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-semibold">Nuevo préstamo</span>
        </nav>

        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-gray-900">Registrar nuevo préstamo</h1>
                        <p class="text-xs text-gray-500 mt-1">Asigna ejemplares físicos disponibles a un socio habilitado.</p>
                    </div>
                    <a href="{{ route('prestamos.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Volver
                    </a>
                </div>

                <form method="POST" action="{{ route('prestamos.store') }}" class="p-6 space-y-6" onsubmit="bloquearBoton(this)"
                    x-data="{ sel: @js(array_map('strval', old('ejemplares', []))), max: {{ config('biblioteca.max_ejemplares_socio') }}, q: '{{ addslashes(request('isbn', request('buscar', ''))) }}' }">
                    @csrf

                    @if(request('reserva'))
                        <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200 text-xs text-gray-800 flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <div class="w-5 h-5 rounded-full bg-gray-900 text-white flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span>Atendiendo <strong>Reserva #{{ request('reserva') }}</strong>. El socio y el libro solicitado han sido preseleccionados.</span>
                            </div>
                            <a href="{{ route('prestamos.index', ['tab' => 'reservas']) }}" class="text-gray-900 hover:text-black font-semibold underline text-[11px]">Volver a reservas</a>
                        </div>
                    @endif

                    <div>
                        <label for="Codigo_Socio" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Socio prestatario <span class="text-rose-500">*</span>
                        </label>
                        <select id="Codigo_Socio" name="Codigo_Socio" required
                            class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Codigo_Socio') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                            <option value="">-- Seleccionar socio --</option>
                            @foreach ($socios as $s)
                                <option value="{{ $s->Codigo_Socio }}" @selected(old('Codigo_Socio', request('socio')) == $s->Codigo_Socio)>
                                    {{ $s->Apellidos }}, {{ $s->Nombres }} — Doc: {{ $s->Numero_Documento ?? $s->Num_Documento }}
                                </option>
                            @endforeach
                        </select>
                        @error('Codigo_Socio')
                            <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Selección de ejemplares <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-xs font-medium text-gray-500">
                                Máximo permitido: <span class="font-bold text-gray-900">{{ config('biblioteca.max_ejemplares_socio') }}</span> copias
                            </span>
                        </div>

                        <div class="relative mb-3">
                            <input type="text" x-model="q" placeholder="Filtrar por título, código o estante..."
                                class="block w-full min-h-[40px] px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all">
                        </div>

                        <div class="max-h-64 overflow-y-auto border border-gray-200/80 rounded-xl divide-y divide-gray-100 bg-white">
                            @forelse ($ejemplares as $e)
                                <label class="flex items-center gap-3 px-3.5 py-2.5 text-xs cursor-pointer hover:bg-gray-50 transition-colors"
                                    data-text="{{ strtolower('#' . $e->Codigo_Ejemplar . ' ' . ($e->libro->Titulo ?? '') . ' ' . ($e->Codigo_ISBN ?? '') . ' ' . $e->Ubicacion_Estante) }}"
                                    x-show="!q || $el.dataset.text.includes(q.toLowerCase())">
                                    <input type="checkbox" name="ejemplares[]" value="{{ $e->Codigo_Ejemplar }}" x-model="sel"
                                        :disabled="sel.length >= max && !sel.includes('{{ $e->Codigo_Ejemplar }}')"
                                        class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 w-4 h-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $e->libro->Titulo ?? '—' }}</p>
                                        <p class="text-[11px] text-gray-500 font-mono">Ejemplar #{{ $e->Codigo_Ejemplar }} · Estante: {{ $e->Ubicacion_Estante }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        Disponible
                                    </span>
                                </label>
                            @empty
                                <div class="p-6 text-center text-xs text-gray-400">
                                    No hay ejemplares con estado disponible actualmente.
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-2.5 flex items-center justify-between text-xs text-gray-500">
                            <div>
                                Seleccionados: <span class="font-bold text-gray-900" x-text="sel.length"></span> de {{ config('biblioteca.max_ejemplares_socio') }}
                            </div>
                            <div>
                                Plazo de devolución: <span class="font-semibold text-gray-800">{{ today()->addDays(config('biblioteca.dias_prestamo'))->format('d/m/Y') }}</span> ({{ config('biblioteca.dias_prestamo') }} días)
                            </div>
                        </div>

                        @error('ejemplares')
                            <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                        @error('ejemplares.*')
                            <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('prestamos.index') }}" class="min-h-[44px] px-5 py-2.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center">
                            Cancelar
                        </a>
                        <button type="submit" class="min-h-[44px] px-6 py-2.5 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-xs flex items-center justify-center"
                            :disabled="sel.length === 0">
                            Confirmar préstamo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
