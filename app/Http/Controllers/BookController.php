<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index() {
        $books = Libro::where('activo', 1)->orderBy('titulo')->get();
        return view('catalogo.index', compact('books'));
    }

    public function show($id) {
        $libro = Libro::findOrFail($id);
        return view('catalogo.show', compact('libro'));
    }

    public function create() {
        return view('books.form');
    }

    public function store(Request $request) {
        $request->validate([
            'titulo' => 'required|max:255',
            'autor' => 'required|max:180',
            'isbn' => 'nullable|max:20|unique:libros,isbn',
            'editorial' => 'nullable|max:150',
            'anio' => 'nullable|integer',
            'sinopsis' => 'nullable',
            'stock_total' => 'required|integer|min:1',
        ]);

        Libro::create([
            'titulo' => $request->titulo,
            'autor' => $request->autor,
            'isbn' => $request->isbn,
            'editorial' => $request->editorial,
            'anio' => $request->anio,
            'sinopsis' => $request->sinopsis,
            'stock_total' => $request->stock_total,
            'stock_disponible' => $request->stock_total,
            'activo' => 1,
        ]);

        return redirect()->route('admin.stock')->with('ok', '✅ Libro registrado correctamente.');
    }
}
