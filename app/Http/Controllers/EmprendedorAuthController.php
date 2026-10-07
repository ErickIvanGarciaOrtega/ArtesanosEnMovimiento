<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Importación agregada
use App\Models\Emprendedor;

class EmprendedorAuthController extends Controller
{
    // Muestra el formulario de login para emprendedores
    public function showLoginForm()
    {
        return view('emprendedores.login');
    }

    // Procesa el inicio de sesión
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscamos al emprendedor por su username
        $emprendedor = Emprendedor::where('username', $request->username)->first();

        // Verificamos si existe y si la contraseña es correcta
        if ($emprendedor && Hash::check($request->password, $emprendedor->password)) {
            
            // Validamos que la cuenta no esté desactivada por el admin
            if (!$emprendedor->estado) {
                return back()->withErrors(['username' => 'Tu cuenta se encuentra inactiva. Contacta al administrador.']);
            }

            // Autenticamos al emprendedor en la guardia por defecto o sesión
            Auth::guard('emprendedor')->login($emprendedor);

            return redirect()->route('emprendedor.dashboard');
        }

        return back()->withErrors(['username' => 'Las credenciales no coinciden con nuestros registros.']);
    }

    // Cierra la sesión del emprendedor
    public function logout(Request $request)
    {
        Auth::guard('emprendedor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('emprendedor.login');
    }
}