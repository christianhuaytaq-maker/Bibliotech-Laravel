@extends('layouts.app')
@section('title', 'Reportes')
@section('content')
<style>
    .bt-resumen{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:14px;margin-bottom:18px}
    .bt-dato{background:#fff;border-radius:12px;padding:16px;box-shadow:0 1px 4px rgba(0,0,0,.06)}
    .bt-dato b{display:block;font-size:26px;color:#6b2d5c}
    .bt-dato span{color:#666;font-size:13px}
    .bt-fila{display:flex;align-items:center;gap:12px;margin:10px 0}
    .bt-fila .tit{flex:0 0 38%;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .bt-fila .barra{flex:1;background:#f1ead9;border-radius:999px;height:14px}
    .bt-fila .barra i{display:block;height:100%;background:#d9a21b;border-radius:999px}
    .bt-vacio{padding:10px 0;color:#777}
</style>

<h1>Reportes</h1>

<div class="bt-resumen">
    <div class="bt-dato"><b>{{ $resumen['activos'] }}</b><span>Préstamos activos</span></div>
    <div class="bt-dato"><b>{{ $resumen['vencidos'] }}</b><span>Préstamos vencidos</span></div>
    <div class="bt-dato"><b>{{ $resumen['reservas'] }}</b><span>Reservas confirmadas</span></div>
    <div class="bt-dato"><b>{{ number_format($resumen['multas'], 2) }}</b><span>Multas pendientes</span></div>
</div>

<div class="card">
    <h2>Libros más solicitados</h2>
    @php $max = max(1, $masPrestados->max('total') ?? 1); @endphp
    @forelse($masPrestados as $m)
        <div class="bt-fila">
            <div class="tit" title="{{ $m->libro->titulo }}">{{ $m->libro->titulo }}</div>
            <div class="barra"><i style="width: {{ $m->total / $max * 100 }}%"></i></div>
            <b>{{ $m->total }}</b>
        </div>
    @empty
        <div class="bt-vacio">Todavía no hay préstamos registrados.</div>
    @endforelse
</div>
@endsection
