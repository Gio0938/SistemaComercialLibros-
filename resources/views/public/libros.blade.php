
@extends('layouts.public')

@section('title', 'Libros - Librería & Cine')

@section('content')
    <div class="row">
        <!-- Sidebar con filtros -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar">
                <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Filtros</h6>

                <form action="{{ route('public.libros') }}" method="GET">
                    <!-- Búsqueda -->
                    <div class="mb-3">
                        <label class="form-label">Buscar</label>
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar libros..." value="{{ request('buscar') }}">
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

                    <!-- Ordenar -->
                    <div class="mb-3">
                        <label class="form-label">Ordenar por</label>
                        <select name="orden" class="form-select">
                            <option value="titulo" {{ request('orden') == 'titulo' ? 'selected' : '' }}>Título (A-Z)</option>
                            <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio (menor a mayor)</option>
                            <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio (mayor a menor)</option>
                            <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más recientes</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filtrar
                    </button>

                    <a href="{{ route('public.libros') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-times me-2"></i>Limpiar filtros
                    </a>
                </form>
            </div>
        </div>

        <!-- Listado de Libros -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Libros disponibles ({{ $libros->total() }})</h5>
            </div>

            <div class="row">
                @forelse($libros as $libro)
                    <div class="col-md-4 mb-3">
                        <div class="card card-product">
                            <div class="card-body">
                                @if($libro->esta_en_promocion)
                                    <span class="badge-promo">-{{ $libro->porcentaje_ahorro }}%</span>
                                @endif
                                <h6 class="card-title">{{ Str::limit($libro->titulo, 30) }}</h6>
                                <p class="text-muted small">{{ Str::limit($libro->autor, 20) }}</p>
                                <p class="price">
                                    ${{ number_format($libro->precio_final, 2) }}
                                    @if($libro->esta_en_promocion)
                                        <span class="old-price">${{ number_format($libro->precio, 2) }}</span>
                                    @endif
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-secondary">{{ $libro->genero ?? 'N/A' }}</span>
                                    <span class="badge bg-{{ $libro->stock > 10 ? 'success' : ($libro->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ $libro->stock > 0 ? $libro->stock . ' disponibles' : 'Agotado' }}
                                </span>
                                </div>
                                <a href="{{ route('public.libro-detalle', $libro->idlibro) }}" class="btn btn-primary btn-sm w-100 mt-2">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fas fa-book fa-3x d-block mb-3"></i>
                        <h5>No se encontraron libros</h5>
                        <p>Prueba con otros filtros o términos de búsqueda</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $libros->links() }}
            </div>
        </div>
    </div>
@endsection
