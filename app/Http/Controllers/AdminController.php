<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Emprendedor;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Muestra el Dashboard principal con los contadores de resumen.
     */
    public function dashboard()
    {
        $totalEmprendedores = Emprendedor::count();
        $totalMarcas = Marca::count();
        $totalProductos = Producto::count();
        $totalUsuarios = User::count();

        return view('admin.dashboard', compact(
            'totalEmprendedores',
            'totalMarcas',
            'totalProductos',
            'totalUsuarios'
        ));
    }

    /**
     * Muestra el listado completo de usuarios y sus perfiles de emprendedor.
     */
    public function usuariosIndex()
    {
        $usuarios = User::with('emprendedor')->latest()->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para registrar un nuevo usuario/emprendedor.
     */
    public function usuariosCreate()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guarda un nuevo usuario en la base de datos y crea su registro de emprendedor si corresponde.
     */
    public function usuariosStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,emprendedor',
            'status' => 'required|in:activo,inactivo',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        // Si el usuario es de rol emprendedor, creamos automáticamente su registro en la tabla emprendedores
        if ($request->role === 'emprendedor') {
            Emprendedor::create([
                'user_id' => $user->id,
                'status' => 'activo',
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     */
    public function usuariosEdit($id)
    {
        $usuario = User::with('emprendedor')->findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza la información del usuario en la base de datos.
     */
    public function usuariosUpdate(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,emprendedor',
            'status' => 'required|in:activo,inactivo',
            'password' => 'nullable|string|min:6',
        ]);

        // Validación 1: Impedir desactivar tu propia cuenta activa
    if (auth()->id() == $usuario->id && $request->status === 'inactivo') {
        return redirect()->back()->with('status', 'No puedes desactivar tu propia cuenta de administrador.');
    }

    // Validación 2: Garantizar que siempre exista al menos un administrador activo
    if ($usuario->role === 'admin' && $request->status === 'inactivo') {
        $adminActivos = User::where('role', 'admin')
            ->where('status', 'activo')
            ->where('id', '!=', $usuario->id)
            ->count();

        if ($adminActivos === 0) {
            return redirect()->back()->with('status', 'No puedes desactivar el único administrador activo del sistema.');
        }
    }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->role = $request->role;
        $usuario->status = $request->status;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        // Si cambió el rol a emprendedor y aún no tiene registro asociado, se lo creamos
        if ($request->role === 'emprendedor' && !$usuario->emprendedor) {
            Emprendedor::create([
                'user_id' => $usuario->id,
                'status' => 'activo',
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function usuariosDestroy($id)
    {
        $usuario = User::findOrFail($id);

        // Previene eliminar al propio usuario autenticado
        if (auth()->id() == $usuario->id) {
            return redirect()->back()->with('status', 'No puedes eliminar tu propia cuenta de administrador.');
        }
        

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario eliminado correctamente.');
    }
}