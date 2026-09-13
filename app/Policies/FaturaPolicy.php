<?php

namespace App\Policies;

use App\Models\Fatura;
use App\Models\User;

class FaturaPolicy
{
    /**
     * Somente o gestor pode marcar uma fatura como paga.
     */
    public function marcarPaga(User $user, Fatura $fatura): bool
    {
        return $user->role === 'gestor';
    }
}
