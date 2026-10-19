<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BiblioTech')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="{{ Auth::check() && Auth::user()->isStaff() ? 'has-sidebar' : '' }}">

    @auth
        @if(Auth::user()->isStaff())
            <aside class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <span>⚙️ Menú</span>
                    <button onclick="document.getElementById('sidebar').classList.remove('open')" class="sidebar-close">✕</button>
                </div>

                <div style="padding: 15px 24px; color: #ccc; font-size: 0.9rem; border-bottom: 1px solid #444;">
                    <strong>{{ Auth::user()->nombres }}</strong><br>
                    <span style="font-size: 0.8rem; opacity: 0.8;">{{ Auth::user()->rol }}</span>
                </div>

                <div style="padding: 15px 24px 5px; font-size: 0.75rem; color: #888; text-transform: uppercase; font-weight: bold;">Administración</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('admin.dashboard') }}"><span class="icon">📊</span><span>Panel</span></a>
                    <a href="{{ route('prestamos.gestionar') }}"><span class="icon">📖</span><span>Préstamos</span></a>
                    <a href="{{ route('reservas.gestionar') }}"><span class="icon">📅</span><span>Reservas activas</span></a>
                    <a href="{{ route('reservas.admin') }}"><span class="icon">📋</span><span>Gestión de Reservas</span></a>
                    <a href="{{ route('multas.gestionar') }}"><span class="icon">💰</span><span>Multas</span></a>
                    <a href="{{ route('libros.nuevo') }}"><span class="icon">➕</span><span>Nuevo libro</span></a>
                    <a href="{{ route('admin.stock') }}"><span class="icon">📦</span><span>Stock</span></a>
                </nav>

                @if(Auth::user()->isAdmin())
                    <div style="padding: 15px 24px 5px; font-size: 0.75rem; color: #888; text-transform: uppercase; font-weight: bold;">Configuración</div>
                    <nav class="sidebar-nav">
                        <a href="{{ route('admin.usuarios') }}"><span class="icon">👥</span><span>Usuarios</span></a>
                        <a href="{{ route('admin.categorias') }}"><span class="icon">🏷️</span><span>Categorías</span></a>
                        <a href="{{ route('admin.autores') }}"><span class="icon">✍️</span><span>Autores</span></a>
                        <a href="{{ route('admin.reportes') }}"><span class="icon">📈</span><span>Reportes</span></a>
                        <a href="{{ route('admin.configuracion') }}"><span class="icon">⚙️</span><span>Configuración</span></a>
                        <a href="{{ route('admin.bitacora') }}"><span class="icon">📋</span><span>Bitácora</span></a>
                    </nav>
                @endif
            </aside>
            <div class="sidebar-overlay" onclick="document.getElementById('sidebar').classList.remove('open')"></div>
        @endif
    @endauth

    <div class="main-content">
        <header class="nav">
            @auth
                @if(Auth::user()->isStaff())
                    <button class="burger" onclick="document.getElementById('sidebar').classList.toggle('open')" aria-label="Menú">☰</button>
                @endif
            @endauth

            <a class="brand" href="{{ url('/') }}">📚 BiblioTech</a>

            <nav id="menu">
                <a href="{{ url('/') }}">Inicio</a>
                <a href="{{ url('/catalogo') }}">Catálogo</a>
                @auth
                    {{-- Si es estudiante, ve sus opciones personales --}}
                    @if(!Auth::user()->isStaff())
                        <a href="{{ route('prestamos') }}">Mis préstamos</a>
                        <a href="{{ route('reservas') }}">Mis reservas</a>
                        <a href="{{ route('multas') }}">Mis multas</a>
                    @endif
                    <a href="#" class="user">👤 {{ Auth::user()->nombres }}</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-nav">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </nav>
        </header>

        <main class="container">
            @if(session('ok')) <div class="alert a-ok">{{ session('ok') }}</div> @endif
            @if(session('error')) <div class="alert a-error">{{ session('error') }}</div> @endif
            @yield('content')
        </main>

        <footer class="foot">© {{ date('Y') }} BiblioTech · Sistema de biblioteca</footer>
    </div>

</body>
</html>
