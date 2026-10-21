<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Emprendedor;
use App\Models\Configuracion;
use App\Models\Marca;

class PublicController extends Controller
{
    public function home()
    {
        $categorias = Categoria::all(); 
        $productosDestacados = Producto::where('estado', 'activo')->take(4)->get();
        $config = Configuracion::pluck('valor', 'clave')->toArray();

        return view('public.home', compact('categorias', 'productosDestacados', 'config'));
    }

    public function productos(Request $request)
    {
        $query = Producto::with(['marca', 'emprendedor.user', 'categoria'])
                        ->where('estado', 'activo');

        // Compatible tanto con 'search' como con 'buscar'
        $busqueda = $request->input('search') ?? $request->input('buscar');
        if ($busqueda) {
            $query->where('nombre', 'like', '%' . $busqueda . '%');
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
        $marcas = Marca::all(); 
        
        // ---> NUEVA LÍNEA: Obtenemos los textos del CMS <---
        $config = Configuracion::pluck('valor', 'clave')->toArray();

        // ---> MODIFICAMOS EL RETURN: agregamos 'config' al compact <---
        return view('public.productos', compact('productos', 'categorias', 'marcas', 'config'));
    }

    // Listado público de emprendedores
    public function emprendedores()
    {
        $emprendedores = Emprendedor::with('user')->get();
        $config = Configuracion::pluck('valor', 'clave')->toArray();

        return view('public.emprendedores', compact('emprendedores', 'config'));
    }

    // Perfil detallado de un emprendedor específico
    public function emprendedorPerfil($id)
    {
        $emprendedor = Emprendedor::with(['user', 'marcas', 'productos.categoria', 'productos.marca'])->findOrFail($id);
        return view('public.emprendedor-detalle', compact('emprendedor'));
    }
    
    public function quienesSomos()
    {
        $config = Configuracion::pluck('valor', 'clave')->toArray();
        return view('public.quienes-somos', compact('config')); 
    }

    // Vista Contacto
public function contacto()
    {
        $config = Configuracion::pluck('valor', 'clave')->toArray();
        return view('public.contacto', compact('config'));
    }

    // Vista de detalle de un producto específico
    public function productoDetalle($id)
    {
        $producto = Producto::with(['marca', 'categoria', 'emprendedor.user'])->findOrFail($id);
        
        $productosRelacionados = Producto::where('categoria_id', $producto->categoria_id)
                                        ->where('id', '!=', $producto->id)
                                        ->where('estado', 'activo')
                                        ->take(4)
                                        ->get();

        return view('public.producto-detalle', compact('producto', 'productosRelacionados'));
    }
}