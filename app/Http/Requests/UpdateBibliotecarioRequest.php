<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBibliotecarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('bibliotecarios.editar');
    }

    public function rules(): array
    {
        $id = $this->route('bibliotecario')->Codigo_Bibliotecario;

        return [
            'Num_documento' => ['required', 'regex:/^\d{8}-\d$/', Rule::unique('bibliotecarios', 'Num_documento')->ignore($id, 'Codigo_Bibliotecario')],
            'Nombres'   => ['required', 'max:50', "regex:/^[\pL\s'-]+$/u"],
            'Apellidos' => ['required', 'max:50', "regex:/^[\pL\s'-]+$/u"],
            'NombreUsuario'     => ['required', 'string', 'max:30', Rule::unique('bibliotecarios', 'NombreUsuario')->ignore($id, 'Codigo_Bibliotecario')],
            'EstadoEmpleado'    => ['required', Rule::in(['Activo', 'Inactivo'])],
            'Codigo_Supervisor' => ['nullable', 'exists:bibliotecarios,Codigo_Bibliotecario'],
            'rol'               => ['nullable', Rule::in(['Administrador', 'Bibliotecario'])],
        ];
    }

    public function messages(): array
    {
        return [
            'Num_documento.unique' => 'Ya existe un bibliotecario registrado con ese documento.',
            'Num_documento.required' => 'Escribe el número de documento.',
            'Num_documento.regex'    => 'El documento debe tener 8 números, un guion y 1 número. Ejemplo: 01234567-8.',

            'Nombres.required'   => 'Escribe los nombres.',
            'Apellidos.required' => 'Escribe los apellidos.',

            'NombreUsuario.required' => 'Escribe un nombre de usuario.',
            'NombreUsuario.unique'   => 'Ese nombre de usuario ya está en uso. Prueba con otro.',

            'EstadoEmpleado.required' => 'Elige un estado.',
            'EstadoEmpleado.in'       => 'Elige una opción de la lista.',

            'Codigo_Supervisor.exists' => 'El supervisor elegido no es válido.',
            'rol.in'                   => 'Elige una opción de la lista.',
        ];
    }
}
