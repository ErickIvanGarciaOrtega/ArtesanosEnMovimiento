@extends('layouts.public')

@section('content')

<!-- Encabezado Dinámico -->
<section class="py-5 bg-white border-bottom text-center">
    <div class="container py-4">
        <span class="text-uppercase tracking-wider fw-semibold mb-2 d-block text-muted" style="letter-spacing: 2px;">
            {{ $config['emprendedores_subtitulo'] ?? 'Nuestros Creadores' }}
        </span>
        <h1 class="fw-bold mb-3 text-brand">
            {{ $config['emprendedores_titulo'] ?? 'Directorio de Emprendedores' }}
        </h1>
        <p class="text-muted mx-auto mb-4" style="max-width: 600px;">
            {{ $config['emprendedores_descripcion'] ?? 'Conoce a las manos talentosas detrás de cada producto.' }}
        </p>
    </div>
</section>

<!-- Listado de Emprendedores -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($emprendedores as $emprendedor)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-0 p-4 h-100 bg-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center me-3" style="width: 70px; height: 70px; min-width: 70px;">
                                <i class="bi bi-person text-muted fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $emprendedor->user->name ?? 'Emprendedor' }}</h5>
                                <p class="small text-brand mb-0"><i class="bi bi-tag me-1"></i>{{ $emprendedor->marca->nombre ?? 'Sin marca' }}</p>
                            </div>
                        </div>
                        <p class="text-muted small mb-4">{{ $emprendedor->descripcion ?? 'Sin descripción disponible.' }}</p>
                        
                        <!-- BOTÓN CORREGIDO -->
                        <div class="mt-auto">
                            <a href="{{ route('public.emprendedor.detalle', $emprendedor->id) }}" class="btn btn-outline-dark rounded-0 w-100 fw-bold">
                                Ver Perfil
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Aún no hay emprendedores registrados.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection