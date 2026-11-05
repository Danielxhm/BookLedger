<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        {{-- Navegación superior y encabezado --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-semibold">Préstamos y Reservas</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Circulación, préstamos y reservas</h1>
                <p class="text-xs text-gray-500">Control de material prestado a socios, plazos de devolución y atención de solicitudes de reserva.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('prestamos.crear')
                    <a href="{{ route('prestamos.create') }}" class="inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-xs gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nuevo préstamo
                    </a>
                @endcan
            </div>
        </div>

        {{-- Mensaje de estado --}}
        @if (session('status'))
            <div class="p-4 rounded-xl bg-white border border-gray-200 text-xs font-medium text-gray-800 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <div class="w-5 h-5 rounded-full bg-gray-900 text-white flex items-center justify-center shrink-0">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        {{-- Alertas de validación --}}
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-white border-l-4 border-l-gray-900 border-y border-r border-gray-200 text-xs font-medium text-gray-900 space-y-1.5 shadow-xs">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pestañas de navegación --}}
        <div class="flex items-center gap-6 border-b border-gray-200">
            <a href="{{ route('prestamos.index', ['tab' => 'prestamos']) }}"
                class="inline-flex items-center gap-2 pb-3 px-0.5 border-b-2 text-xs transition-colors {{ ($tab ?? 'prestamos') !== 'reservas' ? 'border-gray-900 text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-900' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Historial de préstamos</span>
            </a>

            <a href="{{ route('prestamos.index', ['tab' => 'reservas']) }}"
                class="inline-flex items-center gap-2 pb-3 px-0.5 border-b-2 text-xs transition-colors {{ ($tab ?? 'prestamos') === 'reservas' ? 'border-gray-900 text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-900' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Solicitudes de reserva</span>
                @if(($reservasPendientesCount ?? 0) > 0)
                    <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[10px] font-mono font-bold bg-gray-900 text-white">
                        {{ $reservasPendientesCount }}
                    </span>
                @endif
            </a>
        </div>

        {{-- Aviso de reservas pendientes --}}
        @if(($tab ?? 'prestamos') !== 'reservas' && ($reservasPendientesCount ?? 0) > 0)
            <div class="p-4 rounded-2xl bg-white border border-gray-200/90 text-gray-900 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-xs">
                <div class="flex items-center gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-gray-900 text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-xs sm:text-sm">
                            Hay {{ $reservasPendientesCount }} {{ $reservasPendientesCount === 1 ? 'reserva de libro pendiente' : 'reservas de libros pendientes' }} de atención
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Los socios han apartado material desde el catálogo. Puedes despacharlo y registrar la entrega en mostrador.
                        </p>
                    </div>
                </div>
                <a href="{{ route('prestamos.index', ['tab' => 'reservas', 'estado' => 'Pendiente']) }}"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-gray-900 hover:bg-black text-white transition-colors shadow-xs shrink-0 self-start sm:self-auto">
                    Revisar reservas
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        @endif

        {{-- Pestaña: Préstamos --}}
        @if(($tab ?? 'prestamos') !== 'reservas')
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="p-4 sm:px-6 sm:py-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Historial de circulación</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                            {{ $prestamos->total() ?? count($prestamos) }} registros
                        </span>
                        @if(request('buscar'))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                Búsqueda: "{{ request('buscar') }}"
                                <a href="{{ route('prestamos.index', array_filter(['tab' => 'prestamos', 'estado' => request('estado')])) }}" class="hover:text-black ml-0.5" title="Quitar búsqueda">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        {{-- Formulario de búsqueda --}}
                        <form action="{{ route('prestamos.index') }}" method="GET" class="relative flex items-center">
                            <input type="hidden" name="tab" value="prestamos">
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
                                    <a href="{{ route('prestamos.index', array_filter(['tab' => 'prestamos', 'estado' => request('estado')])) }}" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </a>
                                @endif
                            </div>
                        </form>

                        {{-- Filtros de estado --}}
                        <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-xl self-start sm:self-auto">
                            <a href="{{ route('prestamos.index', array_filter(['tab' => 'prestamos', 'buscar' => request('buscar')])) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ !request('estado') ? 'bg-white text-gray-900 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Todos
                            </a>
                            <a href="{{ route('prestamos.index', array_filter(['tab' => 'prestamos', 'estado' => 'Activo', 'buscar' => request('buscar')])) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('estado') === 'Activo' ? 'bg-white text-gray-900 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Activos
                            </a>
                            <a href="{{ route('prestamos.index', array_filter(['tab' => 'prestamos', 'estado' => 'Vencido', 'buscar' => request('buscar')])) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('estado') === 'Vencido' ? 'bg-white text-gray-900 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Vencidos
                            </a>
                            <a href="{{ route('prestamos.index', array_filter(['tab' => 'prestamos', 'estado' => 'Concluido', 'buscar' => request('buscar')])) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('estado') === 'Concluido' ? 'bg-white text-gray-900 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Concluidos
                            </a>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto" role="region" aria-label="Tabla de préstamos" tabindex="0">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200/80 bg-gray-50/75 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                                <th scope="col" class="py-3 px-4"># Préstamo</th>
                                <th scope="col" class="py-3 px-4">Socio prestatario</th>
                                <th scope="col" class="py-3 px-4">Libros / Ejemplares</th>
                                <th scope="col" class="py-3 px-4">Fecha límite</th>
                                <th scope="col" class="py-3 px-4">Estado</th>
                                <th scope="col" class="py-3 px-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($prestamos as $p)
                                @php
                                    $badgeStyles = match($p->EstadoPrestamo) {
                                        'Activo' => 'bg-gray-100 text-gray-900 border-gray-300 font-semibold',
                                        'Vencido' => 'bg-gray-900 text-white font-semibold',
                                        'Concluido' => 'bg-gray-100 text-gray-600 border-gray-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200'
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="font-mono font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded text-[11px]">
                                            #{{ $p->Codigo_Prestamo }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <p class="font-semibold text-gray-900">{{ $p->socio->Nombres }} {{ $p->socio->Apellidos }}</p>
                                        <p class="text-[11px] text-gray-500">Doc: {{ $p->socio->Numero_Documento ?? $p->socio->Num_Documento }}</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="space-y-1">
                                            @foreach($p->detalles as $det)
                                                <div class="flex items-center gap-1.5">
                                                    <span class="font-medium text-gray-900">{{ $det->ejemplar->libro->Titulo ?? '—' }}</span>
                                                    <span class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded font-mono">#{{ $det->Codigo_Ejemplar }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-800">{{ $p->FechaLimite_Devolucion->format('d/m/Y') }}</span>
                                        @if($p->EstadoPrestamo === 'Vencido')
                                            <span class="block text-[10px] text-red-600 font-semibold">Excedió fecha límite</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] border {{ $badgeStyles }}">
                                            {{ $p->EstadoPrestamo }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">
                                        <a href="{{ route('prestamos.show', $p) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detalle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 px-4 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-2">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-gray-400">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <p class="text-xs font-medium text-gray-900">No hay préstamos que coincidan</p>
                                            <p class="text-xs text-gray-500">No se encontraron registros de circulación con los filtros seleccionados.</p>
                                            @if(request('buscar') || request('estado'))
                                                <div class="pt-2">
                                                    <a href="{{ route('prestamos.index', ['tab' => 'prestamos']) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                                                        Limpiar filtros y ver todos
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

                @if($prestamos && method_exists($prestamos, 'links') && $prestamos->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $prestamos->links() }}
                    </div>
                @endif
            </div>
        @else
            {{-- Pestaña: Solicitudes de reserva --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                <div class="p-4 sm:px-6 sm:py-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Solicitudes de reserva</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                            {{ $reservas->total() ?? count($reservas) }} registros
                        </span>
                        @if(request('buscar'))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                Búsqueda: "{{ request('buscar') }}"
                                <a href="{{ route('prestamos.index', array_filter(['tab' => 'reservas', 'estado' => request('estado')])) }}" class="hover:text-black ml-0.5" title="Quitar búsqueda">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        {{-- Formulario de búsqueda --}}
                        <form action="{{ route('prestamos.index') }}" method="GET" class="relative flex items-center">
                            <input type="hidden" name="tab" value="reservas">
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
                                    placeholder="Buscar por socio, doc, libro, #reserva..."
                                    class="w-full pl-9 pr-8 py-1.5 text-xs rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-colors"
                                />
                                @if(request('buscar'))
                                    <a href="{{ route('prestamos.index', array_filter(['tab' => 'reservas', 'estado' => request('estado')])) }}" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </a>
                                @endif
                            </div>
                        </form>

                        {{-- Filtros de estado --}}
                        <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-xl self-start sm:self-auto">
                            <a href="{{ route('prestamos.index', array_filter(['tab' => 'reservas', 'buscar' => request('buscar')])) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ !request('estado') ? 'bg-white text-gray-900 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Todas
                            </a>
                            <a href="{{ route('prestamos.index', array_filter(['tab' => 'reservas', 'estado' => 'Pendiente', 'buscar' => request('buscar')])) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('estado') === 'Pendiente' ? 'bg-white text-gray-900 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Pendientes
                            </a>
                            <a href="{{ route('prestamos.index', array_filter(['tab' => 'reservas', 'estado' => 'Atendida', 'buscar' => request('buscar')])) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('estado') === 'Atendida' ? 'bg-white text-gray-900 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Atendidas
                            </a>
                            <a href="{{ route('prestamos.index', array_filter(['tab' => 'reservas', 'estado' => 'Cancelada', 'buscar' => request('buscar')])) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('estado') === 'Cancelada' ? 'bg-white text-gray-900 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                Canceladas
                            </a>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto" role="region" aria-label="Tabla de reservas" tabindex="0">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200/80 bg-gray-50/75 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                                <th scope="col" class="py-3 px-4"># Reserva</th>
                                <th scope="col" class="py-3 px-4">Socio solicitante</th>
                                <th scope="col" class="py-3 px-4">Libro solicitado</th>
                                <th scope="col" class="py-3 px-4">Disponibilidad en estante</th>
                                <th scope="col" class="py-3 px-4">Fecha solicitud</th>
                                <th scope="col" class="py-3 px-4">Estado</th>
                                <th scope="col" class="py-3 px-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($reservas as $r)
                                @php
                                    $disponiblesCount = $r->libro?->ejemplares?->count() ?? 0;
                                    $reservaBadge = match($r->EstadoReserva) {
                                        'Pendiente' => 'bg-gray-900 text-white font-semibold',
                                        'Atendida' => 'bg-gray-100 text-gray-700 border-gray-200 font-medium',
                                        'Cancelada' => 'bg-gray-100 text-gray-400 border-gray-200 font-medium',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200'
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="font-mono font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded text-[11px]">
                                            #{{ $r->Codigo_Reserva }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $r->socio?->Nombres }} {{ $r->socio?->Apellidos }}</p>
                                                <p class="text-[11px] text-gray-500">Doc: {{ $r->socio?->Numero_Documento ?? $r->socio?->Num_Documento }}</p>
                                            </div>
                                            @if($r->socio?->EstadoSocio === 'Sancionado')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-red-800 border border-red-200">
                                                    Sancionado
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $r->libro?->Titulo ?? '—' }}</p>
                                            <p class="text-[11px] text-gray-500 font-mono">ISBN: {{ $r->Codigo_ISBN }}</p>
                                            @if($r->libro?->autores && $r->libro->autores->count() > 0)
                                                <p class="text-[11px] text-gray-500">
                                                    {{ $r->libro->autores->pluck('Nombre_Completo')->filter()->join(', ') ?: $r->libro->autores->pluck('Nombres')->join(', ') }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @if($disponiblesCount > 0)
                                            <div>
                                                <span class="font-mono font-semibold text-gray-900 text-xs">{{ $disponiblesCount }} {{ $disponiblesCount === 1 ? 'copia' : 'copias' }}</span>
                                                <span class="text-[11px] text-gray-500 ml-1">en estantería</span>
                                            </div>
                                        @else
                                            <div>
                                                <span class="font-mono font-medium text-gray-400 text-xs">0 copias</span>
                                                <span class="text-[11px] text-gray-400 ml-1">· en préstamo</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-800">{{ $r->FechaReserva }}</span>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] border border-transparent {{ $reservaBadge }}">
                                            {{ $r->EstadoReserva }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2">
                                            @if($r->EstadoReserva === 'Pendiente')
                                                @if($disponiblesCount > 0)
                                                    @can('prestamos.crear')
                                                        {{-- Entrega directa de ejemplar --}}
                                                        <form action="{{ route('prestamos.reservas.entregar', $r) }}" method="POST" class="inline"
                                                            onsubmit="return confirm('¿Confirmas la entrega y despacho de este libro al socio {{ addslashes($r->socio?->Nombres . ' ' . $r->socio?->Apellidos) }}?')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-gray-900 hover:bg-black rounded-lg transition-colors shadow-xs" title="Prestar primer ejemplar disponible y marcar atendida">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                Entregar libro
                                                            </button>
                                                        </form>

                                                        {{-- Selección de ejemplar en mostrador --}}
                                                        <a href="{{ route('prestamos.create', ['socio' => $r->Codigo_Socio, 'isbn' => $r->Codigo_ISBN, 'reserva' => $r->Codigo_Reserva]) }}"
                                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg transition-colors shadow-xs"
                                                            title="Elegir copia física específica en el mostrador">
                                                            Mostrador
                                                        </a>
                                                    @endcan
                                                @else
                                                    <span class="text-[11px] text-gray-500 font-medium px-2 py-1 bg-gray-100 rounded-md border border-gray-200">
                                                        En espera de devolución
                                                    </span>
                                                @endif

                                                @can('prestamos.editar')
                                                    {{-- Cancelación de reserva --}}
                                                    <form action="{{ route('prestamos.reservas.cancelar', $r) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('¿Estás seguro de cancelar la reserva #{{ $r->Codigo_Reserva }}?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex items-center px-2 py-1.5 text-xs font-medium text-gray-400 hover:text-red-700 transition-colors" title="Cancelar esta reserva">
                                                            Cancelar
                                                        </button>
                                                    </form>
                                                @endcan
                                            @elseif($r->EstadoReserva === 'Atendida')
                                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-700">
                                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Libro entregado
                                                </span>
                                            @else
                                                <span class="text-[11px] text-gray-400 font-medium">Cancelada</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 px-4 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-2">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-gray-400">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <p class="text-xs font-medium text-gray-900">No hay reservas que coincidan</p>
                                            <p class="text-xs text-gray-500">No se encontraron solicitudes de reserva con los filtros seleccionados.</p>
                                            @if(request('buscar') || request('estado'))
                                                <div class="pt-2">
                                                    <a href="{{ route('prestamos.index', ['tab' => 'reservas']) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
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

                @if($reservas && method_exists($reservas, 'links') && $reservas->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $reservas->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
