<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGraduateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $graduateId = $this->route('graduate')->id;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:graduates,email,{$graduateId}"],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female'],
            'graduation_year' => ['required', 'integer', 'min:1900', 'max:'.date('Y')],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'career_id' => ['required', 'exists:careers,id'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'El nombre es requerido.',
            'last_name.required' => 'El apellido es requerido.',
            'email.required' => 'El email es requerido.',
            'email.email' => 'El email debe ser un correo válido.',
            'email.unique' => 'Este email ya está registrado.',
            'gender.required' => 'El género es requerido.',
            'gender.in' => 'El género debe ser masculino o femenino.',
            'graduation_year.required' => 'El año de graduación es requerido.',
            'graduation_year.integer' => 'El año de graduación debe ser un número.',
            'photo.image' => 'El archivo debe ser una imagen.',
            'photo.mimes' => 'La imagen debe estar en formato jpeg, png, jpg o gif.',
            'photo.max' => 'La imagen no debe pesar más de 2MB.',
            'career_id.required' => 'La carrera es requerida.',
            'career_id.exists' => 'La carrera seleccionada no existe.',
        ];
    }
}
