<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consumidor extends Model
{
    use HasFactory;

    protected $table = 'consumidores';

    protected $fillable = [
        'nome',
        'endereco',
        'numero_medidor',
        'telefone',
    ];

    public function leituras(): HasMany
    {
        return $this->hasMany(Leitura::class);
    }

    public function faturas(): HasMany
    {
        return $this->hasMany(Fatura::class);
    }

    /**
     * Última leitura registrada para este consumidor, usada pelo
     * LeituraService para calcular o consumo do próximo mês.
     */
    public function ultimaLeitura(): ?Leitura
    {
        return $this->leituras()
            ->orderByDesc('ano_referencia')
            ->orderByDesc('mes_referencia')
            ->first();
    }
}
