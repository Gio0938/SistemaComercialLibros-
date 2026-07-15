<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Pelicula;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarioController extends Controller
{
    public function index()
    {
        $libros = Libro::orderBy('titulo')->get();
        $peliculas = Pelicula::orderBy('titulo')->get();

        $totalLibros = $libros->count();
        $totalPeliculas = $peliculas->count();
        $totalStockLibros = $libros->sum('stock');
        $totalStockPeliculas = $peliculas->sum('stock');
        $totalValorLibros = $libros->sum(function($l) { return $l->precio * $l->stock; });
        $totalValorPeliculas = $peliculas->sum(function($p) { return $p->precio * $p->stock; });

        $librosStockBajo = Libro::whereRaw('stock <= stock_minimo')->where('stock', '>', 0)->count();
        $peliculasStockBajo = Pelicula::whereRaw('stock <= stock_minimo')->where('stock', '>', 0)->count();
        $librosAgotados = Libro::where('stock', 0)->count();
        $peliculasAgotadas = Pelicula::where('stock', 0)->count();

        return view('inventario.index', compact(
            'libros',
            'peliculas',
            'totalLibros',
            'totalPeliculas',
            'totalStockLibros',
            'totalStockPeliculas',
            'totalValorLibros',
            'totalValorPeliculas',
            'librosStockBajo',
            'peliculasStockBajo',
            'librosAgotados',
            'peliculasAgotadas'
        ));
    }

    public function stockBajo()
    {
        $libros = Libro::whereRaw('stock <= stock_minimo')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->get();

        $peliculas = Pelicula::whereRaw('stock <= stock_minimo')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->get();

        $agotados = [
            'libros' => Libro::where('stock', 0)->get(),
            'peliculas' => Pelicula::where('stock', 0)->get()
        ];

        return view('inventario.stock-bajo', compact('libros', 'peliculas', 'agotados'));
    }

    public function exportarPDF()
    {
        $libros = Libro::all();
        $peliculas = Pelicula::all();
        $pdf = Pdf::loadView('inventario.pdf', compact('libros', 'peliculas'));
        return $pdf->download('inventario-' . date('Y-m-d') . '.pdf');
    }
}
