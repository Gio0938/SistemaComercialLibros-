<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin - Librería y Cine')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 60px;
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            overflow-x: hidden;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #2d3436 0%, #1a1a2e 100%);
            color: white;
            z-index: 1050;
            transition: transform 0.3s ease;
            overflow-y: auto;
            padding-bottom: 20px;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #2d3436;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        .sidebar-brand {
            padding: 20px 20px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .brand-icon {
            font-size: 2.5rem;
            color: #667eea;
        }

        .sidebar-brand h4 {
            margin: 8px 0 0;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            display: block;
        }

        .sidebar-user {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-user .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            color: white;
            flex-shrink: 0;
        }

        .sidebar-user .user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user .user-info .name {
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user .user-info .role {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.6);
        }

        /* ========== SIDEBAR NAV ========== */
        .sidebar-nav {
            padding: 10px 0;
        }

        .sidebar-nav .nav-section {
            padding: 10px 20px 5px;
            font-size: 0.65rem;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            letter-spacing: 1px;
            font-weight: 600;
        }

        .sidebar-nav .nav-item {
            list-style: none;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
            position: relative;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: white;
            border-left-color: #667eea;
        }

        .sidebar-nav .nav-link.active {
            background: rgba(102, 126, 234, 0.2);
            color: white;
            border-left-color: #667eea;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-nav .nav-link .badge {
            margin-left: auto;
            background: #dc3545;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 10px;
        }

        .sidebar-nav .nav-link .arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
            font-size: 0.7rem;
        }

        .sidebar-nav .nav-link .arrow.open {
            transform: rotate(90deg);
        }

        /* Submenu */
        .sidebar-nav .sub-menu {
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .sidebar-nav .sub-menu.open {
            max-height: 500px;
        }

        .sidebar-nav .sub-menu .nav-link {
            padding: 8px 20px 8px 55px;
            font-size: 0.85rem;
            border-left-color: transparent;
        }

        .sidebar-nav .sub-menu .nav-link i {
            font-size: 0.75rem;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ========== HEADER ========== */
        .header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            position: sticky;
            top: 0;
            z-index: 1040;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .header .toggle-sidebar {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: #333;
            cursor: pointer;
            padding: 5px;
        }

        .header .page-title h5 {
            margin: 0;
            font-weight: 600;
            color: #2d3436;
        }

        .header .page-title small {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: normal;
        }

        .header .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header .header-actions .btn-notification {
            position: relative;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
        }

        .header .header-actions .btn-notification .badge {
            position: absolute;
            top: -2px;
            right: -2px;
            font-size: 0.6rem;
            padding: 2px 6px;
            background: #dc3545;
        }

        .header .header-actions .dropdown-user .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .header .header-actions .dropdown-user .dropdown-toggle:hover {
            background: #f8f9fa;
        }

        .header .header-actions .dropdown-user .avatar-sm {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            color: white;
        }

        /* ========== PAGE CONTENT ========== */
        .page-content {
            padding: 25px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header .toggle-sidebar {
                display: block;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1045;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .header .page-title h5 {
                font-size: 0.95rem;
            }
            .header .page-title small {
                display: none;
            }
            .page-content {
                padding: 15px;
            }
        }

        /* ========== UTILITIES ========== */
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-card {
            padding: 20px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card .stat-icon.primary { background: rgba(102, 126, 234, 0.15); color: #667eea; }
        .stat-card .stat-icon.success { background: rgba(40, 167, 69, 0.15); color: #28a745; }
        .stat-card .stat-icon.warning { background: rgba(255, 193, 7, 0.15); color: #ffc107; }
        .stat-card .stat-icon.danger { background: rgba(220, 53, 69, 0.15); color: #dc3545; }
        .stat-card .stat-icon.info { background: rgba(23, 162, 184, 0.15); color: #17a2b8; }
        .stat-card .stat-icon.purple { background: rgba(118, 75, 162, 0.15); color: #764ba2; }

        .stat-card .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3436;
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 2px;
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .table-container .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .table-container .table-header h6 {
            margin: 0;
            font-weight: 600;
        }

        /* Botones */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            border-color: #667eea;
            color: #667eea;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            color: white;
        }

        /* Badges */
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* ========== SCROLLBAR ========== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c7cd;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a0a7ae;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ==================== SIDEBAR OVERLAY (Mobile) ==================== -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ==================== SIDEBAR ==================== -->
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <i class="fas fa-book brand-icon"></i>
        <h4>Librería & Cine</h4>
        <small>Panel de Administración</small>
    </div>

    <!-- User Info -->
    <div class="sidebar-user">
        <div class="avatar">{{ Auth::user() ? strtoupper(substr(Auth::user()->nombre, 0, 2)) : 'U' }}</div>
        <div class="user-info">
            <div class="name">{{ Auth::user() ? Auth::user()->nombre : 'Usuario' }}</div>
            <div class="role">
                <span class="badge bg-{{ Auth::user() && Auth::user()->esAdmin() ? 'danger' : 'primary' }}">
                    {{ Auth::user() ? ucfirst(Auth::user()->rol) : 'Cliente' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <!-- Gestión de Productos -->
        <div class="nav-section">Gestión de Productos</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('libros.*') ? 'active' : '' }}" href="{{ route('libros.index') }}">
                    <i class="fas fa-book"></i>
                    <span>Libros</span>
                    <span class="badge">{{ \App\Models\Libro::count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('peliculas.*') ? 'active' : '' }}" href="{{ route('peliculas.index') }}">
                    <i class="fas fa-film"></i>
                    <span>Películas</span>
                    <span class="badge">{{ \App\Models\Pelicula::count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('promociones.*') ? 'active' : '' }}" href="{{ route('promociones.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Promociones</span>
                    <span class="badge">{{ \App\Models\Promocion::where('activa', true)->count() }}</span>
                </a>
            </li>
        </ul>

        <!-- Ventas -->
        <div class="nav-section">Ventas</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ventas.pos') ? 'active' : '' }}" href="{{ route('ventas.pos') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Punto de Venta</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ventas.historial') ? 'active' : '' }}" href="{{ route('ventas.historial') }}">
                    <i class="fas fa-history"></i>
                    <span>Historial de Ventas</span>
                    <span class="badge">{{ \App\Models\Venta::whereDate('fecha_venta', today())->count() }}</span>
                </a>
            </li>
        </ul>

        <!-- Inventario y Reportes -->
        <div class="nav-section">Inventario & Reportes</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}" href="{{ route('inventario.index') }}">
                    <i class="fas fa-warehouse"></i>
                    <span>Inventario</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
        </ul>

        <!-- Salir -->
        <div class="nav-section">Cuenta</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start" style="background:none;border:none;color:rgba(255,255,255,0.7);">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('public.index') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Ver Tienda</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<!-- ==================== MAIN CONTENT ==================== -->
<div class="main-content" id="mainContent">

    <!-- ==================== HEADER ==================== -->
    <header class="header">
        <div class="d-flex align-items-center gap-3">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="page-title">
                <h5>@yield('page-title', 'Dashboard') <small>@yield('page-subtitle', 'Panel de administración')</small></h5>
            </div>
        </div>

        <div class="header-actions">
            <!-- Notificaciones -->
            <button class="btn-notification" title="Notificaciones">
                <i class="fas fa-bell"></i>
                <span class="badge">3</span>
            </button>

            <!-- Usuario -->
            <div class="dropdown dropdown-user">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar-sm">{{ Auth::user() ? strtoupper(substr(Auth::user()->nombre, 0, 2)) : 'U' }}</div>
                    <span class="d-none d-sm-inline">{{ Auth::user() ? Auth::user()->nombre : 'Usuario' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configuración</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- ==================== PAGE CONTENT ==================== -->
    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- ==================== SCRIPTS ==================== -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // ==================== SIDEBAR TOGGLE ====================
        $('#toggleSidebar').on('click', function() {
            $('#sidebar').toggleClass('show');
            $('#sidebarOverlay').toggleClass('show');
        });

        $('#sidebarOverlay').on('click', function() {
            $('#sidebar').removeClass('show');
            $('#sidebarOverlay').removeClass('show');
        });

        // ==================== SUBMENU TOGGLE ====================
        $('.nav-link[data-toggle="collapse"]').on('click', function(e) {
            e.preventDefault();
            const target = $(this).data('target');
            $(target).toggleClass('show');
            $(this).find('.arrow').toggleClass('open');
        });

        // ==================== DATATABLES ====================
        $('.datatable').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                    },
                    pageLength: 15,
                    responsive: true,
                    order: [[0, 'desc']]
                });
            }
        });

        // ==================== SELECT2 ====================
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'Selecciona una opción',
            allowClear: true
        });

        // ==================== CONFIRMAR ELIMINACIÓN ====================
        $(document).on('click', '.btn-delete, .delete-form button[type="submit"]', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const message = $(this).data('message') || '¿Estás seguro de eliminar este registro?';

            Swal.fire({
                title: '¿Estás seguro?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // ==================== TOOLTIP ====================
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>

@stack('scripts')

</body>
</html>
