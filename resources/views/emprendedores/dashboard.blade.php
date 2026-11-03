@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--huasteca-verde);">Bienvenido a tu panel de control!</h2>
            <p class="text-muted mb-0">Desde aquí podrás administrar tu perfil, registrar tus marcas y publicar tus productos en el colectivo artesanal.</p>
        </div>
        <span class="badge bg-primary rounded-0 px-3 py-2 fs-6 shadow-sm"><i class="bi bi-person-fill me-1"></i> Rol: Emprendedor</span>
    </div>

    <div class="row g-4 mt-2">
        <!-- Tarjeta Mi Perfil -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-0 h-100" style="border-top: 4px solid var(--huasteca-verde) !important;">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-gear fs-2" style="color: var(--huasteca-verde);"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Mi Perfil</h5>
                    <p class="text-muted small mb-4">Gestiona tu nombre comercial, biografía y redes sociales.</p>
                    <a href="{{ route('emprendedor.perfil') }}" class="btn btn-outline-secondary rounded-0 w-100">Editar Perfil <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Tarjeta Mis Marcas -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-0 h-100" style="border-top: 4px solid var(--huasteca-amarillo) !important;">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-tags fs-2" style="color: var(--huasteca-amarillo);"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Mis Marcas</h5>
                    <p class="text-muted small mb-4">Registra y administra las marcas de tus productos artesanos.</p>
                    <a href="{{ route('emprendedor.marcas.index') }}" class="btn btn-outline-secondary rounded-0 w-100">Gestionar Marcas <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Tarjeta Mis Productos -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-0 h-100" style="border-top: 4px solid var(--huasteca-magenta) !important;">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-box-seam fs-2" style="color: var(--huasteca-magenta);"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Mis Productos</h5>
                    <p class="text-muted small mb-4">Publica nuevos artículos, asigna precios y gestiona tu catálogo.</p>
                    <a href="{{ route('emprendedor.productos.index') }}" class="btn btn-outline-secondary rounded-0 w-100">Gestionar Productos <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection