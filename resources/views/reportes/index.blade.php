@extends('layouts.admin')

@section('title', 'Reportes - Librería & Cine')
@section('page-title', 'Panel de Reportes')
@section('page-subtitle', 'Selecciona el tipo de reporte que deseas consultar')

@section('content')
    <div class="row">
        <!-- Reporte de Libros -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="stat-icon primary mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(102, 126, 234, 0.15);">
                        <i class="fas fa-book fa-2x" style="color: #667eea;"></i>
                    </div>
                    <h5>Reporte de Libros</h5>
                    <p class="text-muted">Consulta el catálogo completo de libros, filtros por género, autor y disponibilidad.</p>
                    <a href="{{ route('reportes.libros') }}" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.exportar-libros-pdf') }}" class="btn btn-outline-secondary mt-2">
                        <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Películas -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="stat-icon success mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(40, 167, 69, 0.15);">
                        <i class="fas fa-film fa-2x" style="color: #28a745;"></i>
                    </div>
                    <h5>Reporte de Películas</h5>
                    <p class="text-muted">Consulta el catálogo completo de películas, filtros por género, formato y disponibilidad.</p>
                    <a href="{{ route('reportes.peliculas') }}" class="btn btn-success">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.exportar-peliculas-pdf') }}" class="btn btn-outline-secondary mt-2">
                        <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Promociones -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="stat-icon warning mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(255, 193, 7, 0.15);">
                        <i class="fas fa-tags fa-2x" style="color: #ffc107;"></i>
                    </div>
                    <h5>Reporte de Promociones</h5>
                    <p class="text-muted">Consulta todas las promociones activas, programadas, expiradas e inactivas.</p>
                    <a href="{{ route('reportes.promociones') }}" class="btn btn-warning text-dark">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.exportar-promociones-pdf') }}" class="btn btn-outline-secondary mt-2">
                        <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Ventas -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="stat-icon info mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(23, 162, 184, 0.15);">
                        <i class="fas fa-shopping-cart fa-2x" style="color: #17a2b8;"></i>
                    </div>
                    <h5>Reporte de Ventas</h5>
                    <p class="text-muted">Consulta el historial de ventas, filtros por fecha, cliente y método de pago.</p>
                    <a href="{{ route('reportes.ventas') }}" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.exportar-ventas-pdf') }}" class="btn btn-outline-secondary mt-2">
                        <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Inventario -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="stat-icon purple mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(118, 75, 162, 0.15);">
                        <i class="fas fa-warehouse fa-2x" style="color: #764ba2;"></i>
                    </div>
                    <h5>Reporte de Inventario</h5>
                    <p class="text-muted">Consulta el inventario completo de libros y películas con sus valores.</p>
                    <a href="{{ route('reportes.inventario') }}" class="btn btn-purple" style="background: #764ba2; color: white; border-color: #764ba2;">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.exportar-inventario-pdf') }}" class="btn btn-outline-secondary mt-2">
                        <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Stock Bajo -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="stat-icon danger mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(220, 53, 69, 0.15);">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #dc3545;"></i>
                    </div>
                    <h5>Productos con Stock Bajo</h5>
                    <p class="text-muted">Lista de productos que están por debajo del stock mínimo o agotados.</p>
                    <a href="{{ route('reportes.stock-bajo') }}" class="btn btn-danger">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
