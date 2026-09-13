<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsumidorRequest;
use App\Models\Consumidor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConsumidorController extends Controller
{
    public function index(): View
    {
        $consumidores = Consumidor::orderBy('nome')->paginate(15);

        return view('consumidores.index', compact('consumidores'));
    }

    public function create(): View
    {
        return view('consumidores.create');
    }

    public function store(StoreConsumidorRequest $request): RedirectResponse
    {
        Consumidor::create($request->validated());

        return redirect()->route('consumidores.index')->with('success', 'Consumidor cadastrado com sucesso.');
    }

    public function edit(Consumidor $consumidor): View
    {
        return view('consumidores.edit', compact('consumidor'));
    }

    public function update(StoreConsumidorRequest $request, Consumidor $consumidor): RedirectResponse
    {
        $consumidor->update($request->validated());

        return redirect()->route('consumidores.index')->with('success', 'Consumidor atualizado com sucesso.');
    }
}
