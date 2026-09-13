<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracoes_taxa', function (Blueprint $table) {
            $table->id();
            $table->decimal('taxa_fixa', 10, 2)->default(25.00);
            $table->decimal('valor_excedente', 10, 2)->default(2.00);
            $table->decimal('limite_m3', 10, 3)->default(10.000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes_taxa');
    }
};
