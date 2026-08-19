<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShortUrlRequest extends FormRequest
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
            'custom_code' => [
                'nullable',
                'string',
                'regex:/^[A-Za-z0-9_-]{3,10}$/',
                Rule::notIn(['up', 'api', 'docs']),
                Rule::unique('short_urls', 'short_code'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'original_url.required' => 'A URL original é obrigatória.',
            'original_url.url' => 'Informe uma URL válida (ex: https://meusite.com/produto/123).',
            'original_url.max' => 'A URL não pode ter mais de :max caracteres.',
            'custom_code.regex' => 'O código customizado deve ter entre 3 e 10 caracteres, usando apenas letras, números, hífen ou underscore.',
            'custom_code.not_in' => 'Esse código é reservado e não pode ser usado.',
            'custom_code.unique' => 'Esse código já está em uso, escolha outro.',
        ];
    }
}
