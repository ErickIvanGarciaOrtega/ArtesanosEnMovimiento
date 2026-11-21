@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1" style="color: var(--huasteca-verde);">Bienvenido a tu panel de control</h1>
            <p class="text-muted mb-0">Desde aquí podrás administrar tu perfil, registrar tus marcas y publicar tus productos en el colectivo artesanal.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            @if(Auth::user()->emprendedor)
                <a href="{{ route('public.emprendedor.detalle', Auth::user()->emprendedor->id) }}" target="_blank" class="btn btn-outline-success rounded-0 fw-bold" aria-label="Ver mi perfil público en el directorio">
                    <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i> Ver mi perfil público
                </a>
            @endif
            <span class="badge text-white rounded-0 px-3 py-2 fs-6 shadow-sm" style="background-color: var(--huasteca-verde);">
                <i class="bi bi-person-fill me-1" aria-hidden="true"></i> Rol: Emprendedor
            </span>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Tarjeta Mi Perfil -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-0 h-100" style="border-top: 4px solid var(--huasteca-verde) !important;">
                <div class="card-body d-flex flex-column text-center p-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-gear fs-2" style="color: var(--huasteca-verde);" aria-hidden="true"></i>
                    </div>
                    <h2 class="h5 fw-bold text-dark">Mi Perfil</h2>
                    <p class="text-muted small mb-4">Gestiona tu nombre comercial, biografía y redes sociales.</p>
                    <div class="mt-auto">
                        <a href="{{ route('emprendedor.perfil') }}" class="btn btn-outline-dark rounded-0 w-100 fw-bold">
                            Editar Perfil <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Mis Marcas -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-0 h-100" style="border-top: 4px solid var(--huasteca-amarillo) !important;">
                <div class="card-body d-flex flex-column text-center p-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-tags fs-2" style="color: var(--huasteca-texto);" aria-hidden="true"></i>
                    </div>
                    <h2 class="h5 fw-bold text-dark">Mis Marcas</h2>
                    <p class="text-muted small mb-4">Registra y administra las marcas de tus productos artesanos.</p>
                    <div class="mt-auto">
                        <a href="{{ route('emprendedor.marcas.index') }}" class="btn btn-outline-dark rounded-0 w-100 fw-bold">
                            Gestionar Marcas <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Mis Productos -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-0 h-100" style="border-top: 4px solid var(--huasteca-magenta) !important;">
                <div class="card-body d-flex flex-column text-center p-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-box-seam fs-2" style="color: var(--huasteca-magenta);" aria-hidden="true"></i>
                    </div>
                    <h2 class="h5 fw-bold text-dark">Mis Productos</h2>
                    <p class="text-muted small mb-4">Publica nuevos artículos, asigna precios y gestiona tu catálogo.</p>
                    <div class="mt-auto">
                        <a href="{{ route('emprendedor.productos.index') }}" class="btn btn-outline-dark rounded-0 w-100 fw-bold">
                            Gestionar Productos <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection