<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Marca | Portal Emprendedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container px-4">
            <a class="navbar-brand fw-bold" href="{{ route('emprendedor.dashboard') }}">
                <i class="bi bi-shop me-2"></i>Mi Portal
            </a>
        </div>
    </nav>

    <div class="container py-4 max-w-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle me-2"></i>Registrar Marca</h4>
            <a href="{{ route('emprendedor.marcas.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver a Mis Marcas
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('emprendedor.marcas.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Marca</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Artesanías Huastecas" required>
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label fw-bold">Descripción (Opcional)</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" placeholder="Describe brevemente la línea o concepto de esta marca..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Guardar Marca
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>