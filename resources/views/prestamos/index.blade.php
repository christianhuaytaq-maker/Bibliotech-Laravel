@extends('layouts.app')

@section('title', 'Mis Préstamos')

@section('content')
    <h1>Mis Préstamos</h1>

    <div class="card">
        @if($prestamos->isEmpty())
            <div style="text-align:center; padding:40px 20px;">
                <div style="font-size:4rem; margin-bottom:15px;">📖</div>
                <h3 style="color:var(--text-light);">Aún no tienes préstamos activos</h3>
                <p class="muted">Explora el catálogo y reserva un libro para comenzar.</p>
                <a href="{{ route('catalogo') }}" class="btn" style="margin-top:15px;">Ir al Catálogo</a>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Fecha Préstamo</th>
                        <th>Devolver el</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prestamos as $p)
                        @php
                            $vencido = \Carbon\Carbon::parse($p->fecha_devolucion_esperada)->isPast() && !$p->fecha_devolucion_real;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $p->libro->titulo ?? '—' }}</strong><br>
                                <small class="muted">{{ $p->libro->autor ?? '' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($p->fecha_prestamo)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->fecha_devolucion_esperada)->format('d/m/Y') }}</td>
                            <td>
                                @if($p->fecha_devolucion_real)
                                    <span class="badge b-disponible">✅ Devuelto</span>
                                @elseif($vencido)
                                    <span class="badge b-prestado">⚠️ Vencido</span>
                                @else
                                    <span class="badge b-reservado">⏳ Activo</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
