@extends('layouts.public')

@section('content')

<!-- Perfil Header -->
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-4 mb-md-0">
                <!-- Contenedor de foto dinámica -->
                @if(isset($emprendedor->imagen) && $emprendedor->imagen)
                    <img src="{{ asset('storage/' . $emprendedor->imagen) }}" alt="{{ $emprendedor->user->name ?? 'Perfil' }}" class="rounded-circle border shadow-sm mx-auto" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-light border rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm" style="width: 150px; height: 150px;">
                        <i class="bi bi-person text-muted" style="font-size: 4rem;"></i>
                    </div>
                @endif
            </div>
            
            <div class="col-md-9">
                <span class="text-uppercase tracking-wider fw-semibold mb-2 d-block text-muted small" style="letter-spacing: 2px;">Perfil del Emprendedor</span>
                <h1 class="fw-bold text-brand mb-2">{{ $emprendedor->user->name ?? 'Emprendedor' }}</h1>
                
                @if($emprendedor->categoria_principal)
                    <p class="text-muted mb-3"><i class="bi bi-tag me-1 text-brand"></i>{{ $emprendedor->categoria_principal }}</p>
                @endif
                
                <p class="text-dark mb-4" style="max-width: 800px;">
                    {{ $emprendedor->biografia ?? 'Este creador aún no ha agregado una biografía a su perfil.' }}
                </p>
                
                <!-- Redes Sociales (Ocultas si están vacías) -->
                @if($emprendedor->facebook || $emprendedor->instagram || $emprendedor->tiktok)
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
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Productos del Emprendedor -->
<section class="py-5">
    <div class="container">
        <h3 class="fw-bold text-dark mb-4">Productos de este Creador</h3>
        <div class="row g-4">
            @forelse($emprendedor->productos as $producto)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 rounded-0 h-100 bg-transparent">
                        <a href="{{ route('public.producto.detalle', $producto->id) }}" class="text-decoration-none">
                            <div class="bg-light border mb-3 position-relative" style="height: 250px; width: 100%;">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted fs-3"></i>
                                    </div>
                                @endif

                                @if($producto->marca)
                                    <span class="position-absolute bottom-0 start-0 bg-white text-dark small fw-bold px-2 py-1 m-2 shadow-sm">
                                        {{ $producto->marca->nombre }}
                                    </span>
                                @endif
                            </div>
                        </a>
                        <div class="card-body p-0">
                            <h6 class="fw-bold text-dark mb-1 text-truncate">
                                <a href="{{ route('public.producto.detalle', $producto->id) }}" class="text-decoration-none text-dark">
                                    {{ $producto->nombre }}
                                </a>
                            </h6>
                            <p class="small text-brand mb-2"><i class="bi bi-tag me-1"></i>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                            <p class="text-accent fw-bold fs-5">${{ number_format($producto->precio, 2) }} <span class="fs-6 text-muted fw-normal">MXN</span></p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Este creador aún no tiene productos publicados en el catálogo.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection