<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamos';
    protected $fillable = [
        'id_usuario', 'id_libro', 'id_reserva', 'fecha_prestamo',
        'fecha_devolucion_esperada', 'fecha_devolucion_real', 'estado'
    ];

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
