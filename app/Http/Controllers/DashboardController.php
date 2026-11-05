<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Multa;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\Socio;
use App\Services\ActividadService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, ActividadService $actividad)
    {
        app()->setLocale('es');
        Carbon::setLocale('es');

        $fechaFiltroActiva = $request->filled('fecha');
        try {
            $selectedDate = $fechaFiltroActiva
                ? Carbon::parse($request->input('fecha'))
                : now();
        } catch (\Exception $e) {
            $selectedDate = now();
            $fechaFiltroActiva = false;
        }

        $year = $selectedDate->year;
        $month = $selectedDate->month;

        $totalLibros = Libro::when($fechaFiltroActiva, fn ($q) => $q
            ->whereYear('created_at', $year)->whereMonth('created_at', $month))->count();
        $sociosActivos = Socio::where('EstadoSocio', 'Activo')
            ->when($fechaFiltroActiva, fn ($q) => $q
                ->whereYear('Fecha_RegistroSocio', $year)->whereMonth('Fecha_RegistroSocio', $month))->count();
        $prestamosActivos = Prestamo::where('EstadoPrestamo', 'Activo')
            ->when($fechaFiltroActiva, fn ($q) => $q
                ->whereYear('FechaPrestamo', $year)->whereMonth('FechaPrestamo', $month))->count();
        $multasPendientes = Multa::where('Estado_Pago', 'Pendiente')
            ->when($fechaFiltroActiva, fn ($q) => $q
                ->whereYear('Fecha_Emision', $year)->whereMonth('Fecha_Emision', $month))->count();
        $reservasPendientes = Reserva::where('EstadoReserva', 'Pendiente')
            ->when($fechaFiltroActiva, fn ($q) => $q
                ->whereYear('FechaReserva', $year)->whereMonth('FechaReserva', $month))->count();

        $heatmap = $actividad->heatmap($fechaFiltroActiva ? $selectedDate : null);

        return view('dashboard', [
            'totalLibros' => $totalLibros,
            'sociosActivos' => $sociosActivos,
            'prestamosActivos' => $prestamosActivos,
            'multasPendientes' => $multasPendientes,
            'reservasPendientes' => $reservasPendientes,
            'heatmapWeeks' => $heatmap['weeks'],
            'totalOperacionesHeatmap' => $heatmap['total'],
            'heatmapEtiqueta' => $heatmap['etiqueta'],
            'heatmapResumen' => $heatmap['resumen'],
            'selectedDate' => $selectedDate,
            'fechaFiltroActiva' => $fechaFiltroActiva,
        ]);
    }
}
