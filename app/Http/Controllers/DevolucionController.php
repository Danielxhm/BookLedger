<?php

namespace App\Http\Controllers;

use App\Models\Bibliotecario;
use App\Models\Devolucion;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDevolucionRequest;
use App\Models\DetallePrestamo;
use App\Models\Socio;
use App\Services\DevolucionService;


class DevolucionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devoluciones = Devolucion::with('bibliotecario', 'detalles.ejemplar.libro')
            ->latest('Codigo_Devolucion')->paginate(15);
        return view('devoluciones.index', compact('devoluciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create(Request $request)
    {
        $detalles = DetallePrestamo::with('prestamo.socio', 'ejemplar.libro')
            ->whereNull('Codigo_Devolucion')
            ->when($request->socio, fn ($q, $s) =>
                $q->whereHas('prestamo', fn ($p) => $p->where('Codigo_Socio', $s)))
            ->orderBy('Codigo_Prestamo')->get();

        $socios = Socio::whereHas('prestamos.detalles', fn ($q) => $q->whereNull('Codigo_Devolucion'))
            ->orderBy('Apellidos')->get();

        return view('devoluciones.create', compact('detalles', 'socios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDevolucionRequest $request, DevolucionService $service)
    {
        $bibliotecario = app(\App\Services\BibliotecarioService::class)->paraUsuario($request->user());

        $devolucion = $service->registrar(
            $request->items(),
            $request->input('Observaciones'),
            $bibliotecario->Codigo_Bibliotecario,
        );

        return redirect()->route('devoluciones.show', $devolucion)
            ->with('status', 'Devolución registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Devolucion $devolucion)
    {
        $devolucion->load('bibliotecario', 'detalles.ejemplar.libro', 'detalles.prestamo.socio', 'detalles.multas');
        return view('devoluciones.show', compact('devolucion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devolucion $devolucion)
    {
        $bibliotecarios = Bibliotecario::all();
        return view('devoluciones.edit', compact('devolucion', 'bibliotecarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Devolucion $devolucion)
    {
        $data = $request->validate([
            'Codigo_Bibliotecario' => 'required|exists:bibliotecarios,Codigo_Bibliotecario',
            'Fecha_Devolucion' => 'required|date',
            'Observaciones' => 'nullable|string|max:200',
        ]);
        $devolucion->update($data);
        return redirect()->route('devoluciones.index')->with('status', 'Devolución actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devolucion $devolucion)
    {
        $devolucion->delete();
        return redirect()->route('devoluciones.index')->with('status', 'Devolución eliminada correctamente.');
    }
}
