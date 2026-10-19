@extends('layouts.app')

@section('title', 'Reservas activas')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="margin:0;">Reservas Activas</h1>
        <span class="badge b-disponible" style="font-size:0.9rem; padding:8px 15px;">
            Total: {{ $reservas->count() }} reserva(s)
        </span>
    </div>

    <div class="card">
        @if($reservas->isEmpty())
            <div style="text-align:center; padding:40px 20px;">
                <div style="font-size:4rem; margin-bottom:15px;">📅</div>
                <h3 style="color:var(--text-light);">No hay reservas activas en este momento</h3>
                <p class="muted">Las reservas que hagan los estudiantes aparecerán aquí para que puedas gestionarlas.</p>
                <a href="{{ route('catalogo') }}" class="btn" style="margin-top:15px;">Ir al Catálogo</a>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Fecha de Reserva</th>
                        <th>Expira el</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $r)
                        @php
                            $expira = \Carbon\Carbon::parse($r->fecha_expiracion);
                            $ahora = \Carbon\Carbon::now();
                            $horasRestantes = $ahora->diffInHours($expira, false);
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $r->usuario->nombres ?? '—' }} {{ $r->usuario->apellidos ?? '' }}</strong><br>
                                <small class="muted">{{ $r->usuario->documento ?? '' }}</small>
                            </td>
                            <td>
                                <strong>{{ $r->libro->titulo ?? '—' }}</strong><br>
                                <small class="muted">{{ $r->libro->autor ?? '' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($r->fecha_reserva)->format('d/m/Y H:i') }}</td>
                            <td>
                                {{ $expira->format('d/m/Y H:i') }}<br>
                                @if($horasRestantes > 24)
                                    <span class="badge b-disponible" style="font-size:0.7rem;">
                                        ⏱️ Quedan {{ floor($horasRestantes / 24) }} días
                                    </span>
                                @elseif($horasRestantes > 0)
                                    <span class="badge b-reservado" style="font-size:0.7rem;">
                                        ⚠️ Quedan {{ $horasRestantes }} horas
                                    </span>
                                @else
                                    <span class="badge b-prestado" style="font-size:0.7rem;">
                                        ❌ Expirada
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($r->estado === 'pendiente')
                                    <span class="badge b-reservado">Pendiente</span>
                                @elseif($r->estado === 'confirmada')
                                    <span class="badge b-disponible">Confirmada</span>
                                @else
                                    <span class="badge b-nodisponible">{{ ucfirst($r->estado) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($r->estado === 'pendiente')
                                    <form action="{{ route('reservas.confirmar', $r->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button class="btn" style="padding:6px 12px; background:#28a745; font-size:0.85rem;">✅ Confirmar</button>
                                    </form>
                                    <form action="{{ route('reservas.cancelar', $r->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button class="btn" style="padding:6px 12px; background:#dc3545; font-size:0.85rem;">❌ Cancelar</button>
                                    </form>
                                @elseif($r->estado === 'confirmada')
                                    <a href="{{ route('prestamos.nuevo') }}?reserva={{ $r->id }}" class="btn" style="padding:6px 12px; background:var(--primary); font-size:0.85rem;">📖 Entregar</a>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
