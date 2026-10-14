<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprendedor;
use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class EmprendedorController extends Controller
{
    // Ver / Editar Perfil del Emprendedor
    public function perfil()
    {
        $user = Auth::user();
        $emprendedor = $user->emprendedor;

        return view('emprendedores.perfil', compact('user', 'emprendedor'));
    }

    // Actualizar Perfil (sin foto)
    public function perfilUpdate(Request $request)
    {
        $user = Auth::user();
        $emprendedor = $user->emprendedor;

        $request->validate([
            'name' => 'required|string|max:255',
            'categoria_principal' => 'nullable|string|max:255',
            'biografia' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
        ]);

        $user->update(['name' => $request->name]);

        $emprendedor->update([
            'categoria_principal' => $request->categoria_principal,
            'biografia' => $request->biografia,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'tiktok' => $request->tiktok,
        ]);

        return redirect()->back()->with('status', 'Perfil actualizado con éxito.');
    }

    // --- MÓDULO DE MARCAS ---

    public function marcasIndex()
    {
        $emprendedor = Auth::user()->emprendedor;
        $marcas = $emprendedor ? $emprendedor->marcas : collect();

        return view('emprendedores.marcas.index', compact('marcas'));
    }

    public function marcasCreate()
    {
        return view('emprendedores.marcas.create');
    }

    public function marcasStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $emprendedor = Auth::user()->emprendedor;

        Marca::create([
            'emprendedor_id' => $emprendedor->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('emprendedor.marcas.index')->with('status', 'Marca registrada con éxito.');
    }

    // Editar Marca
    public function marcasEdit($id)
    {
        $emprendedor = Auth::user()->emprendedor;
        $marca = Marca::where('emprendedor_id', $emprendedor->id)->findOrFail($id);

        return view('emprendedores.marcas.edit', compact('marca'));
    }

    // Actualizar Marca
    public function marcasUpdate(Request $request, $id)
    {
        $emprendedor = Auth::user()->emprendedor;
        $marca = Marca::where('emprendedor_id', $emprendedor->id)->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $marca->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('emprendedor.marcas.index')->with('status', 'Marca actualizada con éxito.');
    }

    // Eliminar Marca
    public function marcasDestroy($id)
    {
        $emprendedor = Auth::user()->emprendedor;
        $marca = Marca::where('emprendedor_id', $emprendedor->id)->findOrFail($id);
        $marca->delete();

        return redirect()->route('emprendedor.marcas.index')->with('status', 'Marca eliminada con éxito.');
    }

    // --- MÓDULO DE PRODUCTOS ---

    public function productosIndex()
    {
        $emprendedor = Auth::user()->emprendedor;
        $productos = $emprendedor ? $emprendedor->productos()->with(['marca', 'categoria'])->get() : collect();

        return view('emprendedores.productos.index', compact('productos'));
    }

    public function productosCreate()
    {
        $emprendedor = Auth::user()->emprendedor;
        $marcas = $emprendedor ? $emprendedor->marcas : collect();
        $categorias = Categoria::all();

        return view('emprendedores.productos.create', compact('marcas', 'categorias'));
    }

    public function productosStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'marca_id' => 'nullable|exists:marcas,id',
            'categoria_id' => 'nullable|exists:categorias,id',
            'descripcion' => 'nullable|string',
        ]);

        $emprendedor = Auth::user()->emprendedor;

        Producto::create([
            'emprendedor_id' => $emprendedor->id,
            'marca_id' => $request->marca_id,
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => 1,
            'estado' => 'activo',
        ]);

        return redirect()->route('emprendedor.productos.index')->with('status', 'Producto publicado exitosamente.');
    }

    public function productosEdit($id)
    {
        $emprendedor = Auth::user()->emprendedor;
        $producto = Producto::where('emprendedor_id', $emprendedor->id)->findOrFail($id);
        $marcas = $emprendedor->marcas;
        $categorias = Categoria::all();

        return view('emprendedores.productos.edit', compact('producto', 'marcas', 'categorias'));
    }

    public function productosUpdate(Request $request, $id)
    {
        $emprendedor = Auth::user()->emprendedor;
        $producto = Producto::where('emprendedor_id', $emprendedor->id)->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'marca_id' => 'nullable|exists:marcas,id',
            'categoria_id' => 'nullable|exists:categorias,id',
            'descripcion' => 'nullable|string',
        ]);

        $producto->update([
            'marca_id' => $request->marca_id,
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
        ]);

        return redirect()->route('emprendedor.productos.index')->with('status', 'Producto actualizado correctamente.');
    }

    public function productosDestroy($id)
    {
        $emprendedor = Auth::user()->emprendedor;
        $producto = Producto::where('emprendedor_id', $emprendedor->id)->findOrFail($id);
        $producto->delete();

        return redirect()->route('emprendedor.productos.index')->with('status', 'Producto eliminado con éxito.');
    }
}