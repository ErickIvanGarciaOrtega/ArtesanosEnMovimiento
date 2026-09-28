<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Artesanos y Emprendedores</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                
                <div class="text-center mb-4">
                    <h2 class="h4">Panel de Administración</h2>
                    <p class="text-muted">Artesanos y Emprendedores en Movimiento</p>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="text-center mb-4">Iniciar Sesión</h5>

                        <!-- Mostrar errores si las credenciales son incorrectas -->
                        @if ($errors->any())
                            <div class="alert alert-danger py-2">
                                <ul class="mb-0 px-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Formulario de Login -->
                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" name="email" id="email" required autofocus>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                            <button type="submit" class="btn btn-dark w-100">Ingresar al Panel</button>
                        </form>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</body>
</html>