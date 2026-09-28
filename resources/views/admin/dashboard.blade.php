<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Panel Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            
            <!-- Menú Lateral (Sidebar) -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar collapse min-vh-100 p-3 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="text-center py-3 border-bottom border-secondary">Panel Admin</h4>
                    <ul class="nav flex-column mt-3">
                        <li class="nav-item mb-2">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white active fw-bold">Inicio</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('admin.emprendedores') }}" class="nav-link text-white-50">Emprendedores</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('admin.usuarios') }}" class="nav-link text-white-50">Administradores</a>
                        </li>
                    </ul>
                </div>

                <!-- Botón de Cerrar Sesión -->
                <div class="pt-3 border-top border-secondary">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 text-start d-flex align-items-center justify-content-between">
                            <span>Cerrar Sesión</span>
                            <small>➔</small>
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Contenido Principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Bienvenido al Panel de Administración</h1>
                </div>
                <p class="lead">Selecciona una opción del menú lateral para gestionar la plataforma.</p>
            </main>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>