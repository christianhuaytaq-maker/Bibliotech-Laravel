@extends('layouts.app')

@section('title', 'Gestión de Reservas')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="margin:0;">Gestión de Reservas</h1>
        <span class="badge b-disponible" style="font-size:0.9rem; padding:8px 15px;">
            Total Histórico: {{ $reservas->count() }}
        </span>
    </div>

    {{-- Filtros visuales --}}
    <div class="filters card" style="margin-bottom:20px;">
        <input type="text" id="searchInput" onkeyup="filtrarTabla()" placeholder="🔍 Buscar por estudiante o libro..." style="flex:1; padding:10px; border:1px solid #ddd; border-radius:6px;">
    </div>

    <div class="card">
        <table id="reservasTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Estudiante</th>
                    <th>Libro Solicitado</th>
                    <th>Fecha de Reserva</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservas as $r)
                    <tr>
                        <td><strong>#{{ $r->id }}</strong></td>
                        <td>
                            {{ $r->usuario->nombres ?? '—' }} {{ $r->usuario->apellidos ?? '' }}<br>
                            <small class="muted">Doc: {{ $r->usuario->documento ?? '—' }}</small>
                        </td>
                        <td>
                            <strong>{{ $r->libro->titulo ?? '—' }}</strong><br>
                            <small class="muted">{{ $r->libro->autor ?? '' }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($r->fecha_reserva)->format('d/m/Y') }}<br>
                            <small class="muted">a las {{ \Carbon\Carbon::parse($r->fecha_reserva)->format('H:i') }} hrs</small>
                        </td>
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
                @empty
                    <tr><td colspan="5" class="center">No hay reservas registradas en el sistema.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Script para buscar en la tabla sin recargar --}}
    <script>
        function filtrarTabla() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let table = document.getElementById("reservasTable");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let rowText = rows[i].textContent || rows[i].innerText;
                if (rowText.toLowerCase().indexOf(input) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    </script>
@endsection
