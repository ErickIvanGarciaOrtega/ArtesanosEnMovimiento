@extends('layouts.public')

@section('content')

<!-- Perfil Header -->
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-4 mb-md-0">
                <!-- Contenedor de foto vacío -->
                <div class="bg-light border rounded-circle mx-auto" style="width: 150px; height: 150px;"></div>
            </div>
            <div class="col-md-9">
                <span class="text-uppercase tracking-wider fw-semibold mb-2 d-block text-muted small" style="letter-spacing: 2px;">Texto de ejemplo</span>
                <h1 class="fw-bold text-brand mb-2">{{ $emprendedor->user->name ?? 'Texto de ejemplo' }}</h1>
                <p class="text-muted mb-3"><i class="bi bi-tag me-1 text-brand"></i>{{ $emprendedor->categoria_principal ?? 'Texto de ejemplo' }}</p>
                <p class="text-dark mb-4" style="max-width: 800px;">
                    {{ $emprendedor->biografia ?? 'Texto de ejemplo' }}
                </p>
                <!-- Redes Sociales -->
                <div class="d-flex gap-3">
                    @if($emprendedor->facebook)
                        <a href="{{ $emprendedor->facebook }}" target="_blank" class="text-dark fs-5"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if($emprendedor->instagram)
                        <a href="{{ $emprendedor->instagram }}" target="_blank" class="text-dark fs-5"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if($emprendedor->tiktok)
                        <a href="{{ $emprendedor->tiktok }}" target="_blank" class="text-dark fs-5"><i class="bi bi-tiktok"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Productos del Emprendedor -->
<section class="py-5">
    <div class="container">
        <h3 class="fw-bold text-dark mb-4">Texto de ejemplo (Productos de este Creador)</h3>
        <div class="row g-4">
            @forelse($emprendedor->productos as $producto)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 rounded-0 h-100 bg-transparent">
                        <div class="bg-light border mb-3 position-relative" style="height: 250px; width: 100%;">
                            @if($producto->marca)
                                <span class="position-absolute bottom-0 start-0 bg-white text-dark small fw-bold px-2 py-1 m-2 shadow-sm">
                                    {{ $producto->marca->nombre }}
                                </span>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $producto->nombre }}</h6>
                            <p class="small text-brand mb-2"><i class="bi bi-tag me-1"></i>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                            <p class="text-accent fw-bold fs-5">${{ number_format($producto->precio, 2) }} <span class="fs-6 text-muted fw-normal">MXN</span></p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Texto de ejemplo (Este emprendedor aún no tiene productos publicados).</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection