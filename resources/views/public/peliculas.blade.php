@extends('layouts.public')

@section('title', 'Películas - Librería & Cine')

@section('content')
    <div class="row">
        <!-- Sidebar con filtros -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar">
                <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Filtros</h6>

                <form action="{{ route('public.peliculas') }}" method="GET">
                    <!-- Búsqueda -->
                    <div class="mb-3">
                        <label class="form-label">Buscar</label>
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar películas..." value="{{ request('buscar') }}">
                    </div>

                    <!-- Género -->
                    <div class="mb-3">
                        <label class="form-label">Género</label>
                        <select name="genero" class="form-select">
                            <option value="">Todos los géneros</option>
                            @foreach($generos ?? [] as $genero)
                                <option value="{{ $genero }}" {{ request('genero') == $genero ? 'selected' : '' }}>
                                    {{ $genero }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Formato -->
                    <div class="mb-3">
                        <label class="form-label">Formato</label>
                        <select name="formato" class="form-select">
                            <option value="">Todos los formatos</option>
                            @foreach($formatos ?? [] as $formato)
                                <option value="{{ $formato }}" {{ request('formato') == $formato ? 'selected' : '' }}>
                                    {{ $formato }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ordenar -->
                    <div class="mb-3">
                        <label class="form-label">Ordenar por</label>
                        <select name="orden" class="form-select">
                            <option value="titulo" {{ request('orden') == 'titulo' ? 'selected' : '' }}>Título (A-Z)</option>
                            <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio (menor a mayor)</option>
                            <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio (mayor a menor)</option>
                            <option value="anio" {{ request('orden') == 'anio' ? 'selected' : '' }}>Año (más reciente)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filtrar
                    </button>

                    <a href="{{ route('public.peliculas') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-times me-2"></i>Limpiar filtros
                    </a>
                </form>
            </div>
        </div>

        <!-- Listado de Películas -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Películas disponibles ({{ $peliculas->total() }})</h5>
            </div>

            <div class="row">
                @forelse($peliculas as $pelicula)
                    <div class="col-md-4 mb-3">
                        <div class="card card-product">
                            <div class="card-body">
                                @if($pelicula->esta_en_promocion)
                                    <span class="badge-promo">-{{ $pelicula->porcentaje_ahorro }}%</span>
                                @endif
                                <h6 class="card-title">{{ Str::limit($pelicula->titulo, 30) }}</h6>
                                <p class="text-muted small">{{ Str::limit($pelicula->director, 20) }}</p>
                                <div class="mb-2">
                                    <span class="badge bg-secondary">{{ $pelicula->genero ?? 'N/A' }}</span>
                                    <span class="badge bg-info">{{ $pelicula->formato ?? 'N/A' }}</span>
                                    <span class="badge bg-dark">{{ $pelicula->anio ?? 'N/A' }}</span>
                                </div>
                                <p class="price">
                                    ${{ number_format($pelicula->precio_final, 2) }}
                                    @if($pelicula->esta_en_promocion)
                                        <span class="old-price">${{ number_format($pelicula->precio, 2) }}</span>
                                    @endif
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-{{ $pelicula->stock > 10 ? 'success' : ($pelicula->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ $pelicula->stock > 0 ? $pelicula->stock . ' disponibles' : 'Agotado' }}
                                </span>
                                </div>
                                <a href="{{ route('public.pelicula-detalle', $pelicula->idpelicula) }}" class="btn btn-primary btn-sm w-100 mt-2">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fas fa-film fa-3x d-block mb-3"></i>
                        <h5>No se encontraron películas</h5>
                        <p>Prueba con otros filtros o términos de búsqueda</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $peliculas->links() }}
            </div>
        </div>
    </div>
@endsection
