<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ReporteController;

// ==================== RUTAS PÚBLICAS ====================
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::get('/nosotros', [PublicController::class, 'nosotros'])->name('public.nosotros');
Route::get('/contacto', [PublicController::class, 'contacto'])->name('public.contacto');
Route::get('/buscar', [PublicController::class, 'buscar'])->name('public.buscar');

// Rutas públicas para libros
Route::get('/libros', [PublicController::class, 'libros'])->name('public.libros');
Route::get('/libros/{id}', [PublicController::class, 'libroDetalle'])->name('public.libro-detalle');

// Rutas públicas para películas
Route::get('/peliculas', [PublicController::class, 'peliculas'])->name('public.peliculas');
Route::get('/peliculas/{id}', [PublicController::class, 'peliculaDetalle'])->name('public.pelicula-detalle');

// Rutas públicas para promociones
Route::get('/promociones', [PublicController::class, 'promociones'])->name('public.promociones');

// ==================== RUTAS DE AUTENTICACIÓN ====================
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('/register', [RegisterController::class, 'formregister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ==================== RUTAS PROTEGIDAS ====================
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/stock-bajo', [DashboardController::class, 'getStockBajo'])->name('dashboard.stock-bajo');

    // ==================== ADMIN - LIBROS ====================
    Route::prefix('admin')->group(function () {
        Route::resource('libros', LibroController::class)->names([
            'index' => 'libros.index',
            'create' => 'libros.create',
            'store' => 'libros.store',
            'show' => 'libros.show',
            'edit' => 'libros.edit',
            'update' => 'libros.update',
            'destroy' => 'libros.destroy',
        ]);
    });
    Route::get('/libros/exportar/csv', [LibroController::class, 'exportarCSV'])->name('libros.exportar-csv');

    // ==================== ADMIN - PELÍCULAS ====================
    Route::prefix('admin')->group(function () {
        Route::resource('peliculas', PeliculaController::class)->names([
            'index' => 'peliculas.index',
            'create' => 'peliculas.create',
            'store' => 'peliculas.store',
            'show' => 'peliculas.show',
            'edit' => 'peliculas.edit',
            'update' => 'peliculas.update',
            'destroy' => 'peliculas.destroy',
        ]);
    });
    Route::get('/peliculas/exportar/csv', [PeliculaController::class, 'exportarCSV'])->name('peliculas.exportar-csv');

    // ==================== ADMIN - PROMOCIONES ====================
    Route::prefix('admin')->group(function () {
        Route::resource('promociones', PromocionController::class)->names([
            'index' => 'promociones.index',
            'create' => 'promociones.create',
            'store' => 'promociones.store',
            'show' => 'promociones.show',
            'edit' => 'promociones.edit',
            'update' => 'promociones.update',
            'destroy' => 'promociones.destroy',
        ]);
    });
    Route::patch('/admin/promociones/{promocion}/toggle', [PromocionController::class, 'toggle'])->name('promociones.toggle');
    Route::get('/admin/promociones/activas', [PromocionController::class, 'activas'])->name('promociones.activas');
    Route::post('/admin/promociones/{promocion}/duplicar', [PromocionController::class, 'duplicar'])->name('promociones.duplicar');
    Route::get('/promociones/exportar/csv', [PromocionController::class, 'exportarCSV'])->name('promociones.exportar-csv');

    // ==================== VENTAS ====================
    Route::get('/ventas/pos', [VentaController::class, 'create'])->name('ventas.pos');
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/ticket/{id}', [VentaController::class, 'ticket'])->name('ventas.ticket');
    Route::get('/ventas/historial', [VentaController::class, 'historial'])->name('ventas.historial');
    Route::get('/ventas/nuevo-folio', [VentaController::class, 'nuevoFolio'])->name('ventas.nuevo-folio');
    Route::get('/ventas/mis-ventas', [VentaController::class, 'misVentas'])->name('ventas.mis-ventas');
    Route::get('/ventas/{id}/edit', [VentaController::class, 'edit'])->name('ventas.edit');
    Route::put('/ventas/{id}', [VentaController::class, 'update'])->name('ventas.update');
    Route::delete('/ventas/{id}', [VentaController::class, 'destroy'])->name('ventas.destroy');

    // ==================== INVENTARIO ====================
    Route::get('/inventario', [ReporteController::class, 'inventario'])->name('inventario.index');
    Route::get('/inventario/stock-bajo', [ReporteController::class, 'stockBajo'])->name('inventario.stock-bajo');
    Route::get('/inventario/exportar-pdf', [ReporteController::class, 'exportarInventarioPDF'])->name('inventario.exportar-pdf');

    // ==================== REPORTES ====================
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/libros', [ReporteController::class, 'libros'])->name('reportes.libros');
    Route::get('/reportes/peliculas', [ReporteController::class, 'peliculas'])->name('reportes.peliculas');
    Route::get('/reportes/promociones', [ReporteController::class, 'promociones'])->name('reportes.promociones');
    Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
    Route::get('/reportes/inventario', [ReporteController::class, 'inventario'])->name('reportes.inventario');
    Route::get('/reportes/stock-bajo', [ReporteController::class, 'stockBajo'])->name('reportes.stock-bajo');

    // Exportaciones PDF
    Route::get('/reportes/libros/pdf', [ReporteController::class, 'exportarLibrosPDF'])->name('reportes.exportar-libros-pdf');
    Route::get('/reportes/peliculas/pdf', [ReporteController::class, 'exportarPeliculasPDF'])->name('reportes.exportar-peliculas-pdf');
    Route::get('/reportes/ventas/pdf', [ReporteController::class, 'exportarVentasPDF'])->name('reportes.exportar-ventas-pdf');
    Route::get('/reportes/promociones/pdf', [ReporteController::class, 'exportarPromocionesPDF'])->name('reportes.exportar-promociones-pdf');
    Route::get('/reportes/inventario/pdf', [ReporteController::class, 'exportarInventarioPDF'])->name('reportes.exportar-inventario-pdf');
});
