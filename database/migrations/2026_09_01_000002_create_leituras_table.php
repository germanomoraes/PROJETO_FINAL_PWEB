<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leituras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumidor_id')->constrained('consumidores')->cascadeOnDelete();
            $table->unsignedTinyInteger('mes_referencia');
            $table->unsignedSmallInteger('ano_referencia');
            $table->decimal('leitura_anterior', 10, 3);
            $table->decimal('leitura_atual', 10, 3);
            $table->decimal('consumo_m3', 10, 3);
            $table->timestamps();

            // Evolução: garante no banco a regra "uma leitura por consumidor
            // por mês", reforçando em nível de dados o que o LeituraService
            // já valida na aplicação.
            $table->unique(['consumidor_id', 'mes_referencia', 'ano_referencia'], 'leitura_unica_por_mes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leituras');
    }
};
