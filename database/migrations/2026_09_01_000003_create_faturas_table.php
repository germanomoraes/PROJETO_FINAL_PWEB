<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leitura_id')->constrained('leituras')->cascadeOnDelete();
            $table->foreignId('consumidor_id')->constrained('consumidores')->cascadeOnDelete();
            $table->decimal('valor_total', 10, 2);
            $table->enum('status', ['pendente', 'pago'])->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faturas');
    }
};
