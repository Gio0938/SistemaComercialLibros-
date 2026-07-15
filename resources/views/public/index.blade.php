@extends('layouts.public')

@section('title', 'Inicio - Librería y Cine')

@section('content')
    <div class="container">
        <!-- Hero Section -->
        <section class="hero-section text-center py-5">
            <h1 class="display-4 fw-bold">Bienvenido a Librería y Cine</h1>
            <p class="lead">Encuentra los mejores libros y películas al mejor precio</p>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ route('public.libros') }}" class="btn btn-primary btn-lg">Ver Libros</a>
                <a href="{{ route('public.peliculas') }}" class="btn btn-success btn-lg">Ver Películas</a>
            </div>
        </section>

        <!-- Promociones -->
        @if($promocionesActivas->count() > 0)
            <section class="promociones-section my-5">
                <h2 class="text-center mb-4">🔥 Promociones Activas</h2>
                <div class="row">
                    @foreach($promocionesActivas as $promocion)
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $promocion->nombre }}</h5>
                                    <p class="card-text">{{ $promocion->descripcion }}</p>
                                    <span class="badge bg-warning text-dark">{{ $promocion->descuento_formateado }}</span>
                                    <p class="mt-2"><small>Válido hasta: {{ $promocion->fecha_fin->format('d/m/Y') }}</small></p>
                                    <a href="{{ route('public.promociones') }}" class="btn btn-outline-warning btn-sm">Ver más</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Libros Destacados -->
        <section class="libros-destacados my-5">
            <h2 class="text-center mb-4">📚 Libros Destacados</h2>
            <div class="row">
                @foreach($librosDestacados as $libro)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $libro->titulo }}</h5>
                                <h6 class="card-subtitle text-muted">{{ $libro->autor }}</h6>
                                <p class="card-text mt-2">
                                    <span class="fw-bold">${{ number_format($libro->precio, 2) }}</span>
                                </p>
                                <a href="{{ route('public.libro-detalle', $libro->idlibro) }}" class="btn btn-primary btn-sm">Ver más</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ route('public.libros') }}" class="btn btn-outline-primary">Ver todos los libros</a>
            </div>
        </section>

        <!-- Películas Destacadas -->
        <section class="peliculas-destacadas my-5">
            <h2 class="text-center mb-4">🎬 Películas Destacadas</h2>
            <div class="row">
                @foreach($peliculasDestacadas as $pelicula)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $pelicula->titulo }}</h5>
                                <h6 class="card-subtitle text-muted">{{ $pelicula->director }}</h6>
                                <p class="card-text mt-2">
                                    <span class="fw-bold">${{ number_format($pelicula->precio, 2) }}</span>
                                    <span class="badge bg-secondary ms-2">{{ $pelicula->formato }}</span>
                                </p>
                                <a href="{{ route('public.pelicula-detalle', $pelicula->idpelicula) }}" class="btn btn-primary btn-sm">Ver más</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ route('public.peliculas') }}" class="btn btn-outline-primary">Ver todas las películas</a>
            </div>
        </section>
    </div>
@endsection
