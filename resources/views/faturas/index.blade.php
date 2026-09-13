@extends('layouts.app')

@section('content')
    <div class="header-actions">
        <h1>Faturas de Água</h1>
    </div>

    <form method="GET" action="{{ route('faturas.index') }}" class="filter-bar">
        <div class="form-group">
            <label for="mes">Mês</label>
            <select id="mes" name="mes" class="form-control">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                        {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label for="ano">Ano</label>
            <input type="number" id="ano" name="ano" class="form-control" value="{{ $ano }}" style="width: 100px;">
        </div>

        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>

    @if ($faturas->isEmpty())
        <div class="card" style="text-align: center; color: #64748b;">
            Nenhuma fatura encontrada para {{ str_pad($mes, 2, '0', STR_PAD_LEFT) }}/{{ $ano }}.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Consumidor</th>
                    <th>Nº Medidor</th>
                    <th>Consumo</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faturas as $fatura)
                    @php
                        $msgWhatsApp = urlencode("Olá " . $fatura->consumidor->nome . ", sua fatura de água ref. " . str_pad($fatura->leitura->mes_referencia, 2, '0', STR_PAD_LEFT) . "/" . $fatura->leitura->ano_referencia . " no valor de R$ " . number_format($fatura->valor_total, 2, ',', '.') . " está disponível.");
                        $cleanPhone = preg_replace('/\D/', '', $fatura->consumidor->telefone);
                    @endphp
                    <tr>
                        <td><strong>{{ $fatura->consumidor->nome }}</strong></td>
                        <td>{{ $fatura->consumidor->numero_medidor }}</td>
                        <td>{{ number_format($fatura->leitura->consumo_m3, 3, ',', '.') }} m³</td>
                        <td><strong>R$ {{ number_format($fatura->valor_total, 2, ',', '.') }}</strong></td>
                        <td>
                            @if ($fatura->status === 'pago')
                                <span class="badge badge-pago">Pago</span>
                            @else
                                <span class="badge badge-pendente">Pendente</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: .5rem; align-items: center;">
                            @if ($fatura->status === 'pendente' && auth()->user()->isGestor())
                                <form method="POST" action="{{ route('faturas.pagar', $fatura) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">Marcar Pago</button>
                                </form>
                            @endif

                            @if (!empty($cleanPhone))
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ $msgWhatsApp }}" target="_blank" class="btn btn-whatsapp btn-sm">
                                    WhatsApp
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
