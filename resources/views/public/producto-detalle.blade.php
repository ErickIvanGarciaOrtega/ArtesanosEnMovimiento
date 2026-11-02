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
            <!-- Columna del Carrusel de Imágenes -->
            <div class="col-md-6">
                <div class="bg-white border rounded-0 p-1 position-relative d-flex align-items-center justify-content-center" style="min-height: 400px;">
                    
                    @if($producto->imagenes->count() > 0)
                        <!-- Carrusel Bootstrap -->
                        <div id="carruselProducto{{ $producto->id }}" class="carousel slide w-100 h-100" data-bs-ride="carousel">
                            
                            <!-- Indicadores -->
                            @if($producto->imagenes->count() > 1)
                                <div class="carousel-indicators">
                                    @foreach($producto->imagenes as $index => $img)
                                        <button type="button" data-bs-target="#carruselProducto{{ $producto->id }}" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Imágenes del Carrusel -->
                            <div class="carousel-inner h-100 bg-light">
                                @foreach($producto->imagenes as $index => $img)
                                    <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $img->ruta) }}" class="d-block w-100 h-100" style="object-fit: contain; max-height: 500px;" alt="{{ $producto->nombre }}">
                                    </div>
                                @endforeach
                            </div>

                            <!-- Controles Anterior/Siguiente -->
                            @if($producto->imagenes->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carruselProducto{{ $producto->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2 shadow" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carruselProducto{{ $producto->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2 shadow" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            @endif
                        </div>
                    @else
                        <!-- Placeholder si no hay imagen -->
                        <div class="bg-light border d-flex align-items-center justify-content-center w-100 h-100" style="min-height: 380px;">
                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                        </div>
                    @endif

                    <!-- Etiqueta de Marca -->
                    @if($producto->marca)
                        <span class="position-absolute top-0 start-0 bg-dark text-white small fw-bold px-3 py-1 m-3 z-3 shadow-sm">
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
                            {{ $producto->descripcion ?: 'Este producto no cuenta con una descripción registrada por el creador.' }}
                        </p>
                    </div>

                    <!-- Ficha del Creador/Emprendedor -->
                    <div class="p-4 bg-white border rounded-0 mb-4 shadow-sm">
                        <div class="d-flex align-items-center">
                            
                            <!-- Foto Dinámica del Emprendedor -->
                            @if(isset($producto->emprendedor->imagen) && $producto->emprendedor->imagen)
                                <img src="{{ asset('storage/' . $producto->emprendedor->imagen) }}" alt="{{ $producto->emprendedor->user->name }}" class="border rounded-circle me-3 flex-shrink-0" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light border rounded-circle me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-person text-muted fs-4"></i>
                                </div>
                            @endif

                            <div>
                                <span class="text-uppercase tracking-wider text-muted small d-block">Elaborado por</span>
                                <h6 class="fw-bold text-dark mb-0">{{ $producto->emprendedor->user->name ?? 'Emprendedor' }}</h6>
                                <p class="small text-brand mb-0">{{ $producto->emprendedor->categoria_principal ?? 'Artesano Local' }}</p>
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
                        <a href="{{ route('public.producto.detalle', $rel->id) }}" class="text-decoration-none">
                            <div class="bg-light border mb-3 position-relative" style="height: 220px; width: 100%;">
                                <!-- Mostrar primera imagen del producto relacionado -->
                                @if($rel->imagenes->count() > 0)
                                    <img src="{{ asset('storage/' . $rel->imagenes->first()->ruta) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $rel->nombre }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted fs-3"></i>
                                    </div>
                                @endif
                            </div>
                        </a>
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