<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEjemplarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Codigo_ISBN'       => ['required', 'exists:libros,Codigo_ISBN'],
            'Ubicacion_Estante' => ['required', 'string', 'max:30'],
            'Estado_Ejemplar'   => ['required', Rule::in(['Disponible', 'Mantenimiento'])],
            'cantidad'          => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'Codigo_ISBN'       => 'libro',
            'Ubicacion_Estante' => 'ubicación',
            'Estado_Ejemplar'   => 'estado',
            'cantidad'          => 'cantidad',
        ];
    }

}
