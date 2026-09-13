<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfiguracaoTaxaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Apenas o gestor pode alterar a taxa (ver ConfiguracaoTaxaPolicy).
        return $this->user()?->can('update', \App\Models\ConfiguracaoTaxa::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'taxa_fixa' => ['required', 'numeric', 'min:0'],
            'valor_excedente' => ['required', 'numeric', 'min:0'],
            'limite_m3' => ['required', 'numeric', 'min:0'],
        ];
    }
}
