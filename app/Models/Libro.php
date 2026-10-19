<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';
    protected $fillable = [
        'titulo', 'autor', 'isbn', 'id_categoria', 'editorial',
        'anio', 'portada_url', 'sinopsis', 'stock_total', 'stock_disponible', 'activo'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
}
