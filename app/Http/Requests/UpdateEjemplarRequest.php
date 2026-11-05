<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEjemplarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ejemplar = $this->route('ejemplar');
        $estados = ['Disponible', 'Mantenimiento'];

        // Si ya está prestado, no se puede cambiar a mano
        if ($ejemplar && $ejemplar->Estado_Ejemplar === 'Prestado') {
            $estados = ['Prestado'];
        }

        return [
            'Ubicacion_Estante' => ['required', 'string', 'max:30'],
            'Estado_Ejemplar'   => ['required', Rule::in($estados)],
        ];
    }

    public function attributes(): array
    {
        return [
            'Ubicacion_Estante' => 'ubicación',
            'Estado_Ejemplar'   => 'estado',
        ];
    }
}
