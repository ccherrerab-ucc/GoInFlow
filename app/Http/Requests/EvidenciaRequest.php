<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Principio: Responsabilidad Única — la validación vive aquí, no en el controlador.
 * Principio: Abierto/Cerrado — se extiende sin tocar el controlador.
 */
class EvidenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // El middleware 'rol' y las Policies controlan el acceso
    }

    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:255'],
            'descripcion'  => ['nullable', 'string', 'max:1000'],
            'id_aspecto'   => ['required', 'exists:aspectos,id_aspecto'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado_actual'=> ['nullable', 'exists:estado_documento,id_estado'],
            'status_id'    => ['required', 'exists:status_cna,id_status'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'            => 'El nombre de la evidencia es obligatorio.',
            'nombre.max'                 => 'El nombre no puede superar 255 caracteres.',
            'id_aspecto.required'        => 'Debe seleccionar un aspecto.',
            'id_aspecto.exists'          => 'El aspecto seleccionado no existe.',
            'fecha_inicio.required'      => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'         => 'La fecha de fin es obligatoria.',
            'fecha_fin.after_or_equal'   => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'estado_actual.exists'       => 'El estado del documento seleccionado no es válido.',
            'status_id.required'         => 'El estado es obligatorio.',
            'status_id.exists'           => 'El estado seleccionado no es válido.',
        ];
    }
}
