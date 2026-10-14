@extends('layouts.emprendedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark mb-0">¡Bienvenido a tu panel de control!</h2>
    <span class="badge bg-primary fs-6">
        <i class="bi bi-person-check-fill me-1"></i> Rol: Emprendedor
    </span>
</div>

<p class="text-muted mb-4">Desde aquí podrás administrar tu perfil, registrar tus marcas y publicar tus productos en el colectivo artesanal.</p>

<div class="row g-4 mb-4">
    {{-- Tarjeta Mi Perfil --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 p-3">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded p-3 me-3">
                    <i class="bi bi-person-gear fs-2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Mi Perfil</h5>
                    <p class="text-muted small mb-0">Gestiona tu nombre comercial, biografía y redes sociales.</p>
                </div>
            </div>
            <a href="{{ route('emprendedor.perfil') }}" class="btn btn-outline-primary mt-auto w-100">
                Editar Perfil <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    {{-- Tarjeta Mis Marcas --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 p-3">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-success text-white rounded p-3 me-3">
                    <i class="bi bi-tags fs-2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Mis Marcas</h5>
                    <p class="text-muted small mb-0">Registra y administra las marcas de tus productos artesanos.</p>
                </div>
            </div>
            <a href="{{ route('emprendedor.marcas.index') }}" class="btn btn-outline-success mt-auto w-100">
                Gestionar Marcas <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    {{-- Tarjeta Mis Productos --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 p-3">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-warning text-dark rounded p-3 me-3">
                    <i class="bi bi-box-seam fs-2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Mis Productos</h5>
                    <p class="text-muted small mb-0">Publica nuevos artículos, asigna precios y gestiona tu catálogo.</p>
                </div>
            </div>
            <a href="{{ route('emprendedor.productos.index') }}" class="btn btn-outline-warning mt-auto w-100 text-dark">
                Gestionar Productos <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection