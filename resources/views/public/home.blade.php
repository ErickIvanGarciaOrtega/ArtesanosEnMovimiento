@extends('layouts.public')

@section('content')

<!-- Hero Banner -->
<section class="position-relative d-flex align-items-center justify-content-center text-center py-5" style="min-height: 50vh; background-color: #e9edc9;">
    <div class="container py-5">
        <span class="text-uppercase tracking-wider fw-semibold mb-2 d-block text-muted" style="letter-spacing: 2px;">Texto de ejemplo</span>
        <h1 class="display-4 fw-bold mb-3 text-brand">Texto de ejemplo</h1>
        <p class="lead mb-4 mx-auto text-muted" style="max-width: 600px;">Texto de ejemplo</p>
        <a href="#" class="btn bg-brand text-white rounded-0 px-4 border-0">Texto de ejemplo</a>
    </div>
</section>

<!-- Sección Técnicas / Categorías -->
<section class="py-5 my-4">
    <div class="container text-center">
        <h2 class="fw-bold mb-5 text-dark">Texto de ejemplo</h2>
        <div class="row g-4 justify-content-center">
            @forelse($categorias as $cat)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="#" class="text-decoration-none text-dark">
                        <div class="card border-0 bg-transparent text-center h-100">
                            <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 90px; height: 90px; border: 1px solid #eaeaea;">
                                <i class="bi bi-stars fs-3 text-brand"></i>
                            </div>
                            <!-- Información dinámica de la categoría creada por el Admin -->
                            <h6 class="fw-bold text-dark">{{ $cat->nombre }}</h6>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-muted">Texto de ejemplo</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Sección Productos Destacados -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Texto de ejemplo</h2>
                <p class="text-muted mb-0">Texto de ejemplo</p>
            </div>
            <a href="#" class="text-decoration-none text-brand fw-bold border-bottom border-brand pb-1">Texto de ejemplo</a>
        </div>

        <div class="row g-4">
            @forelse($productosDestacados as $producto)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 rounded-0 h-100 bg-transparent">
                        <!-- Contenedor de imagen vacío (sin foto genérica) -->
                        <div class="bg-light border mb-3 position-relative" style="height: 250px; width: 100%;">
                            @if($producto->marca)
                                <!-- Información dinámica de la marca -->
                                <span class="position-absolute bottom-0 start-0 bg-white text-dark small fw-bold px-2 py-1 m-2 shadow-sm">
                                    {{ $producto->marca->nombre }}
                                </span>
                            @endif
                        </div>
                        <!-- Información dinámica del producto -->
                        <div class="card-body p-0">
                            <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $producto->nombre }}</h6>
                            <p class="small text-muted mb-2">Por {{ $producto->emprendedor->user->name ?? 'Texto de ejemplo' }}</p>
                            <p class="text-accent fw-bold fs-5">${{ number_format($producto->precio, 2) }} <span class="fs-6 text-muted fw-normal">MXN</span></p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Texto de ejemplo</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Banner con espacio de foto vacío -->
<section class="py-5 bg-light my-5">
    <div class="container">
        <div class="row align-items-center">
            <!-- Espacio de la foto dejado completamente vacío -->
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="bg-white border rounded-0" style="height: 380px; width: 100%;"></div>
            </div>
            <div class="col-md-5 offset-md-1">
                <h2 class="fw-bold mb-4">Texto de ejemplo</h2>
                <p class="text-muted mb-4 lead fs-6">Texto de ejemplo</p>
                <a href="#" class="btn btn-outline-dark rounded-0 px-4 py-2 fw-bold">Texto de ejemplo</a>
            </div>
        </div>
    </div>
</section>

@endsection