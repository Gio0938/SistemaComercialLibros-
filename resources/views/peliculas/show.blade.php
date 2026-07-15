@extends('layouts.admin')

@section('title', $pelicula->titulo . ' - Librería & Cine')
@section('page-title', 'Detalles de la Película')
@section('page-subtitle', $pelicula->titulo)

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Portada -->
                        <div class="col-md-4 text-center">
                            @if($pelicula->portada)
                                <img src="{{ asset('storage/' . $pelicula->portada) }}" alt="{{ $pelicula->titulo }}" class="img-fluid rounded" style="max-height: 300px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="fas fa-film fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Información -->
                        <div class="col-md-8">
                            <h3>{{ $pelicula->titulo }}</h3>
                            <p class="text-muted">dirigida por <strong>{{ $pelicula->director }}</strong></p>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <small class="text-muted">Año</small>
                                    <p>{{ $pelicula->anio ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Duración</small>
                                    <p>{{ $pelicula->duracion_formateada }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Género</small>
                                    <p><span class="badge bg-secondary">{{ $pelicula->genero ?? 'N/A' }}</span></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Clasificación</small>
                                    <p><span class="badge bg-{{ $pelicula->clasificacion_badge ?? 'secondary' }}">{{ $pelicula->clasificacion ?? 'N/A' }}</span></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Formato</small>
                                    <p><span class="badge bg-info">{{ $pelicula->formato ?? 'N/A' }}</span></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Idioma</small>
                                    <p>{{ $pelicula->idioma ?? 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">Subtítulos</small>
                                    <p>{{ $pelicula->subtitulos ?? 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">Reparto</small>
                                    <p>{{ $pelicula->reparto ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Precio</small>
                                    <p>
                                        @if($pelicula->precio_promocion && $pelicula->precio_promocion < $pelicula->precio)
                                            <span class="text-danger fs-5">${{ number_format($pelicula->precio_promocion, 2) }}</span>
                                            <small class="text-muted text-decoration-line-through">${{ number_format($pelicula->precio, 2) }}</small>
                                        @else
                                            <span class="fs-5">${{ number_format($pelicula->precio, 2) }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Stock</small>
                                    <p>
                                        @if($pelicula->stock > 10)
                                            <span class="badge bg-success">{{ $pelicula->stock }} unidades</span>
                                        @elseif($pelicula->stock > 0 && $pelicula->stock <= 10)
                                            <span class="badge bg-warning text-dark">{{ $pelicula->stock }} unidades</span>
                                        @else
                                            <span class="badge bg-danger">Agotado</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <small class="text-muted">Sinopsis</small>
                                <p class="text-justify">{{ $pelicula->sinopsis ?? 'Sin sinopsis' }}</p>
                            </div>

                            @if($pelicula->trailer_url)
                                <div class="mt-3">
                                    <small class="text-muted">Trailer</small>
                                    <div class="mt-2">
                                        <a href="{{ $pelicula->trailer_url }}" target="_blank" class="btn btn-sm btn-danger">
                                            <i class="fab fa-youtube me-2"></i>Ver Trailer
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-3 d-flex gap-2">
                            <span class="badge bg-{{ $pelicula->disponible ? 'success' : 'danger' }}">
                                {{ $pelicula->disponible ? 'Disponible' : 'No disponible' }}
                            </span>
                                @if($pelicula->destacado)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Destacado</span>
                                @endif
                                @if($pelicula->esta_en_promocion)
                                    <span class="badge bg-danger"><i class="fas fa-tags me-1"></i>En Promoción</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('peliculas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                        <div>
                            <a href="{{ route('peliculas.edit', $pelicula->idpelicula) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Editar
                            </a>
                            <form action="{{ route('peliculas.destroy', $pelicula->idpelicula) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-message="¿Estás seguro de eliminar la película '{{ $pelicula->titulo }}'?">
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
