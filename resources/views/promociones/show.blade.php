@extends('layouts.admin')

@section('title', $promocione->nombre . ' - Librería & Cine')
@section('page-title', 'Detalles de la Promoción')
@section('page-subtitle', $promocione->nombre)

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Estado -->
                        <div class="col-12 mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <h3>{{ $promocione->nombre }}</h3>
                                @if($promocione->estado == 'activa')
                                    <span class="badge bg-success fs-6">Activa</span>
                                @elseif($promocione->estado == 'programada')
                                    <span class="badge bg-info fs-6">Programada</span>
                                @elseif($promocione->estado == 'expirada')
                                    <span class="badge bg-warning text-dark fs-6">Expirada</span>
                                @else
                                    <span class="badge bg-danger fs-6">Inactiva</span>
                                @endif
                            </div>
                            @if($promocione->codigo_promocional)
                                <p class="mt-2">
                                    <i class="fas fa-tag me-1"></i>
                                    <strong>Código:</strong>
                                    <span class="badge bg-dark">{{ $promocione->codigo_promocional }}</span>
                                </p>
                            @endif
                        </div>

                        <!-- Descripción -->
                        @if($promocione->descripcion)
                            <div class="col-12 mb-3">
                                <small class="text-muted">Descripción</small>
                                <p>{{ $promocione->descripcion }}</p>
                            </div>
                        @endif

                        <!-- Tipo -->
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Tipo</small>
                            <p>
                            <span class="badge bg-{{ $promocione->tipo == 'libro' ? 'primary' : ($promocione->tipo == 'pelicula' ? 'info' : 'secondary') }}">
                                {{ $promocione->tipo_producto }}
                            </span>
                            </p>
                        </div>

                        <!-- Producto -->
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Producto</small>
                            <p>{{ $promocione->nombre_producto }}</p>
                        </div>

                        <!-- Descuento -->
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Descuento</small>
                            <p>
                            <span class="badge bg-warning text-dark fs-6">
                                {{ $promocione->descuento_formateado }}
                            </span>
                            </p>
                        </div>

                        <!-- Fechas -->
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Fecha de Inicio</small>
                            <p>{{ $promocione->fecha_inicio->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Fecha de Fin</small>
                            <p>{{ $promocione->fecha_fin->format('d/m/Y H:i') }}</p>
                        </div>

                        <!-- Usos -->
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Usos Actuales</small>
                            <p>{{ $promocione->usos_actuales }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Uso Máximo</small>
                            <p>{{ $promocione->uso_maximo ?? 'Sin límite' }}</p>
                        </div>

                        <!-- Estado de la promoción -->
                        <div class="col-12 mb-3">
                            <small class="text-muted">Estado de la Promoción</small>
                            <p>
                                @if($promocione->es_valida)
                                    <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>Válida para usar
                                </span>
                                @elseif($promocione->estado == 'programada')
                                    <span class="text-info">
                                    <i class="fas fa-clock me-1"></i>Programada para iniciar el {{ $promocione->fecha_inicio->format('d/m/Y H:i') }}
                                </span>
                                @elseif($promocione->estado == 'expirada')
                                    <span class="text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Expirada desde el {{ $promocione->fecha_fin->format('d/m/Y H:i') }}
                                </span>
                                @else
                                    <span class="text-danger">
                                    <i class="fas fa-times-circle me-1"></i>Inactiva
                                </span>
                                @endif
                            </p>
                        </div>

                        <!-- Progreso de usos -->
                        @if($promocione->uso_maximo)
                            <div class="col-12 mb-3">
                                <small class="text-muted">Progreso de Usos</small>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $promocione->usos_actuales >= $promocione->uso_maximo ? 'danger' : 'success' }}"
                                         style="width: {{ $promocione->progreso_usos }}%;">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $promocione->usos_actuales }}/{{ $promocione->uso_maximo }} usos</small>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('promociones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                        <div>
                            <a href="{{ route('promociones.edit', $promocione->idpromocion) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Editar
                            </a>
                            <form action="{{ route('promociones.toggle', $promocione->idpromocion) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $promocione->activa ? 'warning' : 'success' }}">
                                    <i class="fas fa-{{ $promocione->activa ? 'pause' : 'play' }} me-2"></i>
                                    {{ $promocione->activa ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                            <form action="{{ route('promociones.destroy', $promocione->idpromocion) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-message="¿Estás seguro de eliminar la promoción '{{ $promocione->nombre }}'?">
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
