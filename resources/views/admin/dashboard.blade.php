@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard Principal
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <span class="badge bg-primary fs-6">
            <i class="bi bi-person-check-fill me-1"></i> Rol: {{ ucfirst(auth()->user()->role) }}
        </span>
    </div>
</div>

{{-- Tarjetas de Métricas Generales --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small fw-bold text-white-50">Emprendedores</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalEmprendedores }}</h2>
                </div>
                <i class="bi bi-person-badge fs-1 text-white-50"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small fw-bold text-white-50">Marcas Registradas</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalMarcas }}</h2>
                </div>
                <i class="bi bi-tags fs-1 text-white-50"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-warning text-dark h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small fw-bold text-black-50">Productos Publicados</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalProductos }}</h2>
                </div>
                <i class="bi bi-box-seam fs-1 text-black-50"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small fw-bold text-white-50">Usuarios Registrados</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalUsuarios }}</h2>
                </div>
                <i class="bi bi-people fs-1 text-white-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- Accesos Rápidos y Bienvenida --}}
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="bi bi-rocket-takeoff me-2 text-primary"></i>Gestión Rápida
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Selecciona una de las siguientes opciones para comenzar a administrar el contenido de la plataforma:</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-outline-primary w-100 py-3 text-start d-flex align-items-center">
                            <i class="bi bi-person-plus fs-3 me-3"></i>
                            <div>
                                <div class="fw-bold">Nuevo Usuario / Emprendedor</div>
                                <small class="text-muted">Registrar un nuevo acceso al sistema</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary w-100 py-3 text-start d-flex align-items-center">
                            <i class="bi bi-people-fill fs-3 me-3"></i>
                            <div>
                                <div class="fw-bold">Ver Todos los Usuarios</div>
                                <small class="text-muted">Gestionar roles, datos y estados</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="bi bi-info-circle me-2 text-info"></i>Información
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Plataforma:</strong> Colectivo Artesanal</p>
                <p class="mb-2"><strong>Administrador:</strong> {{ auth()->user()->name }}</p>
                <p class="mb-0"><strong>Correo:</strong> {{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</div>
@endsection