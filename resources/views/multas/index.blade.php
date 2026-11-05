<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        {{-- Navegación superior y encabezado --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-semibold">Multas</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Control de multas y sanciones</h1>
                <p class="text-xs text-gray-500">Gestión de penalizaciones financieras por mora, extravíos o daños en material bibliográfico.</p>
            </div>

            <div class="flex items-center gap-3">
                @if(Route::has('multas.create'))
                    @can('multas.crear')
                        <a href="{{ route('multas.create') }}" class="inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-xs gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Nueva multa
                        </a>
                    @endcan
                @endif
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

        {{-- Listado principal --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
            <div class="p-4 sm:px-6 sm:py-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Listado de multas</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                        {{ $multas->total() ?? count($multas) }} registros
                    </span>
                    @if(request('buscar'))
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-gray-100 text-gray-800 border border-gray-200">
                            Búsqueda: "{{ request('buscar') }}"
                            <a href="{{ route('multas.index', array_filter(['estado' => request('estado')])) }}" class="hover:text-black ml-0.5" title="Quitar búsqueda">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        </span>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    {{-- Formulario de búsqueda --}}
                    <form action="{{ route('multas.index') }}" method="GET" class="relative flex items-center">
                        @if(request('estado'))
                            <input type="hidden" name="estado" value="{{ request('estado') }}">
                        @endif
                        <div class="relative w-full sm:w-72">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="buscar"
                                value="{{ request('buscar') }}"
                                placeholder="Buscar por socio, doc, libro, #..."
                                class="w-full pl-9 pr-8 py-1.5 text-xs rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-colors"
                            />
                            @if(request('buscar'))
                                <a href="{{ route('multas.index', array_filter(['estado' => request('estado')])) }}" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                    </form>

                    {{-- Filtros de estado --}}
                    <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-xl self-start sm:self-auto">
                        <a href="{{ route('multas.index', array_filter(['buscar' => request('buscar')])) }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ !request('estado') ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                            Todas
                        </a>
                        <a href="{{ route('multas.index', array_filter(['estado' => 'Pendiente', 'buscar' => request('buscar')])) }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('estado') === 'Pendiente' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                            Pendientes
                        </a>
                        <a href="{{ route('multas.index', array_filter(['estado' => 'Pagado', 'buscar' => request('buscar')])) }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('estado') === 'Pagado' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                            Pagadas
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto" role="region" aria-label="Tabla de multas" tabindex="0">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200/80 bg-gray-50/75 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                            <th scope="col" class="py-3 px-4"># Multa</th>
                            <th scope="col" class="py-3 px-4">Socio sancionado</th>
                            <th scope="col" class="py-3 px-4">Libro / Ejemplar</th>
                            <th scope="col" class="py-3 px-4">Motivo</th>
                            <th scope="col" class="py-3 px-4">Monto</th>
                            <th scope="col" class="py-3 px-4">Fecha emisión</th>
                            <th scope="col" class="py-3 px-4">Estado</th>
                            <th scope="col" class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($multas as $m)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="font-mono font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded text-[11px]">
                                        #{{ $m->Codigo_Multa }}
                                    </span>
                                    @if($m->usuario_modificacion)
                                        <p class="text-[10px] text-gray-400 mt-0.5 truncate max-w-[120px]" title="Registrado/Modificado por: {{ $m->usuario_modificacion }} {{ $m->motivo_modificacion ? '· ' . $m->motivo_modificacion : '' }}">
                                            Por: {{ \Illuminate\Support\Str::limit($m->usuario_modificacion, 12) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <p class="font-semibold text-gray-900">{{ $m->detalle->prestamo->socio->Nombres ?? 'Socio no encontrado' }} {{ $m->detalle->prestamo->socio->Apellidos ?? '' }}</p>
                                    <p class="text-[11px] text-gray-500">Doc: {{ $m->detalle->prestamo->socio->Numero_Documento ?? $m->detalle->prestamo->socio->Num_Documento ?? '—' }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-medium text-gray-900">{{ $m->detalle->ejemplar->libro->Titulo ?? '—' }}</p>
                                    <p class="text-[11px] text-gray-500 font-mono">Ejemplar #{{ $m->detalle->Codigo_Ejemplar ?? 'N/A' }}</p>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-800">
                                        {{ $m->Motivo }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap font-mono font-semibold text-gray-900 text-sm">
                                    ${{ number_format($m->Monto, 2) }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-gray-600">
                                    {{ $m->Fecha_Emision->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($m->Estado_Pago === 'Pagado')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                            Pagada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-900 text-white">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('multas.editar')
                                            @if ($m->Estado_Pago === 'Pendiente')
                                                <form action="{{ route('multas.pagar', $m) }}" method="POST" onsubmit="return confirm('¿Confirmas que el socio ha realizado el pago completo de esta multa?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-gray-900 hover:bg-black rounded-lg transition-colors shadow-xs">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        Registrar cobro
                                                    </button>
                                                </form>
                                            @endif
                                            @if (Route::has('multas.edit'))
                                                <a href="{{ route('multas.edit', $m) }}" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Editar multa">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </a>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 px-4 text-center">
                                    <div class="max-w-xs mx-auto text-center space-y-2">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-gray-400">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <p class="text-xs font-medium text-gray-900">No hay multas que coincidan</p>
                                        <p class="text-xs text-gray-500">No se encontraron sanciones registradas en el criterio de búsqueda seleccionado.</p>
                                        @if(request('buscar') || request('estado'))
                                            <div class="pt-2">
                                                <a href="{{ route('multas.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                                                    Limpiar filtros y ver todas
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($multas, 'links') && $multas->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $multas->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
