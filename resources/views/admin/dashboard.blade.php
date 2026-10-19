@extends('layouts.app')

@section('title', 'Panel de control - BiblioTech')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="margin:0; color:var(--primary);">Panel de Control</h1>
        <span class="muted">Resumen general al {{ date('d/m/Y') }}</span>
    </div>

    {{-- ===== TARJETAS DE ESTADÍSTICAS (KPIs) ===== --}}
    <div class="grid4" style="margin-top:20px; margin-bottom:30px;">
        {{-- Préstamos --}}
        <div class="card stat" style="border-top: 4px solid var(--primary); position:relative; overflow:hidden;">
            <div style="position:absolute; right:15px; top:15px; font-size:2.5rem; opacity:0.1;">📖</div>
            <span style="color:var(--primary); font-size:2.5rem;">{{ $stats['prestados'] }}</span>
            <span style="font-weight:bold; color:var(--text);">Libros Prestados</span>
            <small class="muted">Actualmente en préstamo</small>
        </div>

        {{-- Reservas --}}
        <div class="card stat" style="border-top: 4px solid #D4A017; position:relative; overflow:hidden;">
            <div style="position:absolute; right:15px; top:15px; font-size:2.5rem; opacity:0.1;">📅</div>
            <span style="color:#D4A017; font-size:2.5rem;">{{ $stats['reservas_conf'] }}</span>
            <span style="font-weight:bold; color:var(--text);">Reservas Activas</span>
            <small class="muted">Confirmadas y pendientes</small>
        </div>

        {{-- Usuarios --}}
        <div class="card stat" style="border-top: 4px solid #007bff; position:relative; overflow:hidden;">
            <div style="position:absolute; right:15px; top:15px; font-size:2.5rem; opacity:0.1;">👥</div>
            <span style="color:#007bff; font-size:2.5rem;">{{ $stats['usuarios'] }}</span>
            <span style="font-weight:bold; color:var(--text);">Usuarios Registrados</span>
            <small class="muted">Estudiantes y Staff</small>
        </div>

        {{-- Multas --}}
        <div class="card stat" style="border-top: 4px solid #dc3545; position:relative; overflow:hidden;">
            <div style="position:absolute; right:15px; top:15px; font-size:2.5rem; opacity:0.1;">💰</div>
            <span style="color:#dc3545; font-size:2.5rem;">S/ {{ number_format($stats['multas_pendientes'], 2) }}</span>
            <span style="font-weight:bold; color:var(--text);">Multas Pendientes</span>
            <small class="muted">Por cobrar</small>
        </div>
    </div>

    {{-- ===== ALERTAS ===== --}}
    <div class="grid2" style="margin-bottom:30px;">
        {{-- Stock Bajo --}}
        <div class="card" style="border-left: 4px solid #ffc107;">
            <h3 style="margin-bottom:15px; display:flex; align-items:center; gap:10px;">
                ⚠️ Alertas de Stock Bajo
            </h3>
            @if($lowStock->isEmpty())
                <div style="background:#D4EDDA; color:#155724; padding:15px; border-radius:8px; text-align:center;">
                    ✅ Sin alertas. El inventario está en orden.
                </div>
            @else
                <ul style="list-style:none; padding:0;">
                    @foreach($lowStock as $libro)
                        <li style="padding:10px 0; border-bottom:1px solid #eee; display:flex; justify-content:space-between;">
                            <span><strong>{{ $libro->titulo }}</strong></span>
                            <span style="color:#dc3545; font-weight:bold;">{{ $libro->stock_disponible }} disp.</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Préstamos Vencidos --}}
        <div class="card" style="border-left: 4px solid #dc3545;">
            <h3 style="margin-bottom:15px; display:flex; align-items:center; gap:10px;">
                ⏰ Préstamos Vencidos
            </h3>
            @if($overdue->isEmpty())
                <div style="background:#D4EDDA; color:#155724; padding:15px; border-radius:8px; text-align:center;">
                    ✅ Ningún préstamo vencido.
                </div>
            @else
                <ul style="list-style:none; padding:0;">
                    @foreach($overdue as $p)
                        <li style="padding:10px 0; border-bottom:1px solid #eee; display:flex; justify-content:space-between;">
                            <span><strong>{{ $p->usuario->nombres ?? 'Usuario' }}</strong><br><small class="muted">{{ $p->libro->titulo ?? 'Libro' }}</small></span>
                            <span style="color:#dc3545; font-weight:bold;">Vencido</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- ===== ACCESOS RÁPIDOS ===== --}}
    <div class="card">
        <h3 style="margin-bottom:20px;">⚡ Accesos Rápidos</h3>
        <div style="display:flex; flex-wrap:wrap; gap:15px;">
            <a href="{{ route('prestamos.gestionar') }}" class="btn" style="background:var(--primary); display:flex; align-items:center; gap:8px; padding:12px 20px;">
                📖 Ver Préstamos
            </a>
            <a href="{{ route('reservas.gestionar') }}" class="btn" style="background:#D4A017; display:flex; align-items:center; gap:8px; padding:12px 20px;">
                📅 Ver Reservas
            </a>
            <a href="{{ route('multas.gestionar') }}" class="btn" style="background:#dc3545; display:flex; align-items:center; gap:8px; padding:12px 20px;">
                💰 Ver Multas
            </a>
            <a href="{{ route('admin.usuarios') }}" class="btn" style="background:#007bff; display:flex; align-items:center; gap:8px; padding:12px 20px;">
                👥 Ver Usuarios
            </a>
            <a href="{{ route('admin.stock') }}" class="btn" style="background:#28a745; display:flex; align-items:center; gap:8px; padding:12px 20px;">
                📦 Ver Stock
            </a>
        </div>
    </div>
@endsection
