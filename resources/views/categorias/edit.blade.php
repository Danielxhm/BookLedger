<x-app-layout>
    <div class="max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Encabezado con botón Volver -->
        <div class="mb-6">
            <a href="{{ route('categorias.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-900 transition mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Volver al listado de categorías</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                Editar Categoría: {{ $categoria->NombreCategoria }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Modifica el nombre, jerarquía o descripción de la categoría.
            </p>
        </div>

        <!-- Tarjeta de Formulario -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs p-6 sm:p-8">
            <form method="POST" action="{{ route('categorias.update', $categoria) }}" onsubmit="bloquearBoton(this)">
                @csrf
                @method('PUT')

                @include('categorias._form')

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('categorias.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm font-semibold text-gray-700 shadow-2xs transition">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-sm font-semibold text-white shadow-sm transition active:scale-[0.98]">
                        Actualizar categoría
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
