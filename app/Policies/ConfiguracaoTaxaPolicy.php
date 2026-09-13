<?php

namespace App\Policies;

use App\Models\ConfiguracaoTaxa;
use App\Models\User;

/**
 * Evolução de segurança: antes, qualquer usuário autenticado podia
 * alterar a taxa fixa e o valor do excedente pela rota de configuração.
 * Agora apenas usuários com papel "gestor" têm essa permissão.
 */
class ConfiguracaoTaxaPolicy
{
    public function update(User $user, ?ConfiguracaoTaxa $configuracaoTaxa = null): bool
    {
        return $user->role === 'gestor';
    }
}
