<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Emprendedores | Artesanos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

    <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <h3 class="text-center mb-1 font-weight-bold">Acceso Artesanos</h3>
            <p class="text-center text-muted small mb-4">Ingresa con tu usuario asignado</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('emprendedor.login.post') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de Usuario</label>
                    <input type="text" name="username" class="form-control" required placeholder="Ej: erick_artesano" value="{{ old('username') }}" autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••" autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-success w-100 mt-2">Iniciar Sesión</button>
            </form>
        </div>
    </div>

</body>
</html>