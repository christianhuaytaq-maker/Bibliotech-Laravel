@extends('layouts.app')
@section('title', 'Bitácora')
@section('content')
<style>
    .bt-barra{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;align-items:end}
    .bt-barra label{display:flex;flex-direction:column;gap:4px;font-size:13px;font-weight:600}
    .bt-barra input{padding:9px 12px;border:1px solid #d9d2c3;border-radius:8px;font:inherit}
    .bt-btn{display:inline-block;padding:9px 16px;border:0;border-radius:999px;background:#d9a21b;color:#fff;font:inherit;font-weight:600;font-size:13px;cursor:pointer;text-decoration:none}
    .bt-btn-sec{background:#6b2d5c}
    .bt-vacio{padding:14px 0;color:#777}
</style>

<h1>Bitácora de acciones</h1>

<div class="card">
    <form method="GET" class="bt-barra">
        <label>Desde <input type="date" name="desde" value="{{ request('desde') }}"></label>
        <label>Hasta <input type="date" name="hasta" value="{{ request('hasta') }}"></label>
        <button class="bt-btn">Filtrar</button>
        <a href="{{ route('admin.bitacora') }}" class="bt-btn bt-btn-sec">Limpiar</a>
    </form>

    <table>
        <thead>
            <tr><th>Fecha</th><th>Usuario</th><th>Acción</th><th>Detalle</th></tr>
        </thead>
        <tbody>
            @forelse($registros as $b)
                <tr>
                    <td>{{ $b->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $b->usuario ? $b->usuario->nombres.' '.$b->usuario->apellidos : '—' }}</td>
                    <td>{{ $b->accion }}</td>
                    <td>{{ $b->detalle ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="bt-vacio">Aún no hay acciones registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:14px">{{ $registros->links() }}</div>
</div>
@endsection
