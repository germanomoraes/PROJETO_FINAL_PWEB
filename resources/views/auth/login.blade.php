<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Entrar — Sistema de Controle de Consumo de Água</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <main style="max-width:360px; margin-top:4rem;">
        <h1>Entrar</h1>

        @if ($errors->any())
            <div class="alert-success" style="background:#fde2e2; color:#7f1d1d;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <p>
                <label>E-mail</label><br>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus style="width:100%; padding:.5rem;">
            </p>
            <p>
                <label>Senha</label><br>
                <input type="password" name="password" required style="width:100%; padding:.5rem;">
            </p>
            <button type="submit" style="padding:.5rem 1rem;">Entrar</button>
        </form>
    </main>
</body>
</html>
