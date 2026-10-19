<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    protected $fillable = [
        'id_usuario', 'id_libro', 'estado', 'fecha_reserva', 'fecha_expiracion'
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
