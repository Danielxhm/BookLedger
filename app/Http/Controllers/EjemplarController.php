<?php

namespace App\Http\Controllers;

use App\Models\Ejemplar;
use Illuminate\Http\Request;
use App\Models\Libro;
use App\Http\Requests\StoreEjemplarRequest;
use App\Http\Requests\UpdateEjemplarRequest;
use Illuminate\Support\Facades\DB;



class EjemplarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ejemplares = Ejemplar::with('libro', 'detalleActivo.prestamo.socio')
            ->orderBy('Codigo_Ejemplar')->paginate(15);
        return view('ejemplares.index', compact('ejemplares'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $libros = Libro::orderBy('Titulo')->get();
        return view('ejemplares.create', compact('libros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEjemplarRequest $request)
    {
        $data = $request->validated();
        $cantidad = (int) $data['cantidad'];
        unset($data['cantidad']);

        DB::transaction(function () use ($data, $cantidad) {
            for ($i = 0; $i < $cantidad; $i++) {
                Ejemplar::create($data);
            }
        });

        return redirect()->route('ejemplares.index')
            ->with('status', $cantidad === 1
                ? 'Ejemplar registrado correctamente.'
                : "$cantidad ejemplares registrados correctamente.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Ejemplar $ejemplar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ejemplar $ejemplar)
    {
        $libros = Libro::orderBy('Titulo')->get();
        return view('ejemplares.edit', compact('ejemplar', 'libros'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEjemplarRequest $request, Ejemplar $ejemplar)
    {
        $ejemplar->update($request->validated());
        return redirect()->route('ejemplares.index')->with('status', 'Ejemplar actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ejemplar $ejemplar)
    {
        if ($ejemplar->Estado_Ejemplar === 'Prestado') {
            return redirect()->route('ejemplares.index')
                ->with('status', 'No se puede eliminar un ejemplar que está prestado.');
        }

        $ejemplar->delete();
        return redirect()->route('ejemplares.index')->with('status', 'Ejemplar eliminado.');
    }
}
