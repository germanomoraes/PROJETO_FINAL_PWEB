<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Evolução de segurança: adiciona o papel do usuário (gestor | leiturista)
 * para viabilizar autorização granular via Policies, algo que não existia
 * na versão anterior (todo usuário autenticado tinha acesso irrestrito).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('leiturista')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
