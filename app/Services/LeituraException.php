<?php

namespace App\Services;

use Exception;

/**
 * Exceção de domínio lançada pelo LeituraService quando uma regra de
 * negócio é violada (ex: leitura menor que a anterior, leitura duplicada
 * no mês). Permite que o Controller trate o erro sem conhecer detalhes
 * de implementação da regra.
 */
class LeituraException extends Exception
{
}
