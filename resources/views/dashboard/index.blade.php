@extends('layouts.app')

@section('content')
    <h1>Dashboard — {{ $resumo['mes'] }}/{{ $resumo['ano'] }}</h1>

    <div class="cards">
        <div class="card">
            <span>Faturas no mês</span>
            <strong>{{ $resumo['quantidade_faturas'] }}</strong>
        </div>
        <div class="card">
            <span>Consumo total</span>
            <strong>{{ $resumo['consumo_total_m3'] }} m³</strong>
        </div>
        <div class="card">
            <span>Total faturado</span>
            <strong>R$ {{ number_format($resumo['valor_total_faturado'], 2, ',', '.') }}</strong>
        </div>
        <div class="card">
            <span>Pendente de pagamento</span>
            <strong>R$ {{ number_format($resumo['valor_total_pendente'], 2, ',', '.') }}</strong>
        </div>
        <div class="card">
            <span>Já pago</span>
            <strong>R$ {{ number_format($resumo['valor_total_pago'], 2, ',', '.') }}</strong>
        </div>
        <div class="card">
            <span>Faturas pendentes / pagas</span>
            <strong>{{ $resumo['faturas_pendentes'] }} / {{ $resumo['faturas_pagas'] }}</strong>
        </div>
    </div>
@endsection
