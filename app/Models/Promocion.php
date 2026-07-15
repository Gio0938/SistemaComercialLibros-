<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';
    protected $primaryKey = 'idpromocion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'libro_id',
        'pelicula_id',
        'descuento_porcentaje',
        'descuento_fijo',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'codigo_promocional',
        'uso_maximo',
        'usos_actuales'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'descuento_porcentaje' => 'decimal:2',
        'descuento_fijo' => 'decimal:2'
    ];

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'libro_id', 'idlibro');
    }

    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'pelicula_id', 'idpelicula');
    }

    public function getEstadoAttribute()
    {
        if (!$this->activa) return 'inactiva';
        if (now()->lt($this->fecha_inicio)) return 'programada';
        if (now()->gt($this->fecha_fin)) return 'expirada';
        return 'activa';
    }

    public function getEsValidaAttribute()
    {
        return $this->estado === 'activa' &&
            ($this->uso_maximo === null || $this->usos_actuales < $this->uso_maximo);
    }

    public function getNombreProductoAttribute()
    {
        if ($this->tipo == 'libro' && $this->libro) {
            return $this->libro->titulo;
        } elseif ($this->tipo == 'pelicula' && $this->pelicula) {
            return $this->pelicula->titulo;
        }
        return 'Todos los productos';
    }

    public function getDescuentoFormateadoAttribute()
    {
        if ($this->descuento_porcentaje) {
            return $this->descuento_porcentaje . '%';
        } elseif ($this->descuento_fijo) {
            return '$' . number_format($this->descuento_fijo, 2);
        }
        return 'N/A';
    }

    public function getRangoFechasAttribute()
    {
        return $this->fecha_inicio->format('d/m/Y') . ' - ' . $this->fecha_fin->format('d/m/Y');
    }
}
