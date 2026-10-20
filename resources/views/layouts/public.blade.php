<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colectivo Artesanal | Huasteca Potosina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Paleta de Colores inspirada en la Huasteca Potosina */
        :root {
            --huasteca-green: #1b4332;      /* Verde profundo selva */
            --huasteca-turquoise: #2d6a4f;   /* Verde río / vegetación */
            --huasteca-accent: #bc6c25;      /* Terracota cálido (barro/artesanía) */
            --huasteca-bg: #fefae0;          /* Crema suave de fondo */
            --huasteca-text: #283618;        /* Texto oscuro natural */
        }
        body {
            background-color: var(--huasteca-bg);
            color: var(--huasteca-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-public {
            background-color: #ffffff;
            border-bottom: 1px solid #e9edc9;
        }
        .navbar-public .nav-link {
            color: var(--huasteca-text);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            transition: color 0.2s;
        }
        .navbar-public .nav-link:hover {
            color: var(--huasteca-accent);
        }
        .text-brand { color: var(--huasteca-green) !important; }
        .text-accent { color: var(--huasteca-accent) !important; }
        .bg-brand { background-color: var(--huasteca-green) !important; }
        
        .footer { background-color: #111d13; color: #fefae0; }
        .footer a { color: #ffffff; text-decoration: none; }
        .footer a:hover { color: #d4a373; }
    </style>
</head>
<body>

    <!-- Navegación Pública -->
    <nav class="navbar navbar-expand-lg navbar-public py-3 sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 text-brand" href="{{ route('home') }}">
                <i class="bi bi-tree-fill me-2"></i>Artesanías Huasteca
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navPublic">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3">
                    <li class="nav-item"><a class="nav-link px-2" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link px-2" href="{{ route('public.quienes_somos') }}">Quienes Somos</a></li>
                    <li class="nav-item"><a class="nav-link px-2" href="{{ route('public.emprendedores') }}">Emprendedores</a></li>
                    <li class="nav-item"><a class="nav-link px-2" href="{{ route('public.productos') }}">Productos</a></li>
                    <li class="nav-item"><a class="nav-link px-2" href="{{ route('public.contacto') }}">Contacto</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Dinámico -->
    <main>
        @yield('content')
    </main>

    <!-- PIE DE PÁGINA (FOOTER) -->
    @php
        $footerConfig = \App\Models\Configuracion::pluck('valor', 'clave')->toArray();
    @endphp
    
    <footer style="background-color: #1a2219;" class="text-white py-5 mt-auto">
        <div class="container">
            <div class="row">
                <!-- Columna 1: Descripción -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3 text-white"><i class="bi bi-tree-fill me-2"></i> Huasteca Potosina</h5>
                    <p class="text-light small" style="opacity: 0.9;">
                        {{ $footerConfig['footer_descripcion'] ?? 'Texto de ejemplo' }}
                    </p>
                </div>
                
                <!-- Columna 2: Navegación -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3 text-white">Navegación</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('public.quienes_somos') }}" class="text-light text-decoration-none small" style="opacity: 0.9;">Quiénes Somos</a></li>
                        <li class="mb-2"><a href="{{ route('public.productos') }}" class="text-light text-decoration-none small" style="opacity: 0.9;">Catálogo de Productos</a></li>
                        <li class="mb-2"><a href="{{ route('public.emprendedores') }}" class="text-light text-decoration-none small" style="opacity: 0.9;">Directorio de Emprendedores</a></li>
                    </ul>
                </div>
                
                <!-- Columna 3: Redes Sociales -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3 text-white">Síguenos</h5>
                    <div class="d-flex gap-3">
                        @if(!empty($footerConfig['footer_instagram']))
                            <a href="{{ $footerConfig['footer_instagram'] }}" target="_blank" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                        @endif
                        
                        @if(!empty($footerConfig['footer_facebook']))
                            <a href="{{ $footerConfig['footer_facebook'] }}" target="_blank" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                        @endif
                        
                        @if(!empty($footerConfig['footer_tiktok']))
                            <a href="{{ $footerConfig['footer_tiktok'] }}" target="_blank" class="text-white fs-5"><i class="bi bi-tiktok"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            
            <hr class="border-secondary mt-2 mb-4" style="opacity: 0.3;">
            
            <!-- Copyright dinámico -->
            <div class="text-center text-light small" style="opacity: 0.8;">
                &copy; {{ date('Y') }} {{ $footerConfig['footer_copyright'] ?? 'Texto de ejemplo. Todos los derechos reservados.' }}
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>