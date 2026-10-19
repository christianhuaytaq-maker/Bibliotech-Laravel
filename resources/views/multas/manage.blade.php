@extends('layouts.app')

@section('title', 'Gestión de multas')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="margin:0;">Gestión de Multas</h1>
        <span class="badge b-prestado" style="font-size:0.9rem; padding:8px 15px;">
            Total Histórico: {{ $multas->count() }}
        </span>
    </div>

    {{-- ===== TARJETAS DE RESUMEN (KPIs) ===== --}}
    <div class="grid4" style="margin-bottom:30px;">
        {{-- Total Multas --}}
        <div class="card stat" style="border-top: 4px solid var(--primary);">
            <span style="color:var(--primary);">{{ $multas->count() }}</span>
            <span style="font-weight:bold;">Total de Multas</span>
            <small class="muted">Registradas en el sistema</small>
        </div>

        {{-- Pendientes --}}
        <div class="card stat" style="border-top: 4px solid #dc3545;">
            <span style="color:#dc3545;">{{ $multas->where('estado', 'pendiente')->count() }}</span>
            <span style="font-weight:bold;">Pendientes</span>
            <small class="muted">Por cobrar</small>
        </div>

        {{-- Monto Pendiente --}}
        <div class="card stat" style="border-top: 4px solid #D4A017;">
            <span style="color:#D4A017;">S/ {{ number_format($multas->where('estado', 'pendiente')->sum('monto'), 2) }}</span>
            <span style="font-weight:bold;">Monto Pendiente</span>
            <small class="muted">Total por recaudar</small>
        </div>

        {{-- Pagadas --}}
        <div class="card stat" style="border-top: 4px solid #28a745;">
            <span style="color:#28a745;">{{ $multas->where('estado', 'pagada')->count() }}</span>
            <span style="font-weight:bold;">Pagadas</span>
            <small class="muted">Cobradas exitosamente</small>
        </div>
    </div>

    {{-- ===== FILTRO ===== --}}
    <form method="GET" action="{{ route('multas.gestionar') }}" class="filters card" style="margin-bottom:20px;">
        <label style="font-weight:bold; color:var(--primary);">Filtrar por estado:</label>
        <select name="estado" onchange="this.form.submit()" style="padding:8px; border-radius:5px; border:1px solid #ccc; font-weight:bold;">
            <option value="todas" {{ $estado === 'todas' ? 'selected' : '' }}>Todas las multas</option>
            <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
            <option value="pagada" {{ $estado === 'pagada' ? 'selected' : '' }}>Pagadas</option>
            <option value="condonada" {{ $estado === 'condonada' ? 'selected' : '' }}>Condonadas</option>
            <option value="anulada" {{ $estado === 'anulada' ? 'selected' : '' }}>Anuladas</option>
        </select>
    </form>

    {{-- ===== TABLA ===== --}}
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Libro</th>
                    <th>Motivo</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($multas as $m)
                    <tr>
                        <td>
                            <strong>{{ $m->usuario->nombres ?? '—' }} {{ $m->usuario->apellidos ?? '' }}</strong><br>
                            <small class="muted">Doc: {{ $m->usuario->documento ?? '—' }}</small>
                        </td>
                        <td>
                            {{ $m->prestamo->libro->titulo ?? '—' }}<br>
                            <small class="muted">{{ $m->prestamo->libro->autor ?? '' }}</small>
                        </td>
                        <td>
                            @if($m->motivo == 'retraso')
                                <span class="badge b-reservado" style="font-size:0.75rem;">⏱️ Retraso</span>
                            @elseif($m->motivo == 'dano')
                                <span class="badge b-prestado" style="font-size:0.75rem;">🔨 Daño</span>
                            @elseif($m->motivo == 'perdida')
                                <span class="badge b-nodisponible" style="font-size:0.75rem;">❓ Pérdida</span>
                            @else
                                {{ ucfirst($m->motivo ?? '—') }}
                            @endif
                        </td>
                        <td style="font-weight:bold; color:var(--primary); font-size:1.1rem;">
                            S/ {{ number_format($m->monto, 2) }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y') }}<br>
                            <small class="muted">{{ \Carbon\Carbon::parse($m->created_at)->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($m->estado === 'pendiente')
                                <span class="badge b-prestado" style="padding:5px 10px;">Pendiente</span>
                            @elseif($m->estado === 'pagada')
                                <span class="badge b-disponible" style="padding:5px 10px;">Pagada</span>
                            @else
                                <span class="badge b-nodisponible" style="padding:5px 10px;">{{ ucfirst($m->estado) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($m->estado === 'pendiente')
                                <div style="display:flex; gap:5px; flex-direction:column;">
                                    <form action="{{ route('multas.pagar', $m->id) }}" method="POST" onsubmit="return confirm('¿Registrar pago de S/ {{ $m->monto }}?')">
                                        @csrf
                                        <button class="btn" style="padding:6px 12px; background:#28a745; font-size:0.8rem; width:100%;">💵 Registrar Pago</button>
                                    </form>
                                    <form action="{{ route('multas.anular', $m->id) }}" method="POST" style="display:flex; gap:3px;">
                                        @csrf
                                        <select name="tipo" style="padding:4px; font-size:0.75rem; border-radius:4px; flex:1;">
                                            <option value="condonada">Condonar</option>
                                            <option value="anulada">Anular</option>
                                        </select>
                                        <button class="btn" style="padding:6px 8px; background:#dc3545; font-size:0.8rem;">OK</button>
                                    </form>
                                </div>
                            @elseif($m->estado === 'pagada')
                                <div style="background:#D4EDDA; color:#155724; padding:8px; border-radius:5px; font-size:0.8rem; text-align:center;">
                                    <strong>Pagada:</strong><br>
                                    {{ \Carbon\Carbon::parse($m->paid_at)->format('d/m/Y') }}<br>
                                    <small>Comp: {{ $m->comprobante ?? '—' }}</small>
                                </div>
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div style="text-align:center; padding:40px;">
                                <div style="font-size:3rem; margin-bottom:10px;">💰</div>
                                <h3 class="muted">No hay multas registradas</h3>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
