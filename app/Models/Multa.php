<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    protected $table = 'multas';
    protected $fillable = [
        'user_id', 'loan_id', 'monto', 'estado', 'motivo',
        'paid_at', 'comprobante'
    ];

    // Relación con el Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el Préstamo
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'loan_id');
    }
}
