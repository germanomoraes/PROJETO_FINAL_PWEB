@extends('layouts.app')

@section('content')
    <div class="header-actions">
        <h1>Leituras de Medidores</h1>
        <a href="{{ route('leituras.create') }}" class="btn btn-primary">+ Nova Leitura</a>
    </div>

    @if ($leituras->isEmpty())
        <div class="card" style="text-align: center; color: #64748b;">
            Nenhuma leitura registrada ainda.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Consumidor</th>
                    <th>Nº Medidor</th>
                    <th>Referência</th>
                    <th>Leitura Anterior</th>
                    <th>Leitura Atual</th>
                    <th>Consumo (m³)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leituras as $leitura)
                    <tr>
                        <td><strong>{{ $leitura->consumidor->nome }}</strong></td>
                        <td>{{ $leitura->consumidor->numero_medidor }}</td>
                        <td>{{ str_pad($leitura->mes_referencia, 2, '0', STR_PAD_LEFT) }}/{{ $leitura->ano_referencia }}</td>
                        <td>{{ number_format($leitura->leitura_anterior, 3, ',', '.') }} m³</td>
                        <td>{{ number_format($leitura->leitura_atual, 3, ',', '.') }} m³</td>
                        <td><strong>{{ number_format($leitura->consumo_m3, 3, ',', '.') }} m³</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $leituras->links() }}
        </div>
    @endif
@endsection
