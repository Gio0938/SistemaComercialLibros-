<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $venta->folio }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            background: white;
            padding: 20px;
            max-width: 300px;
            margin: 0 auto;
        }
        .ticket {
            border: 1px dashed #ccc;
            padding: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header h2 {
            font-size: 16px;
            margin-bottom: 2px;
        }
        .header .sub {
            font-size: 10px;
            color: #666;
        }
        .info {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .info p {
            margin: 2px 0;
        }
        .items {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        .item .name {
            flex: 1;
        }
        .item .qty {
            text-align: center;
            width: 30px;
        }
        .item .price {
            text-align: right;
            width: 70px;
        }
        .totals {
            text-align: right;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .totals .row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        .totals .row.total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .text-muted { color: #666; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
<div class="ticket">
    <div class="header">
        <h2>📚 Librería & Cine</h2>
        <div class="sub">Sistema de Ventas</div>
        <div class="sub">Tel: (228) 123-4567</div>
    </div>

    <div class="info">
        <p><strong>Folio:</strong> {{ $venta->folio }}</p>
        <p><strong>Fecha:</strong> {{ $venta->fecha_venta->format('d/m/Y H:i') }}</p>
        <p><strong>Cliente:</strong> {{ $venta->cliente ? $venta->cliente->nombre . ' ' . $venta->cliente->apellido : 'Público en general' }}</p>
        <p><strong>Vendedor:</strong> {{ $venta->usuario->nombre ?? 'N/A' }}</p>
        <p><strong>Pago:</strong> {{ $venta->metodo_pago_texto ?? $venta->metodo_pago }}</p>
    </div>

    <div class="items">
        <div style="font-weight:bold;border-bottom:1px solid #333;padding-bottom:5px;margin-bottom:5px;">
            <span>Producto</span>
            <span style="float:right;">Subtotal</span>
        </div>
        @foreach($venta->detalles as $detalle)
            <div class="item">
                    <span class="name">
                        {{ $detalle->tipo_producto == 'libro' ? '📚' : '🎬' }}
                        {{ $detalle->nombre_producto }}
                    </span>
                <span class="qty">x{{ $detalle->cantidad }}</span>
                <span class="price">${{ number_format($detalle->subtotal, 2) }}</span>
            </div>
        @endforeach
    </div>

    <div class="totals">
        <div class="row">
            <span>Subtotal:</span>
            <span>${{ number_format($venta->subtotal, 2) }}</span>
        </div>
        <div class="row">
            <span>IVA (16%):</span>
            <span>${{ number_format($venta->iva, 2) }}</span>
        </div>
        @if($venta->descuento > 0)
            <div class="row">
                <span>Descuento:</span>
                <span>-${{ number_format($venta->descuento, 2) }}</span>
            </div>
        @endif
        <div class="row total">
            <span>TOTAL:</span>
            <span>${{ number_format($venta->total, 2) }}</span>
        </div>
    </div>

    @if($venta->observaciones)
        <div class="info">
            <p><strong>Observaciones:</strong></p>
            <p>{{ $venta->observaciones }}</p>
        </div>
    @endif

    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>Visítanos en www.libreriaycine.com</p>
        <p>--- Fin del Ticket ---</p>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>
</body>
</html>
