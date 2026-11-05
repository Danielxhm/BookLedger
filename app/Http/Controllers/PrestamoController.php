<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Http\Requests\StorePrestamoRequest;
use App\Models\Ejemplar;
use App\Models\Socio;
use App\Services\PrestamoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'prestamos');
        $buscar = trim($request->input('buscar', ''));
        $estado = $request->input('estado');

        $reservasPendientesCount = Reserva::where('EstadoReserva', 'Pendiente')->count();

        $prestamos = null;
        $reservas = null;

        if ($tab === 'reservas') {
            $reservas = Reserva::with([
                    'socio',
                    'libro.autores',
                    'libro.ejemplares' => fn($q) => $q->where('Estado_Ejemplar', 'Disponible'),
                ])
                ->when($estado && in_array($estado, ['Pendiente', 'Atendida', 'Cancelada']), function ($q) use ($estado) {
                    $q->where('EstadoReserva', $estado);
                })
                ->when($buscar !== '', function ($query) use ($buscar) {
                    $query->where(function ($q) use ($buscar) {
                        if (is_numeric($buscar)) {
                            $q->orWhere('Codigo_Reserva', (int) $buscar)
                              ->orWhere('Codigo_Socio', (int) $buscar);
                        }

                        $q->orWhere('Codigo_ISBN', 'like', "%{$buscar}%");

                        $q->orWhereHas('socio', function ($sq) use ($buscar) {
                            $sq->where('Nombres', 'like', "%{$buscar}%")
                               ->orWhere('Apellidos', 'like', "%{$buscar}%")
                               ->orWhere('Num_Documento', 'like', "%{$buscar}%")
                               ->orWhere(DB::raw("CONCAT(Nombres, ' ', Apellidos)"), 'like', "%{$buscar}%");
                        });

                        $q->orWhereHas('libro', function ($lq) use ($buscar) {
                            $lq->where('Titulo', 'like', "%{$buscar}%");
                        });
                    });
                })
                ->latest('Codigo_Reserva')
                ->paginate(15)
                ->withQueryString();
        } else {
            $prestamos = Prestamo::with(['socio', 'detalles.ejemplar.libro'])
                ->when($estado && in_array($estado, ['Activo', 'Vencido', 'Concluido']), fn($q) => $q->where('EstadoPrestamo', $estado))
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
                ->withQueryString();
        }

        return view('prestamos.index', compact('prestamos', 'reservas', 'tab', 'buscar', 'estado', 'reservasPendientesCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $socios = Socio::where('EstadoSocio', 'Activo')->orderBy('Apellidos')->get();
        $ejemplares = Ejemplar::with('libro')
            ->where('Estado_Ejemplar', 'Disponible')->orderBy('Codigo_ISBN')->get();
        return view('prestamos.create', compact('socios', 'ejemplares'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrestamoRequest $request, PrestamoService $service)
    {
        $bibliotecario = app(\App\Services\BibliotecarioService::class)->paraUsuario($request->user());

        $prestamo = $service->registrar(
            $request->integer('Codigo_Socio'),
            $request->input('ejemplares'),
            $bibliotecario->Codigo_Bibliotecario,
            $request->user()->id,
        );

        return redirect()->route('prestamos.show', $prestamo)
            ->with('status', 'Préstamo registrado. Se notificó al socio.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestamo $prestamo)
    {
        $prestamo->load('socio', 'bibliotecario', 'detalles.ejemplar.libro', 'detalles.multas');
        return view('prestamos.show', compact('prestamo'));
    }

    /**
     * Registra el préstamo de un ejemplar disponible a partir de una reserva pendiente.
     */
    public function entregarReserva(Reserva $reserva, PrestamoService $service, Request $request)
    {
        if ($reserva->EstadoReserva !== 'Pendiente') {
            return back()->withErrors(['reserva' => 'Esta reserva ya no está en estado Pendiente.']);
        }

        $ejemplar = Ejemplar::where('Codigo_ISBN', $reserva->Codigo_ISBN)
            ->where('Estado_Ejemplar', 'Disponible')
            ->first();

        if (!$ejemplar) {
            $titulo = $reserva->libro?->Titulo ?? "con ISBN {$reserva->Codigo_ISBN}";
            return back()->withErrors(['reserva' => "No hay ejemplares disponibles en este momento para '{$titulo}'. Debe esperarse la devolución de un ejemplar prestado o registrar uno nuevo."]);
        }

        try {
            $bibliotecario = app(\App\Services\BibliotecarioService::class)->paraUsuario($request->user());

            $prestamo = $service->registrar(
                $reserva->Codigo_Socio,
                [$ejemplar->Codigo_Ejemplar],
                $bibliotecario->Codigo_Bibliotecario
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Throwable $e) {
            return back()->withErrors(['reserva' => 'No se pudo registrar el préstamo: ' . $e->getMessage()]);
        }

        $socioNombre = trim(($reserva->socio?->Nombres ?? '') . ' ' . ($reserva->socio?->Apellidos ?? ''));
        return redirect()->route('prestamos.index', ['tab' => 'reservas'])
            ->with('status', "Préstamo #{$prestamo->Codigo_Prestamo} registrado (Ejemplar #{$ejemplar->Codigo_Ejemplar}) para {$socioNombre}. La reserva #{$reserva->Codigo_Reserva} quedó atendida.");
    }

    /**
     * Cancela una reserva pendiente de libro.
     */
    public function cancelarReserva(Reserva $reserva, Request $request)
    {
        if ($reserva->EstadoReserva !== 'Pendiente') {
            return back()->withErrors(['reserva' => 'Solo se pueden cancelar reservas que se encuentren en estado Pendiente.']);
        }

        $reserva->update([
            'EstadoReserva'        => 'Cancelada',
            'usuario_modificacion' => $request->user()?->name ?? 'Personal',
            'fecha_modificacion'   => now(),
            'motivo_modificacion'  => $request->input('motivo', 'Cancelada desde el panel de préstamos'),
        ]);

        return redirect()->route('prestamos.index', ['tab' => 'reservas'])
            ->with('status', "Reserva #{$reserva->Codigo_Reserva} cancelada exitosamente.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestamo $prestamo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestamo $prestamo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestamo $prestamo)
    {
        //
    }
}
