<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaracteristicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['nullable', 'string', 'max:500'],
            'factor_id'         => ['required', 'exists:factors,id_factor'],
            'responsable'       => ['nullable', 'exists:users,id'],
            'fecha_inicio'      => ['required', 'date'],
            'fecha_fin'         => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'status_id'         => ['required', 'exists:status_cna,id_status'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'El nombre de la característica es obligatorio.',
            'factor_id.required'       => 'Debe seleccionar un factor.',
            'factor_id.exists'         => 'El factor seleccionado no existe.',
            'fecha_inicio.required'    => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'       => 'La fecha de fin es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'status_id.required'       => 'El estado es obligatorio.',
        ];
    }
}