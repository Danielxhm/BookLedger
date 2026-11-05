<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-semibold">Libros</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Catálogo bibliográfico</h1>
                <p class="text-xs text-gray-500">Gestión de títulos, metadatos editoriales, autorías y asignación de portadas.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('libros.crear')
                    <a href="{{ route('libros.create') }}" class="inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-xs gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nuevo libro
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
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Listado general</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                        {{ $libros->total() ?? count($libros) }} libros
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto" role="region" aria-label="Tabla de libros del catálogo" tabindex="0">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200/80 bg-gray-50/75 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                            <th scope="col" class="py-3 px-4 w-14">Portada</th>
                            <th scope="col" class="py-3 px-4">ISBN</th>
                            <th scope="col" class="py-3 px-4">Título y subtítulo</th>
                            <th scope="col" class="py-3 px-4">Categoría</th>
                            <th scope="col" class="py-3 px-4">Editorial</th>
                            <th scope="col" class="py-3 px-4">Autor(es)</th>
                            <th scope="col" class="py-3 px-4">Ficha técnica</th>
                            <th scope="col" class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($libros as $l)
                            @php
                                $categoria = $categorias->firstWhere('Codigo_Categoria', $l->Codigo_Categoria);
                                $editorial = $editoriales->firstWhere('Codigo_Editorial', $l->Codigo_Editorial);
                                $relacionesLibro = $librosAutor->where('Codigo_ISBN', $l->Codigo_ISBN);
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-3 px-4">
                                    @if($l->portada)
                                        <img src="{{ asset('storage/' . $l->portada) }}" alt="{{ $l->Titulo }}" class="w-10 h-14 object-cover rounded border border-gray-200 shadow-xs">
                                    @else
                                        <div class="w-10 h-14 bg-gray-50 border border-dashed border-gray-300 rounded flex flex-col items-center justify-center text-[9px] text-gray-400 text-center leading-tight">
                                            <svg class="w-3.5 h-3.5 mb-0.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            S/P
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-mono font-medium text-gray-800 bg-gray-100 px-2 py-1 rounded text-[11px] whitespace-nowrap">
                                        {{ $l->Codigo_ISBN }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-semibold text-gray-900 text-xs">{{ $l->Titulo }}</p>
                                    @if($l->Subtitulo)
                                        <p class="text-[11px] text-gray-500 line-clamp-1">{{ $l->Subtitulo }}</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $categoria->NombreCategoria ?? 'Sin asignar' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                    {{ $editorial->NombreEditorial ?? '—' }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="space-y-1">
                                        @forelse ($relacionesLibro as $rel)
                                            @php $autor = $autores->firstWhere('Codigo_Autor', $rel->Codigo_Autor); @endphp
                                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                                <span class="text-xs text-gray-800 font-medium">{{ $autor->Nombres ?? 'N/A' }}</span>
                                                <span class="text-[10px] text-gray-500 bg-gray-50 px-1.5 py-0.2 rounded border border-gray-200">{{ $rel->Tipo_Autoria }}</span>
                                            </div>
                                        @empty
                                            <span class="text-gray-400 italic text-[11px]">Sin autores</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-gray-500 text-[11px]">
                                    <div>Año: <span class="font-medium text-gray-800">{{ $l->Anio_Publicacion ?? '—' }}</span></div>
                                    <div>Págs: <span class="font-medium text-gray-800">{{ $l->N_paginas ?? '—' }}</span> · {{ $l->Idioma ?? 'Español' }}</div>
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @can('libros.editar')
                                            <a href="{{ route('libros.edit', $l) }}" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Editar libro">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </a>
                                        @endcan
                                        @can('libros.eliminar')
                                            <form action="{{ route('libros.destroy', $l) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este libro? Se eliminarán también las copias físicas asociadas.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Eliminar libro">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 px-4 text-center">
                                    <div class="max-w-xs mx-auto text-center space-y-2">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-gray-400">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        </div>
                                        <p class="text-xs font-medium text-gray-900">No hay libros registrados</p>
                                        <p class="text-xs text-gray-500">Agrega el primer libro al catálogo para comenzar a gestionar ejemplares y préstamos.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($libros, 'links') && $libros->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $libros->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
