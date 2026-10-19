@extends('layouts.app')

@section('title', 'Mis Reservas')

@section('content')
    <h1>Mis Reservas</h1>

    <div class="card">
        @if($reservas->isEmpty())
            <div style="text-align:center; padding:40px 20px;">
                <div style="font-size:4rem; margin-bottom:15px;">📅</div>
                <h3 style="color:var(--text-light);">No tienes reservas registradas</h3>
                <p class="muted">Cuando reserves un libro, aparecerá aquí con su estado.</p>
                <a href="{{ route('catalogo') }}" class="btn" style="margin-top:15px;">Explorar Catálogo</a>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Fecha Reserva</th>
                        <th>Expira</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $r)
                        <tr>
                            <td>
                                <strong>{{ $r->libro->titulo ?? '—' }}</strong><br>
                                <small class="muted">{{ $r->libro->autor ?? '' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($r->fecha_reserva)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->fecha_expiracion)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($r->estado === 'pendiente')
                                    <span class="badge b-reservado">⏳ Pendiente</span>
                                @elseif($r->estado === 'confirmada')
                                    <span class="badge b-disponible">✅ Confirmada</span>
                                @elseif($r->estado === 'cancelada')
                                    <span class="badge b-prestado">❌ Cancelada</span>
                                @elseif($r->estado === 'vencida')
                                    <span class="badge b-nodisponible">⚠️ Vencida</span>
                                @else
                                    <span class="badge b-nodisponible">{{ ucfirst($r->estado) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
