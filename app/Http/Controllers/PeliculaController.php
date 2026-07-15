<?php
namespace App\Http\Controllers;

use App\Models\Pelicula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PeliculaController extends Controller
{
    /**
     * Display a listing of the peliculas.
     */
    public function index(Request $request)
    {
        $query = Pelicula::query();

        // Filtros
        if ($request->has('search') && $request->search) {
            $query->buscar($request->search);
        }

        if ($request->has('genero') && $request->genero) {
            $query->porGenero($request->genero);
        }

        if ($request->has('formato') && $request->formato) {
            $query->porFormato($request->formato);
        }

        if ($request->has('anio') && $request->anio) {
            $query->porAnio($request->anio);
        }

        if ($request->has('clasificacion') && $request->clasificacion) {
            $query->porClasificacion($request->clasificacion);
        }

        if ($request->has('stock') && $request->stock) {
            if ($request->stock == 'bajo') {
                $query->stockBajo();
            } elseif ($request->stock == 'agotado') {
                $query->sinStock();
            } elseif ($request->stock == 'disponible') {
                $query->conStock();
            }
        }

        if ($request->has('orden')) {
            switch ($request->orden) {
                case 'titulo':
                    $query->orderBy('titulo', 'asc');
                    break;
                case 'director':
                    $query->orderBy('director', 'asc');
                    break;
                case 'anio':
                    $query->orderBy('anio', 'desc');
                    break;
                case 'precio_asc':
                    $query->orderBy('precio', 'asc');
                    break;
                case 'precio_desc':
                    $query->orderBy('precio', 'desc');
                    break;
                case 'stock':
                    $query->orderBy('stock', 'asc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $peliculas = $query->paginate(15);

        // Datos para filtros
        $generos = Pelicula::select('genero')->distinct()->whereNotNull('genero')->pluck('genero');
        $formatos = Pelicula::select('formato')->distinct()->whereNotNull('formato')->pluck('formato');
        $anios = Pelicula::select('anio')->distinct()->whereNotNull('anio')->orderBy('anio', 'desc')->pluck('anio');
        $clasificaciones = ['G', 'PG', 'PG-13', 'R', 'NC-17'];

        return view('peliculas.index', compact('peliculas', 'generos', 'formatos', 'anios', 'clasificaciones'));
    }

    /**
     * Show the form for creating a new pelicula.
     */
    public function create()
    {
        $formatos = ['DVD', 'Blu-ray', 'Digital', 'VHS'];
        $clasificaciones = ['G', 'PG', 'PG-13', 'R', 'NC-17'];
        $idiomas = ['Español', 'Inglés', 'Francés', 'Alemán', 'Italiano', 'Portugués', 'Japonés', 'Chino'];

        return view('peliculas.create', compact('formatos', 'clasificaciones', 'idiomas'));
    }

    /**
     * Store a newly created pelicula in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'director' => 'required|string|max:150',
            'reparto' => 'nullable|string',
            'anio' => 'nullable|integer|min:1888|max:' . (date('Y') + 2),
            'duracion' => 'nullable|integer|min:0',
            'genero' => 'nullable|string|max:50',
            'clasificacion' => 'nullable|string|max:10',
            'sinopsis' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_promocion' => 'nullable|numeric|min:0|lt:precio',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0|default:5',
            'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'trailer_url' => 'nullable|url|max:255',
            'formato' => 'nullable|string|max:30',
            'idioma' => 'nullable|string|max:30',
            'subtitulos' => 'nullable|string|max:50',
            'disponible' => 'nullable|boolean',
            'destacado' => 'nullable|boolean'
        ]);

        // Manejar la imagen
        $portadaPath = null;
        if ($request->hasFile('portada')) {
            $portadaPath = $request->file('portada')->store('peliculas', 'public');
        }

        // Crear la película
        $pelicula = Pelicula::create([
            'titulo' => $validated['titulo'],
            'director' => $validated['director'],
            'reparto' => $validated['reparto'] ?? null,
            'anio' => $validated['anio'] ?? null,
            'duracion' => $validated['duracion'] ?? null,
            'genero' => $validated['genero'] ?? null,
            'clasificacion' => $validated['clasificacion'] ?? null,
            'sinopsis' => $validated['sinopsis'] ?? null,
            'precio' => $validated['precio'],
            'precio_promocion' => $validated['precio_promocion'] ?? null,
            'stock' => $validated['stock'],
            'stock_minimo' => $validated['stock_minimo'] ?? 5,
            'portada' => $portadaPath,
            'trailer_url' => $validated['trailer_url'] ?? null,
            'formato' => $validated['formato'] ?? 'DVD',
            'idioma' => $validated['idioma'] ?? 'Español',
            'subtitulos' => $validated['subtitulos'] ?? null,
            'disponible' => $request->has('disponible'),
            'destacado' => $request->has('destacado')
        ]);

        // Registrar movimiento de inventario
        if ($pelicula->stock > 0) {
            $this->registrarMovimientoInventario($pelicula, 'entrada', $pelicula->stock, 'Creación de película');
        }

        return redirect()->route('peliculas.index')
            ->with('success', "¡Película '{$pelicula->titulo}' creada exitosamente!");
    }

    /**
     * Display the specified pelicula.
     */
    public function show(Pelicula $pelicula)
    {
        $pelicula->load(['promocionesActivas']);
        return view('peliculas.show', compact('pelicula'));
    }

    /**
     * Show the form for editing the specified pelicula.
     */
    public function edit(Pelicula $pelicula)
    {
        $formatos = ['DVD', 'Blu-ray', 'Digital', 'VHS'];
        $clasificaciones = ['G', 'PG', 'PG-13', 'R', 'NC-17'];
        $idiomas = ['Español', 'Inglés', 'Francés', 'Alemán', 'Italiano', 'Portugués', 'Japonés', 'Chino'];

        return view('peliculas.edit', compact('pelicula', 'formatos', 'clasificaciones', 'idiomas'));
    }

    /**
     * Update the specified pelicula in storage.
     */
    public function update(Request $request, Pelicula $pelicula)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'director' => 'required|string|max:150',
            'reparto' => 'nullable|string',
            'anio' => 'nullable|integer|min:1888|max:' . (date('Y') + 2),
            'duracion' => 'nullable|integer|min:0',
            'genero' => 'nullable|string|max:50',
            'clasificacion' => 'nullable|string|max:10',
            'sinopsis' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_promocion' => 'nullable|numeric|min:0|lt:precio',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0|default:5',
            'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'trailer_url' => 'nullable|url|max:255',
            'formato' => 'nullable|string|max:30',
            'idioma' => 'nullable|string|max:30',
            'subtitulos' => 'nullable|string|max:50',
            'disponible' => 'nullable|boolean',
            'destacado' => 'nullable|boolean'
        ]);

        // Guardar stock anterior para registrar movimiento
        $stockAnterior = $pelicula->stock;

        // Manejar la imagen
        $portadaPath = $pelicula->portada;
        if ($request->hasFile('portada')) {
            // Eliminar imagen anterior
            if ($pelicula->portada && Storage::disk('public')->exists($pelicula->portada)) {
                Storage::disk('public')->delete($pelicula->portada);
            }
            $portadaPath = $request->file('portada')->store('peliculas', 'public');
        }

        // Actualizar la película
        $pelicula->update([
            'titulo' => $validated['titulo'],
            'director' => $validated['director'],
            'reparto' => $validated['reparto'] ?? null,
            'anio' => $validated['anio'] ?? null,
            'duracion' => $validated['duracion'] ?? null,
            'genero' => $validated['genero'] ?? null,
            'clasificacion' => $validated['clasificacion'] ?? null,
            'sinopsis' => $validated['sinopsis'] ?? null,
            'precio' => $validated['precio'],
            'precio_promocion' => $validated['precio_promocion'] ?? null,
            'stock' => $validated['stock'],
            'stock_minimo' => $validated['stock_minimo'] ?? 5,
            'portada' => $portadaPath,
            'trailer_url' => $validated['trailer_url'] ?? null,
            'formato' => $validated['formato'] ?? 'DVD',
            'idioma' => $validated['idioma'] ?? 'Español',
            'subtitulos' => $validated['subtitulos'] ?? null,
            'disponible' => $request->has('disponible'),
            'destacado' => $request->has('destacado')
        ]);

        // Registrar movimiento de inventario si cambió el stock
        if ($stockAnterior != $validated['stock']) {
            $tipo = $validated['stock'] > $stockAnterior ? 'entrada' : 'salida';
            $cantidad = abs($validated['stock'] - $stockAnterior);
            $this->registrarMovimientoInventario($pelicula, $tipo, $cantidad, 'Actualización de inventario');
        }

        return redirect()->route('peliculas.index')
            ->with('success', "¡Película '{$pelicula->titulo}' actualizada exitosamente!");
    }

    /**
     * Remove the specified pelicula from storage.
     */
    public function destroy(Pelicula $pelicula)
    {
        // Verificar si tiene ventas asociadas
        if ($pelicula->detallesVenta()->count() > 0) {
            return redirect()->route('peliculas.index')
                ->with('error', 'No se puede eliminar la película porque tiene ventas asociadas.');
        }

        // Eliminar imagen
        if ($pelicula->portada && Storage::disk('public')->exists($pelicula->portada)) {
            Storage::disk('public')->delete($pelicula->portada);
        }

        $titulo = $pelicula->titulo;
        $pelicula->delete();

        return redirect()->route('peliculas.index')
            ->with('success', "¡Película '{$titulo}' eliminada exitosamente!");
    }

    /**
     * Registrar movimiento de inventario.
     */
    private function registrarMovimientoInventario($pelicula, $tipo, $cantidad, $motivo = null)
    {
        // Si tienes el modelo MovimientoInventario
        // \App\Models\MovimientoInventario::create([
        //     'tipo_producto' => 'pelicula',
        //     'producto_id' => $pelicula->idpelicula,
        //     'tipo_movimiento' => $tipo,
        //     'cantidad' => $cantidad,
        //     'cantidad_anterior' => $pelicula->stock - ($tipo == 'entrada' ? $cantidad : -$cantidad),
        //     'cantidad_nueva' => $pelicula->stock,
        //     'motivo' => $motivo,
        //     'usuario_id' => auth()->id()
        // ]);
    }

    /**
     * Export peliculas to CSV.
     */
    public function exportarCSV()
    {
        $peliculas = Pelicula::all();
        $filename = 'peliculas_' . date('Y-m-d_His') . '.csv';

        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Encabezados
        fputcsv($handle, [
            'ID', 'Título', 'Director', 'Año', 'Duración', 'Género',
            'Clasificación', 'Formato', 'Precio', 'Stock', 'Disponible', 'Destacado'
        ]);

        // Datos
        foreach ($peliculas as $pelicula) {
            fputcsv($handle, [
                $pelicula->idpelicula,
                $pelicula->titulo,
                $pelicula->director,
                $pelicula->anio,
                $pelicula->duracion_formateada,
                $pelicula->genero,
                $pelicula->clasificacion,
                $pelicula->formato,
                $pelicula->precio,
                $pelicula->stock,
                $pelicula->disponible ? 'Sí' : 'No',
                $pelicula->destacado ? 'Sí' : 'No'
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Update stock for a pelicula.
     */
    public function actualizarStock(Request $request, Pelicula $pelicula)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'motivo' => 'nullable|string|max:255'
        ]);

        $stockAnterior = $pelicula->stock;
        $cantidad = abs($request->stock - $stockAnterior);

        if ($request->stock > $stockAnterior) {
            $tipo = 'entrada';
        } elseif ($request->stock < $stockAnterior) {
            $tipo = 'salida';
        } else {
            return redirect()->back()
                ->with('warning', 'El stock no ha cambiado.');
        }

        $pelicula->stock = $request->stock;
        $pelicula->save();

        $this->registrarMovimientoInventario(
            $pelicula,
            $tipo,
            $cantidad,
            $request->motivo ?? 'Actualización manual de stock'
        );

        return redirect()->route('peliculas.index')
            ->with('success', "Stock de '{$pelicula->titulo}' actualizado exitosamente!");
    }
}
