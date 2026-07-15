<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'idusuario';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'telefono',
        'activo',
        'ultimo_acceso'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'ultimo_acceso' => 'datetime',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'usuario_id', 'idusuario');
    }

    public function esAdmin()
    {
        return $this->rol === 'admin' || $this->rol === 'administrador';
    }

    public function esVendedor()
    {
        return $this->rol === 'vendedor';
    }

    public function estaActivo()
    {
        return $this->activo;
    }
}
