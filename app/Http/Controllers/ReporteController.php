<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Pelicula;
use App\Models\Promocion;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        $stats = [
            'total_libros' => Libro::count(),
            'libros_activos' => Libro::where('disponible', true)->count(),
            'libros_stock_bajo' => Libro::whereRaw('stock <= stock_minimo')->where('stock', '>', 0)->count(),
            'libros_agotados' => Libro::where('stock', 0)->count(),
            'total_peliculas' => Pelicula::count(),
            'peliculas_activas' => Pelicula::where('disponible', true)->count(),
            'peliculas_stock_bajo' => Pelicula::whereRaw('stock <= stock_minimo')->where('stock', '>', 0)->count(),
            'peliculas_agotadas' => Pelicula::where('stock', 0)->count(),
            'total_promociones' => Promocion::count(),
            'promociones_activas' => Promocion::where('activa', true)
                ->where('fecha_fin', '>=', now())
                ->where('fecha_inicio', '<=', now())
                ->count(),
            'total_ventas' => Venta::count(),
            'ventas_hoy' => Venta::whereDate('fecha_venta', today())->count(),
            'ingresos_totales' => Venta::sum('total'),
            'ingresos_hoy' => Venta::whereDate('fecha_venta', today())->sum('total'),
        ];

        return view('reportes.index', compact('stats'));
    }

    public function libros(Request $request)
    {
        $query = Libro::query();

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('disponible')) {
            $query->where('disponible', $request->disponible);
        }

        if ($request->filled('stock')) {
            if ($request->stock == 'bajo') {
                $query->whereRaw('stock <= stock_minimo')->where('stock', '>', 0);
            } elseif ($request->stock == 'agotado') {
                $query->where('stock', 0);
            }
        }

        $libros = $query->get();

        $estadisticas = [
            'total_valor' => $libros->sum(function($l) { return $l->precio * $l->stock; }),
            'precio_promedio' => $libros->avg('precio'),
            'total_libros' => $libros->count(),
            'total_stock' => $libros->sum('stock'),
        ];

        $generos = Libro::select('genero')->distinct()->whereNotNull('genero')->pluck('genero');

        return view('reportes.libros', compact('libros', 'estadisticas', 'generos'));
    }

    public function peliculas(Request $request)
    {
        $query = Pelicula::query();

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('formato')) {
            $query->where('formato', $request->formato);
        }

        if ($request->filled('disponible')) {
            $query->where('disponible', $request->disponible);
        }

        if ($request->filled('stock')) {
            if ($request->stock == 'bajo') {
                $query->whereRaw('stock <= stock_minimo')->where('stock', '>', 0);
            } elseif ($request->stock == 'agotado') {
                $query->where('stock', 0);
            }
        }

        $peliculas = $query->get();

        $estadisticas = [
            'total_valor' => $peliculas->sum(function($p) { return $p->precio * $p->stock; }),
            'precio_promedio' => $peliculas->avg('precio'),
            'total_peliculas' => $peliculas->count(),
            'total_stock' => $peliculas->sum('stock'),
        ];

        $generos = Pelicula::select('genero')->distinct()->whereNotNull('genero')->pluck('genero');
        $formatos = Pelicula::select('formato')->distinct()->whereNotNull('formato')->pluck('formato');

        return view('reportes.peliculas', compact('peliculas', 'estadisticas', 'generos', 'formatos'));
    }

    public function promociones(Request $request)
    {
        $query = Promocion::with(['libro', 'pelicula']);

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('activa')) {
            if ($request->activa == 'si') {
                $query->where('activa', true)
                    ->where('fecha_fin', '>=', now())
                    ->where('fecha_inicio', '<=', now());
            } elseif ($request->activa == 'no') {
                $query->where('activa', false);
            }
        }

        $promociones = $query->get();

        $estadisticas = [
            'activas' => Promocion::where('activa', true)
                ->where('fecha_fin', '>=', now())
                ->where('fecha_inicio', '<=', now())
                ->count(),
            'proximas' => Promocion::where('fecha_inicio', '>', now())->count(),
            'expiradas' => Promocion::where('fecha_fin', '<', now())->count(),
            'inactivas' => Promocion::where('activa', false)->count(),
            'por_tipo' => Promocion::groupBy('tipo')
                ->select('tipo', DB::raw('count(*) as total'))
                ->get(),
        ];

        return view('reportes.promociones', compact('promociones', 'estadisticas'));
    }

    public function ventas(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario']);

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_venta', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_venta', '<=', $request->fecha_hasta);
        }

        if ($request->filled('empleado')) {
            $query->where('usuario_id', $request->empleado);
        }

        $ventas = $query->orderBy('fecha_venta', 'desc')->paginate(20);

        $estadisticas = [
            'total_ventas' => $query->count(),
            'total_ingresos' => $query->sum('total'),
            'promedio_venta' => $query->avg('total'),
            'ventas_hoy' => Venta::whereDate('fecha_venta', today())->count(),
            'ingresos_hoy' => Venta::whereDate('fecha_venta', today())->sum('total'),
        ];

        // Libros más vendidos
        $librosMasVendidos = DetalleVenta::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->where('tipo_producto', 'libro')
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $libro = Libro::find($item->producto_id);
                $item->nombre = $libro ? $libro->titulo : 'Libro eliminado';
                return $item;
            });

        // Películas más vendidas
        $peliculasMasVendidas = DetalleVenta::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->where('tipo_producto', 'pelicula')
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $pelicula = Pelicula::find($item->producto_id);
                $item->nombre = $pelicula ? $pelicula->titulo : 'Película eliminada';
                return $item;
            });

        $empleados = User::whereIn('rol', ['admin', 'vendedor'])->get();

        return view('reportes.ventas', compact(
            'ventas',
            'estadisticas',
            'librosMasVendidos',
            'peliculasMasVendidas',
            'empleados'
        ));
    }

    public function inventario()
    {
        $libros = Libro::orderBy('genero')->get();
        $peliculas = Pelicula::orderBy('genero')->get();

        $totalLibros = $libros->count();
        $totalPeliculas = $peliculas->count();
        $totalStockLibros = $libros->sum('stock');
        $totalStockPeliculas = $peliculas->sum('stock');
        $totalValorLibros = $libros->sum(function($l) { return $l->precio * $l->stock; });
        $totalValorPeliculas = $peliculas->sum(function($p) { return $p->precio * $p->stock; });

        return view('reportes.inventario', compact(
            'libros',
            'peliculas',
            'totalLibros',
            'totalPeliculas',
            'totalStockLibros',
            'totalStockPeliculas',
            'totalValorLibros',
            'totalValorPeliculas'
        ));
    }

    public function stockBajo()
    {
        $librosStockBajo = Libro::whereRaw('stock <= stock_minimo')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->get();

        $peliculasStockBajo = Pelicula::whereRaw('stock <= stock_minimo')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->get();

        $librosAgotados = Libro::where('stock', 0)->get();
        $peliculasAgotadas = Pelicula::where('stock', 0)->get();

        return view('reportes.stock-bajo', compact(
            'librosStockBajo',
            'peliculasStockBajo',
            'librosAgotados',
            'peliculasAgotadas'
        ));
    }

    public function exportarLibrosPDF()
    {
        $libros = Libro::all();
        $pdf = Pdf::loadView('reportes.pdf.libros', compact('libros'));
        return $pdf->download('reporte-libros-' . date('Y-m-d') . '.pdf');
    }

    public function exportarPeliculasPDF()
    {
        $peliculas = Pelicula::all();
        $pdf = Pdf::loadView('reportes.pdf.peliculas', compact('peliculas'));
        return $pdf->download('reporte-peliculas-' . date('Y-m-d') . '.pdf');
    }

    public function exportarVentasPDF(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario']);

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_venta', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_venta', '<=', $request->fecha_hasta);
        }

        $ventas = $query->orderBy('fecha_venta', 'desc')->get();

        $pdf = Pdf::loadView('reportes.pdf.ventas', compact('ventas'));
        return $pdf->download('reporte-ventas-' . date('Y-m-d') . '.pdf');
    }

    public function exportarPromocionesPDF()
    {
        $promociones = Promocion::all();
        $pdf = Pdf::loadView('reportes.pdf.promociones', compact('promociones'));
        return $pdf->download('reporte-promociones-' . date('Y-m-d') . '.pdf');
    }

    public function exportarInventarioPDF()
    {
        $libros = Libro::all();
        $peliculas = Pelicula::all();
        $pdf = Pdf::loadView('reportes.pdf.inventario', compact('libros', 'peliculas'));
        return $pdf->download('reporte-inventario-' . date('Y-m-d') . '.pdf');
    }
}
