@php
    $l = $libro ?? null;
    $relacionesExistentes = collect($librosAutor ?? [])->pluck('Tipo_Autoria', 'Codigo_Autor');
    $autoresSeleccionados = collect(old('autores', $relacionesExistentes->keys()->all()));
    $tiposPorAutor = old('tipo_autoria', $relacionesExistentes->all());
    $anioActual = now()->year;
@endphp

<div class="space-y-6">
    {{-- Portada e ISBN en fila --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        <div class="md:col-span-1">
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Portada del libro
            </label>
            <div class="flex flex-col items-center p-4 border border-dashed border-gray-300 rounded-xl bg-gray-50/50 hover:bg-gray-50 transition-colors">
                @if(isset($l) && $l->portada)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $l->portada) }}" alt="Portada actual" class="w-20 h-28 object-cover rounded-lg border border-gray-200 shadow-xs">
                    </div>
                    <span class="text-[11px] text-gray-500 text-center mb-2">Portada guardada. Selecciona otra para sustituirla.</span>
                @else
                    <div class="w-16 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 mb-3 border border-gray-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                <input type="file" name="portada" id="portada" accept="image/*"
                    class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-black cursor-pointer">
            </div>
            @error('portada')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="md:col-span-2 space-y-4">
            <div>
                <label for="Codigo_ISBN" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                    Código ISBN <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="Codigo_ISBN"
                    id="Codigo_ISBN"
                    inputmode="numeric"
                    maxlength="13"
                    placeholder="13 dígitos numéricos (ej. 9789584282541)"
                    value="{{ old('Codigo_ISBN', $l->Codigo_ISBN ?? '') }}"
                    @if($l) readonly @endif
                    required
                    class="block w-full min-h-[44px] px-3.5 py-2.5 font-mono text-sm bg-white border @error('Codigo_ISBN') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs @if($l) bg-gray-100 text-gray-600 cursor-not-allowed @endif"
                >
                @if($l)
                    <p class="mt-1.5 text-xs text-gray-500">El identificador ISBN es inmutable una vez registrado el título.</p>
                @else
                    <p class="mt-1.5 text-xs text-gray-500">Ingresa exactamente 13 dígitos numéricos sin guiones ni espacios.</p>
                @endif
                @error('Codigo_ISBN')
                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="Titulo" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Título de la obra <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="Titulo" name="Titulo" value="{{ old('Titulo', $l->Titulo ?? '') }}" required placeholder="Título completo"
                        class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Titulo') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                    @error('Titulo')
                        <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="Subtitulo" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Subtítulo <span class="text-gray-400 font-normal lowercase">(opcional)</span>
                    </label>
                    <input type="text" id="Subtitulo" name="Subtitulo" value="{{ old('Subtitulo', $l->Subtitulo ?? '') }}" placeholder="Subtítulo o descripción breve"
                        class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Subtitulo') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                    @error('Subtitulo')
                        <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Clasificación: Categoría y Editorial --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <label for="Codigo_Categoria" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Categoría temática <span class="text-rose-500">*</span>
            </label>
            <select id="Codigo_Categoria" name="Codigo_Categoria" required
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Codigo_Categoria') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                <option value="">-- Seleccionar categoría --</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->Codigo_Categoria }}" {{ old('Codigo_Categoria', $l->Codigo_Categoria ?? '') == $categoria->Codigo_Categoria ? 'selected' : '' }}>
                        {{ $categoria->NombreCategoria }}
                    </option>
                @endforeach
            </select>
            @error('Codigo_Categoria')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="Codigo_Editorial" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Editorial <span class="text-rose-500">*</span>
            </label>
            <select id="Codigo_Editorial" name="Codigo_Editorial" required
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Codigo_Editorial') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                <option value="">-- Seleccionar editorial --</option>
                @foreach ($editoriales as $editorial)
                    <option value="{{ $editorial->Codigo_Editorial }}" @selected(old('Codigo_Editorial', $l->Codigo_Editorial ?? '') == $editorial->Codigo_Editorial)>
                        {{ $editorial->NombreEditorial }}
                    </option>
                @endforeach
            </select>
            @error('Codigo_Editorial')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    {{-- Ficha técnica: Año, Páginas, Idioma --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div>
            <label for="Anio_Publicacion" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Año de publicación <span class="text-rose-500">*</span>
            </label>
            <select id="Anio_Publicacion" name="Anio_Publicacion" required
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Anio_Publicacion') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
                <option value="">Seleccione un año</option>
                @for ($anio = $anioActual; $anio >= 1450; $anio--)
                    <option value="{{ $anio }}" {{ (string) old('Anio_Publicacion', $l->Anio_Publicacion ?? '') === (string) $anio ? 'selected' : '' }}>
                        {{ $anio }}
                    </option>
                @endfor
            </select>
            @error('Anio_Publicacion')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="N_paginas" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Número de páginas <span class="text-rose-500">*</span>
            </label>
            <input type="number" id="N_paginas" name="N_paginas" min="1" value="{{ old('N_paginas', $l->N_paginas ?? '') }}" required placeholder="Ej. 340"
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('N_paginas') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
            @error('N_paginas')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="Idioma" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                Idioma <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="Idioma" name="Idioma" value="{{ old('Idioma', $l->Idioma ?? 'Español') }}" required placeholder="Ej. Español, Inglés"
                class="block w-full min-h-[44px] px-3.5 py-2.5 bg-white border @error('Idioma') border-rose-300 ring-rose-200 text-rose-900 @else border-gray-300 text-gray-900 @enderror rounded-xl text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all shadow-xs">
            @error('Idioma')
                <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    {{-- Selección de Autores --}}
    <div class="pt-4 border-t border-gray-100">
        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-2">
            Autores vinculados y tipo de autoría <span class="text-rose-500">*</span>
        </label>

        <div class="relative" id="autores-selector">
            <button type="button" id="autores-toggle"
                class="w-full flex justify-between items-center min-h-[44px] px-3.5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900 shadow-xs">
                <span id="autores-resumen" class="truncate font-medium">Seleccione autores</span>
                <svg class="w-4 h-4 text-gray-400 shrink-0 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="autores-panel" class="hidden absolute z-30 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                <div class="p-2 border-b border-gray-100 bg-gray-50/50">
                    <input type="text" id="autores-buscador" placeholder="Filtrar autores por nombre..."
                        class="w-full text-xs rounded-lg border-gray-200 px-3 py-2 focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                </div>
                <ul class="max-h-56 overflow-y-auto divide-y divide-gray-100 p-1">
                    @forelse ($autores as $autor)
                        @php $marcado = $autoresSeleccionados->contains((string) $autor->Codigo_Autor); @endphp
                        <li class="autor-item px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors" data-nombre="{{ \Illuminate\Support\Str::lower($autor->Nombres) }}">
                            <label class="inline-flex items-center gap-2.5 w-full cursor-pointer text-xs font-medium text-gray-800">
                                <input
                                    type="checkbox"
                                    name="autores[]"
                                    value="{{ $autor->Codigo_Autor }}"
                                    class="autor-checkbox rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                    data-autor="{{ $autor->Codigo_Autor }}"
                                    data-nombre="{{ $autor->Nombres }}"
                                    data-tipo-inicial="{{ $tiposPorAutor[$autor->Codigo_Autor] ?? 'Principal' }}"
                                    {{ $marcado ? 'checked' : '' }}
                                >
                                <span>{{ $autor->Nombres }}</span>
                            </label>
                        </li>
                    @empty
                        <li class="px-3 py-4 text-xs text-center text-gray-400">No hay autores registrados en el sistema.</li>
                    @endforelse
                </ul>
                <div class="p-2 border-t border-gray-100 bg-gray-50/50 text-right">
                    <button type="button" id="autores-cerrar" class="text-xs font-semibold text-gray-900 hover:underline px-2 py-1">
                        Cerrar lista
                    </button>
                </div>
            </div>
        </div>

        <div id="autores-seleccionados" class="mt-3 space-y-2"></div>
        @error('autores')
            <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>
</div>

<script>
(function () {
    var isbnInput = document.getElementById('Codigo_ISBN');
    if (isbnInput && !isbnInput.readOnly) {
        isbnInput.addEventListener('input', function () {
            isbnInput.value = isbnInput.value.replace(/\D/g, '').slice(0, 13);
        });
        isbnInput.addEventListener('paste', function (e) {
            e.preventDefault();
            var texto = (e.clipboardData || window.clipboardData).getData('text');
            isbnInput.value = texto.replace(/\D/g, '').slice(0, 13);
        });
    }

    var wrapper = document.getElementById('autores-selector');
    var toggleBtn = document.getElementById('autores-toggle');
    var cerrarBtn = document.getElementById('autores-cerrar');
    var panel = document.getElementById('autores-panel');
    var buscador = document.getElementById('autores-buscador');
    var checkboxes = Array.prototype.slice.call(document.querySelectorAll('.autor-checkbox'));
    var items = Array.prototype.slice.call(document.querySelectorAll('.autor-item'));
    var resumen = document.getElementById('autores-resumen');
    var contenedor = document.getElementById('autores-seleccionados');
    var tipos = ['Principal', 'Coautor', 'Traductor'];

    function actualizarResumen() {
        var marcados = checkboxes.filter(function (cb) { return cb.checked; });
        if (marcados.length === 0) {
            resumen.textContent = 'Seleccione autores';
        } else if (marcados.length <= 2) {
            resumen.textContent = marcados.map(function (cb) { return cb.dataset.nombre; }).join(', ');
        } else {
            resumen.textContent = marcados.length + ' autores seleccionados';
        }
    }

    function crearFila(checkbox, tipoElegido) {
        var codigo = checkbox.dataset.autor;
        var fila = document.createElement('div');
        fila.className = 'flex items-center justify-between gap-3 border border-gray-200/80 rounded-xl px-3.5 py-2.5 bg-gray-50/50';

        var nombreSpan = document.createElement('span');
        nombreSpan.className = 'text-xs font-semibold text-gray-800';
        nombreSpan.textContent = checkbox.dataset.nombre;

        var select = document.createElement('select');
        select.name = 'tipo_autoria[' + codigo + ']';
        select.className = 'rounded-lg border-gray-300 text-xs py-1.5 px-2.5 bg-white focus:ring-gray-900 focus:border-gray-900';
        tipos.forEach(function (tipo) {
            var option = document.createElement('option');
            option.value = tipo;
            option.textContent = tipo;
            if (tipo === tipoElegido) option.selected = true;
            select.appendChild(option);
        });

        var quitar = document.createElement('button');
        quitar.type = 'button';
        quitar.className = 'text-xs font-medium text-rose-600 hover:text-rose-800 transition-colors px-2 py-1';
        quitar.textContent = 'Quitar';
        quitar.addEventListener('click', function () {
            checkbox.checked = false;
            sincronizar();
        });

        var derecha = document.createElement('div');
        derecha.className = 'flex items-center gap-2';
        derecha.appendChild(select);
        derecha.appendChild(quitar);

        fila.appendChild(nombreSpan);
        fila.appendChild(derecha);
        return fila;
    }

    function sincronizar() {
        var tiposActuales = {};
        contenedor.querySelectorAll('select').forEach(function (select) {
            var match = select.name.match(/\[(.+)\]/);
            if (match) tiposActuales[match[1]] = select.value;
        });

        contenedor.innerHTML = '';
        var marcados = checkboxes.filter(function (cb) { return cb.checked; });

        if (marcados.length === 0) {
            var vacio = document.createElement('p');
            vacio.className = 'text-xs text-gray-400 italic py-1';
            vacio.textContent = 'Ningún autor seleccionado todavía.';
            contenedor.appendChild(vacio);
        } else {
            marcados.forEach(function (cb) {
                var tipoElegido = tiposActuales[cb.dataset.autor] || cb.dataset.tipoInicial || 'Principal';
                contenedor.appendChild(crearFila(cb, tipoElegido));
            });
        }

        actualizarResumen();
    }

    checkboxes.forEach(function (cb) { cb.addEventListener('change', sincronizar); });

    toggleBtn.addEventListener('click', function () { panel.classList.toggle('hidden'); });
    cerrarBtn.addEventListener('click', function () { panel.classList.add('hidden'); });

    document.addEventListener('click', function (e) {
        if (wrapper && !wrapper.contains(e.target)) panel.classList.add('hidden');
    });

    if (buscador) {
        buscador.addEventListener('input', function () {
            var q = buscador.value.trim().toLowerCase();
            items.forEach(function (li) {
                li.style.display = li.dataset.nombre.indexOf(q) !== -1 ? '' : 'none';
            });
        });
    }

    sincronizar();
})();
</script>
