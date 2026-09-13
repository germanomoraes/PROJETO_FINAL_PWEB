<?php

namespace Database\Factories;

use App\Models\Consumidor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsumidorFactory extends Factory
{
    protected $model = Consumidor::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->name(),
            'endereco' => $this->faker->address(),
            'numero_medidor' => $this->faker->unique()->numerify('MED-#####'),
            'telefone' => $this->faker->numerify('85#########'),
        ];
    }
}
