<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;


class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::orderBy('NombreCategoria')->paginate(15);
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $padres = Categoria::orderBy('NombreCategoria')->get();
        return view('categorias.create', compact('padres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'NombreCategoria' => 'required|string|max:50',
            'Descripcion' => 'nullable|string|max:200',
            'Codigo_categoriaPadre' => 'nullable|exists:categorias,Codigo_Categoria',
        ]);
        Categoria::create($data);
        return redirect()->route('categorias.index')->with('status', 'Categoría creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        $padres = Categoria::where('Codigo_Categoria', '!=', $categoria->Codigo_Categoria)->orderBy('NombreCategoria')->get();
        return view('categorias.edit', compact('categoria', 'padres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'NombreCategoria' => 'required|string|max:50',
            'Descripcion' => 'nullable|string|max:200',
            'Codigo_categoriaPadre' => 'nullable|exists:categorias,Codigo_Categoria',
        ]);
        $categoria->update($data);
        return redirect()->route('categorias.index')->with('status', 'Categoría actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('status', 'Categoría eliminada.');
    }
}
