<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeituraRequest;
use App\Models\Consumidor;
use App\Models\Leitura;
use App\Services\FaturaService;
use App\Services\LeituraException;
use App\Services\LeituraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller enxuto: apenas orquestra a requisição HTTP e delega toda a
 * regra de negócio para LeituraService e FaturaService. Comparar com a
 * versão anterior (cálculo de consumo e validação de sequência de
 * leitura feitos diretamente aqui) é o principal ponto de comparação
 * antes/depois do relatório técnico.
 */
class LeituraController extends Controller
{
    public function __construct(
        private LeituraService $leituraService,
        private FaturaService $faturaService,
    ) {
    }

    public function index(): View
    {
        $leituras = Leitura::with('consumidor')
            ->orderByDesc('ano_referencia')
            ->orderByDesc('mes_referencia')
            ->paginate(15);

        return view('leituras.index', compact('leituras'));
    }

    public function create(): View
    {
        $consumidores = Consumidor::orderBy('nome')->get();

        return view('leituras.create', compact('consumidores'));
    }

    public function store(StoreLeituraRequest $request): RedirectResponse
    {
        $consumidor = Consumidor::findOrFail($request->integer('consumidor_id'));

        try {
            $leitura = $this->leituraService->registrar(
                $consumidor,
                $request->integer('mes_referencia'),
                $request->integer('ano_referencia'),
                (float) $request->input('leitura_atual'),
            );

            $this->faturaService->gerarParaLeitura($leitura);
        } catch (LeituraException $e) {
            return back()->withInput()->withErrors(['leitura_atual' => $e->getMessage()]);
        }

        return redirect()
            ->route('leituras.index')
            ->with('success', 'Leitura registrada e fatura gerada com sucesso.');
    }
}
