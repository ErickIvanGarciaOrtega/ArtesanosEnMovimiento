@extends('layouts.public')

@section('content')

<!-- Encabezado Dinámico -->
<section class="py-5 bg-white border-bottom text-center">
    <div class="container py-4">
        <span class="text-uppercase tracking-wider fw-semibold mb-2 d-block text-muted" style="letter-spacing: 2px;">
            {{ $config['productos_subtitulo'] ?? 'Catálogo' }}
        </span>
        <h1 class="fw-bold mb-3 text-brand">
            {{ $config['productos_titulo'] ?? 'Nuestros Productos' }}
        </h1>
        <p class="text-muted mx-auto mb-4" style="max-width: 600px;">
            {{ $config['productos_descripcion'] ?? 'Explora las piezas artesanales elaboradas por nuestros creadores.' }}
        </p>

        <!-- Filtros y Búsqueda -->
        <form action="{{ route('public.productos') }}" method="GET" class="row g-3 justify-content-center mt-4">
            <div class="col-md-4">
                <input type="text" name="buscar" class="form-control rounded-0" placeholder="Buscar..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="categoria_id" class="form-select rounded-0">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="marca_id" class="form-select rounded-0">
                    <option value="">Todas las marcas</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}" {{ request('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn bg-brand text-white rounded-0 w-100 fw-bold border-0">Filtrar</button>
            </div>
        </form>
    </div>
</section>

<!-- Listado de Productos -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($productos as $producto)
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
                                        {{ $producto->emprendedor->user->name ?? 'Emprendedor' }}
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

@endsection