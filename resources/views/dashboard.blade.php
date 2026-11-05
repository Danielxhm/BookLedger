<x-app-layout>
    <style>
        .dashboard-geist {
            font-family: 'Geist', ui-sans-serif, system-ui, -apple-system, sans-serif;
            font-size-adjust: from-font;
            line-height: 1.45;
        }
        .visually-hidden {
            position: absolute;
            clip-path: inset(50%);
            overflow: hidden;
            width: 1px;
            height: 1px;
            margin: -1px;
            padding: 0;
            border: 0;
            white-space: nowrap;
        }
        .heatmap-grid {
            display: grid;
            grid-template-columns: repeat(var(--heatmap-cols, 53), minmax(0, 1fr));
        }
    </style>

    <div class="dashboard-geist max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 pb-6">

        <!-- Banner Estilo Finmo (Unido al Navbar con espacio intermedio confortable) -->
        <div class="bg-[#f3f4f6] rounded-b-[32px] border-b border-x border-gray-200/80 px-8 lg:px-10 pt-7 sm:pt-9 pb-7 sm:pb-8 mb-6 shadow-2xs">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                
                <!-- Lado Izquierdo: Fecha en círculo + Selector de Mes y Fecha -->
                <div class="flex flex-wrap items-center gap-4 sm:gap-5">
                    <!-- Día y Mes / Día de la semana -->
                    <div class="flex items-center gap-3.5">
                        <div class="size-15 sm:size-16 rounded-full border border-gray-300/80 bg-white flex items-center justify-center text-2xl font-bold text-gray-900 shadow-2xs shrink-0">
                            {{ $selectedDate->format('d') }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-bold text-gray-900 leading-tight">
                                {{ ucfirst($selectedDate->translatedFormat('F')) }}
                            </span>
                            <span class="text-xs font-medium text-gray-400 mt-0.5">
                                {{ ucfirst($selectedDate->translatedFormat('l')) }}
                            </span>
                        </div>
                    </div>

                    <!-- Separador vertical -->
                    <div class="h-10 w-px bg-gray-300/80 hidden sm:block mx-1.5" aria-hidden="true"></div>

                    <!-- Formulario GET para seleccionar mes y fecha de las KPI cards -->
                    <form action="{{ route('dashboard') }}" method="GET" id="dashboard-date-filter-form" class="flex flex-wrap items-center gap-3">
                        <label for="dashboard-fecha-input" class="visually-hidden">
                            Seleccionar mes y fecha para filtrar las tarjetas KPI
                        </label>
                        <input
                            type="date"
                            id="dashboard-fecha-input"
                            name="fecha"
                            value="{{ $selectedDate->format('Y-m-d') }}"
                            class="visually-hidden"
                        >

                        <!-- Botón Píldora Verde -->
                        <button
                            type="button"
                            id="open-date-picker-pill"
                            class="px-6 py-3 rounded-full bg-[#68b937] hover:bg-[#5aa32e] text-white text-sm font-semibold inline-flex items-center gap-2.5 shadow-sm transition cursor-pointer"
                        >
                            <span>
                                {{ $fechaFiltroActiva ? 'Mes: ' . ucfirst($selectedDate->translatedFormat('F Y')) : 'Seleccionar mes y fecha' }}
                            </span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>

                        <!-- Botón Circular de Calendario -->
                        <button
                            type="button"
                            id="open-date-picker-icon"
                            aria-label="Abrir selector de calendario"
                            title="Cambiar fecha o mes"
                            class="size-11 rounded-full border border-gray-300/80 bg-white hover:bg-gray-50 flex items-center justify-center text-gray-700 shadow-2xs transition cursor-pointer shrink-0"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                        </button>

                        @if ($fechaFiltroActiva)
                            <a
                                href="{{ route('dashboard') }}"
                                class="text-xs font-semibold text-gray-500 hover:text-gray-900 px-3.5 py-1.5 rounded-full bg-white border border-gray-200 shadow-2xs transition"
                            >
                                Ver todo
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Lado Derecho: Bienvenida y Subtítulo con barra cursor -->
                <div class="lg:max-w-xl text-left">
                    <h1 class="text-2xl sm:text-[28px] font-normal tracking-tight text-gray-900 leading-tight">
                        Bienvenido, {{ Auth::user()->name }} 👋
                    </h1>
                    <div class="mt-2 flex items-center gap-2.5">
                        <span class="inline-block w-[2.5px] h-5 bg-gray-800 shrink-0" aria-hidden="true"></span>
                        <p class="text-sm font-light text-gray-400 leading-relaxed">
                            Panel de administración general. Consulta métricas clave, gestiona la circulación de ejemplares y supervisa la actividad en tiempo real.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- KPI Cards: encabezado gris + cuerpo blanco, monocromo -->
        @php
            $kpis = [
                ['label' => 'Libros en catálogo', 'value' => $totalLibros, 'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25'],
                ['label' => 'Socios activos', 'value' => $sociosActivos, 'icon' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z'],
                ['label' => 'Préstamos activos', 'value' => $prestamosActivos, 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                ['label' => 'Multas pendientes', 'value' => $multasPendientes, 'icon' => 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'],
                ['label' => 'Reservas pendientes', 'value' => $reservasPendientes, 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5'],
            ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-5 mb-6">
            @foreach ($kpis as $kpi)
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
                    <div class="flex items-center justify-between gap-3 bg-[#f3f4f6] border-b border-gray-200/80 px-5 py-3">
                        <p class="text-[13px] font-medium text-gray-600">{{ $kpi['label'] }}</p>
                        <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $kpi['icon'] }}" />
                        </svg>
                    </div>
                    <p class="px-5 py-4 text-4xl font-semibold tracking-tight text-gray-900 tabular-nums">{{ $kpi['value'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Heatmap de Actividad (Escala normal y compacta, sin zoom) -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-2xs p-5 sm:p-6">
            <div class="w-full">
                <!-- Header del Heatmap alineado al ancho de la grilla -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3.5 border-b border-gray-100 gap-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-sm sm:text-base font-bold tracking-tight text-gray-900">
                            Historial de Actividad de Biblioteca
                        </h2>
                        <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            {{ $heatmapEtiqueta }}
                        </span>
                        <span class="hidden md:inline text-xs text-gray-400">
                            · Flujo diario de préstamos y operaciones
                        </span>
                    </div>
                    <div class="text-xs sm:text-sm text-gray-500 font-medium whitespace-nowrap">
                        <span class="font-bold text-gray-900 text-sm">{{ number_format($totalOperacionesHeatmap ?? 0) }}</span> {{ $heatmapResumen }}
                    </div>
                </div>

                <!-- Grilla Heatmap con tamaño  y escala auténtica -->
                <div class="pt-4 pb-2">
                    <div class="w-full">
                        <!-- Fila de Meses Superior -->
                        <div class="flex items-center pl-[30px] mb-1.5">
                            <div class="heatmap-grid flex-1 gap-[4px]" style="--heatmap-cols: {{ count($heatmapWeeks) }};">
                                @foreach ($heatmapWeeks as $week)
                                    <div class="text-[10px] font-medium text-gray-400 text-left overflow-visible whitespace-nowrap leading-none">
                                        {{ $week['month'] ?? '' }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Cuerpo del Heatmap: Días + 53 Columnas -->
                        <div class="flex items-stretch gap-2.5">
                            <!-- Días de la semana a la izquierda (Mon, Wed, Fri) -->
                            <div class="flex flex-col justify-between text-[10px] font-medium text-gray-400 w-5 shrink-0 py-[1px] leading-none select-none" aria-hidden="true">
                                <span class="h-[15px] sm:h-[17px] flex items-center">Lun</span>
                                <span class="h-[15px] sm:h-[17px] opacity-0 flex items-center">Mar</span>
                                <span class="h-[15px] sm:h-[17px] flex items-center">Mié</span>
                                <span class="h-[15px] sm:h-[17px] opacity-0 flex items-center">Jue</span>
                                <span class="h-[15px] sm:h-[17px] flex items-center">Vie</span>
                                <span class="h-[15px] sm:h-[17px] opacity-0 flex items-center">Sáb</span>
                                <span class="h-[15px] sm:h-[17px] opacity-0 flex items-center">Dom</span>
                            </div>

                            <!-- 53 Columnas de celdas naturales sin zoom -->
                            <div class="heatmap-grid flex-1 gap-[4px]" style="--heatmap-cols: {{ count($heatmapWeeks) }};">
                                @foreach ($heatmapWeeks as $week)
                                    <div class="flex flex-col gap-[4px]">
                                        @foreach ($week['days'] as $day)
                                            @if ($day['blank'])
                                                <div class="w-full h-[15px] sm:h-[17px] rounded-[3px] bg-transparent"></div>
                                            @else
                                                @php
                                                    $count = $day['count'];
                                                    $color = match(true) {
                                                        $count === 0 => 'bg-[#ebedf0]',
                                                        $count <= 2 => 'bg-[#9be9a8]',
                                                        $count <= 4 => 'bg-[#40c463]',
                                                        $count <= 6 => 'bg-[#30a14e]',
                                                        default => 'bg-[#216e39]',
                                                    };
                                                @endphp
                                                <div 
                                                    class="w-full h-[15px] sm:h-[17px] rounded-[3px] {{ $color }} transition-transform duration-100 hover:scale-125 cursor-pointer"
                                                    title="{{ $count === 0 ? 'Sin operaciones' : $count . ' operación(es)' }} el {{ $day['formattedDate'] }}"
                                                ></div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Footer con leyenda (Less / More) alineado a la grilla -->
                        <div class="flex flex-col sm:flex-row items-center justify-between mt-4 pt-3 border-t border-gray-100 text-xs text-gray-400 gap-3">
                            <span class="hover:text-indigo-600 transition cursor-pointer text-indigo-600 font-medium">
                                Registro histórico de actividad y circulación del sistema
                            </span>

                            <div class="flex items-center gap-1.5 font-medium text-gray-500">
                                <span class="mr-1 text-[11px]">Menos</span>
                                <span class="size-[11px] rounded-[2px] bg-[#ebedf0] inline-block" title="0 operaciones"></span>
                                <span class="size-[11px] rounded-[2px] bg-[#9be9a8] inline-block" title="1-2 operaciones"></span>
                                <span class="size-[11px] rounded-[2px] bg-[#40c463] inline-block" title="3-4 operaciones"></span>
                                <span class="size-[11px] rounded-[2px] bg-[#30a14e] inline-block" title="5-6 operaciones"></span>
                                <span class="size-[11px] rounded-[2px] bg-[#216e39] inline-block" title="7+ operaciones"></span>
                                <span class="ml-1 text-[11px]">Más</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        (function () {
            const initDashboardPicker = () => {
                const form = document.getElementById('dashboard-date-filter-form');
                const dateInput = document.getElementById('dashboard-fecha-input');
                const pillBtn = document.getElementById('open-date-picker-pill');
                const iconBtn = document.getElementById('open-date-picker-icon');

                if (!form || !dateInput || form.dataset.bound === 'true') return;
                form.dataset.bound = 'true';

                const openPicker = () => {
                    if (typeof dateInput.showPicker === 'function') {
                        dateInput.showPicker();
                    } else {
                        dateInput.focus();
                        dateInput.click();
                    }
                };

                pillBtn?.addEventListener('click', openPicker);
                iconBtn?.addEventListener('click', openPicker);

                dateInput.addEventListener('change', () => {
                    if (dateInput.value) {
                        form.submit();
                    }
                });
            };

            initDashboardPicker();
            document.addEventListener('livewire:navigated', initDashboardPicker, { once: true });
        })();
    </script>
</x-app-layout>
