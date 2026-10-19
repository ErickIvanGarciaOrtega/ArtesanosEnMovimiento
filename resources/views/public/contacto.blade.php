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

<!-- Información de Contacto y Google Maps -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Mapa interactivo con la ubicación exacta de Artesanos y Emprendedores en Movimiento -->
            <div class="col-lg-7">
                <div class="bg-white p-2 border rounded-0 h-100 shadow-sm" style="min-height: 400px;">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3694.630048491873!2d-99.0142943!3d21.9862923!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d60ca5bf20521d%3A0x10569dc0097e58a7!2sBlvd.%20M%C3%A9xico%20-%20Laredo%2028%2C%20Zona%20Centro%2C%2079050%20Cdad.%20Valles%2C%20S.L.P.!5e0!3m2!1ses-419!2smx!4v1700000000000!5m2!1ses-419!2smx" 
                        width="100%" 
                        height="100%" 
                        style="border:0; min-height: 380px;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="col-lg-5">
                <div class="p-4 bg-white border rounded-0 h-100 shadow-sm">
                    <h4 class="fw-bold text-dark mb-4">Texto de ejemplo</h4>
                    
                    <div class="mb-4 d-flex align-items-start">
                        <i class="bi bi-geo-alt fs-4 text-brand me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Texto de ejemplo</h6>
                            <p class="text-muted small mb-0">Texto de ejemplo</p>
                        </div>
                    </div>
                    
                    <div class="mb-4 d-flex align-items-start">
                        <i class="bi bi-envelope fs-4 text-brand me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Texto de ejemplo</h6>
                            <p class="text-muted small mb-0">Texto de ejemplo</p>
                        </div>
                    </div>
                    
                    <div class="mb-4 d-flex align-items-start">
                        <i class="bi bi-telephone fs-4 text-brand me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Texto de ejemplo</h6>
                            <p class="text-muted small mb-0">Texto de ejemplo</p>
                        </div>
                    </div>

                    <div class="pt-3 border-top mt-4">
                        <h6 class="fw-bold text-dark mb-2">Texto de ejemplo</h6>
                        <p class="text-muted small mb-0">Texto de ejemplo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection