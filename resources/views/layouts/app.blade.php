<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Librería & Cine - Sistema de Ventas')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a1a2e;
            --secondary: #16213e;
            --accent: #e94560;
            --gold: #f5c518;
            --success: #2ecc71;
            --warning: #f39c12;
            --light-bg: #f0f2f5;
            --book-color: #8b5cf6;
            --movie-color: #ef4444;
        }

        /* Estilos generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
        }

        /* Sidebar mejorado */
        .sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            color: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }

        .sidebar .brand {
            padding: 20px 15px;
            border-bottom: 2px solid rgba(255,255,255,0.1);
        }

        .sidebar .brand h4 {
            font-weight: 700;
            background: linear-gradient(90deg, var(--gold), #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            transition: all 0.3s;
            position: relative;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: var(--accent);
            color: white;
            box-shadow: 0 4px 15px rgba(233, 69, 96, 0.4);
        }

        .sidebar .nav-link i {
            width: 25px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            background: var(--light-bg);
            min-height: 100vh;
        }

        /* Tarjetas de estadísticas */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
        }

        .stat-card .icon-wrapper.books {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card .icon-wrapper.movies {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .stat-card .icon-wrapper.sales {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .stat-card .icon-wrapper.revenue {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        /* Badges de categoría */
        .badge-book {
            background: var(--book-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
        }

        .badge-movie {
            background: var(--movie-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
        }

        /* Tabla mejorada */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .table thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table tbody tr {
            transition: all 0.3s;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        /* Botones de acción rápida */
        .quick-actions .btn {
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .quick-actions .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        /* Filtros */
        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        /* Estilo para tarjetas de productos */
        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .product-card .product-image {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
        }

        .product-card .product-image.book-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .product-card .product-image.movie-bg {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .product-card .product-body {
            padding: 20px;
        }

        .product-card .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
        }

        .product-card .product-category {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        /* Promo Banner */
        .promo-banner {
            background: linear-gradient(135deg, var(--gold) 0%, #f39c12 100%);
            padding: 40px;
            border-radius: 15px;
            color: var(--primary);
            text-align: center;
        }

        .promo-banner h2 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .promo-banner .btn-dark {
            border-radius: 50px;
            padding: 10px 35px;
            font-weight: 600;
        }

        /* Stock badges */
        .stock-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .stock-badge.in-stock {
            background: rgba(46, 204, 113, 0.9);
            color: white;
        }

        .stock-badge.low-stock {
            background: rgba(243, 156, 18, 0.9);
            color: white;
        }

        .stock-badge.out-of-stock {
            background: rgba(231, 76, 60, 0.9);
            color: white;
        }

        /* Sidebar inferior */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            .stat-card {
                margin-bottom: 15px;
            }
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Selector de categoría */
        .category-tabs .nav-link {
            border-radius: 25px;
            padding: 10px 25px;
            color: var(--primary);
            font-weight: 600;
        }

        .category-tabs .nav-link.active {
            background: var(--accent);
            color: white;
        }

        /* Botones */
        .btn-outline-accent {
            border: 2px solid var(--accent);
            color: var(--accent);
            border-radius: 25px;
            padding: 8px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline-accent:hover {
            background: var(--accent);
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 sidebar position-relative">
            <div class="position-sticky">
                <!-- Brand -->
                <div class="brand text-center">
                    <h4><i class="fas fa-book-open me-2"></i>Librería & Cine</h4>
                    <small class="text-light opacity-75">Sistema de Ventas</small>
                    <div class="mt-2">
                        <i class="fas fa-user me-1"></i>
                        <span class="text-light">
                            {{ Auth::user()->name ?? 'Usuario' }}
                        </span>
                    </div>
                </div>

                <!-- Menú Principal -->
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-pie me-2"></i>Dashboard
                        </a>
                    </li>

                    <!-- Libros -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('libros*') ? 'active' : '' }}" href="{{ route('libros.index') }}">
                            <i class="fas fa-book me-2"></i>Libros
                            <span class="badge bg-book float-end mt-1">{{ \App\Models\Libro::count() ?? 0 }}</span>
                        </a>
                    </li>

                    <!-- Películas -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('peliculas*') ? 'active' : '' }}" href="{{ route('peliculas.index') }}">
                            <i class="fas fa-film me-2"></i>Películas
                            <span class="badge bg-movie float-end mt-1">{{ \App\Models\Pelicula::count() ?? 0 }}</span>
                        </a>
                    </li>

                    <!-- Promociones -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('promociones*') ? 'active' : '' }}" href="{{ route('promociones.index') }}">
                            <i class="fas fa-tags me-2"></i>Promociones
                            <span class="badge bg-warning float-end mt-1">{{ \App\Models\Promocion::where('activa', true)->count() ?? 0 }}</span>
                        </a>
                    </li>

                    <!-- Punto de Venta -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('ventas/pos*') ? 'active' : '' }}" href="{{ route('ventas.pos') }}">
                            <i class="fas fa-shopping-cart me-2"></i>Punto de Venta
                        </a>
                    </li>

                    <!-- Gestión de Pedidos -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pedidos*') ? 'active' : '' }}" href="{{ route('pedidos.index') }}">
                            <i class="fas fa-clipboard-list me-2"></i>Gestión de Pedidos
                        </a>
                    </li>

                    <!-- Inventario -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('inventario*') ? 'active' : '' }}" href="{{ route('inventario.index') }}">
                            <i class="fas fa-warehouse me-2"></i>Inventario
                        </a>
                    </li>

                    <!-- Reportes -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('reportes*') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>Reportes
                        </a>
                    </li>

                    <!-- Historial de Ventas (solo admin) -->
                    @if(Auth::check() && Auth::user()->rol === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('ventas/historial*') ? 'active' : '' }}" href="{{ route('ventas.historial') }}">
                                <i class="fas fa-history me-2"></i>Historial de Ventas
                            </a>
                        </li>
                    @endif

                    <!-- Cerrar sesión -->
                    <li class="nav-item mt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>

                <!-- Información del Sistema -->
                <div class="sidebar-footer">
                    <small class="text-light opacity-75 d-block text-center">
                        <i class="fas fa-info-circle me-1"></i>
                        Sistema v2.0<br>
                        <i class="fas fa-database me-1"></i>
                        {{ (\App\Models\Libro::count() ?? 0) + (\App\Models\Pelicula::count() ?? 0) }} productos
                    </small>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 col-lg-10 main-content">
            <div class="p-4">
                <!-- Alertas de Sesión -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-ocultar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });

    // Confirmación para eliminaciones
    function confirmDelete(message = '¿Estás seguro de eliminar este registro?') {
        return confirm(message);
    }

    // Función para mostrar estado del stock
    function getStockBadge(stock, stockMinimo = 5) {
        if (stock <= 0) {
            return '<span class="stock-badge out-of-stock"><i class="fas fa-times-circle me-1"></i>Agotado</span>';
        } else if (stock <= stockMinimo) {
            return '<span class="stock-badge low-stock"><i class="fas fa-exclamation-triangle me-1"></i>Stock Bajo</span>';
        } else {
            return '<span class="stock-badge in-stock"><i class="fas fa-check-circle me-1"></i>En Stock</span>';
        }
    }
</script>
@stack('scripts')
</body>
</html>
