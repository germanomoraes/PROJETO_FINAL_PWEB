<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateConfiguracaoTaxaRequest;
use App\Models\ConfiguracaoTaxa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConfiguracaoTaxaController extends Controller
{
    public function edit(): View
    {
        $configuracao = ConfiguracaoTaxa::vigente();

        return view('configuracoes.edit', compact('configuracao'));
    }

    public function update(UpdateConfiguracaoTaxaRequest $request): RedirectResponse
    {
        // Autorização já validada dentro do próprio FormRequest (authorize()).
        ConfiguracaoTaxa::vigente()->update($request->validated());

        return redirect()->route('configuracoes.edit')->with('success', 'Configuração de taxa atualizada.');
    }
}
