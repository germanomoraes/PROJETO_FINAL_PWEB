<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use App\Services\FaturaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaturaController extends Controller
{
    public function __construct(private FaturaService $faturaService)
    {
    }

    public function index(Request $request): View
    {
        $mes = (int) $request->input('mes', now()->month);
        $ano = (int) $request->input('ano', now()->year);

        $faturas = Fatura::doMes($mes, $ano)->with(['consumidor', 'leitura'])->get();

        return view('faturas.index', compact('faturas', 'mes', 'ano'));
    }

    public function marcarPaga(Fatura $fatura): RedirectResponse
    {
        $this->authorize('marcarPaga', $fatura);

        $this->faturaService->marcarComoPaga($fatura);

        return back()->with('success', 'Fatura marcada como paga.');
    }
}
