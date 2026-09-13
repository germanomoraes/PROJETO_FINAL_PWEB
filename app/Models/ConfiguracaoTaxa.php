<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoTaxa extends Model
{
    protected $table = 'configuracoes_taxa';

    protected $fillable = [
        'taxa_fixa',
        'valor_excedente',
        'limite_m3',
    ];

    protected $casts = [
        'taxa_fixa' => 'decimal:2',
        'valor_excedente' => 'decimal:2',
        'limite_m3' => 'decimal:3',
    ];

    /**
     * Configuração de taxa vigente (registro mais recente).
     * Evita repetir a consulta em vários controllers/services.
     */
    public static function vigente(): self
    {
        return static::latest('id')->firstOrCreate([], [
            'taxa_fixa' => 25.00,
            'valor_excedente' => 2.00,
            'limite_m3' => 10.000,
        ]);
    }
}
