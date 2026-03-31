<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],

            'first_surname' => ['required', 'string', 'max:255'],
            'second_last_name' => ['nullable', 'string', 'max:255'],

            'id_area' => ['required', 'exists:d_area,id_area'],
            'id_departamento' => ['required', 'exists:departamento,id_departamento'],
        ];
    }
}
