<x-app-layout>
    <div x-data="{ open: false, categoria: {} }" class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Encabezado de Página -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700 transition">Panel</a>
                    <span>/</span>
                    <span class="text-gray-900 font-bold">Categorías</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                    Gestión de Categorías
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Clasificación temática del catálogo bibliográfico y jerarquías de géneros.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                @can('categorias.crear')
                    <a href="{{ route('categorias.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold shadow-sm transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Nueva categoría</span>
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
                    <span class="text-sm font-semibold text-gray-900">Listado de Categorías</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-200/70 text-gray-700">
                        {{ $categorias->total() ?? $categorias->count() }} registradas
                    </span>
                </div>
            </div>

            <!-- Tabla Accesible -->
            <div tabindex="0" role="region" aria-label="Tabla de categorías" class="overflow-x-auto focus:outline-none">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#f3f4f6]/80 text-[11px] font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">
                        <tr>
                            <th scope="col" class="py-3.5 px-6">Código</th>
                            <th scope="col" class="py-3.5 px-6">Nombre de Categoría</th>
                            <th scope="col" class="py-3.5 px-6">Categoría Padre</th>
                            <th scope="col" class="py-3.5 px-6">Descripción</th>
                            <th scope="col" class="py-3.5 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($categorias as $c)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-6 text-gray-500 font-mono text-xs whitespace-nowrap">
                                    #{{ $c->Codigo_Categoria }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-900 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-2 h-2 rounded-full bg-gray-900"></div>
                                        <span>{{ $c->NombreCategoria }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    @if ($c->categoriaPadre)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                            {{ $c->categoriaPadre->NombreCategoria }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">Principal</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-gray-500 max-w-xs truncate">
                                    {{ $c->Descripcion ?: 'Sin descripción' }}
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <!-- Botón Ver Detalles (Modal Alpine) -->
                                        <button type="button" 
                                                @click="categoria = {
                                                    codigo: '{{ $c->Codigo_Categoria }}',
                                                    nombre: '{{ addslashes($c->NombreCategoria) }}',
                                                    descripcion: '{{ addslashes($c->Descripcion ?? 'Sin descripción') }}',
                                                    padre: '{{ optional($c->categoriaPadre)->NombreCategoria ?? 'Ninguna (Categoría Principal)' }}',
                                                    fecha: '{{ $c->fecha_modificacion ?? 'N/A' }}',
                                                    usuario: '{{ $c->usuario_modificacion ?? 'N/A' }}',
                                                    motivo: '{{ addslashes($c->motivo_modificacion ?? 'Sin motivo registrado') }}'
                                                }; open = true;"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50 text-xs font-semibold text-gray-700 shadow-2xs transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            <span>Detalles</span>
                                        </button>

                                        @can('categorias.editar')
                                            <a href="{{ route('categorias.edit', $c) }}" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50 text-xs font-semibold text-gray-700 shadow-2xs transition">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>
                                                <span>Editar</span>
                                            </a>
                                        @endcan

                                        @can('categorias.eliminar')
                                            <form action="{{ route('categorias.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
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
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 px-6 text-center text-gray-500">
                                    <div class="max-w-sm mx-auto flex flex-col items-center">
                                        <p class="font-semibold text-gray-900">No hay categorías registradas</p>
                                        <p class="text-xs text-gray-400 mt-1">Crea categorías para organizar los libros por género o materia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categorias->hasPages())
                <div class="px-6 py-4 bg-[#f9fafb] border-t border-gray-200/80">
                    {{ $categorias->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL TAILWIND + ALPINE.JS (Estilo Moderno) -->
        <div x-show="open" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-xs flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 max-w-lg w-full p-6" @click.away="open = false">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Detalles de Categoría</span>
                        <h3 class="text-lg font-bold text-gray-900" x-text="categoria.nombre"></h3>
                    </div>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 font-bold p-1 rounded-lg hover:bg-gray-100 transition cursor-pointer">&times;</button>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="block text-xs font-medium text-gray-500">Código</span>
                            <p x-text="categoria.codigo" class="text-gray-900 font-bold font-mono mt-0.5"></p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="block text-xs font-medium text-gray-500">Categoría Padre</span>
                            <p x-text="categoria.padre" class="text-gray-900 font-semibold mt-0.5 truncate"></p>
                        </div>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="block text-xs font-medium text-gray-500">Descripción</span>
                        <p x-text="categoria.descripcion" class="text-gray-700 mt-1 leading-relaxed"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-xs text-gray-500 border-t border-gray-100 pt-3">
                        <div>
                            <span class="font-medium text-gray-400">Última modificación:</span>
                            <p x-text="categoria.fecha" class="text-gray-700 font-semibold"></p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-400">Modificado por:</span>
                            <p x-text="categoria.usuario" class="text-gray-700 font-semibold"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button @click="open = false" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition cursor-pointer">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
