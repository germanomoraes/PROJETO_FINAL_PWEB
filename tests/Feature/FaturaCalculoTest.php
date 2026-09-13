<?php

namespace Tests\Feature;

use App\Models\ConfiguracaoTaxa;
use App\Services\FaturaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testa isoladamente a regra de cobrança (Etapa 5 do trabalho: testes de
 * regras de negócio). Antes da evolução essa regra só podia ser validada
 * manualmente clicando na interface; agora é verificada automaticamente
 * a cada `php artisan test`.
 */
class FaturaCalculoTest extends TestCase
{
    use RefreshDatabase;

    public function test_cobra_apenas_taxa_fixa_ate_o_limite(): void
    {
        ConfiguracaoTaxa::vigente(); // cria config padrão: 25,00 / 2,00 / 10m³

        $service = new FaturaService();

        $this->assertEquals(25.00, $service->calcularValor(10.0));
        $this->assertEquals(25.00, $service->calcularValor(7.5));
    }

    public function test_cobra_taxa_fixa_mais_excedente_por_milheiro(): void
    {
        ConfiguracaoTaxa::vigente();

        $service = new FaturaService();

        // 15 m³ = 5 m³ excedentes = 5 milheiros x R$2,00 = R$10,00 + R$25,00 = R$35,00
        $this->assertEquals(35.00, $service->calcularValor(15.0));
    }

    public function test_respeita_taxa_customizada_pelo_gestor(): void
    {
        ConfiguracaoTaxa::vigente()->update([
            'taxa_fixa' => 30.00,
            'valor_excedente' => 3.00,
            'limite_m3' => 10.000,
        ]);

        $service = new FaturaService();

        // 12 m³ = 2 m³ excedentes x R$3,00 = R$6,00 + R$30,00 = R$36,00
        $this->assertEquals(36.00, $service->calcularValor(12.0));
    }
}
