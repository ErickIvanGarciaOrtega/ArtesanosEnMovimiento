@extends('layouts.public')

@section('content')

<!-- Encabezado Dinámico -->
<section class="py-5 bg-white border-bottom text-center">
    <div class="container py-4">
        <span class="text-uppercase tracking-wider fw-semibold mb-2 d-block text-muted" style="letter-spacing: 2px;">
            {{ $config['contacto_subtitulo'] ?? 'Contáctanos' }}
        </span>
        <h1 class="fw-bold mb-3 text-brand">
            {{ $config['contacto_titulo'] ?? 'Ponte en contacto con nosotros' }}
        </h1>
        <p class="text-muted mx-auto mb-4" style="max-width: 600px;">
            {{ $config['contacto_descripcion'] ?? 'Estamos aquí para resolver tus dudas y escuchar tus propuestas.' }}
        </p>
    </div>
</section>

<!-- Contenido Principal: Mapa y Tarjeta de Información -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            
            <!-- Columna del Mapa Original -->
            <div class="col-lg-7">
                <div class="bg-white border p-2 h-100 shadow-sm position-relative" style="min-height: 400px;">
                    <div class="position-absolute top-0 start-0 m-3 z-3">
                        <a href="https://maps.app.goo.gl/NJcb8T5YiCyDEnfn6" target="_blank" class="btn btn-primary btn-sm rounded-0 shadow-sm fw-bold">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Abrir en Maps
                        </a>
                    </div>
                    <!-- Iframe de Google Maps ajustado con tu ubicación exacta -->
                    <div class="w-100 h-100">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3714.288235216301!2d-99.018596!3d21.989715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d6b8874c760361%3A0x2a061c16262456e7!2sBlvd.%20M%C3%A9xico-Laredo%2028%2C%20Zona%20Centro%2C%2079050%20Cd%20Valles%2C%20S.L.P.!5e0!3m2!1ses-419!4v1700000000000!5m2!1ses-419!4v1700000000000" width="100%" height="100%" style="border:0; min-height: 380px;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>

            <!-- Columna de Información de Contacto -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-0 p-4 h-100 bg-white">
                    <h4 class="fw-bold text-dark mb-4">{{ $config['contacto_info_titulo'] ?? 'Información de Contacto' }}</h4>
                    
                    <div class="mb-4 d-flex align-items-start">
                        <i class="bi bi-geo-alt fs-4 text-brand me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $config['contacto_dir_label'] ?? 'Ubicación' }}</h6>
                            <p class="text-muted small mb-0">{{ $config['contacto_dir_texto'] ?? 'Ciudad Valles, San Luis Potosí' }}</p>
                        </div>
                    </div>

                    <div class="mb-4 d-flex align-items-start">
                        <i class="bi bi-envelope fs-4 text-brand me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $config['contacto_email_label'] ?? 'Correo Electrónico' }}</h6>
                            <p class="text-muted small mb-0">{{ $config['contacto_email_texto'] ?? 'contacto@artesanos.com' }}</p>
                        </div>
                    </div>

                    <div class="mb-4 d-flex align-items-start">
                        <i class="bi bi-telephone fs-4 text-brand me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $config['contacto_tel_label'] ?? 'Teléfono' }}</h6>
                            <p class="text-muted small mb-0">{{ $config['contacto_tel_texto'] ?? '+52 (481) 000 0000' }}</p>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div>
                        <h6 class="fw-bold text-dark mb-1">{{ $config['contacto_extra_titulo'] ?? 'Horarios de atención' }}</h6>
                        <p class="text-muted small mb-0">{{ $config['contacto_extra_desc'] ?? 'Lunes a Viernes de 9:00 AM a 6:00 PM.' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection