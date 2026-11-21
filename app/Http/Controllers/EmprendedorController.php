<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoImagen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        if (is_string($request->input('sitio_web')) && $request->filled('sitio_web') && ! preg_match('~^[a-z][a-z0-9+.-]*:~i', trim($request->sitio_web))) {
            $request->merge([
                'sitio_web' => 'https://'.trim($request->sitio_web),
            ]);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'categoria_principal' => 'nullable|string|max:255',
            'biografia' => 'nullable|string',
            'facebook' => 'nullable|url:http,https|max:255',
            'instagram' => 'nullable|url:http,https|max:255',
            'tiktok' => 'nullable|url:http,https|max:255',
            'sitio_web' => 'nullable|url:http,https|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // F02 FIX: Actualizamos el nombre en la tabla 'users'
        /** @var User $user */
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
        }

        $data = $request->only([
            'categoria_principal', 'biografia', 'facebook', 'instagram', 'tiktok', 'sitio_web',
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
        $marcas = Marca::where('emprendedor_id', Auth::user()->emprendedor->id)
            ->withCount('productos')
            ->latest()
            ->paginate(10);

        return view('emprendedores.marcas.index', compact('marcas'));
    }

    public function marcasCreate()
    {
        return view('emprendedores.marcas.create');
    }

    public function marcasStore(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255', 'descripcion' => 'nullable|string']);

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
        $productos = Producto::with(['categoria', 'marca', 'imagenPrincipal'])
            ->where('emprendedor_id', Auth::user()->emprendedor->id)
            ->latest()
            ->paginate(10);

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
            'precio' => 'required|numeric|min:0', // F07 FIX: min:0 impide precios negativos
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => [
                'nullable',
                // S02 FIX: Verifica que la marca exista y además le pertenezca a este emprendedor
                Rule::exists('marcas', 'id')->where(function ($query) use ($user) {
                    $query->where('emprendedor_id', $user->emprendedor->id);
                }),
            ],
            'imagenes' => 'nullable|array|max:10',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only([
            'nombre', 'descripcion', 'precio', 'categoria_id', 'marca_id',
        ]);
        $data['emprendedor_id'] = $user->emprendedor->id;
        $data['estado'] = 'activo';
        $data['descripcion'] = $data['descripcion'] ?? '';

        // F10 FIX: DB::transaction asegura que si una foto falla, el producto no se cree a medias
        $rutasGuardadas = [];
        try {
            DB::transaction(function () use ($request, $data, &$rutasGuardadas) {
                $producto = Producto::create($data);

                if ($request->hasFile('imagenes')) {
                    foreach ($request->file('imagenes') as $file) {
                        $ruta = $file->store('productos', 'public');
                        if ($ruta === false) {
                            throw new \RuntimeException('No se pudo guardar la imagen del producto.');
                        }
                        $rutasGuardadas[] = $ruta;
                        $producto->imagenes()->create(['ruta' => $ruta]);
                    }
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($rutasGuardadas);
            report($exception);

            return back()->withInput()->withErrors(['imagenes' => 'Ocurrió un error al guardar el producto. Verifica tus imágenes.']);
        }

        return redirect()->route('emprendedor.productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function productosEdit($id)
    {
        $producto = Producto::with('imagenes')->where('emprendedor_id', Auth::user()->emprendedor->id)->findOrFail($id);
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
            'precio' => 'required|numeric|min:0', // F07 FIX
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => [
                'nullable',
                // S02 FIX: Evita usar una marca de otro emprendedor
                Rule::exists('marcas', 'id')->where(function ($query) use ($user) {
                    $query->where('emprendedor_id', $user->emprendedor->id);
                }),
            ],
            'imagenes' => 'nullable|array|max:10',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // F03 FIX: Comprobamos el límite de fotos ANTES de procesar la actualización en base de datos
        if ($request->hasFile('imagenes')) {
            $imagenesActuales = $producto->imagenes()->count();
            $nuevasImagenes = count($request->file('imagenes'));

            if (($imagenesActuales + $nuevasImagenes) > 10) {
                return back()->withErrors(['imagenes' => 'No puedes superar el límite de 10 imágenes por producto en total.']);
            }
        }

        $data = $request->only([
            'nombre', 'descripcion', 'precio', 'categoria_id', 'marca_id',
        ]);

        $data['descripcion'] = $data['descripcion'] ?? '';
        // La transacción revierte filas; las nuevas imágenes se limpian si falla.
        $rutasGuardadas = [];
        try {
            DB::transaction(function () use ($request, $producto, $data, &$rutasGuardadas) {
                /** @var Producto $producto */
                $producto->update($data);

                if ($request->hasFile('imagenes')) {
                    foreach ($request->file('imagenes') as $file) {
                        $ruta = $file->store('productos', 'public');
                        if ($ruta === false) {
                            throw new \RuntimeException('No se pudo guardar la imagen del producto.');
                        }
                        $rutasGuardadas[] = $ruta;
                        $producto->imagenes()->create(['ruta' => $ruta]);
                    }
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($rutasGuardadas);
            report($exception);

            return back()->withInput()->withErrors(['imagenes' => 'Ocurrió un error al actualizar el producto.']);
        }

        return redirect()->route('emprendedor.productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function productosDestroy($id)
    {
        $producto = Producto::with('imagenes')->where('emprendedor_id', Auth::user()->emprendedor->id)->findOrFail($id);

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