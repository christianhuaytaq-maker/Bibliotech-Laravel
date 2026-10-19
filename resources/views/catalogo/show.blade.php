@extends('layouts.app')

@section('title', $libro->titulo . ' - BiblioTech')

@section('content')
    <div style="margin-bottom:25px;">
        <a href="{{ route('catalogo') }}" style="color:var(--text-light); text-decoration:none; font-weight:bold;">
            ← Volver al Catálogo
        </a>
    </div>

    <div class="card" style="display:flex; gap:40px; flex-wrap:wrap; align-items:flex-start; padding:30px;">

        {{-- COLUMNA IZQUIERDA: Portada --}}
        <div style="flex:0 0 280px; position:sticky; top:20px;">
            @if($libro->portada_url)
                <img src="{{ asset($libro->portada_url) }}" alt="{{ $libro->titulo }}"
                     style="width:100%; border-radius:12px; box-shadow:0 15px 35px rgba(0,0,0,0.2);">
            @else
                <div style="width:100%; height:400px; background:var(--bg); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:5rem; border:2px dashed #ccc;">
                    📚
                </div>
            @endif
        </div>

        {{-- COLUMNA DERECHA: Información --}}
        <div style="flex:1; min-width:320px;">

            {{-- Título y Autor --}}
            <h1 style="color:var(--primary); font-size:2.5rem; margin-bottom:5px; line-height:1.2;">
                {{ $libro->titulo }}
            </h1>
            <p style="font-size:1.2rem; color:var(--text-light); margin-bottom:25px;">
                por <strong style="color:var(--text);">{{ $libro->autor }}</strong>
            </p>

            {{-- Grid de detalles técnicos --}}
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; background:var(--bg); padding:20px; border-radius:10px; margin-bottom:30px;">
                <div>
                    <small style="color:var(--text-light); text-transform:uppercase; font-size:0.75rem; letter-spacing:1px;">ISBN</small><br>
                    <strong>{{ $libro->isbn ?? 'No registrado' }}</strong>
                </div>
                <div>
                    <small style="color:var(--text-light); text-transform:uppercase; font-size:0.75rem; letter-spacing:1px;">Editorial</small><br>
                    <strong>{{ $libro->editorial ?? 'No registrada' }}</strong>
                </div>
                <div>
                    <small style="color:var(--text-light); text-transform:uppercase; font-size:0.75rem; letter-spacing:1px;">Año de Publicación</small><br>
                    <strong>{{ $libro->anio ?? '—' }}</strong>
                </div>
                <div>
                    <small style="color:var(--text-light); text-transform:uppercase; font-size:0.75rem; letter-spacing:1px;">Disponibilidad</small><br>
                    <span style="color:{{ $libro->stock_disponible > 0 ? '#28a745' : '#dc3545' }}; font-weight:bold; font-size:1.1rem;">
                        {{ $libro->stock_disponible }} de {{ $libro->stock_total }} ejemplares
                    </span>
                </div>
            </div>

            {{-- Sinopsis --}}
            <h3 style="color:var(--primary); margin-bottom:10px; border-bottom:2px solid var(--bg); padding-bottom:10px;">
                📖 Sinopsis
            </h3>
            <p style="margin-bottom:35px; line-height:1.8; text-align:justify;">
                {{ $libro->sinopsis ?? 'Este libro aún no tiene una sinopsis registrada.' }}
            </p>

            {{-- Zona de Acción (Reservar) --}}
            <div style="border-top:2px solid var(--bg); padding-top:25px;">
                @if($libro->stock_disponible > 0)
                    <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
                        <form action="{{ route('reservas.crear', $libro->id) }}" method="POST">
                            @csrf
                            <button class="btn" type="submit" style="padding:15px 40px; font-size:1.1rem; background:#28a745; border-radius:30px;">
                                📅 Reservar este Libro
                            </button>
                        </form>
                        <p class="muted" style="margin:0;">
                            <small>⏱️ Tendrás <strong>48 horas</strong> para recogerlo en la biblioteca.</small>
                        </p>
                    </div>
                @else
                    <div style="background:#F8D7DA; color:#721C24; padding:20px; border-radius:10px; text-align:center; border:1px solid #F5C6CB;">
                        <strong>❌ Libro no disponible por el momento</strong><br>
                        <small>Todos los ejemplares están prestados. Vuelve a intentarlo más tarde.</small>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
