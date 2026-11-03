<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Emprendedores - Artesanos y Emprendedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --huasteca-verde: #067A58;
            --huasteca-magenta: #D6145A;
            --huasteca-amarillo: #FFB000;
            --huasteca-crema: #FFF8ED;
            --huasteca-texto: #252525;
        }
        body {
            background-color: var(--huasteca-crema);
            color: var(--huasteca-texto);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .btn-brand {
            background-color: var(--huasteca-magenta) !important;
            border-color: var(--huasteca-magenta) !important;
            color: #fff !important;
            font-weight: 600;
        }
        .btn-brand:hover {
            background-color: #b8104c !important;
            border-color: #b8104c !important;
        }
        .card {
            border-top: 5px solid var(--huasteca-verde) !important;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="card border-0 shadow-lg p-4 rounded-0" style="max-width: 420px; width: 100%;">
        <div class="text-center mb-4">
            <div class="mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 60px; height: 60px; object-fit: cover;" class="rounded-circle border border-2 border-success p-1 bg-white shadow-sm">
            </div>
            <h4 class="fw-bold" style="color: var(--huasteca-verde);">Portal Emprendedores</h4>
            <p class="text-muted small">Ingresa con tus credenciales para administrar tu tienda</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger rounded-0 small py-2">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('emprendedor.login.post') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold small">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text rounded-0 bg-light"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control rounded-0" value="{{ old('email') }}" required autofocus placeholder="tu@correo.com">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text rounded-0 bg-light"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control rounded-0" required placeholder="********">
                </div>
            </div>

            <button type="submit" class="btn btn-brand w-100 rounded-0 py-2 shadow-sm">
                Iniciar Sesión <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted"><i class="bi bi-info-circle me-1"></i> ¿Olvidaste tu contraseña? Contacta al administrador para restablecerla.</small>
        </div>
    </div>

</body>
</html>