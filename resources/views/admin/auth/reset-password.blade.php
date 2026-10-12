<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña | Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%;">
        <h4 class="text-center mb-3 fw-bold">Establecer Nueva Contraseña</h4>

        @if ($errors->any())
            <div class="alert alert-danger small py-2">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Nueva Contraseña</label>
                <input type="password" name="password" class="form-control" required placeholder="Mínimo 6 caracteres" autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar Nueva Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="Repite la contraseña" autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-success w-100">Guardar Nueva Contraseña</button>
        </form>
    </div>
</body>
</html>