<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Emprendedor;

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

public function productos(Request $request)
{
    $query = Producto::with(['marca', 'emprendedor.user', 'categoria'])
                    ->where('estado', 'activo');

    // Filtrar por nombre de producto si se escribe en el buscador
    if ($request->filled('search')) {
        $query->where('nombre', 'like', '%' . $request->search . '%');
    }

    // Filtrar por categoría
    if ($request->filled('categoria_id')) {
        $query->where('categoria_id', $request->categoria_id);
    }

    // Filtrar por marca
    if ($request->filled('marca_id')) {
        $query->where('marca_id', $request->marca_id);
    }

    $productos = $query->get();
    $categorias = Categoria::all();
    $marcas = \App\Models\Marca::all(); // Obtenemos todas las marcas para el filtro

    return view('public.productos', compact('productos', 'categorias', 'marcas'));
}

// Listado público de emprendedores
    public function emprendedores()
    {
        $emprendedores = Emprendedor::with(['user', 'marcas'])->get();
        return view('public.emprendedores', compact('emprendedores'));
    }

    // Perfil detallado de un emprendedor específico
    public function emprendedorPerfil($id)
    {
        $emprendedor = Emprendedor::with(['user', 'marcas', 'productos.categoria', 'productos.marca'])->findOrFail($id);
        return view('public.emprendedor-detalle', compact('emprendedor'));
    }
    
    // Vista Quienes Somos
    public function quienesSomos()
    {
        return view('public.quienes-somos');
    }

    // Vista Contacto
    public function contacto()
    {
        return view('public.contacto');
    }
}