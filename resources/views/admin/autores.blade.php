@extends('layouts.app')
@section('title', 'Autores')
@section('content')
<style>
    .bt-form{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px}
    .bt-form input{flex:1 1 180px;padding:9px 12px;border:1px solid #d9d2c3;border-radius:8px;font:inherit;min-width:0}
    .bt-btn{display:inline-block;padding:8px 16px;border:0;border-radius:999px;background:#d9a21b;color:#fff;font:inherit;font-weight:600;font-size:13px;cursor:pointer;text-decoration:none}
    .bt-btn:hover{filter:brightness(.95)}
    .bt-btn-sec{background:#6b2d5c}
    .bt-btn-del{background:#fff;color:#b3261e;border:1px solid #b3261e}
    .bt-acciones{display:flex;gap:6px;justify-content:flex-end}
    .bt-acciones form{margin:0}
    .bt-msg{padding:10px 14px;border-radius:8px;margin-bottom:14px}
    .bt-ok{background:#d9ecd9;color:#1e5b1e}
    .bt-err{background:#f8d7d3;color:#8a1f17}
    .bt-vacio{padding:14px 0;color:#777}
</style>

<h1>Autores</h1>

@if (session('ok'))<div class="bt-msg bt-ok">{{ session('ok') }}</div>@endif
@if (session('error'))<div class="bt-msg bt-err">{{ session('error') }}</div>@endif
@if ($errors->any())<div class="bt-msg bt-err">{{ $errors->first() }}</div>@endif

<div class="card">
    <form method="POST" action="{{ route('admin.autores.store') }}" class="bt-form">
        @csrf
        <input name="nombre" placeholder="Nombre del autor" value="{{ old('nombre') }}" required>
        <input name="descripcion" placeholder="Descripción (opcional)" value="{{ old('descripcion') }}">
        <button class="bt-btn">Nuevo autor</button>
    </form>

    <table>
        <thead>
            <tr><th>Nombre</th><th>Descripción</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($autores as $a)
                <tr>
                    <td>{{ $a->nombre }}</td>
                    <td>{{ $a->descripcion ?? '—' }}</td>
                    <td>
                        <div class="bt-acciones">
                            <a href="{{ route('admin.autores.edit', $a->id) }}" class="bt-btn bt-btn-sec">Editar</a>
                            <form method="POST" action="{{ route('admin.autores.destroy', $a->id) }}"
                                  onsubmit="return confirm('¿Eliminar a {{ e($a->nombre) }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="bt-btn bt-btn-del">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="bt-vacio">Aún no hay autores. Agrega el primero arriba.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
