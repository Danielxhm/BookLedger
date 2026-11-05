<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ActividadService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActividadApiController extends Controller
{
    /**
     * Actividad diaria real del sistema (préstamos, devoluciones, multas y reservas).
     * Sin parámetros devuelve los últimos 12 meses; con ?fecha=YYYY-MM-DD, ese mes.
     */
    public function index(Request $request, ActividadService $actividad)
    {
        $fecha = null;

        if ($request->filled('fecha')) {
            try {
                $fecha = Carbon::parse($request->input('fecha'));
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'El parámetro fecha no es una fecha válida.',
                ], 422);
            }
        }

        return response()->json($actividad->resumen($fecha));
    }
}
