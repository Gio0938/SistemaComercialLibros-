<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Libro;
use App\Models\Pelicula;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function create()
    {
        $empleado = Auth::user();

        if (!$empleado) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión primero');
        }

        // Obtener libros disponibles
        $libros = Libro::where('disponible', true)
            ->where('stock', '>', 0)
            ->select('idlibro', 'titulo', 'autor', 'precio', 'stock', 'genero')
            ->orderBy('titulo')
            ->get();

        // Obtener películas disponibles
        $peliculas = Pelicula::where('disponible', true)
            ->where('stock', '>', 0)
            ->select('idpelicula', 'titulo', 'director', 'precio', 'stock', 'genero', 'formato')
            ->orderBy('titulo')
            ->get();

        $ultimoFolio = Venta::max('folio');
        $nuevoFolio = $ultimoFolio ? str_pad((int)$ultimoFolio + 1, 5, '0', STR_PAD_LEFT) : '00001';

        // Géneros para filtros
        $generosLibros = Libro::select('genero')->distinct()->whereNotNull('genero')->pluck('genero');
        $generosPeliculas = Pelicula::select('genero')->distinct()->whereNotNull('genero')->pluck('genero');

        return view('ventas.pos', compact(
            'libros',
            'peliculas',
            'nuevoFolio',
            'empleado',
            'generosLibros',
            'generosPeliculas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre' => 'nullable|string',
            'cliente_apellido' => 'nullable|string',
            'cliente_rfc' => 'nullable|string',
            'cliente_telefono' => 'nullable|string',
            'cliente_email' => 'nullable|email',
            'items' => 'required|array|min:1',
            'items.*.tipo' => 'required|in:libro,pelicula',
            'items.*.id' => 'required|integer',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,credito'
        ]);

        DB::beginTransaction();

        try {
            // Buscar o crear cliente
            $cliente = null;
            if ($request->cliente_nombre && $request->cliente_nombre !== 'Público en general') {
                $cliente = Cliente::create([
                    'nombre' => $request->cliente_nombre,
                    'apellido' => $request->cliente_apellido ?? '',
                    'email' => $request->cliente_email,
                    'telefono' => $request->cliente_telefono,
                    'fecha_registro' => now()
                ]);
            }

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['cantidad'] * $item['precio'];
            }

            $iva = $subtotal * 0.16;
            $total = $subtotal + $iva;

            $venta = Venta::create([
                'folio' => $request->folio,
                'usuario_id' => Auth::id(),
                'cliente_id' => $cliente ? $cliente->idcliente : null,
                'fecha_venta' => now(),
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'metodo_pago' => $request->metodo_pago,
                'estado' => 'completada',
                'descuento' => 0
            ]);

            // Crear detalles de venta
            foreach ($request->items as $item) {
                $subtotalItem = $item['cantidad'] * $item['precio'];

                DetalleVenta::create([
                    'venta_id' => $venta->idventa,
                    'tipo_producto' => $item['tipo'],
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'descuento' => 0,
                    'subtotal' => $subtotalItem
                ]);

                // Actualizar stock
                if ($item['tipo'] == 'libro') {
                    $libro = Libro::find($item['id']);
                    if ($libro) {
                        $libro->stock -= $item['cantidad'];
                        $libro->save();
                    }
                } else {
                    $pelicula = Pelicula::find($item['id']);
                    if ($pelicula) {
                        $pelicula->stock -= $item['cantidad'];
                        $pelicula->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada exitosamente',
                'venta_id' => $venta->idventa,
                'folio' => $venta->folio
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ticket($id)
    {
        $venta = Venta::with(['cliente', 'usuario', 'detalles'])->findOrFail($id);
        return view('ventas.ticket', compact('venta'));
    }

    public function historial()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión primero');
        }

        if (!Auth::user()->esAdmin()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder al historial de ventas.');
        }

        $ventas = Venta::with(['cliente', 'usuario'])
            ->orderBy('fecha_venta', 'desc')
            ->paginate(15);

        return view('ventas.historial', compact('ventas'));
    }

    public function nuevoFolio()
    {
        $ultimoFolio = Venta::max('folio');
        $nuevoFolio = $ultimoFolio ? str_pad((int)$ultimoFolio + 1, 5, '0', STR_PAD_LEFT) : '00001';
        return response()->json(['folio' => $nuevoFolio]);
    }

    public function misVentas()
    {
        $ventas = Venta::with(['detalles'])
            ->where('usuario_id', Auth::id())
            ->orderBy('fecha_venta', 'desc')
            ->limit(20)
            ->get();

        return response()->json($ventas);
    }

    public function edit($id)
    {
        $venta = Venta::with(['cliente', 'detalles'])->findOrFail($id);

        if (!Auth::user()->esAdmin()) {
            return redirect()->route('ventas.historial')->with('error', 'No tienes permiso');
        }

        // Obtener productos
        $libros = Libro::where('disponible', true)->orderBy('titulo')->get();
        $peliculas = Pelicula::where('disponible', true)->orderBy('titulo')->get();

        $empleado = Auth::user();
        $nuevoFolio = $venta->folio;

        $carritoExistente = [];
        foreach ($venta->detalles as $detalle) {
            $carritoExistente[] = [
                'id' => $detalle->producto_id,
                'tipo' => $detalle->tipo_producto,
                'cantidad' => $detalle->cantidad,
                'precio' => (float) $detalle->precio_unitario,
                'subtotal' => (float) $detalle->subtotal,
                'nombre' => $detalle->nombre_producto
            ];
        }

        return view('ventas.pos', compact(
            'libros',
            'peliculas',
            'nuevoFolio',
            'empleado',
            'carritoExistente',
            'venta'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $venta = Venta::with('detalles')->findOrFail($id);

            if (!Auth::user()->esAdmin()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }

            DB::beginTransaction();

            // Devolver stock
            foreach ($venta->detalles as $detalle) {
                if ($detalle->tipo_producto == 'libro') {
                    $libro = Libro::find($detalle->producto_id);
                    if ($libro) {
                        $libro->stock += $detalle->cantidad;
                        $libro->save();
                    }
                } else {
                    $pelicula = Pelicula::find($detalle->producto_id);
                    if ($pelicula) {
                        $pelicula->stock += $detalle->cantidad;
                        $pelicula->save();
                    }
                }
            }

            $venta->detalles()->delete();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotalItem = $item['cantidad'] * $item['precio'];
                $subtotal += $subtotalItem;

                DetalleVenta::create([
                    'venta_id' => $venta->idventa,
                    'tipo_producto' => $item['tipo'],
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'descuento' => 0,
                    'subtotal' => $subtotalItem
                ]);

                // Actualizar stock
                if ($item['tipo'] == 'libro') {
                    $libro = Libro::find($item['id']);
                    if ($libro) {
                        $libro->stock -= $item['cantidad'];
                        $libro->save();
                    }
                } else {
                    $pelicula = Pelicula::find($item['id']);
                    if ($pelicula) {
                        $pelicula->stock -= $item['cantidad'];
                        $pelicula->save();
                    }
                }
            }

            $iva = $subtotal * 0.16;
            $total = $subtotal + $iva;

            $venta->update([
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'metodo_pago' => $request->metodo_pago
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta actualizada exitosamente',
                'folio' => $venta->folio,
                'venta_id' => $venta->idventa
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $venta = Venta::with('detalles')->findOrFail($id);

        if (!Auth::user()->esAdmin()) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
        }

        DB::beginTransaction();

        try {
            foreach ($venta->detalles as $detalle) {
                if ($detalle->tipo_producto == 'libro') {
                    $libro = Libro::find($detalle->producto_id);
                    if ($libro) {
                        $libro->stock += $detalle->cantidad;
                        $libro->save();
                    }
                } else {
                    $pelicula = Pelicula::find($detalle->producto_id);
                    if ($pelicula) {
                        $pelicula->stock += $detalle->cantidad;
                        $pelicula->save();
                    }
                }
            }

            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Venta eliminada exitosamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
