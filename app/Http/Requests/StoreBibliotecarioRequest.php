<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBibliotecarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('bibliotecarios.crear');
    }

    public function rules(): array
    {
        return [
            'Num_documento' => ['required', 'regex:/^\d{8}-\d$/', 'unique:bibliotecarios,Num_documento'],
            'Nombres'   => ['required', 'max:50', "regex:/^[\pL\s'-]+$/u"],
            'Apellidos' => ['required', 'max:50', "regex:/^[\pL\s'-]+$/u"],
            'NombreUsuario'     => ['required', 'string', 'max:30', 'unique:bibliotecarios,NombreUsuario'],
            'EstadoEmpleado'    => ['required', Rule::in(['Activo', 'Inactivo'])],
            'Codigo_Supervisor' => ['nullable', 'exists:bibliotecarios,Codigo_Bibliotecario'],

            // cuenta de acceso
            'crear_cuenta'      => ['nullable', 'boolean'],
            'name'              => ['required_if:crear_cuenta,1', 'nullable', 'max:255', "regex:/^[\pL\s'-]+$/u"],
            'email'             => ['required_if:crear_cuenta,1', 'nullable', 'email', 'unique:users,email'],
            'password'          => ['required_if:crear_cuenta,1', 'nullable', 'string', 'min:8'],
            'rol'               => ['required_if:crear_cuenta,1', 'nullable', Rule::in(['Administrador', 'Bibliotecario'])],
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
            'Nombres.regex'   => 'Los nombres solo pueden llevar letras.',
            'Apellidos.regex' => 'Los apellidos solo pueden llevar letras.',
            'name.regex'      => 'El nombre solo puede llevar letras.',

            'NombreUsuario.required' => 'Escribe un nombre de usuario.',
            'NombreUsuario.unique'   => 'Ese nombre de usuario ya está en uso. Prueba con otro.',

            'EstadoEmpleado.required' => 'Elige un estado.',
            'EstadoEmpleado.in'       => 'Elige una opción de la lista.',

            'Codigo_Supervisor.exists' => 'El supervisor elegido no es válido.',

            'name.required_if'     => 'Escribe el nombre completo de la persona.',
            'email.required_if'    => 'Escribe el correo electrónico.',
            'email.email'          => 'Escribe un correo válido, por ejemplo nombre@correo.com.',
            'email.unique'         => 'Ya existe una cuenta con ese correo.',
            'password.required_if' => 'Escribe una contraseña.',
            'password.min'         => 'La contraseña debe tener al menos 8 caracteres.',
            'rol.required_if'      => 'Elige un rol.',
            'rol.in'               => 'Elige una opción de la lista.',
        ];
    }
}
