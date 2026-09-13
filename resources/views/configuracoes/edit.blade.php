@extends('layouts.app')

@section('content')
    <div class="header-actions">
        <h1>Configuração de Taxa</h1>
    </div>

    @if ($errors->any())
        <div class="alert-danger">
            <strong>Atenção:</strong>
            <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-box">
        <form method="POST" action="{{ route('configuracoes.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="taxa_fixa">Taxa Fixa Básica (R$)</label>
                <input type="number" step="0.01" min="0" id="taxa_fixa" name="taxa_fixa" class="form-control" value="{{ old('taxa_fixa', $configuracao->taxa_fixa) }}" required>
                <small style="color: #64748b;">Valor cobrado mensalmente até o limite de consumo franqueado.</small>
            </div>

            <div class="form-group">
                <label for="limite_m3">Limite de Consumo Incluso (m³)</label>
                <input type="number" step="0.001" min="0" id="limite_m3" name="limite_m3" class="form-control" value="{{ old('limite_m3', $configuracao->limite_m3) }}" required>
                <small style="color: #64748b;">Volume em metros cúbicos coberto pela taxa fixa (padrão: 10 m³ ou 10.000 L).</small>
            </div>

            <div class="form-group">
                <label for="valor_excedente">Valor por m³ Excedente (R$)</label>
                <input type="number" step="0.01" min="0" id="valor_excedente" name="valor_excedente" class="form-control" value="{{ old('valor_excedente', $configuracao->valor_excedente) }}" required>
                <small style="color: #64748b;">Valor cobrado por metro cúbico (ou fração) que ultrapassar o limite.</small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Salvar Configurações de Taxa</button>
        </form>
    </div>
@endsection
