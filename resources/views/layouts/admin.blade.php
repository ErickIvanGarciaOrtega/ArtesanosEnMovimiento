<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Artesanos y Emprendedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --huasteca-verde: #067A58;      /* Verde profundo (Sidebar y Navbar) */
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
        /* Sidebar Institucional */
        .sidebar {
            min-height: 100vh;
            background-color: var(--huasteca-verde);
            color: #fff;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 12px 20px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: var(--huasteca-amarillo);
        }
        /* Botones y elementos generales */
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
<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar / Barra Lateral -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0 shadow-sm">
                <div class="position-sticky pt-4">
                    <div class="text-center mb-4 px-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 50px; height: 50px; object-fit: cover;" class="rounded-circle border border-2 border-warning bg-white p-1 mb-2">
                        <h6 class="fw-bold text-white mb-0">Panel Admin</h6>
                        <small class="text-warning">Control Maestro</small>
                    </div>
                    
                    <ul class="nav flex-column gap-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}" href="{{ route('admin.usuarios.index') }}">
                                <i class="bi bi-people me-2"></i> Emprendedores / Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categorias*') ? 'active' : '' }}" href="{{ route('admin.categorias.index') }}">
                                <i class="bi bi-tags me-2"></i> Categorías
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.configuraciones*') ? 'active' : '' }}" href="{{ route('admin.configuraciones.index') }}">
                                <i class="bi bi-gear me-2"></i> CMS / Configuración
                            </a>
                        </li>
                    </ul>

                    <div class="px-3 mt-5">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100 rounded-0 text-start sm-btn">
                                <i class="bi bi-box-arrow-right me-2 text-warning"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Contenido Principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h4 class="fw-bold" style="color: var(--huasteca-verde);">Gestión del Sistema</h4>
                    <span class="badge bg-warning text-dark fw-bold">Admin: {{ Auth::user()->name ?? 'Sistema' }}</span>
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>