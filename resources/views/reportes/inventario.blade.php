
@extends('layouts.admin')

@section('title', 'Inventario - Librería & Cine')
@section('page-title', 'Inventario General')
@section('page-subtitle', 'Resumen del inventario de libros y películas')

@section('content')
    <div class="row">
        <!-- Resumen de Inventario -->
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $totalLibros ?? 0 }}</div>
                        <div class="stat-label">Total Libros</div>
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
                        <div class="stat-number">{{ $totalPeliculas ?? 0 }}</div>
                        <div class="stat-label">Total Películas</div>
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
                        <div class="stat-number">{{ number_format(($totalValorLibros ?? 0) + ($totalValorPeliculas ?? 0), 2) }}</div>
                        <div class="stat-label">Valor Total Inventario</div>
                    </div>
                    <div class="stat-icon warning">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ ($librosStockBajo ?? 0) + ($peliculasStockBajo ?? 0) }}</div>
                        <div class="stat-label">Productos con Stock Bajo</div>
                    </div>
                    <div class="stat-icon danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Libros -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h6><i class="fas fa-book me-2"></i>Inventario de Libros</h6>
                    <a href="{{ route('reportes.exportar-inventario-pdf') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-file-pdf me-1"></i> Exportar PDF
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Género</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Valor</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($libros ?? [] as $libro)
                            <tr>
                                <td>{{ $libro->idlibro }}</td>
                                <td>{{ Str::limit($libro->titulo, 30) }}</td>
                                <td>{{ $libro->autor }}</td>
                                <td><span class="badge bg-secondary">{{ $libro->genero ?? 'N/A' }}</span></td>
                                <td>${{ number_format($libro->precio, 2) }}</td>
                                <td>
                                    @if($libro->stock > 10)
                                        <span class="badge bg-success">{{ $libro->stock }}</span>
                                    @elseif($libro->stock > 0 && $libro->stock <= 10)
                                        <span class="badge bg-warning text-dark">{{ $libro->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">0</span>
                                    @endif
                                </td>
                                <td>${{ number_format($libro->precio * $libro->stock, 2) }}</td>
                                <td>
                                    @if($libro->stock > 10)
                                        <span class="badge bg-success">Normal</span>
                                    @elseif($libro->stock > 0 && $libro->stock <= 10)
                                        <span class="badge bg-warning text-dark">Stock Bajo</span>
                                    @else
                                        <span class="badge bg-danger">Agotado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No hay libros registrados</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Películas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h6><i class="fas fa-film me-2"></i>Inventario de Películas</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Director</th>
                            <th>Género</th>
                            <th>Formato</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Valor</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($peliculas ?? [] as $pelicula)
                            <tr>
                                <td>{{ $pelicula->idpelicula }}</td>
                                <td>{{ Str::limit($pelicula->titulo, 30) }}</td>
                                <td>{{ $pelicula->director }}</td>
                                <td><span class="badge bg-secondary">{{ $pelicula->genero ?? 'N/A' }}</span></td>
                                <td><span class="badge bg-info">{{ $pelicula->formato ?? 'N/A' }}</span></td>
                                <td>${{ number_format($pelicula->precio, 2) }}</td>
                                <td>
                                    @if($pelicula->stock > 10)
                                        <span class="badge bg-success">{{ $pelicula->stock }}</span>
                                    @elseif($pelicula->stock > 0 && $pelicula->stock <= 10)
                                        <span class="badge bg-warning text-dark">{{ $pelicula->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">0</span>
                                    @endif
                                </td>
                                <td>${{ number_format($pelicula->precio * $pelicula->stock, 2) }}</td>
                                <td>
                                    @if($pelicula->stock > 10)
                                        <span class="badge bg-success">Normal</span>
                                    @elseif($pelicula->stock > 0 && $pelicula->stock <= 10)
                                        <span class="badge bg-warning text-dark">Stock Bajo</span>
                                    @else
                                        <span class="badge bg-danger">Agotado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No hay películas registradas</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
