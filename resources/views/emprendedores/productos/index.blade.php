@extends('layouts.emprendedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam me-2"></i>Mis Productos</h3>
    <div>
        <a href="{{ route('emprendedor.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al Inicio
        </a>
        <a href="{{ route('emprendedor.productos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nombre</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $producto->nombre }}</td>
                            <td>{{ $producto->marca->nombre ?? 'Sin Marca' }}</td>
                            <td>{{ $producto->categoria->nombre ?? 'Sin Categoría' }}</td>
                            <td class="text-success fw-bold">${{ number_format($producto->precio, 2) }}</td>
                            <td>{{ $producto->stock }} unidades</td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($producto->estado) }}</span>
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Aún no has publicado ningún producto.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection