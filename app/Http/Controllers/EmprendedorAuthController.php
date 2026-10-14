<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmprendedorAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('emprendedores.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'emprendedor', 'status' => 'activo'])) {
            $request->session()->regenerate();

            return redirect()->route('emprendedor.dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden o la cuenta no está activa.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('emprendedor.login');
    }
}