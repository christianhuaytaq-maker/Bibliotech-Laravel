@extends('layouts.app')

@section('title', 'Préstamos activos')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="margin:0;">Préstamos Activos</h1>
        <div style="display:flex; gap:10px;">
            <span class="badge b-disponible" style="font-size:0.9rem; padding:8px 15px;">
                Activos: {{ $prestamos->count() }}
            </span>
            <a href="{{ route('prestamos.nuevo') }}" class="btn">+ Registrar Préstamo</a>
        </div>
    </div>

    {{-- Tarjetas de resumen (KPIs) --}}
    <div class="grid4" style="margin-bottom:30px;">
        <div class="card stat" style="border-top: 4px solid var(--primary);">
            <span style="color:var(--primary);">{{ $prestamos->count() }}</span>
            <span style="font-weight:bold;">Préstamos Activos</span>
            <small class="muted">Libros en la calle</small>
        </div>
        <div class="card stat" style="border-top: 4px solid #28a745;">
            <span style="color:#28a745;">{{ $prestamos->where('fecha_devolucion_esperada', '>', now())->count() }}</span>
            <span style="font-weight:bold;">En Plazo</span>
            <small class="muted">Aún pueden devolver</small>
        </div>
        <div class="card stat" style="border-top: 4px solid #dc3545;">
            <span style="color:#dc3545;">{{ $prestamos->where('fecha_devolucion_esperada', '<', now())->count() }}</span>
            <span style="font-weight:bold;">Vencidos</span>
            <small class="muted">Fuera de fecha</small>
        </div>
        <div class="card stat" style="border-top: 4px solid #D4A017;">
            <span style="color:#D4A017;">{{ $prestamos->sum(fn($p) => $p->libro->stock_total ?? 0) }}</span>
            <span style="font-weight:bold;">Ejemplares</span>
            <small class="muted">En préstamo</small>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Libro Prestado</th>
                    <th>Fecha Préstamo</th>
                    <th>Devolver Antes del</th>
                    <th>Estado</th>
                    <th>Acción (Devolución)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestamos as $p)
                    @php
                        $fechaDevolucion = \Carbon\Carbon::parse($p->fecha_devolucion_esperada);
                        $vencido = $fechaDevolucion->isPast();
                        $diasRestantes = \Carbon\Carbon::now()->diffInDays($fechaDevolucion, false);
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $p->usuario->nombres ?? '—' }} {{ $p->usuario->apellidos ?? '' }}</strong><br>
                            <small class="muted">Doc: {{ $p->usuario->documento ?? '—' }}</small>
                        </td>
                        <td>
                            <strong>{{ $p->libro->titulo ?? '—' }}</strong><br>
                            <small class="muted">{{ $p->libro->autor ?? '' }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($p->fecha_prestamo)->format('d/m/Y') }}</td>
                        <td>
                            {{ $fechaDevolucion->format('d/m/Y') }}<br>
                            @if($vencido)
                                <span class="badge b-prestado" style="font-size:0.7rem; margin-top:5px;">
                                    ❌ Vencido hace {{ abs($diasRestantes) }} días
                                </span>
                            @elseif($diasRestantes <= 2)
                                <span class="badge b-reservado" style="font-size:0.7rem; margin-top:5px;">
                                    ⚠️ Vence en {{ $diasRestantes }} días
                                </span>
                            @else
                                <span class="badge b-disponible" style="font-size:0.7rem; margin-top:5px;">
                                    ✅ {{ $diasRestantes }} días restantes
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($vencido)
                                <span class="badge b-prestado" style="padding:5px 10px;">Vencido</span>
                            @else
                                <span class="badge b-reservado" style="padding:5px 10px;">En plazo</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('prestamos.devolver', $p->id) }}" style="display:flex; gap:5px;" onsubmit="return confirm('¿Registrar la devolución de este libro?')">
                                @csrf
                                <select name="estado" style="padding:6px; border-radius:5px; border:1px solid #ccc; font-size:0.8rem; flex:1;">
                                    <option value="bueno">Buen estado</option>
                                    <option value="danado">Dañado</option>
                                    <option value="perdido">Perdido</option>
                                </select>
                                <button class="btn" style="padding:6px 12px; font-size:0.8rem; background:var(--primary);">Registrar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div style="text-align:center; padding:40px;">
                                <div style="font-size:3rem; margin-bottom:10px;">📖</div>
                                <h3 class="muted">No hay préstamos activos en este momento</h3>
                                <p class="muted">Todos los libros están en la biblioteca.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
