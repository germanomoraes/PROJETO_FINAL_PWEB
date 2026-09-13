<?php

namespace Tests\Feature;

use App\Models\Consumidor;
use App\Services\LeituraException;
use App\Services\LeituraService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeituraServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calcula_consumo_como_diferenca_entre_leituras(): void
    {
        $consumidor = Consumidor::factory()->create();
        $service = new LeituraService();

        $leitura = $service->registrar($consumidor, 1, 2026, 12.5);

        $this->assertEquals(0, $leitura->leitura_anterior);
        $this->assertEquals(12.5, $leitura->consumo_m3);
    }

    public function test_impede_leitura_atual_menor_que_a_anterior(): void
    {
        $consumidor = Consumidor::factory()->create();
        $service = new LeituraService();

        $service->registrar($consumidor, 1, 2026, 20.0);

        $this->expectException(LeituraException::class);
        $service->registrar($consumidor, 2, 2026, 15.0);
    }

    public function test_impede_duas_leituras_no_mesmo_mes(): void
    {
        $consumidor = Consumidor::factory()->create();
        $service = new LeituraService();

        $service->registrar($consumidor, 3, 2026, 10.0);

        $this->expectException(LeituraException::class);
        $service->registrar($consumidor, 3, 2026, 12.0);
    }
}
