@extends('layouts.app')

@section('content')
    <div class="header-actions">
        <h1>Consumidores</h1>
        <a href="{{ route('consumidores.create') }}" class="btn btn-primary">+ Novo Consumidor</a>
    </div>

    @if ($consumidores->isEmpty())
        <div class="card" style="text-align: center; color: #64748b;">
            Nenhum consumidor cadastrado ainda.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Nº do Medidor</th>
                    <th>Telefone</th>
                    <th style="width: 100px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consumidores as $consumidor)
                    <tr>
                        <td><strong>{{ $consumidor->nome }}</strong></td>
                        <td>{{ $consumidor->endereco }}</td>
                        <td>{{ $consumidor->numero_medidor }}</td>
                        <td>{{ $consumidor->telefone }}</td>
                        <td>
                            <a href="{{ route('consumidores.edit', $consumidor) }}" class="btn btn-secondary btn-sm">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $consumidores->links() }}
        </div>
    @endif
@endsection
