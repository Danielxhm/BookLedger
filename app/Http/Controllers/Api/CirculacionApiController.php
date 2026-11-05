<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDevolucionRequest;
use App\Http\Requests\StorePrestamoRequest;
use App\Models\Bibliotecario;
use App\Models\Devolucion;
use App\Models\Multa;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Services\DevolucionService;
use App\Services\PrestamoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CirculacionApiController extends Controller
{
    public function prestamosIndex(Request $request)
    {
        $buscar = trim($request->input('buscar', $request->input('search', '')));

        return response()->json(
            Prestamo::with('socio', 'bibliotecario', 'detalles.ejemplar.libro')
                ->when($request->estado, fn ($q, $e) => $q->where('EstadoPrestamo', $e))
                ->when($buscar !== '', function ($query) use ($buscar) {
                    $query->where(function ($q) use ($buscar) {
                        if (is_numeric($buscar)) {
                            $q->orWhere('Codigo_Prestamo', (int) $buscar);
                        }

                        $q->orWhereHas('socio', function ($sq) use ($buscar) {
                            $sq->where('Nombres', 'like', "%{$buscar}%")
                               ->orWhere('Apellidos', 'like', "%{$buscar}%")
                               ->orWhere('Num_Documento', 'like', "%{$buscar}%")
                               ->orWhere(DB::raw("CONCAT(Nombres, ' ', Apellidos)"), 'like', "%{$buscar}%");
                        });

                        $q->orWhereHas('detalles.ejemplar.libro', function ($lq) use ($buscar) {
                            $lq->where('Titulo', 'like', "%{$buscar}%");
                        });

                        if (is_numeric($buscar)) {
                            $q->orWhereHas('detalles', function ($dq) use ($buscar) {
                                $dq->where('Codigo_Ejemplar', (int) $buscar);
                            });
                        }
                    });
                })
                ->latest('Codigo_Prestamo')
                ->paginate(15)
        );
    }

    /**
     * Registra un nuevo préstamo y actualiza la disponibilidad de los ejemplares.
     */
    public function prestar(StorePrestamoRequest $request, PrestamoService $service)
    {
        $bibliotecario = $this->bibliotecarioDe($request);

        $prestamo = $service->registrar(
            $request->integer('Codigo_Socio'),
            $request->input('ejemplares'),
            $bibliotecario->Codigo_Bibliotecario,
        );

        return response()->json([
            'message' => 'Préstamo registrado. Se notificó al socio.',
            'prestamo' => $prestamo->load('socio', 'detalles.ejemplar.libro'),
        ], 201);
    }

    public function prestamoShow(Prestamo $prestamo)
    {
        return response()->json([
            'prestamo' => $prestamo->load('socio', 'bibliotecario', 'detalles.ejemplar.libro', 'detalles.multas'),
        ]);
    }

    public function devolucionesIndex()
    {
        return response()->json(
            Devolucion::with('bibliotecario', 'detalles.ejemplar.libro')
                ->latest('Codigo_Devolucion')
                ->paginate(15)
        );
    }

    /**
     * Procesa la devolución de ejemplares, calculando mora y registrando multas si aplica.
     */
    public function devolver(StoreDevolucionRequest $request, DevolucionService $service)
    {
        $bibliotecario = $this->bibliotecarioDe($request);

        $devolucion = $service->registrar(
            $request->items(),
            $request->input('Observaciones'),
            $bibliotecario->Codigo_Bibliotecario,
        );

        return response()->json([
            'message' => 'Devolución registrada correctamente.',
            'devolucion' => $devolucion->load('bibliotecario', 'detalles.ejemplar.libro', 'detalles.multas'),
        ], 201);
    }

    public function devolucionShow(Devolucion $devolucion)
    {
        return response()->json([
            'devolucion' => $devolucion->load('bibliotecario', 'detalles.ejemplar.libro', 'detalles.prestamo.socio', 'detalles.multas'),
        ]);
    }

    public function multasIndex(Request $request)
    {
        $buscar = trim($request->input('buscar', $request->input('search', '')));

        return response()->json(
            Multa::with('detalle.prestamo.socio', 'detalle.ejemplar.libro')
                ->when($request->estado, fn ($q, $e) => $q->where('Estado_Pago', $e))
                ->when($buscar !== '', function ($query) use ($buscar) {
                    $query->where(function ($q) use ($buscar) {
                        if (is_numeric($buscar)) {
                            $q->orWhere('Codigo_Multa', (int) $buscar);
                        }

                        $q->orWhereHas('detalle.prestamo.socio', function ($sq) use ($buscar) {
                            $sq->where('Nombres', 'like', "%{$buscar}%")
                               ->orWhere('Apellidos', 'like', "%{$buscar}%")
                               ->orWhere('Num_Documento', 'like', "%{$buscar}%")
                               ->orWhere(DB::raw("CONCAT(Nombres, ' ', Apellidos)"), 'like', "%{$buscar}%");
                        });

                        $q->orWhereHas('detalle.ejemplar.libro', function ($lq) use ($buscar) {
                            $lq->where('Titulo', 'like', "%{$buscar}%");
                        });
                    });
                })
                ->orderByDesc('Fecha_Emision')
                ->orderByDesc('Codigo_Multa')
                ->paginate(15)
        );
    }

    public function pagarMulta(Multa $multa)
    {
        if ($multa->Estado_Pago === 'Pagado') {
            return response()->json([
                'message' => 'Esa multa ya estaba pagada.',
            ], 422);
        }

        DB::transaction(function () use ($multa) {
            $multa->update(['Estado_Pago' => 'Pagado']);

            $socio = $multa->detalle->prestamo->socio;
            if ($socio->EstadoSocio === 'Sancionado' && ! $socio->multasPendientes()->exists()) {
                $socio->update(['EstadoSocio' => 'Activo']);
            }
        });

        return response()->json([
            'message' => 'Multa marcada como pagada.',
            'multa' => $multa->refresh(),
        ]);
    }

    public function reservasIndex(Request $request)
    {
        return response()->json(
            Reserva::with('socio', 'libro')
                ->when($request->estado, fn ($q, $e) => $q->where('EstadoReserva', $e))
                ->orderByDesc('FechaReserva')
                ->orderByDesc('Codigo_Reserva')
                ->paginate(15)
        );
    }

    private function bibliotecarioDe(Request $request): Bibliotecario
    {
        return app(\App\Services\BibliotecarioService::class)->paraUsuario($request->user());
    }
}
