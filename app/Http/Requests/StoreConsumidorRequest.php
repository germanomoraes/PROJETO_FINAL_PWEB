<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConsumidorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150'],
            'endereco' => ['required', 'string', 'max:255'],
            'numero_medidor' => [
                'required',
                'string',
                'max:50',
                Rule::unique('consumidores', 'numero_medidor')->ignore($this->route('consumidor')),
            ],
            'telefone' => ['required', 'string', 'max:20'],
        ];
    }
}
