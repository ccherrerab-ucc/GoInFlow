<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Principio: Responsabilidad Única — la validación vive aquí, no en el controlador.
 * Principio: Abierto/Cerrado — se extiende sin tocar el controlador.
 */
class ResultadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // El middleware 'rol' y las Policies controlan el acceso
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'tipo_relacion' => ['required', Rule::in(['factor', 'caracteristica', 'aspecto'])],
            'id_referencia' => ['required', 'integer', 'min:1'],
            'fecha_inicio'  => ['required', 'date'],
            'fecha_fin'     => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'status_id'     => ['required', 'exists:status_cna,id_status'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'El nombre del resultado es obligatorio.',
            'name.max'                => 'El nombre no puede superar 255 caracteres.',
            'tipo_relacion.required'  => 'Debe seleccionar el tipo de relación.',
            'tipo_relacion.in'        => 'El tipo debe ser factor, característica o aspecto.',
            'id_referencia.required'  => 'El ID de referencia es obligatorio.',
            'id_referencia.integer'   => 'El ID de referencia debe ser un número entero.',
            'fecha_inicio.required'   => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'      => 'La fecha de fin es obligatoria.',
            'fecha_fin.after_or_equal'=> 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'status_id.required'      => 'El estado es obligatorio.',
            'status_id.exists'        => 'El estado seleccionado no es válido.',
        ];
    }
}
