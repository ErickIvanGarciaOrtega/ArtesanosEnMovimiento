@extends('layouts.public')

@section('content')

<section class="py-5">
    <div class="container">
        <!-- Migas de pan (Breadcrumb) -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.productos') }}" class="text-muted text-decoration-none">Productos</a></li>
                <li class="breadcrumb-item active text-brand fw-bold" aria-current="page">{{ $producto->nombre }}</li>
            </ol>
        </nav>

        <div class="row g-5">
<!-- Imagen del Producto -->
<div class="col-md-6">
    <div class="bg-white border rounded-0 p-3 position-relative d-flex align-items-center justify-content-center" style="min-height: 400px;">
        
        @if($producto->imagen)
            <!-- Muestra la imagen real -->
            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-fluid w-100 h-100" style="object-fit: cover; max-height: 500px;">
        @else
            <!-- Placeholder si no hay imagen -->
            <div class="bg-light border d-flex align-items-center justify-content-center w-100 h-100" style="min-height: 380px;">
                <i class="bi bi-image text-muted fs-1"></i>
            </div>
        @endif

        @if($producto->marca)
            <span class="position-absolute top-0 start-0 bg-dark text-white small fw-bold px-3 py-1 m-4">
                {{ $producto->marca->nombre }}
            </span>
        @endif
    </div>
</div>

            <!-- Detalles del Producto -->
            <div class="col-md-6">
                <div class="ps-md-4">
                    <span class="badge bg-brand text-white fw-normal mb-2 px-3 py-2 fs-6">
                        <i class="bi bi-tag me-1"></i>{{ $producto->categoria->nombre ?? 'Sin categoría' }}
                    </span>

                    <h1 class="fw-bold text-dark mb-3 display-6">{{ $producto->nombre }}</h1>

                    <p class="text-accent fw-bold display-6 mb-4">
                        ${{ number_format($producto->precio, 2) }} <span class="fs-5 text-muted fw-normal">MXN</span>
                    </p>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">Descripción del Producto</h6>
                        <p class="text-muted leading-relaxed">
                            {{ $producto->descripcion ?: 'Texto de ejemplo (Sin descripción registrada por el creador).' }}
                        </p>
                    </div>

                    <!-- Ficha del Creador/Emprendedor -->
                    <div class="p-4 bg-white border rounded-0 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light border rounded-circle me-3 flex-shrink-0" style="width: 60px; height: 60px;"></div>
                            <div>
                                <span class="text-uppercase tracking-wider text-muted small d-block">Elaborado por</span>
                                <h6 class="fw-bold text-dark mb-0">{{ $producto->emprendedor->user->name ?? 'Texto de ejemplo' }}</h6>
                                <p class="small text-brand mb-0">{{ $producto->emprendedor->categoria_principal ?? 'Artesano' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('public.emprendedor.detalle', $producto->emprendedor->id) }}" class="btn btn-outline-dark btn-sm rounded-0 w-100 mt-3 fw-bold">
                            Ver Perfil del Emprendedor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Productos Relacionados -->
@if($productosRelacionados->count() > 0)
<section class="py-5 bg-white border-top">
    <div class="container">
        <h4 class="fw-bold text-dark mb-4">Otros productos que te podrían interesar</h4>
        <div class="row g-4">
            @foreach($productosRelacionados as $rel)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 rounded-0 h-100 bg-transparent">
                        <div class="bg-light border mb-3 position-relative" style="height: 220px; width: 100%;"></div>
                        <div class="card-body p-0">
                            <h6 class="fw-bold text-dark mb-1 text-truncate">
                                <a href="{{ route('public.producto.detalle', $rel->id) }}" class="text-decoration-none text-dark">
                                    {{ $rel->nombre }}
                                </a>
                            </h6>
                            <p class="text-accent fw-bold">${{ number_format($rel->precio, 2) }} MXN</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection