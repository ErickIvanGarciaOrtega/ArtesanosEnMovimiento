@extends('layouts.public')

@section('content')
<section class="py-5 bg-light" style="min-height: 70vh;">
    <div class="container py-4">
        
        <!-- Sección de Historia / Descripción -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <span class="text-uppercase tracking-wider fw-semibold mb-2 d-block text-muted" style="letter-spacing: 2px;">Nuestra Historia</span>
                <h1 class="fw-bold text-brand mb-4">Quiénes Somos</h1>
                <p class="lead text-muted" style="line-height: 1.8;">
                    {{ $config['quienes_historia'] ?? 'Somos un colectivo dedicado a preservar y promover el arte...' }}
                </p>
            </div>
        </div>

        <!-- Sección de Misión, Visión y Valores (Tarjetas) -->
        <div class="row g-4 mt-2">
            
            <!-- Tarjeta Misión -->
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm text-center p-4 rounded-0">
                    <div class="mb-4 mt-2">
                        <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center mx-auto" style="width: 70px; height: 70px;">
                            <i class="bi bi-bullseye fs-3 text-brand"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Nuestra Misión</h4>
                    <p class="text-muted mb-0">
                        {{ $config['quienes_mision'] ?? 'Impulsar el desarrollo económico local.' }}
                    </p>
                </div>
            </div>

            <!-- Tarjeta Visión -->
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm text-center p-4 rounded-0">
                    <div class="mb-4 mt-2">
                        <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center mx-auto" style="width: 70px; height: 70px;">
                            <i class="bi bi-eye fs-3 text-brand"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Nuestra Visión</h4>
                    <p class="text-muted mb-0">
                        {{ $config['quienes_vision'] ?? 'Ser el referente número uno en la región.' }}
                    </p>
                </div>
            </div>

            <!-- Tarjeta Valores -->
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm text-center p-4 rounded-0">
                    <div class="mb-4 mt-2">
                        <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center mx-auto" style="width: 70px; height: 70px;">
                            <i class="bi bi-heart fs-3 text-brand"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Nuestros Valores</h4>
                    <p class="text-muted mb-0">
                        {{ $config['quienes_valores'] ?? 'Comercio justo, identidad y respeto.' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection