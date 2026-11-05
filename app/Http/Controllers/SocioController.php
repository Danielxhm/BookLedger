<?php

namespace App\Http\Controllers;


use App\Models\Socio;
use Illuminate\Http\Request;


class SocioController extends Controller
{
    public function index()
    {
        $socios = Socio::orderBy('Apellidos')->paginate(15);
        return view('socios.index', compact('socios'));
    }

    public function create()
    {
        return view('socios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Num_Documento' => 'required|string|max:20|unique:socios,Num_Documento',
            'Nombres' => 'required|string|max:50',
            'Apellidos' => 'required|string|max:50',
            'Email' => 'required|email|max:100|unique:socios,Email',
            'Telefono' => 'required|string|max:15',
            'Fecha_Nac' => 'required|date',
            'EstadoSocio' => 'required|in:Activo,Sancionado,Inactivo',
            'password' => 'nullable|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        Socio::create($data);

        return redirect()->route('socios.index')->with('status', 'Socio registrado correctamente.');
    }

    public function edit(Socio $socio)
    {
        return view('socios.edit', compact('socio'));
    }

    public function update(Request $request, Socio $socio)
    {
        $data = $request->validate([
            'Num_Documento' => 'required|string|max:20|unique:socios,Num_Documento,' . $socio->Codigo_Socio . ',Codigo_Socio',
            'Nombres' => 'required|string|max:50',
            'Apellidos' => 'required|string|max:50',
            'Email' => 'required|email|max:100|unique:socios,Email,' . $socio->Codigo_Socio . ',Codigo_Socio',
            'Telefono' => 'required|string|max:15',
            'Fecha_Nac' => 'required|date',
            'EstadoSocio' => 'required|in:Activo,Sancionado,Inactivo',
            'password' => 'nullable|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $socio->update($data);

        return redirect()->route('socios.index')->with('status', 'Socio actualizado correctamente.');
    }

    public function destroy(Socio $socio)
    {
        if ($socio->prestamos()->exists() || $socio->reservas()->exists()) {
            $socio->update(['EstadoSocio' => 'Inactivo']);
            return redirect()->route('socios.index')
                ->with('status', 'El socio tiene historial, así que se marcó como Inactivo en lugar de eliminarlo.');
        }

        $socio->delete();
        return redirect()->route('socios.index')->with('status', 'Socio eliminado.');
    }
}
