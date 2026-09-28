<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprendedor; // Importamos el modelo Emprendedor
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function gestionarEmprendedores()
    {
        // Obtenemos todos los emprendedores ordenados del más reciente al más antiguo
        $emprendedores = Emprendedor::latest()->get();

        // Enviamos la lista a la vista 'admin.emprendedores'
        return view('admin.emprendedores', compact('emprendedores'));
    }

    public function storeEmprendedor(Request $request)
{
    $request->validate([
        'nombre_completo' => 'required|string|max:255',
        'username' => 'required|string|unique:emprendedores,username|max:255',
        'password' => 'required|string|min:6',
        'marca_o_giro' => 'required|string|max:255',
        'telefono' => 'required|string|max:15',
    ]);

    Emprendedor::create([
        'nombre_completo' => $request->nombre_completo,
        'username' => $request->username,
        'password' => Hash::make($request->password), // Encriptación Bcrypt
        'marca_o_giro' => $request->marca_o_giro,
        'telefono' => $request->telefono,
        'estado' => true,
    ]);

    return redirect()->route('admin.emprendedores')->with('success', 'Emprendedor registrado correctamente.');
}

public function updateEmprendedor(Request $request, $id)
{
    $emprendedor = Emprendedor::findOrFail($id);

    $request->validate([
        'nombre_completo' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:emprendedores,username,' . $id,
        'marca_o_giro' => 'required|string|max:255',
        'telefono' => 'required|string|max:15',
        'estado' => 'required|boolean',
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
}