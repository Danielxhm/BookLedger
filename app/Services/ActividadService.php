<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class ActividadService
{
    /** Columnas fijas de la grilla: la geometría no cambia entre vista anual y mensual. */
    private const SEMANAS = 53;

    /** Eventos que componen la actividad diaria: tipo => [tabla, columna de fecha]. */
    private const FUENTES = [
        'prestamos' => ['prestamos', 'FechaPrestamo'],
        'devoluciones' => ['devolucions', 'Fecha_Devolucion'],
        'multas' => ['multas', 'Fecha_Emision'],
        'reservas' => ['reservas', 'FechaReserva'],
    ];

    /**
     * Cuadrícula semanal para el heatmap del dashboard.
     * Sin filtro abarca las últimas 52 semanas; con filtro, el mes elegido.
     *
     * @return array{weeks: array<int, array{month: string|null, days: array<int, array{date: string|null, formattedDate: string, count: int, blank: bool}>}>, total: int, etiqueta: string, resumen: string}
     */
    public function heatmap(?Carbon $fecha): array
    {
        $conteo = $this->conteoDiario($fecha);
        $desde = $conteo['desde'];
        $hasta = $conteo['hasta'];
        $hoy = today();

        $weeks = [];
        $total = 0;
        $ultimoMesEtiquetado = null;
        $current = $desde->copy()->startOfWeek(CarbonInterface::MONDAY);

        while ($current->lte($hasta)) {
            $weekDays = [];
            $weekMonth = null;

            for ($d = 0; $d < 7; $d++) {
                $enRango = $current->betweenIncluded($desde, $hasta);
                $visible = $enRango && $current->lte($hoy);
                $dateStr = $current->toDateString();
                $count = $visible ? ($conteo['dias'][$dateStr]['total'] ?? 0) : 0;

                if ($visible) {
                    $total += $count;

                    if ($weekMonth === null && $ultimoMesEtiquetado !== $current->month) {
                        $weekMonth = ucfirst($current->translatedFormat('M'));
                        $ultimoMesEtiquetado = $current->month;
                    }
                }

                $weekDays[] = [
                    'date' => $enRango ? $dateStr : null,
                    'formattedDate' => $current->translatedFormat('d M, Y'),
                    'count' => $count,
                    'blank' => ! $visible,
                ];

                $current->addDay();
            }

            $weeks[] = [
                'month' => $weekMonth,
                'days' => $weekDays,
            ];
        }

        // Misma geometría en vista anual y mensual: siempre 53 columnas; las
        // semanas del rango ocupan las primeras y el resto queda en blanco.
        while (count($weeks) < self::SEMANAS) {
            $weeks[] = [
                'month' => null,
                'days' => array_fill(0, 7, [
                    'date' => null,
                    'formattedDate' => '',
                    'count' => 0,
                    'blank' => true,
                ]),
            ];
        }

        if (count($weeks) > self::SEMANAS) {
            $weeks = array_slice($weeks, -self::SEMANAS);
        }

        return [
            'weeks' => $weeks,
            'total' => $total,
            'etiqueta' => $conteo['etiqueta'],
            'resumen' => $conteo['resumen'],
        ];
    }

    /**
     * Desglose diario por tipo de evento, para consumo vía API.
     *
     * @return array{desde: string, hasta: string, etiqueta: string, total: int, dias: array<int, array{fecha: string, prestamos: int, devoluciones: int, multas: int, reservas: int, total: int}>}
     */
    public function resumen(?Carbon $fecha): array
    {
        $conteo = $this->conteoDiario($fecha);

        $dias = [];
        $current = $conteo['desde']->copy();
        while ($current->lte($conteo['hasta'])) {
            $dateStr = $current->toDateString();
            $c = $conteo['dias'][$dateStr] ?? [];

            $dias[] = [
                'fecha' => $dateStr,
                'prestamos' => $c['prestamos'] ?? 0,
                'devoluciones' => $c['devoluciones'] ?? 0,
                'multas' => $c['multas'] ?? 0,
                'reservas' => $c['reservas'] ?? 0,
                'total' => $c['total'] ?? 0,
            ];

            $current->addDay();
        }

        return [
            'desde' => $conteo['desde']->toDateString(),
            'hasta' => $conteo['hasta']->toDateString(),
            'etiqueta' => $conteo['etiqueta'],
            'total' => array_sum(array_column($dias, 'total')),
            'dias' => $dias,
        ];
    }

    /**
     * @return array{desde: Carbon, hasta: Carbon, etiqueta: string, resumen: string, dias: array<string, array<string, int>>}
     */
    private function conteoDiario(?Carbon $fecha): array
    {
        if ($fecha) {
            $desde = $fecha->copy()->startOfMonth();
            $hasta = $fecha->copy()->endOfMonth();
            $etiqueta = ucfirst($fecha->translatedFormat('F Y'));
            $resumen = 'operaciones en ' . $fecha->translatedFormat('F \d\e Y');
        } else {
            $desde = now()->subWeeks(52)->startOfWeek(CarbonInterface::MONDAY);
            $hasta = now();
            $etiqueta = 'Último año';
            $resumen = 'operaciones en el último año';
        }

        $dias = [];
        foreach (self::FUENTES as $tipo => [$tabla, $columna]) {
            $filas = DB::table($tabla)
                ->selectRaw("DATE({$columna}) as fecha, COUNT(*) as total")
                ->whereBetween($columna, [$desde->copy()->startOfDay(), $hasta->copy()->endOfDay()])
                ->groupBy('fecha')
                ->pluck('total', 'fecha');

            foreach ($filas as $fechaStr => $total) {
                $dias[$fechaStr][$tipo] = ($dias[$fechaStr][$tipo] ?? 0) + (int) $total;
            }
        }

        foreach ($dias as $fechaStr => $conteo) {
            $dias[$fechaStr]['total'] = array_sum($conteo);
        }

        return [
            'desde' => $desde,
            'hasta' => $hasta,
            'etiqueta' => $etiqueta,
            'resumen' => $resumen,
            'dias' => $dias,
        ];
    }
}
