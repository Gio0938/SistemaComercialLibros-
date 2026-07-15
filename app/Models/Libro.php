<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';
    protected $primaryKey = 'idlibro';

    protected $fillable = [
        'titulo',
        'autor',
        'editorial',
        'isbn',
        'genero',
        'descripcion',
        'precio',
        'precio_promocion',
        'stock',
        'stock_minimo',
        'foto',
        'fecha_publicacion',
        'paginas',
        'idioma',
        'disponible',
        'destacado'
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'destacado' => 'boolean',
        'precio' => 'decimal:2',
        'precio_promocion' => 'decimal:2',
        'fecha_publicacion' => 'date'
    ];

    public function promociones()
    {
        return $this->hasMany(Promocion::class, 'libro_id', 'idlibro');
    }

    public function promocionesActivas()
    {
        return $this->hasMany(Promocion::class, 'libro_id', 'idlibro')
            ->where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }

    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'producto_id', 'idlibro')
            ->where('tipo_producto', 'libro');
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

    public function getEstadoStockAttribute()
    {
        if ($this->stock <= 0) return 'agotado';
        if ($this->stock <= $this->stock_minimo) return 'bajo';
        return 'disponible';
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->titulo} - {$this->autor}";
    }
}
