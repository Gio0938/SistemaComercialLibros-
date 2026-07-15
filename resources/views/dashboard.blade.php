@extends('layouts.admin')

@section('title', 'Dashboard - Librería & Cine')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general del sistema')

@section('content')
    <div class="container-fluid">
        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">{{ \App\Models\Libro::count() }}</div>
                            <div class="stat-label">Libros Disponibles</div>
                        </div>
                        <div class="stat-icon primary">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">{{ \App\Models\Pelicula::count() }}</div>
                            <div class="stat-label">Películas Disponibles</div>
                        </div>
                        <div class="stat-icon success">
                            <i class="fas fa-film"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">{{ \App\Models\Venta::count() }}</div>
                            <div class="stat-label">Ventas Realizadas</div>
                        </div>
                        <div class="stat-icon warning">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">${{ number_format(\App\Models\Venta::sum('total') ?? 0, 2) }}</div>
                            <div class="stat-label">Ingresos Totales</div>
                        </div>
                        <div class="stat-icon info">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promociones Activas -->
        @php
            $promocionesActivas = \App\Models\Promocion::where('activa', true)
                ->where('fecha_inicio', '<=', now())
                ->where('fecha_fin', '>=', now())
                ->take(3)
                ->get();
        @endphp

        @if($promocionesActivas->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient text-white p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h4 class="mb-1"><i class="fas fa-tags me-2"></i>¡Ofertas Especiales!</h4>
                                <p class="mb-0 opacity-75">Aprovecha nuestras promociones en libros y películas seleccionadas</p>
                            </div>
                            <a href="{{ route('promociones.index') }}" class="btn btn-light">
                                <i class="fas fa-eye me-2"></i>Ver todas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                @foreach($promocionesActivas as $promocion)
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="card-title">{{ $promocion->nombre }}</h6>
                                    <span class="badge bg-warning text-dark">
                                {{ $promocion->descuento_porcentaje ? $promocion->descuento_porcentaje . '% OFF' : '$' . number_format($promocion->descuento_fijo, 2) . ' OFF' }}
                            </span>
                                </div>
                                <p class="card-text small text-muted">{{ Str::limit($promocion->descripcion ?? 'Sin descripción', 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($promocion->fecha_inicio)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }}
                                    </small>
                                    @if($promocion->codigo_promocional)
                                        <span class="badge bg-dark">
                                    <i class="fas fa-tag me-1"></i>{{ $promocion->codigo_promocional }}
                                </span>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-{{ $promocion->tipo == 'libro' ? 'book' : ($promocion->tipo == 'pelicula' ? 'film' : 'layer-group') }} me-1"></i>
                                        {{ $promocion->tipo == 'libro' ? 'Libros' : ($promocion->tipo == 'pelicula' ? 'Películas' : 'Ambos') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Últimos Libros y Películas -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="table-container">
                    <div class="table-header">
                        <h6><i class="fas fa-book me-2" style="color: var(--primary-color);"></i>Últimos Libros Agregados</h6>
                        <a href="{{ route('libros.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Precio</th>
                                <th>Stock</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse(\App\Models\Libro::orderBy('created_at', 'desc')->take(5)->get() as $libro)
                                <tr>
                                    <td>{{ Str::limit($libro->titulo, 25) }}</td>
                                    <td>{{ Str::limit($libro->autor, 20) }}</td>
                                    <td>
                                        @if($libro->precio_promocion && $libro->precio_promocion < $libro->precio)
                                            <span class="text-danger">${{ number_format($libro->precio_promocion, 2) }}</span>
                                            <small class="text-muted text-decoration-line-through">${{ number_format($libro->precio, 2) }}</small>
                                        @else
                                            ${{ number_format($libro->precio, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($libro->stock > 10)
                                            <span class="badge bg-success">{{ $libro->stock }}</span>
                                        @elseif($libro->stock > 0 && $libro->stock <= 10)
                                            <span class="badge bg-warning">{{ $libro->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">Agotado</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay libros registrados</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="table-container">
                    <div class="table-header">
                        <h6><i class="fas fa-film me-2" style="color: var(--primary-color);"></i>Últimas Películas Agregadas</h6>
                        <a href="{{ route('peliculas.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Título</th>
                                <th>Director</th>
                                <th>Precio</th>
                                <th>Stock</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse(\App\Models\Pelicula::orderBy('created_at', 'desc')->take(5)->get() as $pelicula)
                                <tr>
                                    <td>{{ Str::limit($pelicula->titulo, 25) }}</td>
                                    <td>{{ Str::limit($pelicula->director, 20) }}</td>
                                    <td>
                                        @if($pelicula->precio_promocion && $pelicula->precio_promocion < $pelicula->precio)
                                            <span class="text-danger">${{ number_format($pelicula->precio_promocion, 2) }}</span>
                                            <small class="text-muted text-decoration-line-through">${{ number_format($pelicula->precio, 2) }}</small>
                                        @else
                                            ${{ number_format($pelicula->precio, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($pelicula->stock > 10)
                                            <span class="badge bg-success">{{ $pelicula->stock }}</span>
                                        @elseif($pelicula->stock > 0 && $pelicula->stock <= 10)
                                            <span class="badge bg-warning">{{ $pelicula->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">Agotado</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay películas registradas</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos con Stock Bajo -->
        @php
            $itemsStockBajo = collect();

            $librosStockBajo = \App\Models\Libro::whereRaw('stock <= stock_minimo')
                ->where('stock', '>', 0)
                ->get()
                ->map(function($item) {
                    return (object) [
                        'tipo' => 'Libro',
                        'nombre' => $item->titulo,
                        'stock' => $item->stock,
                        'minimo' => $item->stock_minimo,
                        'estado' => 'Bajo'
                    ];
                });

            $peliculasStockBajo = \App\Models\Pelicula::whereRaw('stock <= stock_minimo')
                ->where('stock', '>', 0)
                ->get()
                ->map(function($item) {
                    return (object) [
                        'tipo' => 'Película',
                        'nombre' => $item->titulo,
                        'stock' => $item->stock,
                        'minimo' => $item->stock_minimo,
                        'estado' => 'Bajo'
                    ];
                });

            $librosAgotados = \App\Models\Libro::where('stock', 0)
                ->get()
                ->map(function($item) {
                    return (object) [
                        'tipo' => 'Libro',
                        'nombre' => $item->titulo,
                        'stock' => 0,
                        'minimo' => $item->stock_minimo,
                        'estado' => 'Agotado'
                    ];
                });

            $peliculasAgotadas = \App\Models\Pelicula::where('stock', 0)
                ->get()
                ->map(function($item) {
                    return (object) [
                        'tipo' => 'Película',
                        'nombre' => $item->titulo,
                        'stock' => 0,
                        'minimo' => $item->stock_minimo,
                        'estado' => 'Agotado'
                    ];
                });

            $itemsStockBajo = $librosStockBajo
                ->merge($peliculasStockBajo)
                ->merge($librosAgotados)
                ->merge($peliculasAgotadas)
                ->sortBy('stock')
                ->take(10);
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="table-container">
                    <div class="table-header">
                        <h6><i class="fas fa-exclamation-triangle me-2" style="color: #ffc107;"></i>Productos con Stock Bajo</h6>
                        <a href="{{ route('inventario.index') }}" class="btn btn-sm btn-outline-primary">Gestionar inventario</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Nombre</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($itemsStockBajo as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $item->tipo == 'Libro' ? 'primary' : 'info' }}">
                                            {{ $item->tipo }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($item->nombre, 40) }}</td>
                                    <td>
                                        <span class="badge {{ $item->estado == 'Agotado' ? 'bg-danger' : 'bg-warning' }}">
                                            {{ $item->stock }}
                                        </span>
                                    </td>
                                    <td>{{ $item->minimo }}</td>
                                    <td>
                                        @if($item->estado == 'Agotado')
                                            <span class="badge bg-danger">Agotado</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Stock Bajo</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        ¡Todo el inventario está en niveles adecuados!
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas Recientes -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="table-container">
                    <div class="table-header">
                        <h6><i class="fas fa-clock me-2" style="color: #17a2b8;"></i>Ventas Recientes</h6>
                        <a href="{{ route('ventas.historial') }}" class="btn btn-sm btn-outline-primary">Ver historial</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse(\App\Models\Venta::orderBy('fecha_venta', 'desc')->take(5)->get() as $venta)
                                <tr>
                                    <td><strong>{{ $venta->folio }}</strong></td>
                                    <td>
                                        @if($venta->cliente)
                                            {{ $venta->cliente->nombre }} {{ $venta->cliente->apellido ?? '' }}
                                        @else
                                            <span class="text-muted">Público en general</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}</td>
                                    <td><strong>${{ number_format($venta->total, 2) }}</strong></td>
                                    <td>
                                        @if($venta->estado == 'completada')
                                            <span class="badge bg-success">Completada</span>
                                        @elseif($venta->estado == 'pendiente')
                                            <span class="badge bg-warning text-dark">Pendiente</span>
                                        @else
                                            <span class="badge bg-danger">Cancelada</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay ventas registradas</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card.bg-gradient {
            border: none;
            border-radius: 15px;
        }
        .card.bg-gradient .btn-light {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
        }
        .card.bg-gradient .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
@endpush
