<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Controle de Consumo de Água</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('consumidores.index') }}">Consumidores</a>
        <a href="{{ route('leituras.index') }}">Leituras</a>
        <a href="{{ route('faturas.index') }}">Faturas</a>
        @if (auth()->check() && auth()->user()->isGestor())
            <a href="{{ route('configuracoes.edit') }}">Configuração de Taxa</a>
        @endif
        <div style="margin-left: auto; display: flex; align-items: center; gap: 1rem;">
            @if (auth()->check())
                <span style="color: #bfdbfe; font-size: 0.85rem;">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background:none;border:none;color:#fff;cursor:pointer;text-decoration:underline;font-size:0.85rem;">Sair</button>
            </form>
        </div>
    </nav>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <main>
        @yield('content')
    </main>
</body>
</html>
