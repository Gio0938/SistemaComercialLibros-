<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

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
        'fecha_publicacion' => 'date',
        'paginas' => 'integer',
        'stock' => 'integer',
        'stock_minimo' => 'integer'
    ];

    // Relaciones
    public function promociones()
    {
        return $this->hasMany(Promocion::class, 'libro_id');
    }

    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'libro_id');
    }

    // Scopes
    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeStockBajo($query, $limite = 5)
    {
        return $query->where('stock', '<=', $limite);
    }

    public function scopeSinStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    public function scopeEnPromocion($query)
    {
        return $query->whereNotNull('precio_promocion')
            ->where('precio_promocion', '>', 0)
            ->where('precio_promocion', '<', 'precio');
    }

    public function scopePorGenero($query, $genero)
    {
        return $query->where('genero', $genero);
    }

    public function scopePorAutor($query, $autor)
    {
        return $query->where('autor', 'LIKE', "%{$autor}%");
    }

    public function scopePorEditorial($query, $editorial)
    {
        return $query->where('editorial', 'LIKE', "%{$editorial}%");
    }

    // Métodos auxiliares
    public function getPrecioFinalAttribute()
    {
        if ($this->precio_promocion && $this->precio_promocion > 0 && $this->precio_promocion < $this->precio) {
            return $this->precio_promocion;
        }
        return $this->precio;
    }

    public function getEstaEnPromocionAttribute()
    {
        return $this->precio_promocion &&
            $this->precio_promocion > 0 &&
            $this->precio_promocion < $this->precio;
    }

    public function getAhorroAttribute()
    {
        if ($this->esta_en_promocion) {
            return $this->precio - $this->precio_promocion;
        }
        return 0;
    }

    public function getPorcentajeAhorroAttribute()
    {
        if ($this->esta_en_promocion && $this->precio > 0) {
            return round((($this->precio - $this->precio_promocion) / $this->precio) * 100);
        }
        return 0;
    }

    public function getEstadoStockAttribute()
    {
        if ($this->stock <= 0) {
            return 'agotado';
        } elseif ($this->stock <= $this->stock_minimo) {
            return 'bajo';
        }
        return 'disponible';
    }

    public function getEstadoStockLabelAttribute()
    {
        $estados = [
            'agotado' => 'danger',
            'bajo' => 'warning',
            'disponible' => 'success'
        ];
        return $estados[$this->estado_stock] ?? 'secondary';
    }

    public function getEstadoStockTextoAttribute()
    {
        $textos = [
            'agotado' => 'Agotado',
            'bajo' => 'Stock Bajo',
            'disponible' => 'En Stock'
        ];
        return $textos[$this->estado_stock] ?? 'Desconocido';
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->titulo} - {$this->autor}";
    }
}
