<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDevolucionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'detalles'                 => ['required', 'array'],
            'detalles.*.devolver'      => ['sometimes', 'boolean'],
            'detalles.*.estado'        => ['required_with:detalles.*.devolver', Rule::in(['Buen estado', 'Dañado', 'Perdido'])],
            'Observaciones'            => ['nullable', 'string', 'max:200'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if (empty($this->items())) {
                $v->errors()->add('detalles', 'Selecciona al menos un ejemplar a devolver.');
            }
        });
    }

    /** @return array<int,string> [Codigo_Detalle => EstadoEntrega] */
    public function items(): array
    {
        return collect($this->input('detalles', []))
            ->filter(fn($d) => !empty($d['devolver']))
            ->map(fn($d) => $d['estado'])
            ->all();
    }
}
