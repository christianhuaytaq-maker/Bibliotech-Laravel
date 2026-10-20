@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
@php $lista = $usuarios ?? $users ?? collect(); @endphp
<style>
    .bt-barra{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;align-items:center}
    .bt-barra input,.bt-barra select{padding:9px 12px;border:1px solid #d9d2c3;border-radius:8px;font:inherit;min-width:0}
    .bt-barra input{flex:1 1 200px}
    .bt-btn{display:inline-block;padding:8px 16px;border:0;border-radius:999px;background:#d9a21b;color:#fff;font:inherit;font-weight:600;font-size:13px;cursor:pointer;text-decoration:none}
    .bt-btn:hover{filter:brightness(.95)}
    .bt-btn-sec{background:#6b2d5c}
    .bt-btn-del{background:#fff;color:#b3261e;border:1px solid #b3261e}
    .bt-acciones{display:flex;gap:6px;justify-content:flex-end}
    .bt-acciones form{margin:0}
    .bt-badge{padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;background:#d9ecd9;color:#1e5b1e}
    .bt-badge.off{background:#eee;color:#666}
    .bt-msg{padding:10px 14px;border-radius:8px;margin-bottom:14px}
    .bt-ok{background:#d9ecd9;color:#1e5b1e}
    .bt-err{background:#f8d7d3;color:#8a1f17}
    .bt-vacio{padding:14px 0;color:#777}
</style>

<h1>Usuarios</h1>

@if (session('ok'))<div class="bt-msg bt-ok">{{ session('ok') }}</div>@endif
@if (session('error'))<div class="bt-msg bt-err">{{ session('error') }}</div>@endif

<div class="card">
    <div class="bt-barra">
        <input id="buscar" type="search" placeholder="Buscar por nombre o correo">
        <select id="filtroRol">
            <option value="">Todos los roles</option>
            <option value="administrador">Administrador</option>
            <option value="bibliotecario">Bibliotecario</option>
            <option value="estudiante">Estudiante</option>
        </select>
        <a href="{{ route('admin.usuarios.create') }}" class="bt-btn">Nuevo usuario</a>
    </div>

    <table>
        <thead>
            <tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th><th></th></tr>
        </thead>
        <tbody id="filas">
            @forelse($lista as $u)
                <tr data-rol="{{ strtolower($u->rol) }}"
                    data-texto="{{ strtolower($u->nombres.' '.$u->apellidos.' '.$u->email) }}">
                    <td>{{ $u->nombres }} {{ $u->apellidos }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ ucfirst($u->rol) }}</td>
                    <td>
                        <span class="bt-badge {{ strtolower($u->estado) === 'activo' ? '' : 'off' }}">
                            {{ ucfirst($u->estado) }}
                        </span>
                    </td>
                    <td>
                        <div class="bt-acciones">
                            <a href="{{ route('admin.usuarios.edit', $u->id) }}" class="bt-btn bt-btn-sec">Editar</a>
                            @if ($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.usuarios.destroy', $u->id) }}"
                                  onsubmit="return confirm('¿Eliminar a {{ e($u->nombres) }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="bt-btn bt-btn-del">Eliminar</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="bt-vacio">Aún no hay usuarios.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    const q = document.getElementById('buscar'), r = document.getElementById('filtroRol');
    function filtrar() {
        const t = q.value.toLowerCase().trim(), rol = r.value;
        document.querySelectorAll('#filas tr[data-rol]').forEach(f => {
            f.style.display = (!rol || f.dataset.rol === rol) && f.dataset.texto.includes(t) ? '' : 'none';
        });
    }
    q.addEventListener('input', filtrar);
    r.addEventListener('change', filtrar);
</script>
@endsection
