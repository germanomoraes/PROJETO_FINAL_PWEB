@extends('layouts.app')

@section('content')
    <div class="header-actions">
        <h1>Novo Consumidor</h1>
        <a href="{{ route('consumidores.index') }}" class="btn btn-secondary">Voltar</a>
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
        <form method="POST" action="{{ route('consumidores.store') }}">
            @csrf
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="form-control" value="{{ old('nome') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" class="form-control" value="{{ old('endereco') }}" required>
            </div>

            <div class="form-group">
                <label for="numero_medidor">Número do Medidor</label>
                <input type="text" id="numero_medidor" name="numero_medidor" class="form-control" value="{{ old('numero_medidor') }}" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone (WhatsApp)</label>
                <input type="text" id="telefone" name="telefone" class="form-control" value="{{ old('telefone') }}" placeholder="(99) 99999-9999" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Salvar Consumidor</button>
        </form>
    </div>
@endsection
