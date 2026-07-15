<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'detalles_venta';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'iddetalle';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'venta_id',
        'tipo_producto',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'subtotal'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'descuento' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'cantidad' => 'integer'
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Get the venta that owns the detalle.
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id', 'idventa');
    }

    /**
     * Get the libro associated with the detalle.
     */
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'producto_id', 'idlibro');
    }

    /**
     * Get the pelicula associated with the detalle.
     */
    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'producto_id', 'idpelicula');
    }

    // ============================================
    // ACCESORES
    // ============================================

    /**
     * Get the product (libro or pelicula) dynamically.
     */
    public function getProductoAttribute()
    {
        if ($this->tipo_producto == 'libro') {
            return $this->libro;
        }
        return $this->pelicula;
    }

    /**
     * Get the product name.
     */
    public function getNombreProductoAttribute()
    {
        $producto = $this->producto;
        return $producto ? $producto->titulo : 'Producto eliminado';
    }

    /**
     * Get the subtotal formateado.
     */
    public function getSubtotalFormateadoAttribute()
    {
        return '$' . number_format($this->subtotal, 2);
    }

    /**
     * Get the precio unitario formateado.
     */
    public function getPrecioUnitarioFormateadoAttribute()
    {
        return '$' . number_format($this->precio_unitario, 2);
    }

    /**
     * Get the descuento formateado.
     */
    public function getDescuentoFormateadoAttribute()
    {
        return '$' . number_format($this->descuento, 2);
    }
}
