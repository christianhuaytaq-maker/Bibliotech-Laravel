@extends('layouts.app')

@section('title', 'Catálogo - BiblioTech')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="margin:0;">Catálogo de libros</h1>
        <span class="muted">{{ $books->count() }} resultado(s)</span>
    </div>

    <div class="books">
        @foreach($books as $book)
            <a class="book" href="{{ route('catalogo.show', $book->id) }}">
                <img src="{{ $book->portada_url ? asset($book->portada_url) : asset('img/no-cover.svg') }}" alt="Portada de {{ $book->titulo }}">
                <div>
                    <strong>{{ $book->titulo }}</strong><br>
                    <small>{{ $book->autor }}</small><br>
                    <span class="badge b-{{ $book->stock_disponible > 0 ? 'disponible' : 'nodisponible' }}">
                        {{ $book->stock_disponible > 0 ? 'Disponible' : 'No disponible' }}
                    </span>
                    <small>{{ $book->stock_disponible }}/{{ $book->stock_total }}</small>
                </div>
            </a>
        @endforeach
    </div>
@endsection
