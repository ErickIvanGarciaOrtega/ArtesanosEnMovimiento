<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//Importar el controlador para manejar las vistas del panel de administración
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('admin.login');
})->name('login');

// Procesar el formulario de inicio de sesión
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden con nuestros registros.',
    ]);
})->name('login.post');

// Cerrar sesión
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');



// Grupo de rutas protegidas para el panel de administración (Redirige al usuario al loguin en caso de no haber iniciado sesion)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Ruta principal del panel (Dashboard)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Ruta para la gestión de emprendedores
    Route::get('/emprendedores', [AdminController::class, 'gestionarEmprendedores'])->name('admin.emprendedores');


    // Ruta temporal para evitar el error 500 al redirigir con el middleware 'auth'
Route::get('/login', function () {
    return 'Pantalla de Inicio de Sesión';
})->name('login');


});