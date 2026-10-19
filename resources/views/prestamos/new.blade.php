@extends('layouts.app')

@section('title', 'Registrar préstamo')

@section('content')
    <div class="card" style="max-width:600px; margin:0 auto;">
        <h2 style="color:var(--primary); margin-bottom:20px;">Registrar préstamo</h2>

        @if(session('error'))
            <div class="alert a-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('prestamos.registrar') }}">
            @csrf

            <div class="form-group">
                <label>Documento del usuario</label>
                <input type="text" name="documento" placeholder="Ej: 22222222" required value="{{ old('documento') }}">
                @error('documento')
                    <small style="color:red">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Libro</label>
                <select name="book_id" required>
                    <option value="">Selecciona un libro</option>
                    @foreach($libros as $libro)
                        <option value="{{ $libro->id }}" {{ old('book_id') == $libro->id ? 'selected' : '' }}>
                            {{ $libro->titulo }} ({{ $libro->stock_disponible }} disp.)
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <small style="color:red">{{ $message }}</small>
                @enderror
            </div>

            <p class="muted">La fecha de devolución se calcula a 7 días desde hoy.</p>

            <div style="display:flex; gap:10px; margin-top:20px;">
                <button class="btn" type="submit">Registrar</button>
                <a href="{{ route('prestamos.gestionar') }}" class="btn" style="background:#888;">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
