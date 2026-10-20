@extends('layouts.public')

@section('content')

@php
    $bgImage = isset($config['home_banner_image']) ? asset('storage/' . $config['home_banner_image']) : '';
    $bgStyle = $bgImage 
        ? "background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{$bgImage}') center/cover no-repeat;" 
        : "background-color: #e9edc9;";
@endphp

<!-- Hero Banner -->
<section class="position-relative d-flex align-items-center justify-content-center text-center py-5" style="min-height: 50vh; {{ $bgStyle }}">
    <div class="container py-5">
        
        <span class="text-uppercase tracking-wider fw-semibold mb-2 d-block {{ $bgImage ? 'text-light' : 'text-muted' }}" style="letter-spacing: 2px;">
            {{ $config['home_subtitulo'] ?? 'Descubre el arte de nuestra tierra' }}
        </span>
        
        <h1 class="display-4 fw-bold mb-3 {{ $bgImage ? 'text-white' : 'text-brand' }}">
            {{ $config['home_titulo'] ?? 'Artesanías de la Huasteca Potosina' }}
        </h1>
        
        <p class="lead mb-4 mx-auto {{ $bgImage ? 'text-light' : 'text-muted' }}" style="max-width: 600px;">
            {{ $config['home_descripcion'] ?? 'Apoya a los artesanos locales y lleva un pedazo de nuestra cultura a tu hogar.' }}
        </p>
        
        <a href="{{ route('public.productos') }}" class="btn bg-brand text-white rounded-0 px-4 border-0">Ver Catálogo</a>
    </div>
</section>

<!-- Sección Técnicas / Categorías -->
<section class="py-5 my-4">
    <div class="container text-center">
        <!-- Vuelve a ser texto estático -->
        <h2 class="fw-bold mb-5 text-dark">Explora nuestras categorías</h2>
        
        <div class="row g-4 justify-content-center">
            @forelse($categorias as $cat)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('public.productos', ['categoria_id' => $cat->id]) }}" class="text-decoration-none text-dark">
                        <div class="card border-0 bg-transparent text-center h-100">
                            <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 90px; height: 90px; border: 1px solid #eaeaea;">
                                <i class="bi bi-stars fs-3 text-brand"></i>
                            </div>
                            <h6 class="fw-bold text-dark">{{ $cat->nombre }}</h6>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-muted">Aún no hay categorías registradas.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Sección Productos Destacados -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <!-- Vuelven a ser textos estáticos -->
                <h2 class="fw-bold text-dark mb-1">Productos Destacados</h2>
                <p class="text-muted mb-0">Lo más reciente de nuestros creadores</p>
            </div>
            <a href="{{ route('public.productos') }}" class="text-decoration-none text-brand fw-bold border-bottom border-brand pb-1">Ver Catálogo Completo</a>
        </div>

        <div class="row g-4">
            @forelse($productosDestacados as $producto)
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
                            <p class="small text-muted mb-2">
                                Por 
                                @if($producto->emprendedor)
                                    <a href="{{ route('public.emprendedor.detalle', $producto->emprendedor->id) }}" class="text-decoration-none text-muted">
                                        {{ $producto->emprendedor->user->name ?? 'Emprendedor Anónimo' }}
                                    </a>
                                @else
                                    Emprendedor Anónimo
                                @endif
                            </p>
                            <p class="text-accent fw-bold fs-5">${{ number_format($producto->precio, 2) }} <span class="fs-6 text-muted fw-normal">MXN</span></p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Aún no hay productos disponibles.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Banner promocional -->
<section class="py-5 bg-light my-5">
    <div class="container">
        <div class="row align-items-center">
            
            <!-- Columna de la Imagen -->
            <div class="col-md-6 mb-4 mb-md-0">
                @if(isset($config['home_promo_image']))
                    <img src="{{ asset('storage/' . $config['home_promo_image']) }}" alt="Promocional" class="img-fluid border rounded-0 w-100" style="height: 380px; object-fit: cover;">
                @else
                    <div class="bg-white border rounded-0 d-flex align-items-center justify-content-center" style="height: 380px; width: 100%;">
                        <i class="bi bi-shop fs-1 text-muted"></i>
                    </div>
                @endif
            </div>

            <!-- Columna de los Textos -->
            <div class="col-md-5 offset-md-1">
                <h2 class="fw-bold mb-4">
                    {{ $config['home_promo_titulo'] ?? 'Descubre el talento de nuestra región' }}
                </h2>
                <p class="text-muted mb-4 lead fs-6">
                    {{ $config['home_promo_descripcion'] ?? 'Conoce a los creadores detrás de cada pieza y apoya el comercio local.' }}
                </p>
                <a href="{{ route('public.emprendedores') }}" class="btn btn-outline-dark rounded-0 px-4 py-2 fw-bold">
                    {{ $config['home_promo_boton'] ?? 'Directorio de Emprendedores' }}
                </a>
            </div>
            
        </div>
    </div>
</section>

@endsection