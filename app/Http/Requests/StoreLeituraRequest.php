<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Antes: validação feita manualmente dentro do LeituraController com
 * Validator::make(), sem mensagens padronizadas e sem reaproveitamento.
 * Depois: FormRequest dedicado, testável isoladamente e reaproveitável
 * caso o mesmo cadastro passe a ser exposto também via API.
 */
class StoreLeituraRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Apenas leituristas e gestores autenticados podem lançar leituras.
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'consumidor_id' => ['required', 'exists:consumidores,id'],
            'mes_referencia' => ['required', 'integer', 'between:1,12'],
            'ano_referencia' => ['required', 'integer', 'digits:4'],
            'leitura_atual' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'consumidor_id.required' => 'Selecione o consumidor.',
            'consumidor_id.exists' => 'Consumidor não encontrado.',
            'leitura_atual.min' => 'A leitura não pode ser negativa.',
        ];
    }
}
