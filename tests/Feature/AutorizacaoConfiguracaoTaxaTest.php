<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testa a evolução de segurança: apenas usuários com role "gestor"
 * podem alterar a configuração de taxa.
 */
class AutorizacaoConfiguracaoTaxaTest extends TestCase
{
    use RefreshDatabase;

    public function test_leiturista_nao_pode_alterar_taxa(): void
    {
        $leiturista = User::factory()->create(['role' => 'leiturista']);

        $response = $this->actingAs($leiturista)->put('/configuracoes/taxa', [
            'taxa_fixa' => 40,
            'valor_excedente' => 5,
            'limite_m3' => 10,
        ]);

        $response->assertForbidden();
    }

    public function test_gestor_pode_alterar_taxa(): void
    {
        $gestor = User::factory()->create(['role' => 'gestor']);

        $response = $this->actingAs($gestor)->put('/configuracoes/taxa', [
            'taxa_fixa' => 40,
            'valor_excedente' => 5,
            'limite_m3' => 10,
        ]);

        $response->assertRedirect(route('configuracoes.edit'));
        $this->assertDatabaseHas('configuracoes_taxa', ['taxa_fixa' => 40]);
    }
}
