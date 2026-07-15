<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'idcliente';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'estado',
        'codigo_postal',
        'pais',
        'fecha_registro'
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id', 'idcliente');
    }

    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->apellido);
    }
}
