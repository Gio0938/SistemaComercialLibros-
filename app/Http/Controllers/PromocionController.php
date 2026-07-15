<?php
// app/Http/Controllers/PromocionController.php
namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\Libro;
use App\Models\Pelicula;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PromocionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Promocion::query();

        // Filtros
        if ($request->has('estado') && $request->estado) {
            if ($request->estado == 'activas') {
                $query->where('activa', true)
                    ->where('fecha_inicio', '<=', now())
                    ->where('fecha_fin', '>=', now());
            } elseif ($request->estado == 'programadas') {
                $query->where('activa', true)
                    ->where('fecha_inicio', '>', now());
            } elseif ($request->estado == 'expiradas') {
                $query->where('activa', true)
                    ->where('fecha_fin', '<', now());
            } elseif ($request->estado == 'inactivas') {
                $query->where('activa', false);
            }
        }

        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->has('search') && $request->search) {
            $query->where('nombre', 'LIKE', "%{$request->search}%")
                ->orWhere('codigo_promocional', 'LIKE', "%{$request->search}%")
                ->orWhere('descripcion', 'LIKE', "%{$request->search}%");
        }

        $promociones = $query->with(['libro', 'pelicula'])
            ->latest()
            ->paginate(15);

        return view('promociones.index', compact('promociones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $libros = Libro::where('disponible', true)->where('stock', '>', 0)->get();
        $peliculas = Pelicula::where('disponible', true)->where('stock', '>', 0)->get();
        $tiposPromocion = ['Porcentaje', 'Fijo', '2x1', '3x2', 'Envio Gratis'];

        return view('promociones.create', compact('libros', 'peliculas', 'tiposPromocion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:libro,pelicula,ambos',
            'libro_id' => 'nullable|exists:libros,idlibro',
            'pelicula_id' => 'nullable|exists:peliculas,idpelicula',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'descuento_fijo' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'activa' => 'nullable|boolean',
            'codigo_promocional' => 'nullable|string|max:50|unique:promociones,codigo_promocional',
            'uso_maximo' => 'nullable|integer|min:1',
        ]);

        // Validar que al menos un descuento esté presente
        if (empty($validated['descuento_porcentaje']) && empty($validated['descuento_fijo'])) {
            return redirect()->back()
                ->withErrors(['error' => 'Debes especificar al menos un tipo de descuento'])
                ->withInput();
        }

        // Validar tipo y producto
        if ($validated['tipo'] == 'libro' && empty($validated['libro_id'])) {
            return redirect()->back()
                ->withErrors(['libro_id' => 'Debes seleccionar un libro'])
                ->withInput();
        }

        if ($validated['tipo'] == 'pelicula' && empty($validated['pelicula_id'])) {
            return redirect()->back()
                ->withErrors(['pelicula_id' => 'Debes seleccionar una película'])
                ->withInput();
        }

        // Crear promoción
        $promocion = Promocion::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'tipo' => $validated['tipo'],
            'libro_id' => $validated['libro_id'] ?? null,
            'pelicula_id' => $validated['pelicula_id'] ?? null,
            'descuento_porcentaje' => $validated['descuento_porcentaje'] ?? null,
            'descuento_fijo' => $validated['descuento_fijo'] ?? null,
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'activa' => $request->has('activa'),
            'codigo_promocional' => $validated['codigo_promocional'] ?? null,
            'uso_maximo' => $validated['uso_maximo'] ?? null,
            'usos_actuales' => 0
        ]);

        return redirect()->route('promociones.index')
            ->with('success', "¡Promoción '{$promocion->nombre}' creada exitosamente!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Promocion $promocione)
    {
        $promocione->load(['libro', 'pelicula']);
        $esActiva = $promocione->activa &&
            $promocione->fecha_inicio <= now() &&
            $promocione->fecha_fin >= now();

        $diasRestantes = now()->diffInDays($promocione->fecha_fin, false);
        $usosRestantes = $promocione->uso_maximo ? $promocione->uso_maximo - $promocione->usos_actuales : null;

        return view('promociones.show', compact('promocione', 'esActiva', 'diasRestantes', 'usosRestantes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promocion $promocione)
    {
        $libros = Libro::where('disponible', true)->where('stock', '>', 0)->get();
        $peliculas = Pelicula::where('disponible', true)->where('stock', '>', 0)->get();
        $tiposPromocion = ['Porcentaje', 'Fijo', '2x1', '3x2', 'Envio Gratis'];

        $promocione->load(['libro', 'pelicula']);

        return view('promociones.edit', compact('promocione', 'libros', 'peliculas', 'tiposPromocion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promocion $promocione)
    {
        // Validación
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:libro,pelicula,ambos',
            'libro_id' => 'nullable|exists:libros,idlibro',
            'pelicula_id' => 'nullable|exists:peliculas,idpelicula',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'descuento_fijo' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'activa' => 'nullable|boolean',
            'codigo_promocional' => 'nullable|string|max:50|unique:promociones,codigo_promocional,' . $promocione->idpromocion . ',idpromocion',
            'uso_maximo' => 'nullable|integer|min:1',
        ]);

        // Validar que al menos un descuento esté presente
        if (empty($validated['descuento_porcentaje']) && empty($validated['descuento_fijo'])) {
            return redirect()->back()
                ->withErrors(['error' => 'Debes especificar al menos un tipo de descuento'])
                ->withInput();
        }

        // Validar tipo y producto
        if ($validated['tipo'] == 'libro' && empty($validated['libro_id'])) {
            return redirect()->back()
                ->withErrors(['libro_id' => 'Debes seleccionar un libro'])
                ->withInput();
        }

        if ($validated['tipo'] == 'pelicula' && empty($validated['pelicula_id'])) {
            return redirect()->back()
                ->withErrors(['pelicula_id' => 'Debes seleccionar una película'])
                ->withInput();
        }

        // Actualizar promoción
        $promocione->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'tipo' => $validated['tipo'],
            'libro_id' => $validated['libro_id'] ?? null,
            'pelicula_id' => $validated['pelicula_id'] ?? null,
            'descuento_porcentaje' => $validated['descuento_porcentaje'] ?? null,
            'descuento_fijo' => $validated['descuento_fijo'] ?? null,
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'activa' => $request->has('activa'),
            'codigo_promocional' => $validated['codigo_promocional'] ?? null,
            'uso_maximo' => $validated['uso_maximo'] ?? null,
        ]);

        return redirect()->route('promociones.index')
            ->with('success', "¡Promoción '{$promocione->nombre}' actualizada exitosamente!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promocion $promocione)
    {
        // Verificar si la promoción tiene usos registrados
        if ($promocione->usos_actuales > 0) {
            return redirect()->route('promociones.index')
                ->with('error', 'No se puede eliminar la promoción porque ya ha sido utilizada en ventas.');
        }

        $nombre = $promocione->nombre;
        $promocione->delete();

        return redirect()->route('promociones.index')
            ->with('success', "¡Promoción '{$nombre}' eliminada exitosamente!");
    }

    /**
     * Activar/Desactivar promoción
     */
    public function toggle(Promocion $promocione)
    {
        $promocione->update(['activa' => !$promocione->activa]);

        $estado = $promocione->activa ? 'activada' : 'desactivada';
        return redirect()->route('promociones.index')
            ->with('success', "Promoción {$estado} exitosamente!");
    }

    /**
     * Get active promotions (for public view)
     */
    public function activas()
    {
        $promociones = Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->with(['libro', 'pelicula'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('promociones.activas', compact('promociones'));
    }

    /**
     * Duplicate a promotion
     */
    public function duplicar(Promocion $promocione)
    {
        $nuevaPromocion = $promocione->replicate();
        $nuevaPromocion->nombre = $promocione->nombre . ' (Copia)';
        $nuevaPromocion->usos_actuales = 0;
        $nuevaPromocion->codigo_promocional = null;
        $nuevaPromocion->activa = false;
        $nuevaPromocion->save();

        return redirect()->route('promociones.edit', $nuevaPromocion)
            ->with('success', "Promoción duplicada exitosamente. Edita la copia para personalizarla.");
    }

    /**
     * Apply promotion to a product price
     */
    public function aplicarPromocion($precio, $promocion)
    {
        if ($promocion->descuento_porcentaje) {
            return $precio - ($precio * $promocion->descuento_porcentaje / 100);
        } elseif ($promocion->descuento_fijo) {
            return max(0, $precio - $promocion->descuento_fijo);
        }
        return $precio;
    }

    /**
     * Export promotions to CSV
     */
    public function exportarCSV()
    {
        $promociones = Promocion::with(['libro', 'pelicula'])->get();
        $filename = 'promociones_' . date('Y-m-d_His') . '.csv';

        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Encabezados
        fputcsv($handle, [
            'ID', 'Nombre', 'Tipo', 'Producto', 'Descuento %', 'Descuento $',
            'Fecha Inicio', 'Fecha Fin', 'Activa', 'Código', 'Usos', 'Límite'
        ]);

        // Datos
        foreach ($promociones as $promocion) {
            $producto = '';
            if ($promocion->tipo == 'libro' && $promocion->libro) {
                $producto = $promocion->libro->titulo;
            } elseif ($promocion->tipo == 'pelicula' && $promocion->pelicula) {
                $producto = $promocion->pelicula->titulo;
            } elseif ($promocion->tipo == 'ambos') {
                $producto = 'Todos los productos';
            }

            fputcsv($handle, [
                $promocion->idpromocion,
                $promocion->nombre,
                $promocion->tipo,
                $producto,
                $promocion->descuento_porcentaje ?? 'N/A',
                $promocion->descuento_fijo ?? 'N/A',
                $promocion->fecha_inicio->format('d/m/Y H:i'),
                $promocion->fecha_fin->format('d/m/Y H:i'),
                $promocion->activa ? 'Sí' : 'No',
                $promocion->codigo_promocional ?? 'N/A',
                $promocion->usos_actuales,
                $promocion->uso_maximo ?? 'Sin límite'
            ]);
        }

        fclose($handle);
        exit;
    }
}
