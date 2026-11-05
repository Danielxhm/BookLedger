<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBibliotecarioRequest;
use App\Http\Requests\UpdateBibliotecarioRequest;
use App\Models\Bibliotecario;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BibliotecarioController extends Controller
{

    public function index()
    {
        $bibliotecarios = Bibliotecario::with('user')->orderBy('Nombres')->paginate(15);
        return view('bibliotecarios.index', compact('bibliotecarios'));
    }
    public function create()
    {
        $supervisores = Bibliotecario::orderBy('Nombres')->get();
        return view('bibliotecarios.create', compact('supervisores'));
    }

    public function store(StoreBibliotecarioRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $userId = null;

            if (!empty($data['crear_cuenta'])) {
                $user = User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
                $user->syncRoles([$data['rol']]);
                $userId = $user->id;
            }

            Bibliotecario::create([
                'Num_documento'     => $data['Num_documento'],
                'Nombres'           => $data['Nombres'],
                'Apellidos'         => $data['Apellidos'],
                'NombreUsuario'     => $data['NombreUsuario'],
                'clave_hash'        => Hash::make($data['password'] ?? str()->random(16)),
                'EstadoEmpleado'    => $data['EstadoEmpleado'],
                'Codigo_Supervisor' => $data['Codigo_Supervisor'] ?? null,
                'user_id'           => $userId,
            ]);
        });

        return redirect()->route('bibliotecarios.index')->with('status', 'Bibliotecario creado.');
    }

    public function edit(Bibliotecario $bibliotecario)
    {
        $supervisores = Bibliotecario::where('Codigo_Bibliotecario', '!=', $bibliotecario->Codigo_Bibliotecario)->orderBy('Nombres')->get();
        return view('bibliotecarios.edit', compact('bibliotecario', 'supervisores'));
    }

    public function update(UpdateBibliotecarioRequest $request, Bibliotecario $bibliotecario)
    {
        $data = $request->validated();

        $bibliotecario->update($data);

        if (!empty($data['rol']) && $bibliotecario->user) {
            $bibliotecario->user->syncRoles([$data['rol']]);
        }

        return redirect()->route('bibliotecarios.index')->with('status', 'Bibliotecario actualizado.');
    }

    public function destroy(Bibliotecario $bibliotecario)
    {
        if ($bibliotecario->user_id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $bibliotecario->delete();
        return back()->with('status', 'Bibliotecario eliminado.');
    }
}
