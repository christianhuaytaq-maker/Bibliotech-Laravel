<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Autor;
use Illuminate\Http\Request;

class TaxonomyController extends Controller
{
    public function categorias() { $categorias = Categoria::orderBy('nombre')->get(); return view('admin.categorias', compact('categorias')); }
    public function categoriasStore(Request $request) { $request->validate(['nombre' => 'required']); Categoria::create($request->only('nombre', 'descripcion')); return back()->with('ok', 'Creada.'); }
    public function categoriasDestroy($id) { Categoria::findOrFail($id)->delete(); return back()->with('ok', 'Eliminada.'); }

    public function autores() { $autores = Autor::orderBy('nombre')->get(); return view('admin.autores', compact('autores')); }
    public function autoresStore(Request $request) { $request->validate(['nombre' => 'required']); Autor::create($request->only('nombre', 'descripcion')); return back()->with('ok', 'Creado.'); }
    public function autoresDestroy($id) { Autor::findOrFail($id)->delete(); return back()->with('ok', 'Eliminado.'); }
}
