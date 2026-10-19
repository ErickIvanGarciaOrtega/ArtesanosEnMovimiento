<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmprendedorController;
use App\Http\Controllers\EmprendedorAuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Landing, Catálogo y Directorio Abierto)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/productos', [PublicController::class, 'productos'])->name('public.productos');
Route::get('/emprendedores', [PublicController::class, 'emprendedores'])->name('public.emprendedores');
Route::get('/quienes-somos', [PublicController::class, 'quienesSomos'])->name('public.quienes_somos');
Route::get('/contacto', [PublicController::class, 'contacto'])->name('public.contacto');

// Rutas de autenticación pública para Emprendedores (colocadas ANTES de /emprendedor/{id})
Route::get('/emprendedor/login', [EmprendedorAuthController::class, 'showLoginForm'])->name('emprendedor.login');
Route::post('/emprendedor/login', [EmprendedorAuthController::class, 'login'])->name('emprendedor.login.post');

Route::get('/emprendedor/{id}', [PublicController::class, 'emprendedorPerfil'])
    ->name('public.emprendedor.detalle')
    ->where('id', '[0-9]+'); // <--- Esto obliga a que el ID sea solo numérico

/*
|--------------------------------------------------------------------------
| Rutas Públicas de Administradores (Autenticación)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('admin.login');
})->name('login');

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

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Recuperación de Contraseña
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas del Panel de Administradores (/admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Gestión de Usuarios y Emprendedores (CRUD)
    Route::get('/usuarios', [AdminController::class, 'usuariosIndex'])->name('admin.usuarios.index');
    Route::get('/usuarios/crear', [AdminController::class, 'usuariosCreate'])->name('admin.usuarios.create');
    Route::post('/usuarios', [AdminController::class, 'usuariosStore'])->name('admin.usuarios.store');
    Route::get('/usuarios/{id}/editar', [AdminController::class, 'usuariosEdit'])->name('admin.usuarios.edit');
    Route::put('/usuarios/{id}', [AdminController::class, 'usuariosUpdate'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{id}', [AdminController::class, 'usuariosDestroy'])->name('admin.usuarios.destroy');

    // Módulo: Gestión de Categorías Globales (CRUD)
    Route::get('/categorias', [AdminController::class, 'categoriasIndex'])->name('admin.categorias.index');
    Route::get('/categorias/crear', [AdminController::class, 'categoriasCreate'])->name('admin.categorias.create');
    Route::post('/categorias', [AdminController::class, 'categoriasStore'])->name('admin.categorias.store');
    Route::get('/categorias/{id}/editar', [AdminController::class, 'categoriasEdit'])->name('admin.categorias.edit');
    Route::put('/categorias/{id}', [AdminController::class, 'categoriasUpdate'])->name('admin.categorias.update');
    Route::delete('/categorias/{id}', [AdminController::class, 'categoriasDestroy'])->name('admin.categorias.destroy');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas del Portal de Emprendedores (/emprendedor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('emprendedor')->group(function () {
    Route::get('/dashboard', function () {
        return view('emprendedores.dashboard');
    })->name('emprendedor.dashboard');

    // Perfil del Emprendedor
    Route::get('/perfil', [EmprendedorController::class, 'perfil'])->name('emprendedor.perfil');
    Route::put('/perfil', [EmprendedorController::class, 'perfilUpdate'])->name('emprendedor.perfil.update');

    // Marcas (CRUD completo)
    Route::get('/marcas', [EmprendedorController::class, 'marcasIndex'])->name('emprendedor.marcas.index');
    Route::get('/marcas/crear', [EmprendedorController::class, 'marcasCreate'])->name('emprendedor.marcas.create');
    Route::post('/marcas', [EmprendedorController::class, 'marcasStore'])->name('emprendedor.marcas.store');
    Route::get('/marcas/{id}/editar', [EmprendedorController::class, 'marcasEdit'])->name('emprendedor.marcas.edit');
    Route::put('/marcas/{id}', [EmprendedorController::class, 'marcasUpdate'])->name('emprendedor.marcas.update');
    Route::delete('/marcas/{id}', [EmprendedorController::class, 'marcasDestroy'])->name('emprendedor.marcas.destroy');

    // Productos (CRUD completo)
    Route::get('/productos', [EmprendedorController::class, 'productosIndex'])->name('emprendedor.productos.index');
    Route::get('/productos/crear', [EmprendedorController::class, 'productosCreate'])->name('emprendedor.productos.create');
    Route::post('/productos', [EmprendedorController::class, 'productosStore'])->name('emprendedor.productos.store');
    Route::get('/productos/{id}/editar', [EmprendedorController::class, 'productosEdit'])->name('emprendedor.productos.edit');
    Route::put('/productos/{id}', [EmprendedorController::class, 'productosUpdate'])->name('emprendedor.productos.update');
    Route::delete('/productos/{id}', [EmprendedorController::class, 'productosDestroy'])->name('emprendedor.productos.destroy');

    // Cierre de sesión de Emprendedor
    Route::post('/logout', [EmprendedorAuthController::class, 'logout'])->name('emprendedor.logout');
});