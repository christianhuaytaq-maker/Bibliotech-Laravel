@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
    <h1>Usuarios</h1>
    <div class="card">
        <table>
            <thead><tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th></tr></thead>
            <tbody>
                @foreach($usuarios as $u)
                    <tr>
                        <td>{{ $u->nombres }} {{ $u->apellidos }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ ucfirst($u->rol) }}</td>
                        <td><span class="badge b-disponible">{{ ucfirst($u->estado) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
