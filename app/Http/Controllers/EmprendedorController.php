<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Emprendedor;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Categoria;

class EmprendedorController extends Controller
{
    // ==========================================
    // PERFIL DEL EMPRENDEDOR
    // ==========================================
    public function perfil()
    {
        $user = Auth::user();
        $emprendedor = $user->emprendedor;
        $categorias = Categoria::all(); // Categorías para el desplegable

        return view('emprendedores.perfil', compact('user', 'emprendedor', 'categorias'));
    }

    public function perfilUpdate(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'categoria_principal' => 'nullable|string|max:255',
            'biografia' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|string',
            'tiktok' => 'nullable|string',
        ]);

        $user->emprendedor->update($request->all());

        return redirect()->route('emprendedor.perfil')->with('success', 'Perfil actualizado correctamente.');
    }

    // ==========================================
    // MARCAS (CRUD Básico)
    // ==========================================
    public function marcasIndex()
    {
        $marcas = Marca::where('emprendedor_id', Auth::user()->emprendedor->id)->get();
        return view('emprendedores.marcas.index', compact('marcas'));
    }

    public function marcasCreate()
    {
        return view('emprendedores.marcas.create');
    }

    public function marcasStore(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255', 'descripcion' => 'nullable|string']);
        $data = $request->all();
        $data['emprendedor_id'] = Auth::user()->emprendedor->id;
        Marca::create($data);
        return redirect()->route('emprendedor.marcas.index')->with('success', 'Marca creada.');
    }

    public function marcasEdit($id)
    {
        $marca = Marca::where('emprendedor_id', Auth::user()->emprendedor->id)->findOrFail($id);
        return view('emprendedores.marcas.edit', compact('marca'));
    }

    public function marcasUpdate(Request $request, $id)
    {
        $marca = Marca::where('emprendedor_id', Auth::user()->emprendedor->id)->findOrFail($id);
        $request->validate(['nombre' => 'required|string|max:255', 'descripcion' => 'nullable|string']);
        $marca->update($request->all());
        return redirect()->route('emprendedor.marcas.index')->with('success', 'Marca actualizada.');
    }

    public function marcasDestroy($id)
    {
        $marca = Marca::where('emprendedor_id', Auth::user()->emprendedor->id)->findOrFail($id);
        $marca->delete();
        return redirect()->route('emprendedor.marcas.index')->with('success', 'Marca eliminada.');
    }

    // ==========================================
    // PRODUCTOS (CRUD con Imágenes)
    // ==========================================
    public function productosIndex()
    {
        $productos = Producto::where('emprendedor_id', Auth::user()->emprendedor->id)->get();
        return view('emprendedores.productos.index', compact('productos'));
    }

    public function productosCreate()
    {
        $categorias = Categoria::all();
        $marcas = Marca::where('emprendedor_id', Auth::user()->emprendedor->id)->get();
        return view('emprendedores.productos.create', compact('categorias', 'marcas'));
    }

    public function productosStore(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validación de imagen
        ]);

        $data = $request->all();
        $data['emprendedor_id'] = $user->emprendedor->id;
        $data['estado'] = 'activo';

        // Guardar imagen si se subió
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);
        return redirect()->route('emprendedor.productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function productosEdit($id)
    {
        $producto = Producto::where('emprendedor_id', Auth::user()->emprendedor->id)->findOrFail($id);
        $categorias = Categoria::all();
        $marcas = Marca::where('emprendedor_id', Auth::user()->emprendedor->id)->get();
        
        return view('emprendedores.productos.edit', compact('producto', 'categorias', 'marcas'));
    }

    public function productosUpdate(Request $request, $id)
    {
        $user = Auth::user();
        $producto = Producto::where('emprendedor_id', $user->emprendedor->id)->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        // Si sube una nueva imagen, eliminar la anterior y guardar la nueva
        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);
        return redirect()->route('emprendedor.productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function productosDestroy($id)
    {
        $producto = Producto::where('emprendedor_id', Auth::user()->emprendedor->id)->findOrFail($id);
        
        // Eliminar imagen del servidor si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        
        $producto->delete();
        return redirect()->route('emprendedor.productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}