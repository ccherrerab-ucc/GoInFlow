<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Principio: Responsabilidad Única — la validación vive aquí, no en el controlador.
 * Principio: Abierto/Cerrado — se extiende sin tocar el controlador.
 */
class FactorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // El middleware 'rol' ya controla el acceso
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'responsable' => ['nullable', 'exists:users,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'status_id'   => ['required', 'exists:status_cna,id_status'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'El nombre del factor es obligatorio.',
            'name.max'                 => 'El nombre no puede superar 255 caracteres.',
            'fecha_inicio.required'    => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'       => 'La fecha de fin es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'status_id.required'       => 'El estado es obligatorio.',
            'status_id.exists'         => 'El estado seleccionado no es válido.',
            'responsable.exists'       => 'El responsable seleccionado no existe.',
        ];
    }
}