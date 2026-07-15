<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Pelicula;
use App\Models\Promocion;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Página de inicio
     */
    public function index()
    {
        $librosDestacados = Libro::where('disponible', true)
            ->where('destacado', true)
            ->limit(4)
            ->get();

        $peliculasDestacadas = Pelicula::where('disponible', true)
            ->where('destacado', true)
            ->limit(4)
            ->get();

        $promocionesActivas = Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->with(['libro', 'pelicula'])
            ->limit(3)
            ->get();

        $librosRecientes = Libro::where('disponible', true)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $peliculasRecientes = Pelicula::where('disponible', true)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('public.index', compact(
            'librosDestacados',
            'peliculasDestacadas',
            'promocionesActivas',
            'librosRecientes',
            'peliculasRecientes'
        ));
    }

    public function nosotros()
    {
        return view('public.nosotros');
    }

    public function contacto()
    {
        return view('public.contacto');
    }

    /**
     * Listado de libros - RUTA: public.libros
     */
    public function libros(Request $request)
    {
        $query = Libro::where('disponible', true);

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'LIKE', '%' . $request->buscar . '%')
                    ->orWhere('autor', 'LIKE', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('orden')) {
            switch ($request->orden) {
                case 'titulo':
                    $query->orderBy('titulo', 'asc');
                    break;
                case 'precio_asc':
                    $query->orderBy('precio', 'asc');
                    break;
                case 'precio_desc':
                    $query->orderBy('precio', 'desc');
                    break;
                default:
                    $query->orderBy('titulo', 'asc');
            }
        } else {
            $query->orderBy('titulo', 'asc');
        }

        $libros = $query->paginate(12);

        $generos = Libro::where('disponible', true)
            ->select('genero')
            ->distinct()
            ->whereNotNull('genero')
            ->pluck('genero');

        return view('public.libros', compact('libros', 'generos'));
    }

    /**
     * Detalle de un libro - RUTA: public.libro-detalle
     */
    public function libroDetalle($id)
    {
        $libro = Libro::where('disponible', true)->findOrFail($id);

        $relacionados = Libro::where('disponible', true)
            ->where('genero', $libro->genero)
            ->where('idlibro', '!=', $id)
            ->limit(4)
            ->get();

        $promociones = Promocion::where('activa', true)
            ->where(function($q) use ($id) {
                $q->where('tipo', 'ambos')
                    ->orWhere(function($q2) use ($id) {
                        $q2->where('tipo', 'libro')
                            ->where('libro_id', $id);
                    });
            })
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();

        return view('public.libro-detalle', compact('libro', 'relacionados', 'promociones'));
    }

    /**
     * Listado de películas - RUTA: public.peliculas
     */
    public function peliculas(Request $request)
    {
        $query = Pelicula::where('disponible', true);

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('formato')) {
            $query->where('formato', $request->formato);
        }

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'LIKE', '%' . $request->buscar . '%')
                    ->orWhere('director', 'LIKE', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('orden')) {
            switch ($request->orden) {
                case 'titulo':
                    $query->orderBy('titulo', 'asc');
                    break;
                case 'precio_asc':
                    $query->orderBy('precio', 'asc');
                    break;
                case 'precio_desc':
                    $query->orderBy('precio', 'desc');
                    break;
                case 'anio':
                    $query->orderBy('anio', 'desc');
                    break;
                default:
                    $query->orderBy('titulo', 'asc');
            }
        } else {
            $query->orderBy('titulo', 'asc');
        }

        $peliculas = $query->paginate(12);

        $generos = Pelicula::where('disponible', true)
            ->select('genero')
            ->distinct()
            ->whereNotNull('genero')
            ->pluck('genero');

        $formatos = Pelicula::where('disponible', true)
            ->select('formato')
            ->distinct()
            ->whereNotNull('formato')
            ->pluck('formato');

        return view('public.peliculas', compact('peliculas', 'generos', 'formatos'));
    }

    /**
     * Detalle de una película - RUTA: public.pelicula-detalle
     */
    public function peliculaDetalle($id)
    {
        $pelicula = Pelicula::where('disponible', true)->findOrFail($id);

        $relacionados = Pelicula::where('disponible', true)
            ->where('genero', $pelicula->genero)
            ->where('idpelicula', '!=', $id)
            ->limit(4)
            ->get();

        $promociones = Promocion::where('activa', true)
            ->where(function($q) use ($id) {
                $q->where('tipo', 'ambos')
                    ->orWhere(function($q2) use ($id) {
                        $q2->where('tipo', 'pelicula')
                            ->where('pelicula_id', $id);
                    });
            })
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();

        return view('public.pelicula-detalle', compact('pelicula', 'relacionados', 'promociones'));
    }

    public function promociones()
    {
        $promociones = Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->with(['libro', 'pelicula'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.promociones', compact('promociones'));
    }

    public function buscar(Request $request)
    {
        $termino = $request->get('q');

        if (strlen($termino) < 2) {
            return response()->json([]);
        }

        $libros = Libro::where('disponible', true)
            ->where('titulo', 'LIKE', "%{$termino}%")
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->idlibro,
                    'tipo' => 'libro',
                    'titulo' => $item->titulo,
                    'autor' => $item->autor,
                    'precio' => $item->precio,
                    'url' => route('public.libro-detalle', $item->idlibro)
                ];
            });

        $peliculas = Pelicula::where('disponible', true)
            ->where('titulo', 'LIKE', "%{$termino}%")
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->idpelicula,
                    'tipo' => 'pelicula',
                    'titulo' => $item->titulo,
                    'director' => $item->director,
                    'precio' => $item->precio,
                    'url' => route('public.pelicula-detalle', $item->idpelicula)
                ];
            });

        $resultados = $libros->merge($peliculas)->take(10);

        return response()->json($resultados);
    }
}
