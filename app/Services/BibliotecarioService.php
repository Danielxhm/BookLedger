<?php

namespace App\Services;

use App\Models\Bibliotecario;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BibliotecarioService
{
    /**
     * Ficha de empleado que sella la operación de circulación: la vinculada a
     * la cuenta o, para Administradores sin ficha, una creada al vuelo con el
     * nombre de la cuenta para que el registro diga quién la operó.
     */
    public function paraUsuario(User $user): Bibliotecario
    {
        $bibliotecario = $user->bibliotecario;

        if ($bibliotecario) {
            return $bibliotecario;
        }

        abort_unless(
            $user->hasRole('Administrador'),
            403,
            'Tu usuario no está vinculado a un bibliotecario.'
        );

        return Bibliotecario::firstOrCreate(
            ['user_id' => $user->id],
            [
                'Num_documento' => 'ADMIN-' . $user->id,
                'Nombres' => $user->name,
                'Apellidos' => '',
                'NombreUsuario' => 'admin-' . $user->id,
                'clave_hash' => Hash::make(str()->random(32)),
                'EstadoEmpleado' => 'Activo',
            ]
        );
    }
}
