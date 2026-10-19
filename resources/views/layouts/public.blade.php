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
            --huasteca-green: #1b4332;       /* Verde profundo selva */
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

    <!-- Footer -->
    <footer class="footer py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-white"><i class="bi bi-tree-fill me-2"></i>Huasteca Potosina</h5>
                    <p class="small text-light mb-0" style="opacity: 0.85;">Texto de ejemplo</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-white mb-3">Navegación</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('public.quienes_somos') }}" class="text-light text-decoration-none" style="opacity: 0.85;">Quienes Somos</a></li>
                        <li class="mb-2"><a href="{{ route('public.productos') }}" class="text-light text-decoration-none" style="opacity: 0.85;">Catálogo de Productos</a></li>
                        <li class="mb-2"><a href="{{ route('public.emprendedores') }}" class="text-light text-decoration-none" style="opacity: 0.85;">Directorio de Emprendedores</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-white mb-3">Síguenos</h6>
                    <a href="#" class="fs-5 me-3 text-light"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="fs-5 me-3 text-light"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="fs-5 text-light"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
            <div class="text-center small text-light mt-5 pt-3 border-top border-secondary" style="opacity: 0.75;">
                &copy; {{ date('Y') }} Texto de ejemplo. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>