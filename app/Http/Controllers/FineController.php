<?php

namespace App\Http\Controllers;

use App\Models\Multa;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class FineController extends Controller
{
    // 1. Mis multas (Vista del Estudiante)
    public function index()
    {
        $multas = Multa::with(['usuario', 'prestamo.libro'])
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();

        return view('multas.index', compact('multas'));
    }

    // 2. Gestión de multas (Vista del Administrador)
    public function gestionar(Request $request)
    {
        $estado = $request->query('estado', 'todas');

        $query = Multa::with(['usuario', 'prestamo.libro'])->orderBy('id', 'desc');

        // Si el filtro no es "todas", filtramos por el estado seleccionado
        if ($estado !== 'todas') {
            $query->where('estado', $estado);
        }

        $multas = $query->get();

        return view('multas.manage', compact('multas', 'estado'));
    }

    // 3. Registrar Pago (Acción del Administrador)
    public function pagar($id)
    {
        $multa = Multa::findOrFail($id);

        // Verificamos que no esté pagada ya
        if ($multa->estado === 'pagada') {
            return back()->with('error', 'Esta multa ya fue pagada anteriormente.');
        }

        // Actualizamos la multa a pagada
        $multa->update([
            'estado' => 'pagada',
            'paid_at' => now(),
            // Generamos un código de comprobante único (Ej: C-20261019-00001)
            'comprobante' => 'C-' . date('Ymd') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT)
        ]);

        return back()->with('ok', 'Pago registrado correctamente. Comprobante generado.');
    }

    // 4. Anular o Condonar Multa (Acción del Administrador)
    public function anular(Request $request, $id)
    {
        $multa = Multa::findOrFail($id);

        // Validamos que el tipo sea el correcto
        $tipo = $request->tipo;
        if (!in_array($tipo, ['condonada', 'anulada'])) {
            return back()->with('error', 'Acción no válida.');
        }

        $multa->update([
            'estado' => $tipo,
        ]);

        $mensaje = $tipo === 'condonada' ? 'Multa condonada (perdonada).' : 'Multa anulada.';
        return back()->with('ok', $mensaje);
    }
}
