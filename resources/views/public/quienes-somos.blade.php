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

<!-- Sección Principal / Historia -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <!-- Contenedor de imagen vacío -->
                <div class="bg-white border rounded-0" style="height: 380px; width: 100%;"></div>
            </div>
            <div class="col-md-6">
                <h2 class="fw-bold text-dark mb-3">Texto de ejemplo</h2>
                <p class="text-muted mb-3">Texto de ejemplo</p>
                <p class="text-muted mb-4">Texto de ejemplo</p>
            </div>
        </div>
    </div>
</section>

<!-- Misión y Visión / Valores -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4">
                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-compass fs-3 text-brand"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Texto de ejemplo</h5>
                    <p class="text-muted small">Texto de ejemplo</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-eye fs-3 text-brand"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Texto de ejemplo</h5>
                    <p class="text-muted small">Texto de ejemplo</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-heart fs-3 text-brand"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Texto de ejemplo</h5>
                    <p class="text-muted small">Texto de ejemplo</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection