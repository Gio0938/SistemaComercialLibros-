<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    protected $table = 'peliculas';
    protected $primaryKey = 'idpelicula';

    protected $fillable = [
        'titulo',
        'director',
        'reparto',
        'anio',
        'duracion',
        'genero',
        'clasificacion',
        'sinopsis',
        'precio',
        'precio_promocion',
        'stock',
        'stock_minimo',
        'portada',
        'trailer_url',
        'formato',
        'idioma',
        'subtitulos',
        'disponible',
        'destacado'
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'destacado' => 'boolean',
        'precio' => 'decimal:2',
        'precio_promocion' => 'decimal:2',
        'anio' => 'integer',
        'duracion' => 'integer'
    ];

    public function promociones()
    {
        return $this->hasMany(Promocion::class, 'pelicula_id', 'idpelicula');
    }

    public function promocionesActivas()
    {
        return $this->hasMany(Promocion::class, 'pelicula_id', 'idpelicula')
            ->where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }

    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'producto_id', 'idpelicula')
            ->where('tipo_producto', 'pelicula');
    }

    public function getPrecioFinalAttribute()
    {
        if ($this->precio_promocion && $this->precio_promocion > 0 && $this->precio_promocion < $this->precio) {
            return $this->precio_promocion;
        }
        return $this->precio;
    }

    public function getEstaEnPromocionAttribute()
    {
        return $this->precio_promocion && $this->precio_promocion > 0 && $this->precio_promocion < $this->precio;
    }

    public function getDuracionFormateadaAttribute()
    {
        if (!$this->duracion) return 'N/A';
        $horas = floor($this->duracion / 60);
        $minutos = $this->duracion % 60;
        if ($horas > 0 && $minutos > 0) {
            return "{$horas}h {$minutos}min";
        } elseif ($horas > 0) {
            return "{$horas}h";
        }
        return "{$minutos}min";
    }

    public function getEstadoStockAttribute()
    {
        if ($this->stock <= 0) return 'agotado';
        if ($this->stock <= $this->stock_minimo) return 'bajo';
        return 'disponible';
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->titulo} - {$this->director}";
    }
}
