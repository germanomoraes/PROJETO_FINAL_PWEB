<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'gestor@teste.com'],
            [
                'name' => 'Gestor da Associação',
                'password' => bcrypt('12345678'),
                'role' => 'gestor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'leiturista@teste.com'],
            [
                'name' => 'Leiturista',
                'password' => bcrypt('12345678'),
                'role' => 'leiturista',
            ]
        );
    }
}
