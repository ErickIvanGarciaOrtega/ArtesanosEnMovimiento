<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Emprendedores | Panel Admin</title>
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
                            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white-50">Inicio</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('admin.emprendedores') }}" class="nav-link text-white active fw-bold">Emprendedores</a>
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
                    <h1 class="h2">Gestión de Emprendedores y Artesanos</h1>
                    <!-- Botón para dar de alta nuevo artesano -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearEmprendedor">
                        + Registrar Nuevo Emprendedor
                    </button>
                </div>

                <!-- Mostrar Mensajes de Error de Validación -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tabla de Emprendedores -->
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre Completo</th>
                                        <th>Usuario (Login)</th>
                                        <th>Marca / Giro</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($emprendedores as $emprendedor)
                                        <tr>
                                            <td>{{ $emprendedor->id }}</td>
                                            <td class="fw-bold">{{ $emprendedor->nombre_completo }}</td>
                                            <td><code>{{ $emprendedor->username }}</code></td>
                                            <td>{{ $emprendedor->marca_o_giro }}</td>
                                            <td>
                                                <a href="https://wa.me/52{{ $emprendedor->telefono }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                    WhatsApp ({{ $emprendedor->telefono }})
                                                </a>
                                            </td>
                                            <td>
                                                @if($emprendedor->estado)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <!-- Botón Editar -->
                                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $emprendedor->id }}">
                                                    Editar
                                                </button>
                                                <!-- Botón Restablecer Clave -->
                                                <button class="btn btn-sm btn-info me-1 text-white" data-bs-toggle="modal" data-bs-target="#modalReset{{ $emprendedor->id }}">
                                                    Restablecer Clave
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Editar Emprendedor -->
                                        <div class="modal fade" id="modalEditar{{ $emprendedor->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title fw-bold">Editar Emprendedor: {{ $emprendedor->nombre_completo }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.emprendedores.update', $emprendedor->id) }}" method="POST" autocomplete="off">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nombre Completo</label>
                                                                <input type="text" class="form-control" name="nombre_completo" value="{{ $emprendedor->nombre_completo }}" required autocomplete="off">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nombre de Usuario (Login)</label>
                                                                <input type="text" class="form-control" name="username" value="{{ $emprendedor->username }}" required autocomplete="off">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Marca o Giro Artesanal</label>
                                                                <input type="text" class="form-control" name="marca_o_giro" value="{{ $emprendedor->marca_o_giro }}" required autocomplete="off">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Teléfono (WhatsApp - 10 dígitos)</label>
                                                                <input type="text" 
                                                                       inputmode="numeric" 
                                                                       class="form-control" 
                                                                       name="telefono" 
                                                                       value="{{ $emprendedor->telefono }}" 
                                                                       maxlength="10" 
                                                                       pattern="[0-9]{10}"
                                                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                                                       placeholder="4811234567" 
                                                                       required 
                                                                       autocomplete="off">
                                                                <small class="text-muted">Solo 10 dígitos numéricos sin espacios ni guiones.</small>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Estado de la cuenta</label>
                                                                <select class="form-select" name="estado">
                                                                    <option value="1" {{ $emprendedor->estado ? 'selected' : '' }}>Activo</option>
                                                                    <option value="0" {{ !$emprendedor->estado ? 'selected' : '' }}>Inactivo</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-warning">Actualizar Datos</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Restablecer Contraseña -->
                                        <div class="modal fade" id="modalReset{{ $emprendedor->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title fw-bold">Restablecer Contraseña</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.emprendedores.resetPassword', $emprendedor->id) }}" method="POST" autocomplete="off">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <p>Asigna una nueva contraseña de acceso para el usuario: <strong>{{ $emprendedor->username }}</strong></p>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nueva Contraseña</label>
                                                                <input type="password" class="form-control" name="password" required placeholder="Ingresa la nueva clave (mín. 6 caracteres)" autocomplete="new-password">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-info text-white">Cambiar Contraseña</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No hay emprendedores registrados aún. Haz clic en <strong>"Registrar Nuevo Emprendedor"</strong> para agregar el primero.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal para Crear Emprendedor -->
    <div class="modal fade" id="modalCrearEmprendedor" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabel">Registrar Nuevo Emprendedor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.emprendedores.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre_completo" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre_completo" required placeholder="Ej: María Hernández" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Nombre de Usuario (Login)</label>
                            <input type="text" class="form-control" name="username" required placeholder="Ej: maria_artesanias" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña Inicial</label>
                            <input type="password" class="form-control" name="password" required placeholder="Mínimo 6 caracteres" autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label for="marca_o_giro" class="form-label">Marca o Giro Artesanal</label>
                            <input type="text" class="form-control" name="marca_o_giro" required placeholder="Ej: Textiles Huastecos" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono (WhatsApp - 10 dígitos)</label>
                            <input type="text" 
                                   inputmode="numeric" 
                                   class="form-control" 
                                   name="telefono" 
                                   maxlength="10" 
                                   pattern="[0-9]{10}"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                   required 
                                   placeholder="Ej: 4811234567" 
                                   autocomplete="off">
                            <small class="text-muted">Solo 10 dígitos numéricos sin espacios ni guiones.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Emprendedor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>