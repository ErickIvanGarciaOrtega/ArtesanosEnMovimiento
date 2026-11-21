<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Configuracion;
use App\Models\Emprendedor;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $categorias = Categoria::all();
        $productosDestacados = Producto::with(['imagenPrincipal', 'marca', 'emprendedor.user'])
            ->where('estado', 'activo')
            ->take(4)
            ->get();
        $config = Configuracion::pluck('valor', 'clave')->toArray();

        return view('public.home', compact('categorias', 'productosDestacados', 'config'));
    }

    public function productos(Request $request)
    {
        foreach (['buscar', 'search', 'categoria_id', 'marca_id'] as $filtro) {
            if (! is_string($request->input($filtro))) {
                $request->merge([$filtro => null]);
            }
        }

        $query = Producto::with(['imagenPrincipal', 'marca', 'emprendedor.user', 'categoria'])
            ->where('estado', 'activo');

        // F08 FIX: Nos aseguramos de que la búsqueda sea estrictamente un texto.
        // Esto evita el Error 500 (Array to String conversion) si manipulan la URL con corchetes.
        $busqueda = $request->input('search') ?? $request->input('buscar');
        if (is_string($busqueda) && $busqueda !== '') {
            $query->where('nombre', 'like', '%'.$busqueda.'%');
        }

        // Filtrar por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtrar por marca
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }

        $productos = $query->paginate(12)->withQueryString();
        $categorias = Categoria::all();
        $marcas = Marca::all();

        $config = Configuracion::pluck('valor', 'clave')->toArray();

        return view('public.productos', compact('productos', 'categorias', 'marcas', 'config'));
    }

    // Listado público de emprendedores
    public function emprendedores()
    {
        $emprendedores = Emprendedor::with(['user', 'marcas'])
            ->withCount(['productos' => function ($query) {
                $query->where('estado', 'activo');
            }])
            ->paginate(12);
        $config = Configuracion::pluck('valor', 'clave')->toArray();

        return view('public.emprendedores', compact('emprendedores', 'config'));
    }

    // Perfil detallado de un emprendedor específico
    public function emprendedorPerfil($id)
    {
        // S04 FIX: Cargar en el perfil público SOLAMENTE los productos que estén 'activos'
        $emprendedor = Emprendedor::with(['user', 'marcas', 'productos' => function ($query) {
            $query->where('estado', 'activo')->with(['categoria', 'marca', 'imagenPrincipal']);
        }])->findOrFail($id);

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
        // S04 FIX: Proteger el enlace directo. Si el producto no está 'activo', dará error 404.
        $producto = Producto::with(['marca', 'categoria', 'emprendedor.user', 'imagenes', 'imagenPrincipal'])
            ->where('estado', 'activo')
            ->findOrFail($id);

        $productosRelacionados = Producto::with(['imagenPrincipal', 'marca', 'emprendedor.user'])
            ->where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->where('estado', 'activo')
            ->take(4)
            ->get();

        return view('public.producto-detalle', compact('producto', 'productosRelacionados'));
    }
}