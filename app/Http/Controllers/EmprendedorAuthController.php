<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\PasswordInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmprendedorAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('emprendedores.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', new PasswordInput],
        ]);

        $guard = Auth::guard('web');

        if ($guard->attemptWhen(
            [...$credentials, 'role' => 'emprendedor', 'status' => 'activo'],
            fn (User $user): bool => $user->emprendedor()->where('status', 'activo')->exists(),
        )) {
            $request->session()->regenerate();
            $request->session()->put('password_hash_web', $guard->hashPasswordForCookie($guard->user()->getAuthPassword()));

            return redirect()->route('emprendedor.dashboard');
        }

        return back()->withErrors(['email' => 'No se pudo iniciar sesión con esas credenciales.'])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('emprendedor.login');
    }
}
