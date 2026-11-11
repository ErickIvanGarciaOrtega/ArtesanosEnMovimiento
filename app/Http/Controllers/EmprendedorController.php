<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Emprendedor;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoImagen;

class EmprendedorController extends Controller
{
    // ==========================================
    // PERFIL DEL EMPRENDEDOR
    // ==========================================
    public function perfil()
    {
        $user = Auth::user();
        $emprendedor = $user->emprendedor;
        $categorias = Categoria::all();

        return view('emprendedores.perfil', compact('user', 'emprendedor', 'categorias'));
    }

public function perfilUpdate(Request $request)
{
    $user = Auth::user();
    $emprendedor = $user->emprendedor;

    // Si el emprendedor escribió un sitio web sin http:// o https://, se lo agregamos automáticamente
    if ($request->filled('sitio_web') && !preg_match("~^(?:f|ht)tps?://~i", $request->sitio_web)) {
        $request->merge([
            'sitio_web' => 'https://' . trim($request->sitio_web)
        ]);
    }

    $request->validate([
        'categoria_principal' => 'nullable|string|max:255',
        'biografia'           => 'nullable|string',
        'facebook'            => 'nullable|string|max:255',
        'instagram'           => 'nullable|string|max:255',
        'tiktok'              => 'nullable|string|max:255',
        'sitio_web'           => 'nullable|string|max:255', // Cambiado a string para que no rechace dominios sin protocolo
        'imagen'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

  $data = $request->only([
        'categoria_principal', 'biografia', 'facebook', 'instagram', 'tiktok', 'sitio_web'
    ]);

    if ($request->hasFile('imagen')) {
        if ($emprendedor->imagen) {
            Storage::disk('public')->delete($emprendedor->imagen);
        }
        $data['imagen'] = $request->file('imagen')->store('emprendedores', 'public');
    }

    $emprendedor->update($data);

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
        
        // S01 FIX: Evitamos $request->all()
        $data = $request->only(['nombre', 'descripcion']);
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
        
        // S01 FIX: Extraemos estrictamente nombre y descripción
        $marca->update($request->only(['nombre', 'descripcion']));
        
        return redirect()->route('emprendedor.marcas.index')->with('success', 'Marca actualizada.');
    }

    public function marcasDestroy($id)
    {
        $marca = Marca::where('emprendedor_id', Auth::user()->emprendedor->id)->findOrFail($id);
        $marca->delete();
        return redirect()->route('emprendedor.marcas.index')->with('success', 'Marca eliminada.');
    }

    // ==========================================
    // PRODUCTOS (CRUD con Múltiples Imágenes)
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
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id'     => 'nullable|exists:marcas,id',
            'imagenes'     => 'nullable|array|max:10',
            'imagenes.*'   => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

  $data = $request->only([
            'nombre', 'descripcion', 'precio', 'categoria_id', 'marca_id'
        ]);
        $data['emprendedor_id'] = $user->emprendedor->id;
        $data['estado'] = 'activo';

        $producto = Producto::create($data); 

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                $ruta = $file->store('productos', 'public');
                $producto->imagenes()->create(['ruta' => $ruta]);
            }
        }

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
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id'     => 'nullable|exists:marcas,id',
            'imagenes'     => 'nullable|array|max:10', 
            'imagenes.*'   => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

$data = $request->only([
            'nombre', 'descripcion', 'precio', 'categoria_id', 'marca_id'
        ]);

        /** @var \App\Models\Producto $producto */
        $producto->update($data);

        if ($request->hasFile('imagenes')) {
            $imagenesActuales = $producto->imagenes()->count();
            $nuevasImagenes = count($request->file('imagenes'));
            
            if (($imagenesActuales + $nuevasImagenes) > 10) {
                return back()->withErrors(['imagenes' => 'No puedes superar el límite de 10 imágenes por producto en total.']);
            }

            foreach ($request->file('imagenes') as $file) {
                $ruta = $file->store('productos', 'public');
                $producto->imagenes()->create(['ruta' => $ruta]);
            }
        }

        return redirect()->route('emprendedor.productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function productosDestroy($id)
    {
        $producto = Producto::where('emprendedor_id', Auth::user()->emprendedor->id)->findOrFail($id);
        
        if ($producto->imagenes->count() > 0) {
            foreach ($producto->imagenes as $imagen) {
                Storage::disk('public')->delete($imagen->ruta);
            }
        }
        
        $producto->delete();
        
        return redirect()->route('emprendedor.productos.index')->with('success', 'Producto eliminado exitosamente.');
    }

    // ==========================================
    // ELIMINAR IMAGEN INDIVIDUAL DEL PRODUCTO
    // ==========================================
    public function eliminarImagen($id)
    {
        $imagen = ProductoImagen::findOrFail($id);
        
        if ($imagen->producto->emprendedor_id !== Auth::user()->emprendedor->id) {
            abort(403, 'Acción no autorizada.');
        }

        Storage::disk('public')->delete($imagen->ruta);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }
}