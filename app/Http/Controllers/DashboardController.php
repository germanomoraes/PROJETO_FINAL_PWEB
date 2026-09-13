<?php

namespace App\Http\Controllers;

use App\Services\RelatorioMensalService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Nova funcionalidade adicionada nesta evolução: painel com o resumo
 * financeiro e de consumo do mês selecionado, para o gestor acompanhar
 * a saúde financeira da associação sem precisar somar faturas manualmente.
 */
class DashboardController extends Controller
{
    public function __construct(private RelatorioMensalService $relatorioMensalService)
    {
    }

    public function index(Request $request): View
    {
        $mes = (int) $request->input('mes', now()->month);
        $ano = (int) $request->input('ano', now()->year);

        $resumo = $this->relatorioMensalService->gerar($mes, $ano);

        return view('dashboard.index', compact('resumo'));
    }
}
