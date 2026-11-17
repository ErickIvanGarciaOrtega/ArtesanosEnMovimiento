<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\EmprendedorAuthController;
use App\Http\Controllers\EmprendedorController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/productos', [PublicController::class, 'productos'])->name('public.productos');
Route::get('/emprendedores', [PublicController::class, 'emprendedores'])->name('public.emprendedores');
Route::get('/quienes-somos', [PublicController::class, 'quienesSomos'])->name('public.quienes_somos');
Route::get('/contacto', [PublicController::class, 'contacto'])->name('public.contacto');

// NUEVA RUTA PÚBLICA: Detalle de Producto (Fuera de cualquier middleware)
Route::get('/producto/{id}', [PublicController::class, 'productoDetalle'])
    ->name('public.producto.detalle')
    ->where('id', '[0-9]+');

Route::get('/emprendedor/{id}', [PublicController::class, 'emprendedorPerfil'])
    ->name('public.emprendedor.detalle')
    ->where('id', '[0-9]+');

Route::middleware('guest:web')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:login')->name('login.post');
    Route::get('/emprendedor/login', [EmprendedorAuthController::class, 'showLoginForm'])->name('emprendedor.login');
    Route::post('/emprendedor/login', [EmprendedorAuthController::class, 'login'])->middleware('throttle:login')->name('emprendedor.login.post');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:password-recovery')->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->middleware('throttle:password-recovery')->name('password.update');
});

Route::middleware(['auth:web', 'auth.session'])->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::post('/emprendedor/logout', [EmprendedorAuthController::class, 'logout'])->name('emprendedor.logout');

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/emprendedores', function () {
            return redirect()->route('admin.usuarios.index');
        })->name('admin.emprendedores');
        Route::get('/usuarios', [AdminController::class, 'usuariosIndex'])->name('admin.usuarios.index');
        Route::get('/usuarios/crear', [AdminController::class, 'usuariosCreate'])->name('admin.usuarios.create');
        Route::post('/usuarios', [AdminController::class, 'usuariosStore'])->name('admin.usuarios.store');
        Route::get('/usuarios/{id}/editar', [AdminController::class, 'usuariosEdit'])->whereNumber('id')->name('admin.usuarios.edit');
        Route::put('/usuarios/{id}', [AdminController::class, 'usuariosUpdate'])->whereNumber('id')->name('admin.usuarios.update');
        Route::delete('/usuarios/{id}', [AdminController::class, 'usuariosDestroy'])->whereNumber('id')->name('admin.usuarios.destroy');
        // Módulo: Gestión de Categorías Globales (CRUD)
        Route::get('/categorias', [AdminController::class, 'categoriasIndex'])->name('admin.categorias.index');
        Route::get('/categorias/crear', [AdminController::class, 'categoriasCreate'])->name('admin.categorias.create');
        Route::post('/categorias', [AdminController::class, 'categoriasStore'])->name('admin.categorias.store');
        Route::get('/categorias/{id}/editar', [AdminController::class, 'categoriasEdit'])->name('admin.categorias.edit');
        Route::put('/categorias/{id}', [AdminController::class, 'categoriasUpdate'])->name('admin.categorias.update');
        Route::delete('/categorias/{id}', [AdminController::class, 'categoriasDestroy'])->name('admin.categorias.destroy');

        // Módulo: Gestión de Contenidos (CMS)
        Route::get('/configuraciones', [AdminController::class, 'configuracionesIndex'])->name('admin.configuraciones.index');
        Route::put('/configuraciones', [AdminController::class, 'configuracionesUpdate'])->name('admin.configuraciones.update');
    });

    Route::middleware('role:emprendedor')->prefix('emprendedor')->group(function () {
        Route::view('/dashboard', 'emprendedores.dashboard')->name('emprendedor.dashboard');
        Route::get('/perfil', [EmprendedorController::class, 'perfil'])->name('emprendedor.perfil');
        Route::put('/perfil', [EmprendedorController::class, 'perfilUpdate'])->name('emprendedor.perfil.update');
        Route::get('/marcas', [EmprendedorController::class, 'marcasIndex'])->name('emprendedor.marcas.index');
        Route::get('/marcas/crear', [EmprendedorController::class, 'marcasCreate'])->name('emprendedor.marcas.create');
        Route::post('/marcas', [EmprendedorController::class, 'marcasStore'])->name('emprendedor.marcas.store');
        Route::get('/productos', [EmprendedorController::class, 'productosIndex'])->name('emprendedor.productos.index');
        Route::get('/productos/crear', [EmprendedorController::class, 'productosCreate'])->name('emprendedor.productos.create');
        Route::post('/productos', [EmprendedorController::class, 'productosStore'])->name('emprendedor.productos.store');
        Route::get('/marcas/{id}/editar', [EmprendedorController::class, 'marcasEdit'])->name('emprendedor.marcas.edit');
        Route::put('/marcas/{id}', [EmprendedorController::class, 'marcasUpdate'])->name('emprendedor.marcas.update');
        Route::delete('/marcas/{id}', [EmprendedorController::class, 'marcasDestroy'])->name('emprendedor.marcas.destroy');
        Route::get('/productos/{id}/editar', [EmprendedorController::class, 'productosEdit'])->name('emprendedor.productos.edit');
        Route::put('/productos/{id}', [EmprendedorController::class, 'productosUpdate'])->name('emprendedor.productos.update');
        Route::delete('/productos/{id}', [EmprendedorController::class, 'productosDestroy'])->name('emprendedor.productos.destroy');
        Route::delete('/productos/imagen/{id}', [EmprendedorController::class, 'eliminarImagen'])->name('emprendedor.productos.imagen.eliminar');
    });
});
