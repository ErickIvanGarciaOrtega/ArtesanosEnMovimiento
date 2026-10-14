@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark mb-0">Panel de Administración</h2>
    <span class="badge bg-danger fs-6"><i class="bi bi-shield-lock-fill me-1"></i> Rol: Administrador</span>
</div>

<p class="text-muted mb-4">Bienvenido al sistema de gestión central. Selecciona una sección para comenzar:</p>

<div class="row g-4 mb-4">
    {{-- Tarjeta Usuarios / Emprendedores --}}
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100 p-3">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded p-3 me-3">
                    <i class="bi bi-people fs-2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Usuarios</h5>
                    <p class="text-muted small mb-0">Administra las cuentas de administradores y emprendedores.</p>
                </div>
            </div>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-primary mt-auto w-100">
                Gestionar Usuarios <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    {{-- Tarjeta Categorías --}}
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100 p-3">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-info text-white rounded p-3 me-3">
                    <i class="bi bi-grid-fill fs-2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Categorías</h5>
                    <p class="text-muted small mb-0">Administra las categorías globales para los productos del catálogo.</p>
                </div>
            </div>
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-info mt-auto w-100">
                Gestionar Categorías <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection