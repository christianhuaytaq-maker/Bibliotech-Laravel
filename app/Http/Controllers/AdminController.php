<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\Multa;
use App\Models\User;

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

    // 2. Control de Stock (Con estadísticas para el diseño profesional)
    public function stock()
    {
        $libros = Libro::where('activo', 1)->orderBy('titulo')->get();

        // Calculamos las estadísticas para las tarjetas de arriba
        $stats = [
            'total_libros' => $libros->count(),
            'total_ejemplares' => $libros->sum('stock_total'),
            'disponibles' => $libros->sum('stock_disponible'),
            'agotados' => $libros->where('stock_disponible', 0)->count(),
        ];

        return view('admin.stock', compact('libros', 'stats'));
    }

    // 3. Vistas simples (por ahora)
    public function reportes() { return view('admin.reports'); }
    public function configuracion() { return view('admin.settings'); }
    public function bitacora() { return view('admin.audit'); }
}
