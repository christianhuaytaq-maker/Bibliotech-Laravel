@extends('layouts.app')

@section('title', 'Mis Multas')

@section('content')
    <h1>Mis Multas</h1>

    <div class="card">
        @if($multas->isEmpty())
            <div style="text-align:center; padding:40px 20px;">
                <div style="font-size:4rem; margin-bottom:15px;">💰</div>
                <h3 style="color:var(--text-light);">¡Felicidades! No tienes multas</h3>
                <p class="muted">Sigue devolviendo tus libros a tiempo para mantener tu récord limpio.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Motivo</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($multas as $m)
                        <tr>
                            <td>{{ $m->prestamo->libro->titulo ?? '—' }}</td>
                            <td>{{ ucfirst($m->motivo ?? '—') }}</td>
                            <td style="font-weight:bold; color:#dc3545;">S/ {{ number_format($m->monto, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y') }}</td>
                            <td>
                                @if($m->estado === 'pendiente')
                                    <span class="badge b-prestado">⏳ Pendiente</span>
                                @elseif($m->estado === 'pagada')
                                    <span class="badge b-disponible">✅ Pagada</span>
                                @else
                                    <span class="badge b-nodisponible">{{ ucfirst($m->estado) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
