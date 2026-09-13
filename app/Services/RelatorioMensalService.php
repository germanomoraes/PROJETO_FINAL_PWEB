<?php

namespace App\Services;

use App\Models\Fatura;
use App\Models\Leitura;

/**
 * Nova funcionalidade (evolução funcional): consolida os indicadores do
 * mês para o Dashboard do gestor — total faturado, total consumido,
 * faturas pendentes/pagas. Antes da evolução essa informação só existia
 * espalhada na listagem de faturas, exigindo soma manual pelo gestor.
 */
class RelatorioMensalService
{
    public function gerar(int $mes, int $ano): array
    {
        $faturas = Fatura::doMes($mes, $ano)->get();

        $consumoTotal = Leitura::where('mes_referencia', $mes)
            ->where('ano_referencia', $ano)
            ->sum('consumo_m3');

        return [
            'mes' => $mes,
            'ano' => $ano,
            'quantidade_faturas' => $faturas->count(),
            'valor_total_faturado' => round((float) $faturas->sum('valor_total'), 2),
            'valor_total_pendente' => round((float) $faturas->where('status', Fatura::STATUS_PENDENTE)->sum('valor_total'), 2),
            'valor_total_pago' => round((float) $faturas->where('status', Fatura::STATUS_PAGO)->sum('valor_total'), 2),
            'consumo_total_m3' => round((float) $consumoTotal, 3),
            'faturas_pendentes' => $faturas->where('status', Fatura::STATUS_PENDENTE)->count(),
            'faturas_pagas' => $faturas->where('status', Fatura::STATUS_PAGO)->count(),
        ];
    }
}
