<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user('web');

        if ($user === null || $user->status !== 'activo') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route($role === 'emprendedor' ? 'emprendedor.login' : 'login')
                ->withErrors(['email' => 'La sesión ha finalizado. Inicia sesión con una cuenta activa.']);
        }

        abort_unless($user->role === $role, 403);

        if ($role === 'emprendedor') {
            abort_unless($user->emprendedor()->where('status', 'activo')->exists(), 403,
                'El perfil de emprendedor no está disponible. Contacta al administrador.');
        }

        return $next($request);
    }
}
