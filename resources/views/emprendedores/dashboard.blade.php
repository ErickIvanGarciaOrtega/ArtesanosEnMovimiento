<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Emprendedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container px-4">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-shop me-2"></i>Mi Portal de Emprendedor
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 small">Hola, {{ auth()->user()->name }}</span>
                <form action="{{ route('emprendedor.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="card border-0 shadow-sm p-4">
            <h2 class="fw-bold">¡Bienvenido a tu panel de control!</h2>
            <p class="text-muted">Desde aquí podrás administrar tus marcas y productos en el colectivo artesanal.</p>
        </div>
    </div>
</body>
</html>