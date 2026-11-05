<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-semibold">Devoluciones</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Registro de devoluciones</h1>
                <p class="text-xs text-gray-500">Historial de recepciones de préstamos, inspección de estado físico y cálculo de penalizaciones.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('devoluciones.crear')
                    <a href="{{ route('devoluciones.create') }}" class="inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-xs gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Registrar devolución
                    </a>
                @endcan
            </div>
        </div>

        @if (session('status'))
            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs font-medium text-gray-800 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
            <div class="p-4 sm:px-6 sm:py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Devoluciones registradas</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                        {{ $devoluciones->total() ?? count($devoluciones) }} registros
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto" role="region" aria-label="Tabla de devoluciones" tabindex="0">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200/80 bg-gray-50/75 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                            <th scope="col" class="py-3 px-4"># Devolución</th>
                            <th scope="col" class="py-3 px-4">Fecha recepción</th>
                            <th scope="col" class="py-3 px-4">Libros y ejemplares devueltos</th>
                            <th scope="col" class="py-3 px-4">Bibliotecario receptor</th>
                            <th scope="col" class="py-3 px-4">Observaciones</th>
                            <th scope="col" class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($devoluciones as $dev)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="font-mono font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded text-[11px]">
                                        #{{ $dev->Codigo_Devolucion }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap font-medium text-gray-800">
                                    {{ $dev->Fecha_Devolucion->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="space-y-1">
                                        @foreach($dev->detalles as $det)
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-gray-900">{{ $det->ejemplar->libro->Titulo ?? '—' }}</span>
                                                <span class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded font-mono">Ej. #{{ $det->Codigo_Ejemplar }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-gray-700">
                                    <span class="font-medium">{{ $dev->bibliotecario->Nombres ?? '—' }} {{ $dev->bibliotecario->Apellidos ?? '' }}</span>
                                </td>
                                <td class="py-3 px-4 text-gray-500 max-w-xs truncate">
                                    {{ $dev->Observaciones ?: 'Sin observaciones' }}
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <a href="{{ route('devoluciones.show', $dev) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Ver acta
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-4 text-center">
                                    <div class="max-w-xs mx-auto text-center space-y-2">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-gray-400">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <p class="text-xs font-medium text-gray-900">No hay devoluciones registradas</p>
                                        <p class="text-xs text-gray-500">Las devoluciones aparecerán aquí una vez que los socios entreguen sus préstamos activos.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($devoluciones, 'links') && $devoluciones->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $devoluciones->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
