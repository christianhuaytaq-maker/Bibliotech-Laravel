@extends('layouts.app')
@section('title', 'Configuración')
@section('content')
<style>
    .bt-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:18px}
    .bt-campo{display:flex;flex-direction:column;gap:6px}
    .bt-campo label{font-weight:600}
    .bt-campo small{color:#777}
    .bt-campo input{padding:9px 12px;border:1px solid #d9d2c3;border-radius:8px;font:inherit}
    .bt-btn{display:inline-block;padding:8px 16px;border:0;border-radius:999px;background:#d9a21b;color:#fff;font:inherit;font-weight:600;font-size:13px;cursor:pointer}
    .bt-msg{padding:10px 14px;border-radius:8px;margin-bottom:14px}
    .bt-ok{background:#d9ecd9;color:#1e5b1e}
    .bt-err{background:#f8d7d3;color:#8a1f17}
</style>

<h1>Configuración</h1>

@if (session('ok'))<div class="bt-msg bt-ok">{{ session('ok') }}</div>@endif
@if ($errors->any())<div class="bt-msg bt-err">{{ $errors->first() }}</div>@endif

<div class="card">
    <form method="POST" action="{{ route('admin.configuracion.guardar') }}">
        @csrf
        <div class="bt-grid">
            <div class="bt-campo">
                <label for="dias_prestamo">Días de préstamo</label>
                <input id="dias_prestamo" name="dias_prestamo" type="number" min="1" max="90"
                       value="{{ old('dias_prestamo', $config['dias_prestamo']) }}" required>
                <small>Plazo para devolver un libro.</small>
            </div>
            <div class="bt-campo">
                <label for="multa_por_dia">Multa por día de atraso</label>
                <input id="multa_por_dia" name="multa_por_dia" type="number" min="0" step="0.01"
                       value="{{ old('multa_por_dia', $config['multa_por_dia']) }}" required>
                <small>Monto que se cobra por cada día de retraso.</small>
            </div>
            <div class="bt-campo">
                <label for="max_renovaciones">Máximo de renovaciones</label>
                <input id="max_renovaciones" name="max_renovaciones" type="number" min="0" max="10"
                       value="{{ old('max_renovaciones', $config['max_renovaciones']) }}" required>
                <small>Veces que se puede renovar un préstamo.</small>
            </div>
        </div>
        <button class="bt-btn">Guardar cambios</button>
    </form>
</div>
@endsection
