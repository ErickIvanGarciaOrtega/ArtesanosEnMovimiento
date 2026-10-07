<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprendedor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Muestra la vista principal del dashboard
    public function index()
    {
        return view('admin.dashboard');
    }

    // --- SECCIÓN: GESTIÓN DE EMPRENDEDORES ---

    // Obtiene y muestra el listado de emprendedores
    public function gestionarEmprendedores()
    {
        $emprendedores = Emprendedor::latest()->get();
        return view('admin.emprendedores', compact('emprendedores'));
    }

    // Registra un nuevo emprendedor
    public function storeEmprendedor(Request $request)
    {
        // Limpiamos espacios antes de validar
        $request->merge([
            'telefono' => preg_replace('/\s+/', '', $request->telefono)
        ]);

        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'username' => 'required|string|unique:emprendedores,username|max:255',
            'password' => 'required|string|min:6',
            'marca_o_giro' => 'required|string|max:255',
            'telefono' => 'required|numeric|digits:10', // Exactamente 10 dígitos numéricos
        ], [
            'telefono.digits' => 'El teléfono de WhatsApp debe tener exactamente 10 dígitos numéricos.',
            'telefono.numeric' => 'El teléfono solo debe contener números sin espacios ni caracteres especiales.',
        ]);

        Emprendedor::create([
            'nombre_completo' => $request->nombre_completo,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'marca_o_giro' => $request->marca_o_giro,
            'telefono' => $request->telefono,
            'estado' => true,
        ]);

        return redirect()->route('admin.emprendedores')->with('success', 'Emprendedor registrado correctamente.');
    }

    // Actualiza la información general del emprendedor
    public function updateEmprendedor(Request $request, $id)
    {
        $emprendedor = Emprendedor::findOrFail($id);

        // Limpiamos espacios antes de validar
        $request->merge([
            'telefono' => preg_replace('/\s+/', '', $request->telefono)
        ]);

        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:emprendedores,username,' . $id,
            'marca_o_giro' => 'required|string|max:255',
            'telefono' => 'required|numeric|digits:10', // Exactamente 10 dígitos numéricos
            'estado' => 'required|boolean',
        ], [
            'telefono.digits' => 'El teléfono de WhatsApp debe tener exactamente 10 dígitos numéricos.',
            'telefono.numeric' => 'El teléfono solo debe contener números sin espacios ni caracteres especiales.',
        ]);

        $emprendedor->update([
            'nombre_completo' => $request->nombre_completo,
            'username' => $request->username,
            'marca_o_giro' => $request->marca_o_giro,
            'telefono' => $request->telefono,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.emprendedores')->with('success', 'Datos actualizados correctamente.');
    }

    // Restablece la contraseña del emprendedor
    public function resetPasswordEmprendedor(Request $request, $id)
    {
        $emprendedor = Emprendedor::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $emprendedor->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.emprendedores')->with('success', 'Contraseña restablecida con éxito.');
    }

    // --- SECCIÓN: GESTIÓN DE ADMINISTRADORES (SUB-ADMINS) ---

    // Obtiene y muestra el listado de usuarios administradores
    public function gestionarUsuarios()
    {
        $administradores = User::latest()->get();
        return view('admin.usuarios', compact('administradores'));
    }

    // Registra un nuevo usuario administrador
    public function storeUsuario(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Administrador registrado correctamente.');
    }
}