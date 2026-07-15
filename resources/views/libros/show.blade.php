@extends('layouts.admin')

@section('title', $libro->titulo . ' - Librería & Cine')
@section('page-title', 'Detalles del Libro')
@section('page-subtitle', $libro->titulo)

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Imagen -->
                        <div class="col-md-4 text-center">
                            @if($libro->foto)
                                <img src="{{ asset('storage/' . $libro->foto) }}" alt="{{ $libro->titulo }}" class="img-fluid rounded" style="max-height: 300px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="fas fa-book fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Información -->
                        <div class="col-md-8">
                            <h3>{{ $libro->titulo }}</h3>
                            <p class="text-muted">por <strong>{{ $libro->autor }}</strong></p>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <small class="text-muted">Editorial</small>
                                    <p>{{ $libro->editorial ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">ISBN</small>
                                    <p>{{ $libro->isbn ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Género</small>
                                    <p><span class="badge bg-secondary">{{ $libro->genero ?? 'N/A' }}</span></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Idioma</small>
                                    <p>{{ $libro->idioma ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Páginas</small>
                                    <p>{{ $libro->paginas ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Fecha de Publicación</small>
                                    <p>{{ $libro->fecha_publicacion ? $libro->fecha_publicacion->format('d/m/Y') : 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Precio</small>
                                    <p>
                                        @if($libro->precio_promocion && $libro->precio_promocion < $libro->precio)
                                            <span class="text-danger fs-5">${{ number_format($libro->precio_promocion, 2) }}</span>
                                            <small class="text-muted text-decoration-line-through">${{ number_format($libro->precio, 2) }}</small>
                                        @else
                                            <span class="fs-5">${{ number_format($libro->precio, 2) }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Stock</small>
                                    <p>
                                        @if($libro->stock > 10)
                                            <span class="badge bg-success">{{ $libro->stock }} unidades</span>
                                        @elseif($libro->stock > 0 && $libro->stock <= 10)
                                            <span class="badge bg-warning text-dark">{{ $libro->stock }} unidades</span>
                                        @else
                                            <span class="badge bg-danger">Agotado</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <small class="text-muted">Descripción</small>
                                <p class="text-justify">{{ $libro->descripcion ?? 'Sin descripción' }}</p>
                            </div>

                            <div class="mt-3 d-flex gap-2">
                            <span class="badge bg-{{ $libro->disponible ? 'success' : 'danger' }}">
                                {{ $libro->disponible ? 'Disponible' : 'No disponible' }}
                            </span>
                                @if($libro->destacado)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Destacado</span>
                                @endif
                                @if($libro->esta_en_promocion)
                                    <span class="badge bg-danger"><i class="fas fa-tags me-1"></i>En Promoción</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('libros.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                        <div>
                            <a href="{{ route('libros.edit', $libro->idlibro) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Editar
                            </a>
                            <form action="{{ route('libros.destroy', $libro->idlibro) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-message="¿Estás seguro de eliminar el libro '{{ $libro->titulo }}'?">
                                    <i class="fas fa-trash me-2"></i>Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
