@extends('layouts.app')

@section('title', 'Control de stock')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="margin:0;">Control de Stock</h1>
        <a href="{{ route('libros.nuevo') }}" class="btn">➕ Nuevo Libro</a>
    </div>

    {{-- Tarjetas de resumen (KPIs) --}}
    <div class="grid4" style="margin-bottom:20px;">
        <div class="card stat">
            <span>{{ $stats['total_libros'] }}</span>
            Títulos Registrados
        </div>
        <div class="card stat">
            <span>{{ $stats['total_ejemplares'] }}</span>
            Total Ejemplares
        </div>
        <div class="card stat">
            <span style="color: #28a745;">{{ $stats['disponibles'] }}</span>
            Ejemplares Disponibles
        </div>
        <div class="card stat">
            <span style="color: #dc3545;">{{ $stats['agotados'] }}</span>
            Títulos Agotados
        </div>
    </div>

    <div class="card">
        {{-- Barra de búsqueda (filtra en tiempo real con JS simple) --}}
        <div style="margin-bottom: 15px;">
            <input type="text" id="searchInput" onkeyup="filtrarTabla()" placeholder="🔍 Buscar por título..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
        </div>

        <table id="stockTable">
            <thead>
                <tr>
                    <th>Título del Libro</th>
                    <th>Autor</th>
                    <th>Stock Total</th>
                    <th>Disponibles</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($libros as $libro)
                    <tr>
                        <td><strong>{{ $libro->titulo }}</strong></td>
                        <td>{{ $libro->autor }}</td>
                        <td>{{ $libro->stock_total }}</td>
                        <td style="font-weight: bold; font-size: 1.1rem;">{{ $libro->stock_disponible }}</td>
                        <td>
                            @if($libro->stock_disponible == 0)
                                <span class="badge b-prestado" style="background:#F8D7DA; color:#721C24; padding:5px 10px;">❌ Agotado</span>
                            @elseif($libro->stock_disponible <= 2)
                                <span class="badge b-reservado" style="background:#FFF3CD; color:#856404; padding:5px 10px;">⚠️ Bajo Stock</span>
                            @else
                                <span class="badge b-disponible" style="background:#D4EDDA; color:#155724; padding:5px 10px;">✅ Disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Script para que la búsqueda funcione sin recargar la página --}}
    <script>
        function filtrarTabla() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let table = document.getElementById("stockTable");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let titleCell = rows[i].getElementsByTagName("td")[0]; // La primera columna es el título
                if (titleCell) {
                    let titleText = titleCell.textContent || titleCell.innerText;
                    if (titleText.toLowerCase().indexOf(input) > -1) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
