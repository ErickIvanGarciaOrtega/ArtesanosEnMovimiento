<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmprendedorAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\EmprendedorController;

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

//Recuperacion de contraseña para administradores
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

//Rutas del panel de administración
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Gestión de Usuarios y Emprendedores (CRUD)
    Route::get('/usuarios', [AdminController::class, 'usuariosIndex'])->name('admin.usuarios.index');
    Route::get('/usuarios/crear', [AdminController::class, 'usuariosCreate'])->name('admin.usuarios.create');
    Route::post('/usuarios', [AdminController::class, 'usuariosStore'])->name('admin.usuarios.store');
    Route::get('/usuarios/{id}/editar', [AdminController::class, 'usuariosEdit'])->name('admin.usuarios.edit');
    Route::put('/usuarios/{id}', [AdminController::class, 'usuariosUpdate'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{id}', [AdminController::class, 'usuariosDestroy'])->name('admin.usuarios.destroy');

    // Rutas de Login para Emprendedores
Route::get('/emprendedor/login', [EmprendedorAuthController::class, 'showLoginForm'])->name('emprendedor.login');
Route::post('/emprendedor/login', [EmprendedorAuthController::class, 'login']);

// Categorías
    Route::get('/categorias', [AdminController::class, 'categoriasIndex'])->name('admin.categorias.index');
    Route::get('/categorias/crear', [AdminController::class, 'categoriasCreate'])->name('admin.categorias.create');
    Route::post('/categorias', [AdminController::class, 'categoriasStore'])->name('admin.categorias.store');
    Route::get('/categorias/{id}/editar', [AdminController::class, 'categoriasEdit'])->name('admin.categorias.edit');
    Route::put('/categorias/{id}', [AdminController::class, 'categoriasUpdate'])->name('admin.categorias.update');
    Route::delete('/categorias/{id}', [AdminController::class, 'categoriasDestroy'])->name('admin.categorias.destroy');


// Rutas protegidas del Portal Emprendedor
Route::middleware(['auth'])->prefix('emprendedor')->group(function () {
    Route::get('/dashboard', function () {
        return view('emprendedores.dashboard');
    })->name('emprendedor.dashboard');

    Route::post('/logout', [EmprendedorAuthController::class, 'logout'])->name('emprendedor.logout');
});
});

// Rutas protegidas para Emprendedores autenticados
Route::middleware(['auth:emprendedor'])->group(function () {
    Route::get('/emprendedor/dashboard', function () {
        return view('emprendedores.dashboard');
    })->name('emprendedor.dashboard');
});

Route::middleware(['auth'])->prefix('emprendedor')->group(function () {
    Route::get('/dashboard', function () {
        return view('emprendedores.dashboard');
    })->name('emprendedor.dashboard');

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

    Route::post('/logout', [EmprendedorAuthController::class, 'logout'])->name('emprendedor.logout');Route::post('/marcas', [EmprendedorController::class, 'marcasStore'])->name('emprendedor.marcas.store');

    Route::post('/logout', [EmprendedorAuthController::class, 'logout'])->name('emprendedor.logout');
});

// Rutas protegidas del Portal Emprendedor
Route::middleware(['auth'])->prefix('emprendedor')->group(function () {
    Route::get('/dashboard', function () {
        return view('emprendedores.dashboard');
    })->name('emprendedor.dashboard');

    // Perfil
    Route::get('/perfil', [EmprendedorController::class, 'perfil'])->name('emprendedor.perfil');
    Route::put('/perfil', [EmprendedorController::class, 'perfilUpdate'])->name('emprendedor.perfil.update');

    // Marcas
    Route::get('/marcas', [EmprendedorController::class, 'marcasIndex'])->name('emprendedor.marcas.index');
    Route::get('/marcas/crear', [EmprendedorController::class, 'marcasCreate'])->name('emprendedor.marcas.create');
    Route::post('/marcas', [EmprendedorController::class, 'marcasStore'])->name('emprendedor.marcas.store');

    // Productos
    Route::get('/productos', [EmprendedorController::class, 'productosIndex'])->name('emprendedor.productos.index');
    Route::get('/productos/crear', [EmprendedorController::class, 'productosCreate'])->name('emprendedor.productos.create');
    Route::post('/productos', [EmprendedorController::class, 'productosStore'])->name('emprendedor.productos.store');

    Route::post('/logout', [EmprendedorAuthController::class, 'logout'])->name('emprendedor.logout');

    Route::get('/categorias', [AdminController::class, 'categoriasIndex'])->name('admin.categorias.index');
    Route::get('/categorias/crear', [AdminController::class, 'categoriasCreate'])->name('admin.categorias.create');
    Route::post('/categorias', [AdminController::class, 'categoriasStore'])->name('admin.categorias.store');
    Route::get('/categorias/{id}/editar', [AdminController::class, 'categoriasEdit'])->name('admin.categorias.edit');
    Route::put('/categorias/{id}', [AdminController::class, 'categoriasUpdate'])->name('admin.categorias.update');
    Route::delete('/categorias/{id}', [AdminController::class, 'categoriasDestroy'])->name('admin.categorias.destroy');
});