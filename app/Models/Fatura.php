<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'leitura_id',
        'consumidor_id',
        'valor_total',
        'status',
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
    ];

    const STATUS_PENDENTE = 'pendente';
    const STATUS_PAGO = 'pago';

    public function leitura(): BelongsTo
    {
        return $this->belongsTo(Leitura::class);
    }

    public function consumidor(): BelongsTo
    {
        return $this->belongsTo(Consumidor::class);
    }

    public function scopeDoMes($query, int $mes, int $ano)
    {
        return $query->whereHas('leitura', function ($q) use ($mes, $ano) {
            $q->where('mes_referencia', $mes)->where('ano_referencia', $ano);
        });
    }

    public function scopePendentes($query)
    {
        return $query->where('status', self::STATUS_PENDENTE);
    }
}
