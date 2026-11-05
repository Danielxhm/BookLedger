<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use Illuminate\Http\Request;

class EditorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $editoriales = Editorial::orderBy('NombreEditorial')->paginate(15);
        return view('editoriales.index', compact('editoriales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('editoriales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'NombreEditorial' => 'required|string|max:100',
            'Pais' => 'required|string|max:50',
        ]);
        Editorial::create($data);
        return redirect()->route('editoriales.index')->with('status', 'Editorial creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Editorial $editorial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Editorial $editorial)
    {
        return view('editoriales.edit', compact('editorial'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Editorial $editorial)
    {
        $data = $request->validate([
            'NombreEditorial' => 'required|string|max:100',
            'Pais' => 'required|string|max:50',
        ]);
        $editorial->update($data);
        return redirect()->route('editoriales.index')->with('status', 'Editorial actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Editorial $editorial)
    {
        $editorial->delete();
        return redirect()->route('editoriales.index')->with('status', 'Editorial eliminada.');
    }
}
