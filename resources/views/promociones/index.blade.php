@extends('layouts.admin')

@section('title', 'Promociones - Librería & Cine')
@section('page-title', 'Gestión de Promociones')
@section('page-subtitle', 'Administra las promociones y descuentos')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('promociones.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nueva Promoción
                        </a>
                        <a href="{{ route('promociones.exportar-csv') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-csv me-2"></i>Exportar CSV
                        </a>
                    </div>
                    <div>
                        <form action="{{ route('promociones.index') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('search') }}">
                            <select name="estado" class="form-select form-select-sm">
                                <option value="">Todos los estados</option>
                                <option value="activas" {{ request('estado') == 'activas' ? 'selected' : '' }}>Activas</option>
                                <option value="programadas" {{ request('estado') == 'programadas' ? 'selected' : '' }}>Programadas</option>
                                <option value="expiradas" {{ request('estado') == 'expiradas' ? 'selected' : '' }}>Expiradas</option>
                                <option value="inactivas" {{ request('estado') == 'inactivas' ? 'selected' : '' }}>Inactivas</option>
                            </select>
                            <select name="tipo" class="form-select form-select-sm">
                                <option value="">Todos los tipos</option>
                                <option value="libro" {{ request('tipo') == 'libro' ? 'selected' : '' }}>Libros</option>
                                <option value="pelicula" {{ request('tipo') == 'pelicula' ? 'selected' : '' }}>Películas</option>
                                <option value="ambos" {{ request('tipo') == 'ambos' ? 'selected' : '' }}>Ambos</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('promociones.index') }}" class="btn btn-sm btn-outline-secondary">
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
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Producto</th>
                            <th>Descuento</th>
                            <th>Vigencia</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($promociones as $promocion)
                            <tr>
                                <td>{{ $promocion->idpromocion }}</td>
                                <td>
                                    <strong>{{ Str::limit($promocion->nombre, 25) }}</strong>
                                    @if($promocion->codigo_promocional)
                                        <br><small class="text-muted"><i class="fas fa-tag"></i> {{ $promocion->codigo_promocional }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $promocion->tipo == 'libro' ? 'primary' : ($promocion->tipo == 'pelicula' ? 'info' : 'secondary') }}">
                                        {{ $promocion->tipo_producto }}
                                    </span>
                                </td>
                                <td>{{ $promocion->nombre_producto }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $promocion->descuento_formateado }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        {{ $promocion->fecha_inicio->format('d/m/Y') }}<br>
                                        <i class="fas fa-arrow-right"></i> {{ $promocion->fecha_fin->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td>
                                    @if($promocion->estado == 'activa')
                                        <span class="badge bg-success">Activa</span>
                                    @elseif($promocion->estado == 'programada')
                                        <span class="badge bg-info">Programada</span>
                                    @elseif($promocion->estado == 'expirada')
                                        <span class="badge bg-warning text-dark">Expirada</span>
                                    @else
                                        <span class="badge bg-danger">Inactiva</span>
                                    @endif
                                    @if($promocion->uso_maximo)
                                        <br><small>{{ $promocion->usos_actuales }}/{{ $promocion->uso_maximo }} usos</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('promociones.show', $promocion->idpromocion) }}" class="btn btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('promociones.edit', $promocion->idpromocion) }}" class="btn btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('promociones.toggle', $promocion->idpromocion) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-{{ $promocion->activa ? 'warning' : 'success' }}" title="{{ $promocion->activa ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas fa-{{ $promocion->activa ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('promociones.destroy', $promocion->idpromocion) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar" data-message="¿Estás seguro de eliminar la promoción '{{ $promocion->nombre }}'?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-tags fa-2x d-block mb-2"></i>
                                    No hay promociones registradas
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $promociones->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
