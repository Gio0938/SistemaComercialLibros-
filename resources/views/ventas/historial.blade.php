@extends('layouts.admin')

@section('title', 'Historial de Ventas - Librería & Cine')
@section('page-title', 'Historial de Ventas')
@section('page-subtitle', 'Consulta todas las ventas realizadas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h6><i class="fas fa-history me-2"></i>Listado de Ventas</h6>
                    <div>
                        <a href="{{ route('ventas.pos') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Nueva Venta
                        </a>
                    </div>
                </div>

                <!-- Filtros -->
                <form action="{{ route('ventas.historial') }}" method="GET" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar por folio o cliente..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-select form-select-sm">
                            <option value="">Todos los estados</option>
                            <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-search me-1"></i> Filtrar
                        </button>
                        <a href="{{ route('ventas.historial') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-times me-1"></i> Limpiar
                        </a>
                        <a href="{{ route('reportes.ventas') }}" class="btn btn-sm btn-info" target="_blank">
                            <i class="fas fa-chart-bar me-1"></i> Reporte
                        </a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Subtotal</th>
                            <th>IVA</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($ventas as $venta)
                            <tr>
                                <td><strong>{{ $venta->folio }}</strong></td>
                                <td>
                                    @if($venta->cliente)
                                        {{ $venta->cliente->nombre }} {{ $venta->cliente->apellido ?? '' }}
                                    @else
                                        <span class="text-muted">Público en general</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}</td>
                                <td>${{ number_format($venta->subtotal, 2) }}</td>
                                <td>${{ number_format($venta->iva, 2) }}</td>
                                <td><strong>${{ number_format($venta->total, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($venta->metodo_pago) }}
                                    </span>
                                </td>
                                <td>
                                    @if($venta->estado == 'completada')
                                        <span class="badge bg-success">Completada</span>
                                    @elseif($venta->estado == 'pendiente')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @else
                                        <span class="badge bg-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('ventas.ticket', $venta->idventa) }}" class="btn btn-outline-info" target="_blank" title="Ver ticket">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                        @if(Auth::user()->esAdmin())
                                            <a href="{{ route('ventas.edit', $venta->idventa) }}" class="btn btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('ventas.destroy', $venta->idventa) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Eliminar" data-message="¿Estás seguro de eliminar la venta {{ $venta->folio }}?">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-cart fa-2x d-block mb-2"></i>
                                    No hay ventas registradas
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $ventas->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de ventas -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">{{ $ventas->total() }}</div>
                <div class="stat-label">Total de Ventas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">${{ number_format($ventas->sum('total'), 2) }}</div>
                <div class="stat-label">Total Ingresos</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">{{ $ventas->where('estado', 'completada')->count() }}</div>
                <div class="stat-label">Completadas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">{{ $ventas->where('estado', 'pendiente')->count() }}</div>
                <div class="stat-label">Pendientes</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar DataTable si existe la clase
            if ($.fn.DataTable) {
                $('.datatable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                    },
                    pageLength: 15,
                    responsive: true,
                    ordering: true
                });
            }
        });
    </script>
@endpush
