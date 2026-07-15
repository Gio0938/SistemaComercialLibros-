<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'idventa';

    protected $fillable = [
        'folio',
        'cliente_id',
        'usuario_id',
        'fecha_venta',
        'subtotal',
        'descuento',
        'iva',
        'total',
        'metodo_pago',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_venta' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'idcliente');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'idusuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id', 'idventa');
    }

    public function getTotalFormateadoAttribute()
    {
        return '$' . number_format($this->total, 2);
    }

    public function getFechaFormateadaAttribute()
    {
        return $this->fecha_venta ? $this->fecha_venta->format('d/m/Y H:i') : 'N/A';
    }

    public function getEstadoTextoAttribute()
    {
        $estados = [
            'completada' => 'Completada',
            'pendiente' => 'Pendiente',
            'cancelada' => 'Cancelada'
        ];
        return $estados[$this->estado] ?? 'Desconocido';
    }

    public function getClienteNombreAttribute()
    {
        if ($this->cliente) {
            return $this->cliente->nombre_completo;
        }
        return 'Público en general';
    }
}
