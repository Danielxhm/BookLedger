<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('prestamos.index') }}" class="hover:text-gray-900 transition-colors">Préstamos</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-semibold">Préstamo #{{ $prestamo->Codigo_Prestamo }}</span>
        </nav>

        <div class="max-w-4xl mx-auto space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-xl font-bold tracking-tight text-gray-900">Detalle de préstamo #{{ $prestamo->Codigo_Prestamo }}</h1>
                            @php
                                $badgeStyles = match($prestamo->EstadoPrestamo) {
                                    'Activo' => 'bg-gray-100 text-gray-900 border-gray-300 font-semibold',
                                    'Vencido' => 'bg-gray-900 text-white font-semibold',
                                    'Concluido' => 'bg-gray-100 text-gray-600 border-gray-200',
                                    default => 'bg-gray-100 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeStyles }}">
                                {{ $prestamo->EstadoPrestamo }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Información de entrega, fechas y estado de los libros asociados.</p>
                    </div>
                    <a href="{{ route('prestamos.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Volver
                    </a>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 p-4 rounded-xl bg-gray-50 border border-gray-200/80">
                        <div>
                            <span class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Socio</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $prestamo->socio->Nombres }} {{ $prestamo->socio->Apellidos }}</span>
                            <span class="block text-xs text-gray-500">Doc: {{ $prestamo->socio->Numero_Documento ?? $prestamo->socio->Num_Documento }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Registrado por</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $prestamo->bibliotecario->Nombres ?? '—' }} {{ $prestamo->bibliotecario->Apellidos ?? '' }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Fecha de entrega</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $prestamo->FechaPrestamo->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Fecha límite retorno</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $prestamo->FechaLimite_Devolucion->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Ejemplares prestados</h2>
                        <div class="overflow-x-auto rounded-xl border border-gray-200/80" role="region" aria-label="Ejemplares prestados" tabindex="0">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-200/80 bg-gray-50/75 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                                        <th scope="col" class="py-3 px-4"># Ejemplar</th>
                                        <th scope="col" class="py-3 px-4">Título del libro</th>
                                        <th scope="col" class="py-3 px-4">Estado de entrega</th>
                                        <th scope="col" class="py-3 px-4">Multas / Penalizaciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($prestamo->detalles as $d)
                                        <tr>
                                            <td class="py-3 px-4 whitespace-nowrap font-mono font-medium text-gray-800">
                                                #{{ $d->Codigo_Ejemplar }}
                                            </td>
                                            <td class="py-3 px-4">
                                                <p class="font-semibold text-gray-900">{{ $d->ejemplar->libro->Titulo ?? '—' }}</p>
                                                <p class="text-[11px] text-gray-500">ISBN: {{ $d->ejemplar->Codigo_ISBN ?? 'N/A' }}</p>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                @if($d->Codigo_Devolucion)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                        Devuelto ({{ $d->EstadoEntrega }})
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-900 text-white">
                                                        En poder del socio
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                @forelse ($d->multas as $m)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-red-50 text-red-800 border border-red-200">
                                                        {{ $m->Motivo }}: ${{ number_format($m->Monto, 2) }} ({{ $m->Estado_Pago }})
                                                    </span>
                                                @empty
                                                    <span class="text-gray-400 italic">Sin multas</span>
                                                @endforelse
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <a href="{{ route('prestamos.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                            ← Volver al listado
                        </a>

                        @if ($prestamo->EstadoPrestamo !== 'Concluido')
                            @can('devoluciones.crear')
                                <a href="{{ route('devoluciones.create', ['socio' => $prestamo->Codigo_Socio]) }}" class="inline-flex items-center justify-center min-h-[44px] px-5 py-2.5 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-xs gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Registrar devolución de este préstamo
                                </a>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
