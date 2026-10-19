@extends('layouts.public')

@section('content')

<!-- Encabezado -->
<section class="py-5 bg-white border-bottom text-center">
    <div class="container py-4">
        <span class="text-uppercase tracking-wider fw-semibold mb-2 d-block text-muted" style="letter-spacing: 2px;">Texto de ejemplo</span>
        <h1 class="fw-bold mb-3 text-brand">Texto de ejemplo</h1>
        <p class="text-muted mx-auto" style="max-width: 600px;">Texto de ejemplo</p>
    </div>
</section>

<!-- Listado de Emprendedores -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($emprendedores as $emp)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 rounded-0 shadow-sm h-100 p-4 bg-white d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <!-- Contenedor de foto vacío -->
                            <div class="bg-light border rounded-circle me-3 flex-shrink-0" style="width: 70px; height: 70px;"></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $emp->user->name ?? 'Texto de ejemplo' }}</h5>
                                <p class="small text-brand mb-0"><i class="bi bi-tag me-1"></i>{{ $emp->categoria_principal ?? 'Texto de ejemplo' }}</p>
                            </div>
                        </div>
                        <p class="text-muted small mb-4 flex-grow-1">
                            {{ Str::limit($emp->biografia, 100) ?: 'Texto de ejemplo (Sin biografía registrada).' }}
                        </p>
                        <a href="{{ route('public.emprendedor.detalle', $emp->id) }}" class="btn btn-outline-dark rounded-0 w-100 fw-bold">
                            Texto de ejemplo (Ver Perfil)
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Texto de ejemplo (No hay emprendedores registrados).</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection