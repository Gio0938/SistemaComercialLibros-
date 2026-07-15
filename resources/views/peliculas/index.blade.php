@extends('layouts.admin')

@section('title', 'Películas - Librería & Cine')
@section('page-title', 'Gestión de Películas')
@section('page-subtitle', 'Administra el catálogo de películas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('peliculas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nueva Película
                        </a>
                        <a href="{{ route('peliculas.exportar-csv') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-csv me-2"></i>Exportar CSV
                        </a>
                    </div>
                    <div>
                        <form action="{{ route('peliculas.index') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('search') }}">
                            <select name="genero" class="form-select form-select-sm">
                                <option value="">Todos los géneros</option>
                                @foreach($generos ?? [] as $genero)
                                    <option value="{{ $genero }}" {{ request('genero') == $genero ? 'selected' : '' }}>{{ $genero }}</option>
                                @endforeach
                            </select>
                            <select name="formato" class="form-select form-select-sm">
                                <option value="">Todos los formatos</option>
                                @foreach($formatos ?? [] as $formato)
                                    <option value="{{ $formato }}" {{ request('formato') == $formato ? 'selected' : '' }}>{{ $formato }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('peliculas.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Director</th>
                            <th>Año</th>
                            <th>Formato</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($peliculas as $pelicula)
                            <tr>
                                <td>{{ $pelicula->idpelicula }}</td>
                                <td>
                                    <strong>{{ Str::limit($pelicula->titulo, 25) }}</strong>
                                    @if($pelicula->destacado)
                                        <span class="badge bg-warning text-dark ms-1"><i class="fas fa-star"></i></span>
                                    @endif
                                </td>
                                <td>{{ $pelicula->director }}</td>
                                <td>{{ $pelicula->anio ?? 'N/A' }}</td>
                                <td><span class="badge bg-info">{{ $pelicula->formato ?? 'N/A' }}</span></td>
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
                                        <span class="badge bg-warning text-dark">{{ $pelicula->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">Agotado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pelicula->disponible)
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-danger">No disponible</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('peliculas.show', $pelicula->idpelicula) }}" class="btn btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('peliculas.edit', $pelicula->idpelicula) }}" class="btn btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('peliculas.destroy', $pelicula->idpelicula) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar" data-message="¿Estás seguro de eliminar la película '{{ $pelicula->titulo }}'?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-film fa-2x d-block mb-2"></i>
                                    No hay películas registradas
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $peliculas->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
