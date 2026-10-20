<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Bitacora;
use App\Models\Categoria;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TaxonomyController extends Controller
{
    // ---------- Categorías ----------
    public function categorias()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('admin.categorias', compact('categorias'));
    }

    public function categoriasStore(Request $request)
    {
        $datos = $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $cat = Categoria::create($datos);
        Bitacora::registrar('Categoría creada', $cat->nombre);

        return back()->with('ok', 'Categoría creada.');
    }

    public function categoriasEdit($id)
    {
        $item = Categoria::findOrFail($id);
        return view('admin.categorias_edit', compact('item'));
    }

    public function categoriasUpdate(Request $request, $id)
    {
        $datos = $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $cat = Categoria::findOrFail($id);
        $cat->update($datos);
        Bitacora::registrar('Categoría editada', $cat->nombre);

        return redirect()->route('admin.categorias')->with('ok', 'Cambios guardados.');
    }

    public function categoriasDestroy($id)
    {
        $cat = Categoria::findOrFail($id);

        try {
            $cat->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'No se puede eliminar: hay libros que usan esta categoría.');
        }

        Bitacora::registrar('Categoría eliminada', $cat->nombre);
        return back()->with('ok', 'Categoría eliminada.');
    }

    // ---------- Autores ----------
    public function autores()
    {
        $autores = Autor::orderBy('nombre')->get();
        return view('admin.autores', compact('autores'));
    }

    public function autoresStore(Request $request)
    {
        $datos = $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $autor = Autor::create($datos);
        Bitacora::registrar('Autor creado', $autor->nombre);

        return back()->with('ok', 'Autor creado.');
    }

    public function autoresEdit($id)
    {
        $item = Autor::findOrFail($id);
        return view('admin.autores_edit', compact('item'));
    }

    public function autoresUpdate(Request $request, $id)
    {
        $datos = $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $autor = Autor::findOrFail($id);
        $autor->update($datos);
        Bitacora::registrar('Autor editado', $autor->nombre);

        return redirect()->route('admin.autores')->with('ok', 'Cambios guardados.');
    }

    public function autoresDestroy($id)
    {
        $autor = Autor::findOrFail($id);

        try {
            $autor->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'No se puede eliminar: hay libros de este autor.');
        }

        Bitacora::registrar('Autor eliminado', $autor->nombre);
        return back()->with('ok', 'Autor eliminado.');
    }
}
