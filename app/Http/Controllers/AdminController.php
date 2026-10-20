<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\Multa;
use App\Models\User;
use App\Models\Bitacora;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // 1. Panel de Control (Dashboard)
    public function dashboard()
    {
        $stats = [
            'prestados' => Prestamo::whereNull('fecha_devolucion_real')->count(),
            'reservas_conf' => Reserva::where('estado', 'confirmada')->count(),
            'usuarios' => User::count(),
            'multas_pendientes' => Multa::where('estado', 'pendiente')->sum('monto'),
        ];

        $lowStock = Libro::where('activo', 1)->where('stock_disponible', '<=', 1)->limit(10)->get();

        $overdue = Prestamo::with(['libro', 'usuario'])
            ->whereNull('fecha_devolucion_real')
            ->where('fecha_devolucion_esperada', '<', now())
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'lowStock', 'overdue'));
    }

    // 2. Control de Stock
    public function stock()
    {
        $libros = Libro::where('activo', 1)->orderBy('titulo')->get();

        $stats = [
            'total_libros' => $libros->count(),
            'total_ejemplares' => $libros->sum('stock_total'),
            'disponibles' => $libros->sum('stock_disponible'),
            'agotados' => $libros->where('stock_disponible', 0)->count(),
        ];

        return view('admin.stock', compact('libros', 'stats'));
    }

    // 3. Reportes
    public function reportes()
    {
        // Usa la relación libro(), así no depende del nombre de la columna
        $masPrestados = Prestamo::with('libro')
            ->get()
            ->filter(fn ($p) => $p->libro !== null)
            ->groupBy(fn ($p) => $p->libro->id)
            ->map(fn ($grupo) => (object) [
                'libro' => $grupo->first()->libro,
                'total' => $grupo->count(),
            ])
            ->sortByDesc('total')
            ->take(10)
            ->values();

        $resumen = [
            'activos'  => Prestamo::whereNull('fecha_devolucion_real')->count(),
            'vencidos' => Prestamo::whereNull('fecha_devolucion_real')
                                  ->where('fecha_devolucion_esperada', '<', now())->count(),
            'multas'   => Multa::where('estado', 'pendiente')->sum('monto'),
            'reservas' => Reserva::where('estado', 'confirmada')->count(),
        ];

        return view('admin.reports', compact('masPrestados', 'resumen'));
    }

    // 4. Configuración
    public function configuracion()
    {
        $config = [
            'dias_prestamo'    => Configuracion::obtener('dias_prestamo', 7),
            'multa_por_dia'    => Configuracion::obtener('multa_por_dia', 1),
            'max_renovaciones' => Configuracion::obtener('max_renovaciones', 2),
        ];

        return view('admin.settings', compact('config'));
    }

    public function guardarConfiguracion(Request $request)
    {
        $datos = $request->validate([
            'dias_prestamo'    => 'required|integer|min:1|max:90',
            'multa_por_dia'    => 'required|numeric|min:0',
            'max_renovaciones' => 'required|integer|min:0|max:10',
        ]);

        foreach ($datos as $clave => $valor) {
            Configuracion::guardar($clave, $valor);
        }

        Bitacora::registrar('Configuración', 'Se actualizaron los parámetros generales');

        return back()->with('ok', 'Cambios guardados.');
    }

    // 5. Bitácora
    public function bitacora(Request $request)
    {
        $registros = Bitacora::with('usuario')
            ->when($request->filled('desde'), fn ($q) => $q->whereDate('created_at', '>=', $request->desde))
            ->when($request->filled('hasta'), fn ($q) => $q->whereDate('created_at', '<=', $request->hasta))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.audit', compact('registros'));
    }
}
