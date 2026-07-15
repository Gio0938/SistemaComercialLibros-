<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Pelicula;
use App\Models\Promocion;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================================
        // ESTADÍSTICAS PRINCIPALES
        // ============================================

        // Libros
        $totalLibros = Libro::count();
        $librosActivos = Libro::where('disponible', true)->count();
        $librosDestacados = Libro::where('destacado', true)->count();
        $librosEnStock = Libro::where('stock', '>', 0)->count();
        $librosStockBajo = Libro::whereRaw('stock <= stock_minimo')->where('stock', '>', 0)->count();
        $librosAgotados = Libro::where('stock', 0)->count();

        // Películas
        $totalPeliculas = Pelicula::count();
        $peliculasActivas = Pelicula::where('disponible', true)->count();
        $peliculasDestacadas = Pelicula::where('destacado', true)->count();
        $peliculasEnStock = Pelicula::where('stock', '>', 0)->count();
        $peliculasStockBajo = Pelicula::whereRaw('stock <= stock_minimo')->where('stock', '>', 0)->count();
        $peliculasAgotadas = Pelicula::where('stock', 0)->count();

        // Promociones
        $totalPromociones = Promocion::count();
        $promocionesActivas = Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->count();

        // Ventas
        $totalVentas = Venta::count();
        $ventasHoy = Venta::whereDate('fecha_venta', today())->count();
        $ingresosTotales = Venta::sum('total') ?? 0;
        $ingresosHoy = Venta::whereDate('fecha_venta', today())->sum('total') ?? 0;

        // Clientes
        $totalClientes = Cliente::count();
        $clientesNuevos = Cliente::whereDate('fecha_registro', '>=', now()->subDays(30))->count();

        // ============================================
        // LISTAS RECIENTES
        // ============================================

        $librosRecientes = Libro::orderBy('created_at', 'desc')->take(5)->get();
        $peliculasRecientes = Pelicula::orderBy('created_at', 'desc')->take(5)->get();

        $promocionesRecientes = Promocion::with(['libro', 'pelicula'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $ventasRecientes = Venta::with(['cliente', 'usuario'])
            ->orderBy('fecha_venta', 'desc')
            ->take(5)
            ->get();

        // ============================================
        // PRODUCTOS CON STOCK BAJO
        // ============================================

        $productosStockBajo = $this->getProductosStockBajo();

        // ============================================
        // ESTADÍSTICAS POR GÉNERO
        // ============================================

        $librosPorGenero = Libro::select('genero')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('genero')
            ->groupBy('genero')
            ->get();

        $peliculasPorGenero = Pelicula::select('genero')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('genero')
            ->groupBy('genero')
            ->get();

        // ============================================
        // ESTADÍSTICAS DE PROMOCIONES
        // ============================================

        $promocionesPorEstado = [
            'activas' => $promocionesActivas,
            'programadas' => Promocion::where('activa', true)
                ->where('fecha_inicio', '>', now())
                ->count(),
            'expiradas' => Promocion::where('activa', true)
                ->where('fecha_fin', '<', now())
                ->count(),
            'inactivas' => Promocion::where('activa', false)->count()
        ];

        // ============================================
        // VENTAS POR MES
        // ============================================

        $ventasPorMes = Venta::selectRaw('MONTH(fecha_venta) as mes, YEAR(fecha_venta) as año, COUNT(*) as total_ventas, SUM(total) as total_ingresos')
            ->where('fecha_venta', '>=', now()->subMonths(6))
            ->groupBy('año', 'mes')
            ->orderBy('año', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        // ============================================
        // TOP PRODUCTOS MÁS VENDIDOS
        // ============================================

        $topLibros = DetalleVenta::select(
            'producto_id',
            DB::raw('SUM(cantidad) as total_vendido'),
            DB::raw('SUM(subtotal) as total_ingresos')
        )
            ->where('tipo_producto', 'libro')
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                $libro = Libro::find($item->producto_id);
                return (object) [
                    'tipo' => 'libro',
                    'nombre' => $libro ? $libro->titulo : 'Libro eliminado',
                    'total_vendido' => $item->total_vendido,
                    'total_ingresos' => $item->total_ingresos
                ];
            });

        $topPeliculas = DetalleVenta::select(
            'producto_id',
            DB::raw('SUM(cantidad) as total_vendido'),
            DB::raw('SUM(subtotal) as total_ingresos')
        )
            ->where('tipo_producto', 'pelicula')
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                $pelicula = Pelicula::find($item->producto_id);
                return (object) [
                    'tipo' => 'pelicula',
                    'nombre' => $pelicula ? $pelicula->titulo : 'Película eliminada',
                    'total_vendido' => $item->total_vendido,
                    'total_ingresos' => $item->total_ingresos
                ];
            });

        $topProductos = $topLibros->merge($topPeliculas)->sortByDesc('total_vendido')->take(5);

        return view('dashboard', compact(
            'totalLibros',
            'librosActivos',
            'librosDestacados',
            'librosEnStock',
            'librosStockBajo',
            'librosAgotados',
            'totalPeliculas',
            'peliculasActivas',
            'peliculasDestacadas',
            'peliculasEnStock',
            'peliculasStockBajo',
            'peliculasAgotadas',
            'totalPromociones',
            'promocionesActivas',
            'totalVentas',
            'ventasHoy',
            'ingresosTotales',
            'ingresosHoy',
            'totalClientes',
            'clientesNuevos',
            'librosRecientes',
            'peliculasRecientes',
            'promocionesRecientes',
            'ventasRecientes',
            'productosStockBajo',
            'librosPorGenero',
            'peliculasPorGenero',
            'promocionesPorEstado',
            'ventasPorMes',
            'topProductos'
        ));
    }

    private function getProductosStockBajo()
    {
        $librosStockBajo = Libro::whereRaw('stock <= stock_minimo')
            ->where('stock', '>', 0)
            ->get()
            ->map(function($item) {
                return (object) [
                    'tipo' => 'Libro',
                    'id' => $item->idlibro,
                    'nombre' => $item->titulo,
                    'stock' => $item->stock,
                    'minimo' => $item->stock_minimo,
                    'estado' => 'Bajo'
                ];
            });

        $peliculasStockBajo = Pelicula::whereRaw('stock <= stock_minimo')
            ->where('stock', '>', 0)
            ->get()
            ->map(function($item) {
                return (object) [
                    'tipo' => 'Película',
                    'id' => $item->idpelicula,
                    'nombre' => $item->titulo,
                    'stock' => $item->stock,
                    'minimo' => $item->stock_minimo,
                    'estado' => 'Bajo'
                ];
            });

        $librosAgotados = Libro::where('stock', 0)
            ->get()
            ->map(function($item) {
                return (object) [
                    'tipo' => 'Libro',
                    'id' => $item->idlibro,
                    'nombre' => $item->titulo,
                    'stock' => 0,
                    'minimo' => $item->stock_minimo,
                    'estado' => 'Agotado'
                ];
            });

        $peliculasAgotadas = Pelicula::where('stock', 0)
            ->get()
            ->map(function($item) {
                return (object) [
                    'tipo' => 'Película',
                    'id' => $item->idpelicula,
                    'nombre' => $item->titulo,
                    'stock' => 0,
                    'minimo' => $item->stock_minimo,
                    'estado' => 'Agotado'
                ];
            });

        return $librosStockBajo
            ->merge($peliculasStockBajo)
            ->merge($librosAgotados)
            ->merge($peliculasAgotadas)
            ->sortBy('stock')
            ->take(10);
    }

    public function getStats()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'total_libros' => Libro::count(),
                    'total_peliculas' => Pelicula::count(),
                    'total_ventas' => Venta::count(),
                    'ingresos_totales' => number_format(Venta::sum('total') ?? 0, 2),
                    'promociones_activas' => Promocion::where('activa', true)
                        ->where('fecha_inicio', '<=', now())
                        ->where('fecha_fin', '>=', now())
                        ->count(),
                    'productos_agotados' => Libro::where('stock', 0)->count() + Pelicula::where('stock', 0)->count(),
                    'ventas_hoy' => Venta::whereDate('fecha_venta', today())->count(),
                    'ingresos_hoy' => number_format(Venta::whereDate('fecha_venta', today())->sum('total') ?? 0, 2),
                    'ultima_venta' => Venta::latest('fecha_venta')->first()?->fecha_venta,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStockBajo()
    {
        $productos = $this->getProductosStockBajo();
        return response()->json($productos);
    }
}
