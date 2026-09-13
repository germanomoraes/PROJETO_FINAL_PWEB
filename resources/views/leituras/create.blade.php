@extends('layouts.app')

@section('content')
    <div class="header-actions">
        <h1>Nova Leitura de Medidor</h1>
        <a href="{{ route('leituras.index') }}" class="btn btn-secondary">Voltar</a>
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
        <form method="POST" action="{{ route('leituras.store') }}">
            @csrf
            <div class="form-group">
                <label for="consumidor_id">Consumidor</label>
                <select id="consumidor_id" name="consumidor_id" class="form-control" required>
                    <option value="">Selecione o consumidor...</option>
                    @foreach ($consumidores as $consumidor)
                        <option value="{{ $consumidor->id }}" {{ old('consumidor_id') == $consumidor->id ? 'selected' : '' }}>
                            {{ $consumidor->nome }} (Medidor: {{ $consumidor->numero_medidor }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 1rem;">
                <div class="form-group" style="flex: 1;">
                    <label for="mes_referencia">Mês de Referência</label>
                    <select id="mes_referencia" name="mes_referencia" class="form-control" required>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('mes_referencia', now()->month) == $m ? 'selected' : '' }}>
                                {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="ano_referencia">Ano de Referência</label>
                    <input type="number" id="ano_referencia" name="ano_referencia" class="form-control" value="{{ old('ano_referencia', now()->year) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="leitura_atual">Leitura Atual no Medidor (m³)</label>
                <input type="number" step="0.001" min="0" id="leitura_atual" name="leitura_atual" class="form-control" value="{{ old('leitura_atual') }}" placeholder="Ex: 15.250" required>
                <small style="color: #64748b; display: block; margin-top: 0.25rem;">
                    O sistema buscará a leitura anterior e calculará o consumo e o valor da fatura automaticamente.
                </small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Registrar Leitura e Gerar Fatura</button>
        </form>
    </div>
@endsection
