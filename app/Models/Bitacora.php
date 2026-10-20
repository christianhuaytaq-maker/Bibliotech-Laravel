<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bitacora extends Model
{
    protected $table = 'bitacora';
    protected $fillable = ['usuario_id', 'accion', 'detalle'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public static function registrar(string $accion, ?string $detalle = null): void
    {
        static::create([
            'usuario_id' => Auth::id(),
            'accion'     => $accion,
            'detalle'    => $detalle,
        ]);
    }
}
