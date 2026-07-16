
@extends('layouts.admin')

@section('title', 'Productos con Stock Bajo - Librería & Cine')
@section('page-title', 'Productos con Stock Bajo')
@section('page-subtitle', 'Lista de productos que necesitan reabastecimiento')

@section('content')
    <div class="row">
        <!-- Resumen -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number text-warning">{{ $librosStockBajo->count() + $peliculasStockBajo->count() }}</div>
                        <div class="stat-label">Stock Bajo</div>
                    </div>
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number text-danger">{{ $librosAgotados->count() + $peliculasAgotadas->count() }}</div>
                        <div class="stat-label">Agotados</div>
                    </div>
                    <div class="stat-icon danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number text-success">{{ $librosStockBajo->count() + $peliculasStockBajo->count() + $librosAgotados->count() + $peliculasAgotadas->count() }}</div>
                        <div class="stat-label">Total Productos Críticos</div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Libros con Stock Bajo -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="table-container">
                <div class="table-header">
                    <h6><i class="fas fa-book me-2 text-warning"></i>Libros con Stock Bajo</h6>
                    <a href="{{ route('libros.index') }}" class="btn btn-sm btn-outline-primary">Gestionar</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($librosStockBajo as $libro)
                            <tr>
                                <td>{{ Str::limit($libro->titulo, 25) }}</td>
                                <td>{{ $libro->autor }}</td>
                                <td><span class="badge bg-warning text-dark">{{ $libro->stock }}</span></td>
                                <td>{{ $libro->stock_minimo }}</td>
                                <td>
                                    <a href="{{ route('libros.edit', $libro->idlibro) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-success">
                                    <i class="fas fa-check-circle me-2"></i>No hay libros con stock bajo
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Películas con Stock Bajo -->
        <div class="col-md-6">
            <div class="table-container">
                <div class="table-header">
                    <h6><i class="fas fa-film me-2 text-warning"></i>Películas con Stock Bajo</h6>
                    <a href="{{ route('peliculas.index') }}" class="btn btn-sm btn-outline-primary">Gestionar</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Título</th>
                            <th>Director</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($peliculasStockBajo as $pelicula)
                            <tr>
                                <td>{{ Str::limit($pelicula->titulo, 25) }}</td>
                                <td>{{ $pelicula->director }}</td>
                                <td><span class="badge bg-warning text-dark">{{ $pelicula->stock }}</span></td>
                                <td>{{ $pelicula->stock_minimo }}</td>
                                <td>
                                    <a href="{{ route('peliculas.edit', $pelicula->idpelicula) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-success">
                                    <i class="fas fa-check-circle me-2"></i>No hay películas con stock bajo
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos Agotados -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="table-container">
                <div class="table-header">
                    <h6><i class="fas fa-book me-2 text-danger"></i>Libros Agotados</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Stock</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($librosAgotados as $libro)
                            <tr>
                                <td>{{ Str::limit($libro->titulo, 25) }}</td>
                                <td>{{ $libro->autor }}</td>
                                <td><span class="badge bg-danger">0</span></td>
                                <td>
                                    <a href="{{ route('libros.edit', $libro->idlibro) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-success">
                                    <i class="fas fa-check-circle me-2"></i>No hay libros agotados
                                </td>
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
                    <h6><i class="fas fa-film me-2 text-danger"></i>Películas Agotadas</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Título</th>
                            <th>Director</th>
                            <th>Stock</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($peliculasAgotadas as $pelicula)
                            <tr>
                                <td>{{ Str::limit($pelicula->titulo, 25) }}</td>
                                <td>{{ $pelicula->director }}</td>
                                <td><span class="badge bg-danger">0</span></td>
                                <td>
                                    <a href="{{ route('peliculas.edit', $pelicula->idpelicula) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-success">
                                    <i class="fas fa-check-circle me-2"></i>No hay películas agotadas
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
