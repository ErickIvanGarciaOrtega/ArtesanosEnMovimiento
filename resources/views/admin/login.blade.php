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
            border-top: 5px solid var(--huasteca-amarillo) !important;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="card border-0 shadow-lg p-4 rounded-0" style="max-width: 420px; width: 100%;">
        <div class="text-center mb-4">
            <div class="mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 60px; height: 60px; object-fit: cover;" class="rounded-circle border border-2 border-warning p-1 bg-white shadow-sm">
            </div>
            <h4 class="fw-bold" style="color: var(--huasteca-verde);">Panel de Administración</h4>
            <p class="text-muted small">Artesanos y Emprendedores en Movimiento</p>
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

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold small">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text rounded-0 bg-light"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control rounded-0" value="{{ old('email') }}" required autofocus placeholder="tu@correo.com">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text rounded-0 bg-light"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control rounded-0" required placeholder="********">
                </div>
            </div>

            <!-- ENLACE DE RECUPERACIÓN DE CONTRASEÑA -->
            <div class="mb-4 text-end">
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small" style="color: var(--huasteca-verde);" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-brand w-100 rounded-0 py-2 shadow-sm">
                Ingresar al Panel <i class="bi bi-box-arrow-in-right ms-1"></i>
            </button>
        </form>
    </div>

</body>
</html>