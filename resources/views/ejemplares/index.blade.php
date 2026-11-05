<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Encabezado de Página -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700 transition">Panel</a>
                    <span>/</span>
                    <span class="text-gray-900 font-bold">Ejemplares</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                    Control de Ejemplares Físicos
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Inventario de copias físicas disponibles, ubicación en estanterías y préstamos en curso.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                @can('ejemplares.crear')
                    <a href="{{ route('ejemplares.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold shadow-sm transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Nuevo ejemplar</span>
                    </a>
                @endcan
            </div>
        </div>

        @if (session('status'))
            <div class="mb-6 flex items-center justify-between gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-sm font-medium shadow-2xs" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Card Contenedora con Tabla Responsive -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <!-- Barra superior -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 bg-[#f9fafb] border-b border-gray-200/80">
                <div class="flex items-center gap-2.5">
                    <span class="text-sm font-semibold text-gray-900">Listado de Ejemplares</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-200/70 text-gray-700">
                        {{ $ejemplares->total() ?? $ejemplares->count() }} copias registradas
                    </span>
                </div>
            </div>

            <!-- Tabla Accesible -->
            <div tabindex="0" role="region" aria-label="Tabla de ejemplares físicos" class="overflow-x-auto focus:outline-none">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#f3f4f6]/80 text-[11px] font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">
                        <tr>
                            <th scope="col" class="py-3.5 px-6">Código Copia</th>
                            <th scope="col" class="py-3.5 px-6">Libro / Título</th>
                            <th scope="col" class="py-3.5 px-6">Ubicación (Estante)</th>
                            <th scope="col" class="py-3.5 px-6 text-center">Estado</th>
                            <th scope="col" class="py-3.5 px-6">Préstamo Actual</th>
                            <th scope="col" class="py-3.5 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($ejemplares as $ej)
                            @php
                                $color = match($ej->Estado_Ejemplar) {
                                    'Disponible' => 'bg-emerald-50 text-emerald-700 border-emerald-200/70',
                                    'Prestado' => 'bg-sky-50 text-sky-700 border-sky-200/70',
                                    'Mantenimiento' => 'bg-amber-50 text-amber-700 border-amber-200/70',
                                    'Perdido' => 'bg-rose-50 text-rose-700 border-rose-200/70',
                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                };
                                $prestamo = $ej->detalleActivo?->prestamo;
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-6 text-gray-900 font-mono font-bold text-xs whitespace-nowrap">
                                    #{{ $ej->Codigo_Ejemplar }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-900 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-9 rounded bg-gray-100 border border-gray-200 flex items-center justify-center text-[10px] text-gray-400 shrink-0">
                                            📖
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 leading-tight">{{ $ej->libro->Titulo ?? 'Sin título asociado' }}</p>
                                            <p class="text-xs text-gray-400 font-mono font-normal">ISBN: {{ $ej->Codigo_ISBN }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-mono font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                        📍 {{ $ej->Ubicacion_Estante }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $color }}">
                                        {{ $ej->Estado_Ejemplar }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-gray-600 whitespace-nowrap">
                                    @if ($prestamo)
                                        <div>
                                            <a href="{{ route('prestamos.show', $prestamo) }}" class="font-semibold text-gray-900 hover:text-gray-600 transition flex items-center gap-1">
                                                <span>{{ $prestamo->socio->Nombres }} {{ $prestamo->socio->Apellidos }}</span>
                                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                                </svg>
                                            </a>
                                            <span class="text-xs text-gray-400">Vence: {{ $prestamo->FechaLimite_Devolucion->format('d/m/Y') }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">— En estante</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        @can('ejemplares.editar')
                                            <a href="{{ route('ejemplares.edit', $ej) }}" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50 text-xs font-semibold text-gray-700 shadow-2xs transition">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>
                                                <span>Editar</span>
                                            </a>
                                        @endcan

                                        @can('ejemplares.eliminar')
                                            @if ($ej->Estado_Ejemplar !== 'Prestado')
                                                <form action="{{ route('ejemplares.destroy', $ej) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este ejemplar?')">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-rose-200 hover:border-rose-300 bg-white hover:bg-rose-50 text-xs font-semibold text-rose-600 shadow-2xs transition cursor-pointer">
                                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                        <span>Eliminar</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-6 text-center text-gray-500">
                                    <div class="max-w-sm mx-auto flex flex-col items-center">
                                        <p class="font-semibold text-gray-900">No hay ejemplares registrados</p>
                                        <p class="text-xs text-gray-400 mt-1">Registra copias físicas para que puedan ser prestadas a los socios.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($ejemplares->hasPages())
                <div class="px-6 py-4 bg-[#f9fafb] border-t border-gray-200/80">
                    {{ $ejemplares->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
