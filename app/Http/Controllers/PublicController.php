<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class PublicController extends Controller
{
    public function home()
    {
        // Obtener las categorías principales (máximo 6 para la cuadrícula)
        $categorias = Categoria::take(6)->get();

        // Obtener los últimos 8 productos activos con su marca y emprendedor
        $productosDestacados = Producto::with(['marca', 'emprendedor.user'])
                                ->where('estado', 'activo')
                                ->latest()
                                ->take(8)
                                ->get();

        return view('public.home', compact('categorias', 'productosDestacados'));
    }
}