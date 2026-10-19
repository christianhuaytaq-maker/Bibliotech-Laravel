<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - BiblioTech</title>
    <style>
        /* Pega aquí el CSS de tu login */
        :root {
            --primary: #6B2D5C;
            --primary-dark: #4A1F40;
            --accent: #D4A017;
            --bg: #FDF8E4;
            --text: #2E2E2E;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { background: var(--bg); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(107,45,92,0.15); padding: 50px 45px 40px; max-width: 420px; width: 100%; text-align: center; border-top: 5px solid var(--primary); }
        .login-logo { width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px; box-shadow: 0 8px 20px rgba(107,45,92,0.3); }
        .login-title { color: var(--primary); font-size: 2rem; font-weight: bold; margin-bottom: 5px; }
        .login-sub { color: #666; font-size: 0.95rem; margin-bottom: 5px; }
        .login-hint { color: #666; font-size: 0.85rem; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .login-field { margin-bottom: 20px; text-align: left; }
        .login-field label { display: block; font-weight: bold; color: var(--primary); font-size: 0.9rem; margin-bottom: 8px; }
        .login-field input { width: 100%; padding: 14px 16px; border: 2px solid #e5e5e5; border-radius: 10px; font-size: 1rem; background: #fafafa; }
        .login-field input:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(107,45,92,0.1); }
        .login-btn { width: 100%; padding: 15px; font-size: 1.1rem; font-weight: bold; border-radius: 10px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none; cursor: pointer; }
        .login-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(107,45,92,0.3); }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .a-error { background: #F8D7DA; color: #721C24; border: 1px solid #F5C6CB; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">📚</div>
        <h1 class="login-title">BiblioTech</h1>
        <p class="login-sub">Sistema de Gestión de Biblioteca</p>
        <p class="login-hint">Ingresa tus credenciales para continuar</p>

        @if($errors->any())
            <div class="alert a-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="login-field">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="tucorreo@bibliotech.test" required autofocus>
            </div>
            <div class="login-field">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" placeholder="••••••••" required>
            </div>
            <button class="login-btn" type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>