@extends('layouts.app')
@section('title', 'Autores')
@section('content')
    <h1>Autores</h1>
    <div class="card">
        <table>
            <thead><tr><th>Nombre</th><th>Descripción</th></tr></thead>
            <tbody>
                @foreach($autores as $a)
                    <tr>
                        <td>{{ $a->nombre }}</td>
                        <td>{{ $a->descripcion ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
