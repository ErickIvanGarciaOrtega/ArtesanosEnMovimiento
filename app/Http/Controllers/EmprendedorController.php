<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Emprendedor;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoImagen; // Importamos el modelo de imágenes

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
        $emprendedor = $user->emprendedor;

        $request->validate([
            'categoria_principal' => 'nullable|string|max:255',
            'biografia'           => 'nullable|string',
            'facebook'            => 'nullable|string|max:255',
            'instagram'           => 'nullable|string|max:255',
            'tiktok'              => 'nullable|string|max:255',
            'imagen'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validación de imagen de perfil
        ]);

        $data = $request->except('imagen');

        // Procesar subida de imagen de perfil
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($emprendedor->imagen) {
                Storage::disk('public')->delete($emprendedor->imagen);
            }
            // Guardar la nueva imagen en la carpeta 'emprendedores'
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
            'imagenes'     => 'nullable|array|max:10', // Acepta arreglo de máximo 10
            'imagenes.*'   => 'image|mimes:jpeg,png,jpg,webp|max:2048', // Valida cada archivo
        ]);

        $data = $request->except('imagenes'); // Evita insertar el arreglo directo al crear el producto
        $data['emprendedor_id'] = $user->emprendedor->id;
        $data['estado'] = 'activo';

        // 1. Crear el registro principal del producto
        $producto = Producto::create($data); 

        // 2. Procesar y guardar el arreglo de imágenes asociadas a este producto
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                $ruta = $file->store('productos', 'public');
                $producto->imagenes()->create(['ruta' => $ruta]); // Usando la relación definida en el modelo
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

        $data = $request->except('imagenes');
        $producto->update($data); // 1. Actualizar datos base

        // 2. Procesar nuevas imágenes si se envían
        if ($request->hasFile('imagenes')) {
            // Validar límite total de imágenes (las que ya tiene + las que intenta subir)
            $imagenesActuales = $producto->imagenes()->count();
            $nuevasImagenes = count($request->file('imagenes'));
            
            if (($imagenesActuales + $nuevasImagenes) > 10) {
                return back()->withErrors(['imagenes' => 'No puedes superar el límite de 10 imágenes por producto en total.']);
            }

            // Guardar nuevas rutas
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
        
        // Eliminar archivos físicos de todas las imágenes asociadas antes de borrar el registro
        if ($producto->imagenes->count() > 0) {
            foreach ($producto->imagenes as $imagen) {
                Storage::disk('public')->delete($imagen->ruta);
            }
        }
        
        $producto->delete(); // Elimina el producto (Las relaciones en cascada borrarán los registros en BD)
        
        return redirect()->route('emprendedor.productos.index')->with('success', 'Producto eliminado exitosamente.');
    }

    // ==========================================
    // ELIMINAR IMAGEN INDIVIDUAL DEL PRODUCTO
    // ==========================================
    public function eliminarImagen($id)
    {
        $imagen = ProductoImagen::findOrFail($id);
        
        // Medida de seguridad: Validar que la imagen pertenezca a un producto de este emprendedor
        if ($imagen->producto->emprendedor_id !== Auth::user()->emprendedor->id) {
            abort(403, 'Acción no autorizada.');
        }

        // Eliminar del almacenamiento físico (storage)
        Storage::disk('public')->delete($imagen->ruta);
        
        // Eliminar registro de la base de datos
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }
}