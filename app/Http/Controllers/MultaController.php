<?php

namespace App\Http\Controllers;

use App\Models\DetallePrestamo;
use App\Models\Multa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = trim($request->input('buscar', ''));

        $multas = Multa::with(['detalle.prestamo.socio', 'detalle.ejemplar.libro'])
            ->when($request->estado, fn($q, $e) => $q->where('Estado_Pago', $e))
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
            ->orderByDesc('Fecha_Emision')->orderByDesc('Codigo_Multa')
            ->paginate(15)->withQueryString();

        return view('multas.index', compact('multas', 'buscar'));
    }

    public function pagar(Multa $multa)
    {
        if ($multa->Estado_Pago === 'Pagado') {
            return back()->with('status', 'Esa multa ya estaba pagada.');
        }

        DB::transaction(function () use ($multa) {
            $multa->update(['Estado_Pago' => 'Pagado']);

            $socio = $multa->detalle->prestamo->socio;
            if ($socio->EstadoSocio === 'Sancionado' && !$socio->multasPendientes()->exists()) {
                $socio->update(['EstadoSocio' => 'Activo']);
            }
        });

        return back()->with('status', 'Multa marcada como pagada.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $detalles = DetallePrestamo::with('prestamo.socio', 'ejemplar.libro')
            ->latest('Codigo_Detalle')
            ->limit(250)
            ->get();
        return view('multas.create', compact('detalles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validarYPreparar($request);
        Multa::create($data);

        return redirect()->route('multas.index')->with('status', 'Multa creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Multa $multa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Multa $multa)
    {
        $detalles = DetallePrestamo::with('prestamo.socio', 'ejemplar.libro')
            ->latest('Codigo_Detalle')
            ->limit(250)
            ->get();
        return view('multas.edit', compact('multa', 'detalles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Multa $multa)
    {
        $data = $this->validarYPreparar($request);
        $multa->update($data);

        return redirect()->route('multas.index')->with('status', 'Multa actualizada.');
    }

    /**
     * Valida los datos de la multa contra las reglas y límites de la biblioteca,
     * e inyecta los campos de trazabilidad y auditoría.
     */
    private function validarYPreparar(Request $request): array
    {
        $data = $request->validate([
            'Codigo_Detalle' => 'required|exists:detalle_prestamos,Codigo_Detalle',
            'Motivo' => 'required|in:Mora,Daño,Perdida',
            'Monto' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    $motivo = $request->input('Motivo');
                    $montoMax = match ($motivo) {
                        'Daño' => (float) config('biblioteca.multa_dano', 2.00),
                        'Perdida' => (float) config('biblioteca.multa_perdida', 10.00),
                        'Mora' => (function () use ($request) {
                            $detalle = DetallePrestamo::with('prestamo')->find($request->input('Codigo_Detalle'));
                            if (! $detalle || ! $detalle->prestamo) {
                                return (float) config('biblioteca.multa_maxima', 50.00);
                            }
                            $fechaLimite = $detalle->prestamo->FechaLimite_Devolucion;
                            $fechaEmision = \Carbon\Carbon::parse($request->input('Fecha_Emision', today()));
                            $dias = max(1, (int) $fechaLimite->diffInDays($fechaEmision, false));
                            $calc = round($dias * config('biblioteca.multa_por_dia', 0.25), 2);
                            return min($calc, (float) config('biblioteca.multa_maxima', 50.00));
                        })(),
                        default => (float) config('biblioteca.multa_maxima', 50.00),
                    };

                    if ((float) $value > $montoMax) {
                        $fail("El monto para '{$motivo}' no puede superar $" . number_format($montoMax, 2) . " según las reglas de la biblioteca.");
                    }
                },
            ],
            'Estado_Pago' => 'required|in:Pendiente,Pagado',
            'Fecha_Emision' => 'required|date',
            'motivo_modificacion' => 'nullable|string|max:200',
        ]);

        $usuario = $request->user()?->name ?? $request->user()?->email ?? 'Sistema';
        $data['usuario_modificacion'] = $usuario;
        $data['fecha_modificacion'] = now();
        if (empty($data['motivo_modificacion'])) {
            $data['motivo_modificacion'] = "Gestión manual ({$data['Motivo']}) por {$usuario}";
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Multa $multa)
    {
        $multa->delete();
        return redirect()->route('multas.index')->with('status', 'Multa eliminada.');
    }
}
