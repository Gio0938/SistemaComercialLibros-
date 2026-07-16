@extends('layouts.public')

@section('title', 'Promociones - Librería & Cine')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="section-title"><i class="fas fa-tags me-2" style="color: #dc3545;"></i>Promociones Activas</h4>

            @if($promociones->count() > 0)
                <div class="row">
                    @foreach($promociones as $promocion)
                        <div class="col-md-4 mb-4">
                            <div class="card card-product border-warning h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title">{{ $promocion->nombre }}</h5>
                                        <span class="badge bg-warning text-dark">
                                        {{ $promocion->descuento_formateado }}
                                    </span>
                                    </div>
                                    <p class="card-text text-muted small">{{ $promocion->descripcion ?? 'Sin descripción' }}</p>

                                    <div class="mt-3">
                                        <p class="mb-1"><strong>Tipo:</strong> {{ $promocion->tipo_producto }}</p>
                                        <p class="mb-1"><strong>Producto:</strong> {{ $promocion->nombre_producto }}</p>
                                        @if($promocion->codigo_promocional)
                                            <p class="mb-1">
                                                <strong>Código:</strong>
                                                <span class="badge bg-dark">{{ $promocion->codigo_promocional }}</span>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($promocion->fecha_inicio)->format('d/m/Y H:i') }} -
                                            {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>

                                    @if($promocion->tipo == 'libro' && $promocion->libro)
                                        <a href="{{ route('public.libro-detalle', $promocion->libro->idlibro) }}" class="btn btn-primary btn-sm w-100 mt-3">
                                            <i class="fas fa-book me-2"></i>Ver libro
                                        </a>
                                    @elseif($promocion->tipo == 'pelicula' && $promocion->pelicula)
                                        <a href="{{ route('public.pelicula-detalle', $promocion->pelicula->idpelicula) }}" class="btn btn-primary btn-sm w-100 mt-3">
                                            <i class="fas fa-film me-2"></i>Ver película
                                        </a>
                                    @else
                                        <a href="{{ route('public.libros') }}" class="btn btn-primary btn-sm w-100 mt-3">
                                            <i class="fas fa-shopping-bag me-2"></i>Ver productos
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $promociones->links() }}
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="fas fa-tags fa-3x d-block mb-3"></i>
                    <h5>No hay promociones activas</h5>
                    <p>Actualmente no hay promociones disponibles. Vuelve a consultar más tarde.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
