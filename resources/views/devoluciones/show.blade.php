<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('devoluciones.index') }}" class="hover:text-gray-900 transition-colors">Devoluciones</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-semibold">Devolución #{{ $devolucion->Codigo_Devolucion }}</span>
        </nav>

        <div class="max-w-4xl mx-auto space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-gray-900">Acta de devolución #{{ $devolucion->Codigo_Devolucion }}</h1>
                        <p class="text-xs text-gray-500 mt-1">Comprobante de recepción de material prestado registrado en el sistema.</p>
                    </div>
                    <a href="{{ route('devoluciones.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Volver al listado
                    </a>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-xl bg-gray-50 border border-gray-200/80">
                        <div>
                            <span class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Fecha de recepción</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $devolucion->Fecha_Devolucion->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Bibliotecario receptor</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $devolucion->bibliotecario->Nombres ?? '—' }} {{ $devolucion->bibliotecario->Apellidos ?? '' }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500">Observaciones</span>
                            <span class="text-sm text-gray-700">{{ $devolucion->Observaciones ?: 'Ninguna' }}</span>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Detalle de ejemplares recibidos</h2>
                        <div class="overflow-x-auto rounded-xl border border-gray-200/80" role="region" aria-label="Detalles de devolución" tabindex="0">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-200/80 bg-gray-50/75 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                                        <th scope="col" class="py-3 px-4">Libro / Ejemplar</th>
                                        <th scope="col" class="py-3 px-4">Socio prestatario</th>
                                        <th scope="col" class="py-3 px-4">Estado al recibir</th>
                                        <th scope="col" class="py-3 px-4">Multas generadas</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($devolucion->detalles as $d)
                                        <tr>
                                            <td class="py-3 px-4">
                                                <p class="font-semibold text-gray-900">{{ $d->ejemplar->libro->Titulo ?? '—' }}</p>
                                                <p class="text-[11px] text-gray-500 font-mono">Ejemplar #{{ $d->Codigo_Ejemplar }}</p>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <p class="font-medium text-gray-900">{{ $d->prestamo->socio->Nombres }} {{ $d->prestamo->socio->Apellidos }}</p>
                                                <p class="text-[11px] text-gray-500">Doc: {{ $d->prestamo->socio->Numero_Documento }}</p>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium {{ $d->EstadoEntrega === 'Buen estado' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                                    {{ $d->EstadoEntrega }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                @forelse ($d->multas as $m)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                        {{ $m->Motivo }}: ${{ number_format($m->Monto, 2) }}
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
