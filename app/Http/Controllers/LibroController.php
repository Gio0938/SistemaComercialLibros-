<?php
namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LibroController extends Controller
{
    /**
     * Display a listing of the libros.
     */
    public function index(Request $request)
    {
        $query = Libro::query();

        // Filtros
        if ($request->has('search') && $request->search) {
            $query->buscar($request->search);
        }

        if ($request->has('genero') && $request->genero) {
            $query->porGenero($request->genero);
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
                case 'autor':
                    $query->orderBy('autor', 'asc');
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

        $libros = $query->paginate(15);
        $generos = Libro::select('genero')->distinct()->whereNotNull('genero')->pluck('genero');

        return view('libros.index', compact('libros', 'generos'));
    }

    /**
     * Show the form for creating a new libro.
     */
    public function create()
    {
        return view('libros.create');
    }

    /**
     * Store a newly created libro in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:150',
            'editorial' => 'nullable|string|max:100',
            'isbn' => 'nullable|string|max:20|unique:libros,isbn',
            'genero' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_promocion' => 'nullable|numeric|min:0|lt:precio',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0|default:5',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'fecha_publicacion' => 'nullable|date',
            'paginas' => 'nullable|integer|min:0',
            'idioma' => 'nullable|string|max:30',
            'disponible' => 'nullable|boolean',
            'destacado' => 'nullable|boolean'
        ]);

        // Manejar la imagen
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('libros', 'public');
        }

        // Crear el libro
        $libro = Libro::create([
            'titulo' => $validated['titulo'],
            'autor' => $validated['autor'],
            'editorial' => $validated['editorial'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'genero' => $validated['genero'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'precio' => $validated['precio'],
            'precio_promocion' => $validated['precio_promocion'] ?? null,
            'stock' => $validated['stock'],
            'stock_minimo' => $validated['stock_minimo'] ?? 5,
            'foto' => $fotoPath,
            'fecha_publicacion' => $validated['fecha_publicacion'] ?? null,
            'paginas' => $validated['paginas'] ?? null,
            'idioma' => $validated['idioma'] ?? 'Español',
            'disponible' => $request->has('disponible'),
            'destacado' => $request->has('destacado')
        ]);

        // Registrar movimiento de inventario
        if ($libro->stock > 0) {
            $this->registrarMovimientoInventario($libro, 'entrada', $libro->stock, 'Creación de libro');
        }

        return redirect()->route('libros.index')
            ->with('success', "¡Libro '{$libro->titulo}' creado exitosamente!");
    }

    /**
     * Display the specified libro.
     */
    public function show(Libro $libro)
    {
        $libro->load(['promocionesActivas']);
        return view('libros.show', compact('libro'));
    }

    /**
     * Show the form for editing the specified libro.
     */
    public function edit(Libro $libro)
    {
        return view('libros.edit', compact('libro'));
    }

    /**
     * Update the specified libro in storage.
     */
    public function update(Request $request, Libro $libro)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:150',
            'editorial' => 'nullable|string|max:100',
            'isbn' => 'nullable|string|max:20|unique:libros,isbn,' . $libro->idlibro . ',idlibro',
            'genero' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_promocion' => 'nullable|numeric|min:0|lt:precio',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0|default:5',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'fecha_publicacion' => 'nullable|date',
            'paginas' => 'nullable|integer|min:0',
            'idioma' => 'nullable|string|max:30',
            'disponible' => 'nullable|boolean',
            'destacado' => 'nullable|boolean'
        ]);

        // Guardar stock anterior para registrar movimiento
        $stockAnterior = $libro->stock;

        // Manejar la imagen
        $fotoPath = $libro->foto;
        if ($request->hasFile('foto')) {
            // Eliminar imagen anterior
            if ($libro->foto && Storage::disk('public')->exists($libro->foto)) {
                Storage::disk('public')->delete($libro->foto);
            }
            $fotoPath = $request->file('foto')->store('libros', 'public');
        }

        // Actualizar el libro
        $libro->update([
            'titulo' => $validated['titulo'],
            'autor' => $validated['autor'],
            'editorial' => $validated['editorial'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'genero' => $validated['genero'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'precio' => $validated['precio'],
            'precio_promocion' => $validated['precio_promocion'] ?? null,
            'stock' => $validated['stock'],
            'stock_minimo' => $validated['stock_minimo'] ?? 5,
            'foto' => $fotoPath,
            'fecha_publicacion' => $validated['fecha_publicacion'] ?? null,
            'paginas' => $validated['paginas'] ?? null,
            'idioma' => $validated['idioma'] ?? 'Español',
            'disponible' => $request->has('disponible'),
            'destacado' => $request->has('destacado')
        ]);

        // Registrar movimiento de inventario si cambió el stock
        if ($stockAnterior != $validated['stock']) {
            $tipo = $validated['stock'] > $stockAnterior ? 'entrada' : 'salida';
            $cantidad = abs($validated['stock'] - $stockAnterior);
            $this->registrarMovimientoInventario($libro, $tipo, $cantidad, 'Actualización de inventario');
        }

        return redirect()->route('libros.index')
            ->with('success', "¡Libro '{$libro->titulo}' actualizado exitosamente!");
    }

    /**
     * Remove the specified libro from storage.
     */
    public function destroy(Libro $libro)
    {
        // Verificar si tiene ventas asociadas
        if ($libro->detallesVenta()->count() > 0) {
            return redirect()->route('libros.index')
                ->with('error', 'No se puede eliminar el libro porque tiene ventas asociadas.');
        }

        // Eliminar imagen
        if ($libro->foto && Storage::disk('public')->exists($libro->foto)) {
            Storage::disk('public')->delete($libro->foto);
        }

        $titulo = $libro->titulo;
        $libro->delete();

        return redirect()->route('libros.index')
            ->with('success', "¡Libro '{$titulo}' eliminado exitosamente!");
    }

    /**
     * Registrar movimiento de inventario.
     */
    private function registrarMovimientoInventario($libro, $tipo, $cantidad, $motivo = null)
    {
        // Si tienes el modelo MovimientoInventario
        // \App\Models\MovimientoInventario::create([
        //     'tipo_producto' => 'libro',
        //     'producto_id' => $libro->idlibro,
        //     'tipo_movimiento' => $tipo,
        //     'cantidad' => $cantidad,
        //     'cantidad_anterior' => $libro->stock - ($tipo == 'entrada' ? $cantidad : -$cantidad),
        //     'cantidad_nueva' => $libro->stock,
        //     'motivo' => $motivo,
        //     'usuario_id' => auth()->id()
        // ]);
    }

    /**
     * Export libros to CSV.
     */
    public function exportarCSV()
    {
        $libros = Libro::all();
        $filename = 'libros_' . date('Y-m-d_His') . '.csv';

        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Encabezados
        fputcsv($handle, [
            'ID', 'Título', 'Autor', 'Editorial', 'ISBN', 'Género',
            'Precio', 'Stock', 'Disponible', 'Destacado'
        ]);

        // Datos
        foreach ($libros as $libro) {
            fputcsv($handle, [
                $libro->idlibro,
                $libro->titulo,
                $libro->autor,
                $libro->editorial,
                $libro->isbn,
                $libro->genero,
                $libro->precio,
                $libro->stock,
                $libro->disponible ? 'Sí' : 'No',
                $libro->destacado ? 'Sí' : 'No'
            ]);
        }

        fclose($handle);
        exit;
    }
}
