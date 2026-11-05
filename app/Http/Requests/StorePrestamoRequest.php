<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePrestamoRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'Codigo_Socio' => ['required', 'exists:socios,Codigo_Socio'],
            'ejemplares'   => ['required', 'array', 'min:1', 'max:' . config('biblioteca.max_ejemplares_socio')],
            'ejemplares.*' => ['integer', 'distinct', 'exists:ejemplars,Codigo_Ejemplar'],
        ];
    }

    public function attributes(): array
    {
        return ['Codigo_Socio' => 'socio', 'ejemplares' => 'ejemplares'];
    }
}
