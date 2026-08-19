<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShortUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'original_url' => ['required', 'url', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'original_url.required' => 'A URL original é obrigatória.',
            'original_url.url' => 'Informe uma URL válida (ex: https://meusite.com/produto/123).',
            'original_url.max' => 'A URL não pode ter mais de :max caracteres.',
        ];
    }
}
