@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--huasteca-verde);">
            <i class="bi bi-box-seam me-2"></i> Mis Productos
        </h2>
        <div>
            <a href="{{ route('emprendedor.dashboard') }}" class="btn btn-outline-secondary rounded-0 me-2">
                <i class="bi bi-arrow-left"></i> Volver al Inicio
            </a>
            <a href="{{ route('emprendedor.productos.create') }}" class="btn text-white rounded-0 fw-bold shadow-sm" style="background-color: var(--huasteca-magenta);">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Contenedor de la Tabla -->
    <div class="card border-0 shadow-sm rounded-0" style="border-top: 5px solid var(--huasteca-verde) !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="px-4 py-3" style="color: var(--huasteca-texto);">Nombre del Producto</th>
                            <th class="py-3" style="color: var(--huasteca-texto);">Marca</th>
                            <th class="py-3" style="color: var(--huasteca-texto);">Categoría</th>
                            <th class="py-3" style="color: var(--huasteca-texto);">Precio</th>
                            <th class="py-3 text-center" style="color: var(--huasteca-texto);">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td class="px-4 py-3 fw-bold">{{ $producto->nombre }}</td>
                                <td class="py-3 text-muted">{{ $producto->marca->nombre ?? 'Sin Marca' }}</td>
                                <td class="py-3"><span class="badge bg-light text-dark border">{{ $producto->categoria->nombre ?? 'N/A' }}</span></td>
                                <td class="py-3 fw-bold text-success">${{ number_format($producto->precio, 2) }}</td>
                                <td class="py-3 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('emprendedor.productos.edit', $producto->id) }}" class="btn btn-sm btn-outline-primary rounded-0 fw-bold px-3">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <form action="{{ route('emprendedor.productos.destroy', $producto->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-0 fw-bold px-3">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1 text-light"></i></div>
                                    Aún no has publicado ningún producto. <br>
                                    <a href="{{ route('emprendedor.productos.create') }}" class="text-decoration-none fw-bold mt-2 d-inline-block" style="color: var(--huasteca-magenta);">Comienza publicando el primero aquí.</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection