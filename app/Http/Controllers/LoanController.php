<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\User;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index() { $prestamos = Prestamo::with(['libro', 'usuario'])->where('id_usuario', auth()->id())->get(); return view('prestamos.index', compact('prestamos')); }
    public function gestionar() { $prestamos = Prestamo::with(['libro', 'usuario'])->whereNull('fecha_devolucion_real')->get(); return view('prestamos.manage', compact('prestamos')); }
    public function nuevo() { $libros = Libro::where('activo', 1)->where('stock_disponible', '>', 0)->get(); return view('prestamos.new', compact('libros')); }

    public function registrar(Request $request) {
        $request->validate(['documento' => 'required', 'book_id' => 'required|exists:libros,id']);
        $usuario = User::where('documento', $request->documento)->first();
        if (!$usuario) return back()->with('error', 'Usuario no encontrado.');
        $libro = Libro::find($request->book_id);
        if ($libro->stock_disponible <= 0) return back()->with('error', 'Sin stock.');

        Prestamo::create(['id_usuario' => $usuario->id, 'id_libro' => $libro->id, 'fecha_prestamo' => now(), 'fecha_devolucion_esperada' => now()->addDays(7), 'estado' => 'activo']);
        $libro->decrement('stock_disponible');
        return redirect()->route('prestamos.gestionar')->with('ok', 'Préstamo registrado.');
    }

    public function devolver(Request $request, $id) {
        $prestamo = Prestamo::findOrFail($id);
        $prestamo->update(['fecha_devolucion_real' => now(), 'estado' => 'devuelto']);
        if ($request->estado === 'bueno') { $prestamo->libro->increment('stock_disponible'); }
        return redirect()->route('prestamos.gestionar')->with('ok', 'Devolución registrada.');
    }
}
