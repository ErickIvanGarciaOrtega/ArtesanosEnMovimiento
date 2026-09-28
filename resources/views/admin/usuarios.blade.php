<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Administradores | Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            
            <!-- Menú Lateral -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar collapse min-vh-100 p-3 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="text-center py-3 border-bottom border-secondary">Panel Admin</h4>
                    <ul class="nav flex-column mt-3">
                        <li class="nav-item mb-2">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white-50">Inicio</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('admin.emprendedores') }}" class="nav-link text-white-50">Emprendedores</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('admin.usuarios') }}" class="nav-link text-white active fw-bold">Administradores</a>
                        </li>
                    </ul>
                </div>

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
                    <h1 class="h2">Gestión de Administradores del Sistema</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearAdmin">
                        + Registrar Nuevo Administrador
                    </button>
                </div>

                <!-- Tabla de Administradores -->
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre Completo</th>
                                        <th>Correo Electrónico</th>
                                        <th>Fecha de Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($administradores as $admin)
                                        <tr>
                                            <td>{{ $admin->id }}</td>
                                            <td class="fw-bold">{{ $admin->name }}</td>
                                            <td><code>{{ $admin->email }}</code></td>
                                            <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal Crear Administrador -->
    <div class="modal fade" id="modalCrearAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Nuevo Administrador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.usuarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" name="name" required placeholder="Ej: Carlos López">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico (Login)</label>
                            <input type="email" class="form-control" name="email" required placeholder="Ej: carlos@artesanos.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" required placeholder="Mínimo 6 caracteres">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Administrador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>