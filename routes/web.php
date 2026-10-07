<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmprendedorAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Rutas Públicas de Administradores
|--------------------------------------------------------------------------
*/
// Formulario de inicio de sesión para Administradores
Route::get('/login', function () {
    return view('admin.login');
})->name('login');

// Procesar inicio de sesión del Administrador (Redirección directa a admin.dashboard)
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden con nuestros registros.',
    ]);
})->name('login.post');

// Cierre de sesión de Administrador
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Redirección inicial
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas del Panel de Administradores (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard principal
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Módulo: Gestión de Emprendedores
    Route::get('/admin/emprendedores', [AdminController::class, 'gestionarEmprendedores'])->name('admin.emprendedores');
    Route::post('/admin/emprendedores', [AdminController::class, 'storeEmprendedor'])->name('admin.emprendedores.store');
    Route::put('/admin/emprendedores/{id}', [AdminController::class, 'updateEmprendedor'])->name('admin.emprendedores.update');
    Route::put('/admin/emprendedores/{id}/reset-password', [AdminController::class, 'resetPasswordEmprendedor'])->name('admin.emprendedores.resetPassword');

    // Módulo: Gestión de Administradores (Sub-Admins)
    Route::get('/admin/usuarios', [AdminController::class, 'gestionarUsuarios'])->name('admin.usuarios');
    Route::post('/admin/usuarios', [AdminController::class, 'storeUsuario'])->name('admin.usuarios.store');
});

/*
|--------------------------------------------------------------------------
| Rutas del Módulo de Emprendedores (Artesanos)
|--------------------------------------------------------------------------
*/
// Autenticación pública de emprendedores
Route::get('/emprendedor/login', [EmprendedorAuthController::class, 'showLoginForm'])->name('emprendedor.login');
Route::post('/emprendedor/login', [EmprendedorAuthController::class, 'login'])->name('emprendedor.login.post');
Route::post('/emprendedor/logout', [EmprendedorAuthController::class, 'logout'])->name('emprendedor.logout');

// Rutas protegidas para Emprendedores autenticados
Route::middleware(['auth:emprendedor'])->group(function () {
    Route::get('/emprendedor/dashboard', function () {
        return view('emprendedores.dashboard');
    })->name('emprendedor.dashboard');
});