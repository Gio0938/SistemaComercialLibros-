
@extends('layouts.public')

@section('title', $libro->titulo . ' - Librería & Cine')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('public.index') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('public.libros') }}">Libros</a></li>
                    <li class="breadcrumb-item active">{{ $libro->titulo }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($libro->foto)
                                <img src="{{ asset('storage/' . $libro->foto) }}" alt="{{ $libro->titulo }}" class="img-fluid rounded" style="max-height: 300px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="fas fa-book fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $libro->titulo }}</h3>
                            <p class="text-muted">por <strong>{{ $libro->autor }}</strong></p>

                            <div class="mt-3">
                                <span class="badge bg-secondary">{{ $libro->genero ?? 'N/A' }}</span>
                                <span class="badge bg-{{ $libro->stock > 10 ? 'success' : ($libro->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $libro->stock > 0 ? $libro->stock . ' disponibles' : 'Agotado' }}
                            </span>
                                @if($libro->destacado)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Destacado</span>
                                @endif
                                @if($libro->esta_en_promocion)
                                    <span class="badge bg-danger"><i class="fas fa-tags me-1"></i>En promoción</span>
                                @endif
                            </div>

                            <div class="mt-3">
                                <h4 class="price">
                                    ${{ number_format($libro->precio_final, 2) }}
                                    @if($libro->esta_en_promocion)
                                        <span class="old-price">${{ number_format($libro->precio, 2) }}</span>
                                        <small class="text-success">Ahorras ${{ number_format($libro->ahorro, 2) }}</small>
                                    @endif
                                </h4>
                            </div>

                            <div class="mt-3">
                                <p><strong>Editorial:</strong> {{ $libro->editorial ?? 'N/A' }}</p>
                                <p><strong>ISBN:</strong> {{ $libro->isbn ?? 'N/A' }}</p>
                                <p><strong>Páginas:</strong> {{ $libro->paginas ?? 'N/A' }}</p>
                                <p><strong>Idioma:</strong> {{ $libro->idioma ?? 'Español' }}</p>
                                <p><strong>Publicación:</strong> {{ $libro->fecha_publicacion ? \Carbon\Carbon::parse($libro->fecha_publicacion)->format('d/m/Y') : 'N/A' }}</p>
                            </div>

                            <div class="mt-3">
                                <h6>Descripción</h6>
                                <p>{{ $libro->descripcion ?? 'Sin descripción disponible' }}</p>
                            </div>

                            @if($libro->stock > 0)
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

            <!-- Libros relacionados -->
            @if(isset($relacionados) && $relacionados->count() > 0)
                <div class="mt-5">
                    <h5 class="section-title">Libros relacionados</h5>
                    <div class="row">
                        @foreach($relacionados as $relacionado)
                            <div class="col-md-3 mb-3">
                                <div class="card card-product">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($relacionado->titulo, 25) }}</h6>
                                        <p class="text-muted small">{{ Str::limit($relacionado->autor, 15) }}</p>
                                        <p class="price">${{ number_format($relacionado->precio_final, 2) }}</p>
                                        <a href="{{ route('public.libro-detalle', $relacionado->idlibro) }}" class="btn btn-primary btn-sm w-100">
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
