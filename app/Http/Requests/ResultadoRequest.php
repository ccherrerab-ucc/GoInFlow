<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'description'         => ['nullable', 'string', 'max:1000'],
            'fecha_inicio'        => ['required', 'date'],
            'fecha_fin'           => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'status_id'           => ['required', 'exists:status_cna,id_status'],
            'evidencias'          => ['nullable', 'array'],
            'evidencias.*'        => ['integer', 'exists:evidencias,id_evidencia'],
            'evidencias_enviadas' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'El nombre del resultado es obligatorio.',
            'name.max'                 => 'El nombre no puede superar 255 caracteres.',
            'fecha_inicio.required'    => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'       => 'La fecha de fin es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'status_id.required'       => 'El estado es obligatorio.',
            'status_id.exists'         => 'El estado seleccionado no es válido.',
            'evidencias.*.integer'     => 'Los IDs de evidencia deben ser números enteros.',
            'evidencias.*.exists'      => 'Una o más evidencias seleccionadas no existen.',
        ];
    }
}
