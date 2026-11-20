<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'string', 'email', 'max:255']]);

        try {
            Password::broker('users')->sendResetLink([...$credentials, 'role' => 'admin', 'status' => 'activo']);
        } catch (TransportExceptionInterface $exception) {
            report($exception);
        }

        return back()->with('status', 'Si el correo corresponde a una cuenta administrativa activa, recibirás un enlace para restablecer la contraseña.');
    }

    public function showResetForm(Request $request, string $token): View
    {
        $attributes = $request->validate(['email' => ['required', 'string', 'email', 'max:255']]);

        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $attributes['email'],
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'token' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:1024', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::broker('users')->reset(
            [...$credentials, 'role' => 'admin', 'status' => 'activo'],
            function (User $user, string $password): void {
                $user->forceFill(['password' => Hash::make($password)])
                    ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            },
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida. Inicia sesión nuevamente.')
            : back()->withErrors(['email' => 'El enlace de restablecimiento no es válido o ha vencido.']);
    }
}
