<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // Muestra el formulario para solicitar el correo de recuperación
    public function showLinkRequestForm()
    {
        return view('admin.auth.forgot-password');
    }

    // Envía el correo con el token de restablecimiento
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.exists' => 'No encontramos ningún administrador registrado con este correo.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Hemos enviado el enlace de restablecimiento a tu correo electrónico.')
            : back()->withErrors(['email' => __($status)]);
    }

    // Muestra la vista para ingresar la nueva contraseña
    public function showResetForm(Request $request, $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Procesa la actualización de la contraseña en la base de datos
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida con éxito!')
            : back()->withErrors(['email' => __($status)]);
    }
}