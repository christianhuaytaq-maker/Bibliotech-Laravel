@extends('layouts.app')
@section('title', 'Categorías')
@section('content')
    <h1>Categorías</h1>
    <div class="card">
        <table>
            <thead><tr><th>Nombre</th><th>Descripción</th></tr></thead>
            <tbody>
                @foreach($categorias as $c)
                    <tr>
                        <td>{{ $c->nombre }}</td>
                        <td>{{ $c->descripcion ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
