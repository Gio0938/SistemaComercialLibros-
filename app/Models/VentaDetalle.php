<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalles_venta';
    protected $primaryKey = 'iddetalle';
    public $timestamps = false;

    protected $fillable = [
        'venta_id',
        'tipo_producto',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'subtotal'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'descuento' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id', 'idventa');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'producto_id', 'idlibro');
    }

    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'producto_id', 'idpelicula');
    }

    public function getProductoAttribute()
    {
        if ($this->tipo_producto == 'libro') {
            return $this->libro;
        }
        return $this->pelicula;
    }

    public function getNombreProductoAttribute()
    {
        $producto = $this->producto;
        return $producto ? $producto->titulo : 'Producto eliminado';
    }
}
