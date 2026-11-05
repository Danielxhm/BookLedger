<div>
    @if($isOpen && $categoria)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900 bg-opacity-50">
        <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-xl">
            
            <!-- Encabezado -->
            <div class="flex items-center justify-between pb-3 border-b">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                        ID: {{ $categoria->Codigo_Categoria }}
                    </span>
                    <h3 class="text-lg font-bold text-gray-900">Detalles de la Categoría</h3>
                </div>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>

            <!-- Contenido / Vista (Show) -->
            <div class="mt-4 space-y-4">
                
                <!-- Nombre de Categoría -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre de Categoría</label>
                    <p class="mt-1 text-base text-gray-900 font-medium">{{ $categoria->NombreCategoria }}</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Descripción</label>
                    <p class="mt-1 text-sm text-gray-700 bg-gray-50 p-2.5 rounded-md border">
                        {{ $categoria->Descripcion ?: 'Sin descripción' }}
                    </p>
                </div>

                <!-- Categoría Padre -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría Padre</label>
                    <p class="mt-1 text-sm text-gray-800">
                        @if($categoria->categoriaPadre)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-800">
                                {{ $categoria->categoriaPadre->NombreCategoria }} (ID: {{ $categoria->Codigo_categoriaPadre }})
                            </span>
                        @else
                            <span class="text-gray-400 italic">Ninguna (Categoría Principal)</span>
                        @endif
                    </p>
                </div>

                <hr class="my-3 border-gray-200">

                <!-- Auditoría / Modificación -->
                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-lg text-xs">
                    <div>
                        <span class="font-semibold text-gray-600 block">Última Modificación:</span>
                        <span class="text-gray-800">{{ $categoria->fecha_modificacion ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-600 block">Usuario Modificación:</span>
                        <span class="text-gray-800">{{ $categoria->usuario_modificacion ?? 'N/A' }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="font-semibold text-gray-600 block">Motivo de Modificación:</span>
                        <span class="text-gray-800">{{ $categoria->motivo_modificacion ?? 'Sin registro' }}</span>
                    </div>
                </div>

            </div>

            <!-- Botón de cierre -->
            <div class="flex justify-end pt-4 mt-4 border-t">
                <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm text-white bg-gray-600 rounded-md hover:bg-gray-700">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
    @endif
</div>