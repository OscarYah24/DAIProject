<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta petición.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Obtiene las reglas de validación que se aplicarán a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:120',
                'min:2'
            ],
            'description' => [
                'required',
                'string',
                'max:120',
                'min:5'
            ]
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $categoryId = $this->route('category');
            $rules['name'][] = 'unique:categories,name,' . $categoryId;
        } else {
            $rules['name'][] = 'unique:categories,name';
        }

        return $rules;
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto válida.',
            'name.max' => 'El nombre no puede tener más de 120 caracteres.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.unique' => 'Ya existe una categoría con este nombre.',
            
            'description.required' => 'La descripción de la categoría es obligatoria.',
            'description.string' => 'La descripción debe ser una cadena de texto válida.',
            'description.max' => 'La descripción no puede tener más de 120 caracteres.',
            'description.min' => 'La descripción debe tener al menos 5 caracteres.',
        ];
    }

    /**
     * Obtiene los nombres de atributos personalizados para mensajes de error.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
        ];
    }

    /**
     * Preparar los datos para la validación.
     * Limpia espacios en blanco adicionales.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name ?? ''),
            'description' => trim($this->description ?? ''),
        ]);
    }

    /**
     * Configurar el validador después de que se haya creado.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->name && strlen(trim($this->name)) !== strlen($this->name)) {
                $validator->errors()->add('name', 'El nombre no puede comenzar o terminar con espacios.');
            }
            
            if ($this->description && strlen(trim($this->description)) !== strlen($this->description)) {
                $validator->errors()->add('description', 'La descripción no puede comenzar o terminar con espacios.');
            }
        });
    }
}