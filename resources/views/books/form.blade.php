@extends('layouts.app')

@section('title', 'Registrar Nuevo Libro')

@section('content')
    <div style="max-width:800px; margin:0 auto;">

        {{-- Encabezado --}}
        <div style="display:flex; align-items:center; gap:15px; margin-bottom:25px;">
            <a href="{{ route('admin.stock') }}" style="text-decoration:none; font-size:1.5rem; color:var(--text-light); background:white; padding:8px 15px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.05);">←</a>
            <h1 style="margin:0; color:var(--primary);">Registrar Nuevo Libro</h1>
        </div>

        {{-- Alertas de error --}}
        @if($errors->any())
            <div class="alert a-error" style="margin-bottom:20px;">
                <strong>⚠️ Por favor corrige los siguientes errores:</strong>
                <ul style="margin-top:10px; margin-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('libros.store') }}">
            @csrf

            {{-- SECCIÓN 1: Información Principal --}}
            <div class="card" style="margin-bottom:25px; border-top:4px solid var(--primary); padding:30px;">
                <h3 style="color:var(--primary); display:flex; align-items:center; gap:10px; margin-bottom:25px;">
                    <span style="background:var(--primary); color:white; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem;">1</span>
                    Información Principal
                </h3>

                <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label>Título del Libro *</label>
                        <input type="text" name="titulo" required value="{{ old('titulo') }}" placeholder="Ej: Cien Años de Soledad">
                    </div>

                    <div class="form-group">
                        <label>ISBN (Opcional)</label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}" placeholder="Ej: 978-3-16-148410-0">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label>Autor *</label>
                    <input type="text" name="autor" required value="{{ old('autor') }}" placeholder="Ej: Gabriel García Márquez">
                </div>
            </div>

            {{-- SECCIÓN 2: Detalles de Publicación --}}
            <div class="card" style="margin-bottom:25px; border-top:4px solid var(--accent); padding:30px;">
                <h3 style="color:var(--accent); display:flex; align-items:center; gap:10px; margin-bottom:25px;">
                    <span style="background:var(--accent); color:white; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem;">2</span>
                    Detalles de Publicación
                </h3>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Editorial</label>
                        <input type="text" name="editorial" value="{{ old('editorial') }}" placeholder="Ej: Sudamericana">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label>Año de Publicación</label>
                        <input type="number" name="anio" value="{{ old('anio') }}" placeholder="Ej: 1967">
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 3: Inventario y Sinopsis --}}
            <div class="card" style="margin-bottom:25px; border-top:4px solid #28a745; padding:30px;">
                <h3 style="color:#28a745; display:flex; align-items:center; gap:10px; margin-bottom:25px;">
                    <span style="background:#28a745; color:white; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem;">3</span>
                    Inventario y Sinopsis
                </h3>

                <div class="form-group">
                    <label>Stock Total (Cantidad de ejemplares) *</label>
                    <input type="number" name="stock_total" min="1" required value="{{ old('stock_total') }}" placeholder="Ej: 5" style="max-width:200px; font-size:1.2rem; font-weight:bold;">
                    <small class="muted" style="display:block; margin-top:5px;">Este número de ejemplares estará disponible para préstamo.</small>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label>Sinopsis / Descripción</label>
                    <textarea name="sinopsis" rows="4" placeholder="Escribe un breve resumen del libro...">{{ old('sinopsis') }}</textarea>
                </div>
            </div>

            {{-- BOTONES DE ACCIÓN --}}
            <div style="display:flex; justify-content:flex-end; gap:15px; margin-top:30px;">
                <a href="{{ route('admin.stock') }}" class="btn" style="background:#888; padding:12px 30px;">Cancelar</a>
                <button class="btn" type="submit" style="padding:12px 40px; font-size:1.1rem; background:var(--primary); box-shadow:0 4px 15px rgba(107, 45, 92, 0.3);">
                    💾 Guardar Libro
                </button>
            </div>

        </form>
    </div>
@endsection
