@extends('layouts.public')

@section('title', $pelicula->titulo . ' - Librería & Cine')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('public.index') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('public.peliculas') }}">Películas</a></li>
                    <li class="breadcrumb-item active">{{ $pelicula->titulo }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($pelicula->portada)
                                <img src="{{ asset('storage/' . $pelicula->portada) }}" alt="{{ $pelicula->titulo }}" class="img-fluid rounded" style="max-height: 300px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="fas fa-film fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $pelicula->titulo }}</h3>
                            <p class="text-muted">dirigida por <strong>{{ $pelicula->director }}</strong></p>

                            <div class="mt-3">
                                <span class="badge bg-secondary">{{ $pelicula->genero ?? 'N/A' }}</span>
                                <span class="badge bg-info">{{ $pelicula->formato ?? 'N/A' }}</span>
                                <span class="badge bg-dark">{{ $pelicula->anio ?? 'N/A' }}</span>
                                <span class="badge bg-{{ $pelicula->stock > 10 ? 'success' : ($pelicula->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $pelicula->stock > 0 ? $pelicula->stock . ' disponibles' : 'Agotado' }}
                            </span>
                                @if($pelicula->destacado)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Destacado</span>
                                @endif
                                @if($pelicula->esta_en_promocion)
                                    <span class="badge bg-danger"><i class="fas fa-tags me-1"></i>En promoción</span>
                                @endif
                            </div>

                            <div class="mt-3">
                                <p><strong>Clasificación:</strong> {{ $pelicula->clasificacion ?? 'N/A' }}</p>
                                <p><strong>Duración:</strong> {{ $pelicula->duracion_formateada }}</p>
                                <p><strong>Idioma:</strong> {{ $pelicula->idioma ?? 'Español' }}</p>
                                @if($pelicula->subtitulos)
                                    <p><strong>Subtítulos:</strong> {{ $pelicula->subtitulos }}</p>
                                @endif
                                @if($pelicula->reparto)
                                    <p><strong>Reparto:</strong> {{ $pelicula->reparto }}</p>
                                @endif
                            </div>

                            <div class="mt-3">
                                <h4 class="price">
                                    ${{ number_format($pelicula->precio_final, 2) }}
                                    @if($pelicula->esta_en_promocion)
                                        <span class="old-price">${{ number_format($pelicula->precio, 2) }}</span>
                                        <small class="text-success">Ahorras ${{ number_format($pelicula->ahorro, 2) }}</small>
                                    @endif
                                </h4>
                            </div>

                            <div class="mt-3">
                                <h6>Sinopsis</h6>
                                <p>{{ $pelicula->sinopsis ?? 'Sin sinopsis disponible' }}</p>
                            </div>

                            @if($pelicula->trailer_url)
                                <div class="mt-3">
                                    <a href="{{ $pelicula->trailer_url }}" target="_blank" class="btn btn-danger">
                                        <i class="fab fa-youtube me-2"></i>Ver trailer
                                    </a>
                                </div>
                            @endif

                            @if($pelicula->stock > 0)
                                <div class="mt-3">
                                    <a href="{{ route('ventas.pos') }}" class="btn btn-success btn-lg">
                                        <i class="fas fa-shopping-cart me-2"></i>Comprar ahora
                                    </a>
                                </div>
                            @else
                                <div class="mt-3">
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="fas fa-times me-2"></i>Agotado
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Películas relacionadas -->
            @if(isset($relacionados) && $relacionados->count() > 0)
                <div class="mt-5">
                    <h5 class="section-title">Películas relacionadas</h5>
                    <div class="row">
                        @foreach($relacionados as $relacionado)
                            <div class="col-md-3 mb-3">
                                <div class="card card-product">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($relacionado->titulo, 25) }}</h6>
                                        <p class="text-muted small">{{ Str::limit($relacionado->director, 15) }}</p>
                                        <p class="price">${{ number_format($relacionado->precio_final, 2) }}</p>
                                        <a href="{{ route('public.pelicula-detalle', $relacionado->idpelicula) }}" class="btn btn-primary btn-sm w-100">
                                            Ver detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
