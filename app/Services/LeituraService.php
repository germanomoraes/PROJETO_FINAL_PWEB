<?php

namespace App\Services;

use App\Models\Consumidor;
use App\Models\Leitura;
use Illuminate\Support\Facades\DB;

/**
 * Centraliza a regra de negócio de registro de leitura mensal.
 *
 * Antes da evolução, o cálculo de consumo e as validações de
 * "leitura atual >= leitura anterior" e "uma leitura por mês"
 * estavam misturados dentro do LeituraController, dificultando
 * reaproveitamento e testes automatizados. Extrair essa lógica para
 * um Service isola a regra de negócio da camada HTTP (Controller),
 * tornando-a testável isoladamente e reutilizável (ex: por um futuro
 * import em lote ou por uma API).
 */
class LeituraService
{
    public function registrar(Consumidor $consumidor, int $mes, int $ano, float $leituraAtual): Leitura
    {
        $this->garantirLeituraUnicaNoMes($consumidor, $mes, $ano);

        $leituraAnterior = optional($consumidor->ultimaLeitura())->leitura_atual ?? 0;

        if ($leituraAtual < $leituraAnterior) {
            throw new LeituraException(
                "A leitura atual ({$leituraAtual} m³) não pode ser menor que a leitura anterior ({$leituraAnterior} m³)."
            );
        }

        $consumo = round($leituraAtual - $leituraAnterior, 3);

        return DB::transaction(function () use ($consumidor, $mes, $ano, $leituraAnterior, $leituraAtual, $consumo) {
            return Leitura::create([
                'consumidor_id' => $consumidor->id,
                'mes_referencia' => $mes,
                'ano_referencia' => $ano,
                'leitura_anterior' => $leituraAnterior,
                'leitura_atual' => $leituraAtual,
                'consumo_m3' => $consumo,
            ]);
        });
    }

    private function garantirLeituraUnicaNoMes(Consumidor $consumidor, int $mes, int $ano): void
    {
        $existe = $consumidor->leituras()
            ->where('mes_referencia', $mes)
            ->where('ano_referencia', $ano)
            ->exists();

        if ($existe) {
            throw new LeituraException(
                "Já existe uma leitura registrada para {$consumidor->nome} em {$mes}/{$ano}."
            );
        }
    }
}
