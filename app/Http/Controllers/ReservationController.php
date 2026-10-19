<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Libro;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index() {
        $reservas = Reserva::with('libro')->where('id_usuario', auth()->id())->orderBy('id','desc')->get();
        return view('reservas.index', compact('reservas'));
    }

    public function gestionar() {
        $reservas = Reserva::with(['libro', 'usuario'])->whereIn('estado', ['pendiente', 'confirmada'])->get();
        return view('reservas.manage', compact('reservas'));
    }

    public function admin() {
        $reservas = Reserva::with(['libro', 'usuario'])->orderBy('id','desc')->get();
        return view('reservas.admin', compact('reservas'));
    }

    public function crear($id) {
        $libro = Libro::findOrFail($id);
        if ($libro->stock_disponible <= 0) {
            return back()->with('error', 'No hay ejemplares disponibles.');
        }

        $existente = Reserva::where('id_usuario', auth()->id())
            ->where('id_libro', $id)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->first();
        if ($existente) {
            return back()->with('error', 'Ya tienes una reserva activa de este libro.');
        }

        Reserva::create([
            'id_usuario' => auth()->id(),
            'id_libro' => $libro->id,
            'estado' => 'pendiente',
            'fecha_reserva' => now(),
            'fecha_expiracion' => now()->addHours(48),
        ]);

        return redirect()->route('reservas')->with('ok', '✅ Reserva creada. Tienes 48 horas para recoger el libro.');
    }

    public function confirmar($id) {
        Reserva::findOrFail($id)->update(['estado' => 'confirmada']);
        return back()->with('ok', 'Reserva confirmada.');
    }

    public function cancelar($id) {
        Reserva::findOrFail($id)->update(['estado' => 'cancelada']);
        return back()->with('ok', 'Reserva cancelada.');
    }
}
