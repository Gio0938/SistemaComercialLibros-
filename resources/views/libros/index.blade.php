@extends('layouts.admin')

@section('title', 'Libros - Librería & Cine')
@section('page-title', 'Gestión de Libros')
@section('page-subtitle', 'Administra el catálogo de libros')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('libros.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nuevo Libro
                        </a>
                        <a href="{{ route('libros.exportar-csv') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-csv me-2"></i>Exportar CSV
                        </a>
                    </div>
                    <div>
                        <form action="{{ route('libros.index') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('search') }}">
                            <select name="genero" class="form-select form-select-sm">
                                <option value="">Todos los géneros</option>
                                @foreach($generos ?? [] as $genero)
                                    <option value="{{ $genero }}" {{ request('genero') == $genero ? 'selected' : '' }}>{{ $genero }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('libros.index') }}" class="btn btn-sm btn-outline-secondary">
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
                            <th>Autor</th>
                            <th>Género</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($libros as $libro)
                            <tr>
                                <td>{{ $libro->idlibro }}</td>
                                <td>
                                    <strong>{{ Str::limit($libro->titulo, 30) }}</strong>
                                    @if($libro->destacado)
                                        <span class="badge bg-warning text-dark ms-1"><i class="fas fa-star"></i></span>
                                    @endif
                                </td>
                                <td>{{ $libro->autor }}</td>
                                <td><span class="badge bg-secondary">{{ $libro->genero ?? 'N/A' }}</span></td>
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
                                        <span class="badge bg-warning text-dark">{{ $libro->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">Agotado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($libro->disponible)
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-danger">No disponible</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('libros.show', $libro->idlibro) }}" class="btn btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('libros.edit', $libro->idlibro) }}" class="btn btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('libros.destroy', $libro->idlibro) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar" data-message="¿Estás seguro de eliminar el libro '{{ $libro->titulo }}'?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-book fa-2x d-block mb-2"></i>
                                    No hay libros registrados
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $libros->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
