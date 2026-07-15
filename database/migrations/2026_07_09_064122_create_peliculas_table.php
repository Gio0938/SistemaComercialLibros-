<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    use HasFactory;

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
        'duracion' => 'integer',
        'stock' => 'integer',
        'stock_minimo' => 'integer'
    ];

    // Relaciones
    public function promociones()
    {
        return $this->hasMany(Promocion::class, 'pelicula_id');
    }

    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'pelicula_id');
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

    public function scopePorDirector($query, $director)
    {
        return $query->where('director', 'LIKE', "%{$director}%");
    }

    public function scopePorAnio($query, $anio)
    {
        return $query->where('anio', $anio);
    }

    public function scopePorFormato($query, $formato)
    {
        return $query->where('formato', $formato);
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

    public function getDuracionFormateadaAttribute()
    {
        if (!$this->duracion) return 'N/A';
        $horas = floor($this->duracion / 60);
        $minutos = $this->duracion % 60;
        if ($horas > 0) {
            return "{$horas}h {$minutos}min";
        }
        return "{$minutos}min";
    }
}
