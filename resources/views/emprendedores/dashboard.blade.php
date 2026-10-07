<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Emprendedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success px-4">
        <span class="navbar-brand">Panel del Artesano</span>
        <form method="POST" action="{{ route('emprendedor.logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm">Cerrar Sesión</button>
        </form>
    </nav>

    <div class="container py-5">
        <h2>Bienvenido, {{ Auth::guard('emprendedor')->user()->nombre_completo }}</h2>
        <p class="text-muted">Marca/Giro: {{ Auth::guard('emprendedor')->user()->marca_o_giro }}</p>
    </div>

</body>
</html>