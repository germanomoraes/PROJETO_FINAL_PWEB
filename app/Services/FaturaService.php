<?php

namespace App\Services;

use App\Models\ConfiguracaoTaxa;
use App\Models\Fatura;
use App\Models\Leitura;

/**
 * Calcula e gera a fatura a partir de uma leitura, aplicando a regra de
 * cobrança configurável (taxa fixa até o limite de m³, mais valor por
 * milheiro excedente).
 *
 * Evolução em relação à versão anterior: o cálculo do valor da fatura
 * estava hardcoded (R$ 25,00 / R$ 2,00) diretamente no FaturaController,
 * o que obrigava alterar código-fonte sempre que a associação decidisse
 * reajustar o valor. Agora a regra lê a ConfiguracaoTaxa vigente em
 * tempo de execução e o cálculo em si é isolado e coberto por testes
 * unitários (ver tests/Feature/FaturaCalculoTest.php).
 */
class FaturaService
{
    public function gerarParaLeitura(Leitura $leitura): Fatura
    {
        $valor = $this->calcularValor($leitura->consumo_m3);

        return Fatura::updateOrCreate(
            ['leitura_id' => $leitura->id],
            [
                'consumidor_id' => $leitura->consumidor_id,
                'valor_total' => $valor,
                'status' => Fatura::STATUS_PENDENTE,
            ]
        );
    }

    public function calcularValor(float $consumoM3): float
    {
        $config = ConfiguracaoTaxa::vigente();

        if ($consumoM3 <= $config->limite_m3) {
            return (float) $config->taxa_fixa;
        }

        $excedenteM3 = $consumoM3 - (float) $config->limite_m3;
        $milheirosExcedentes = ceil(($excedenteM3 * 1000) / 1000);

        return round((float) $config->taxa_fixa + ($milheirosExcedentes * (float) $config->valor_excedente), 2);
    }

    public function marcarComoPaga(Fatura $fatura): Fatura
    {
        $fatura->update(['status' => Fatura::STATUS_PAGO]);

        return $fatura;
    }
}
