<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombres',
        'apellidos',
        'documento',
        'telefono',
        'email',
        'password_hash',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    // Laravel espera que la contraseña esté en 'password', pero tu tabla tiene 'password_hash'
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'administrador';
    }

    public function isStaff(): bool
    {
        return in_array($this->rol, ['administrador', 'bibliotecario']);
    }
}
