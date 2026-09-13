<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Leitura extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumidor_id',
        'mes_referencia',
        'ano_referencia',
        'leitura_anterior',
        'leitura_atual',
        'consumo_m3',
    ];

    protected $casts = [
        'leitura_anterior' => 'decimal:3',
        'leitura_atual' => 'decimal:3',
        'consumo_m3' => 'decimal:3',
    ];

    public function consumidor(): BelongsTo
    {
        return $this->belongsTo(Consumidor::class);
    }

    public function fatura(): HasOne
    {
        return $this->hasOne(Fatura::class);
    }
}
