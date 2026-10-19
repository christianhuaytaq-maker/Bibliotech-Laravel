<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaxonomyController;

// Ruta raíz
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isStaff() ? redirect()->route('admin.dashboard') : redirect()->route('catalogo');
    }
    return redirect()->route('login');
});

// Autenticación
Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas para usuarios (Estudiantes y Staff)
Route::middleware('auth')->group(function () {
    Route::get('/catalogo', [BookController::class, 'index'])->name('catalogo');
    Route::get('/catalogo/ver/{id}', [BookController::class, 'show'])->name('catalogo.show');
    Route::post('/reservas/crear/{id}', [ReservationController::class, 'crear'])->name('reservas.crear');

    Route::get('/prestamos', [LoanController::class, 'index'])->name('prestamos');
    Route::get('/reservas', [ReservationController::class, 'index'])->name('reservas');
    Route::get('/multas', [FineController::class, 'index'])->name('multas');
});

// Rutas de Administrador
Route::middleware('auth')->prefix('admin')->group(function () {
    // Panel
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('admin.reportes');
    Route::get('/configuracion', [AdminController::class, 'configuracion'])->name('admin.configuracion');
    Route::get('/bitacora', [AdminController::class, 'bitacora'])->name('admin.bitacora');
    Route::get('/stock', [AdminController::class, 'stock'])->name('admin.stock');

    // Préstamos
    Route::get('/prestamos/gestionar', [LoanController::class, 'gestionar'])->name('prestamos.gestionar');
    Route::get('/prestamos/nuevo', [LoanController::class, 'nuevo'])->name('prestamos.nuevo');
    Route::post('/prestamos/registrar', [LoanController::class, 'registrar'])->name('prestamos.registrar');
    Route::post('/prestamos/devolver/{id}', [LoanController::class, 'devolver'])->name('prestamos.devolver');

    // Reservas
    Route::get('/reservas/gestionar', [ReservationController::class, 'gestionar'])->name('reservas.gestionar');
    Route::get('/reservas/admin', [ReservationController::class, 'admin'])->name('reservas.admin');
    Route::post('/reservas/confirmar/{id}', [ReservationController::class, 'confirmar'])->name('reservas.confirmar');
    Route::post('/reservas/cancelar/{id}', [ReservationController::class, 'cancelar'])->name('reservas.cancelar');

    // Multas
    Route::get('/multas/gestionar', [FineController::class, 'gestionar'])->name('multas.gestionar');
    Route::post('/multas/pagar/{id}', [FineController::class, 'pagar'])->name('multas.pagar');
    Route::post('/multas/anular/{id}', [FineController::class, 'anular'])->name('multas.anular');

    // Libros
    Route::get('/libros/nuevo', [BookController::class, 'create'])->name('libros.nuevo');
    Route::post('/libros/guardar', [BookController::class, 'store'])->name('libros.store');

    // Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('admin.usuarios');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/usuarios/guardar', [UserController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/usuarios/editar/{id}', [UserController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/usuarios/actualizar/{id}', [UserController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/usuarios/eliminar/{id}', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');

    // Categorías y Autores
    Route::get('/categorias', [TaxonomyController::class, 'categorias'])->name('admin.categorias');
    Route::post('/categorias', [TaxonomyController::class, 'categoriasStore'])->name('admin.categorias.store');
    Route::delete('/categorias/{id}', [TaxonomyController::class, 'categoriasDestroy'])->name('admin.categorias.destroy');

    Route::get('/autores', [TaxonomyController::class, 'autores'])->name('admin.autores');
    Route::post('/autores', [TaxonomyController::class, 'autoresStore'])->name('admin.autores.store');
    Route::delete('/autores/{id}', [TaxonomyController::class, 'autoresDestroy'])->name('admin.autores.destroy');
});
