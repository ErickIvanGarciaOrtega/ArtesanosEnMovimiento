<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Emprendedor;
use App\Models\Configuracion;

class PublicController extends Controller
{
public function home()
    {
        // Tus consultas actuales (quizás las tengas un poco distintas, mantenlas)
        $categorias = Categoria::all(); 
        $productosDestacados = Producto::where('estado', 'activo')->take(4)->get();
        
        // ---> NUEVA LÍNEA: Obtenemos los textos del CMS <---
        $config = Configuracion::pluck('valor', 'clave')->toArray();

        // ---> MODIFICAMOS EL RETURN: agregamos 'config' al compact <---
        return view('public.home', compact('categorias', 'productosDestacados', 'config'));
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
    
public function quienesSomos()
    {
        // Traemos todos los textos guardados en el CMS
        $config = Configuracion::pluck('valor', 'clave')->toArray();

        // Se los pasamos a la vista
        return view('public.quienes-somos', compact('config')); 
        // Nota: Asegúrate de que el nombre de tu archivo blade coincida (quienes-somos o quienes_somos)
    }

    // Vista Contacto
    public function contacto()
    {
        return view('public.contacto');
    }

    // Vista de detalle de un producto específico
    public function productoDetalle($id)
    {
        $producto = Producto::with(['marca', 'categoria', 'emprendedor.user'])->findOrFail($id);
        
        // Obtener productos relacionados de la misma categoría o emprendedor
        $productosRelacionados = Producto::where('categoria_id', $producto->categoria_id)
                                          ->where('id', '!=', $producto->id)
                                          ->where('estado', 'activo')
                                          ->take(4)
                                          ->get();

        return view('public.producto-detalle', compact('producto', 'productosRelacionados'));
    }
}