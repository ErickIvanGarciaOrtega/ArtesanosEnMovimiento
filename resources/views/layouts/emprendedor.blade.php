<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Emprendedor - Artesanos y Emprendedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --huasteca-verde: #067A58;      /* Verde profundo (Navbar superior) */
            --huasteca-magenta: #D6145A;    /* Magenta intenso (Botones principales) */
            --huasteca-amarillo: #FFB000;   /* Amarillo dorado (Acentos y destacados) */
            --huasteca-rojo: #E53935;       /* Rojo vibrante (Alertas y avisos) */
            --huasteca-crema: #FFF8ED;      /* Fondo suave crema */
            --huasteca-blanco: #FFFFFF;     /* Blanco puro */
            --huasteca-texto: #252525;      /* Texto oscuro principal */
        }
        body {
            background-color: var(--huasteca-crema);
            color: var(--huasteca-texto);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-emprendedor {
            background-color: var(--huasteca-verde) !important;
            border-bottom: 3px solid var(--huasteca-amarillo);
        }
        .navbar-emprendedor .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: color 0.2s;
        }
        .navbar-emprendedor .nav-link:hover, .navbar-emprendedor .nav-link.active {
            color: var(--huasteca-amarillo) !important;
        }
        .btn-primary, .btn-brand {
            background-color: var(--huasteca-magenta) !important;
            border-color: var(--huasteca-magenta) !important;
            color: #fff !important;
        }
        .btn-primary:hover, .btn-brand:hover {
            background-color: #b8104c !important;
            border-color: #b8104c !important;
        }
        .card {
            border-color: rgba(6, 122, 88, 0.15);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar del Emprendedor -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-emprendedor shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-white" href="{{ route('emprendedor.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle border border-2 border-warning bg-white p-1">
                <span>Mi Portal | Emprendedor</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navEmprendedor">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navEmprendedor">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('emprendedor.dashboard') ? 'active' : '' }}" href="{{ route('emprendedor.dashboard') }}">
                            <i class="bi bi-house me-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('emprendedor.perfil') ? 'active' : '' }}" href="{{ route('emprendedor.perfil') }}">
                            <i class="bi bi-person-gear me-1"></i> Mi Perfil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('emprendedor.marcas*') ? 'active' : '' }}" href="{{ route('emprendedor.marcas.index') }}">
                            <i class="bi bi-tags me-1"></i> Mis Marcas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('emprendedor.productos*') ? 'active' : '' }}" href="{{ route('emprendedor.productos.index') }}">
                            <i class="bi bi-box-seam me-1"></i> Mis Productos
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-white small d-none d-lg-inline">Hola, <strong class="text-warning">{{ Auth::user()->name }}</strong></span>
<form action="{{ route('emprendedor.logout') }}" method="POST" class="m-0">
    @csrf
    <button type="submit" class="btn btn-outline-light btn-sm rounded-0 fw-bold">
        <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
    </button>
</form>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="flex-grow-1 py-4">
        @yield('content')
    </main>

    <!-- Footer sencillo institucional -->
    <footer class="bg-dark text-white text-center py-3 mt-auto small" style="border-top: 4px solid var(--huasteca-amarillo);">
        <div class="container">
            &copy; {{ date('Y') }} Artesanos y Emprendedores en Movimiento — Portal Exclusivo para Creadores.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>