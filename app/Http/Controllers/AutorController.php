<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $autores = Autor::orderBy('Apellidos')->paginate(15);
        return view('autores.index', compact('autores'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('autores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'Nombres' => 'required|string|max:50',
            'Apellidos' => 'required|string|max:50',
            'Seudonimo' => 'nullable|string|max:50',
            'Biografia' => 'nullable|string',
            'Nacionalidad' => 'required|string|max:50',
        ]);
        Autor::create($data);
        return redirect()->route('autores.index')->with('status', 'Autor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Autor $autor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Autor $autor)
    {
        return view('autores.edit', compact('autor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Autor $autor)
    {
        $data = $request->validate([
            'Nombres' => 'required|string|max:50',
            'Apellidos' => 'required|string|max:50',
            'Seudonimo' => 'nullable|string|max:50',
            'Biografia' => 'nullable|string',
            'Nacionalidad' => 'required|string|max:50',
        ]);
        $autor->update($data);
        return redirect()->route('autores.index')->with('status', 'Autor actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Autor $autor)
    {
        $autor->delete();
        return redirect()->route('autores.index')->with('status', 'Autor eliminado.');
    }
}
