@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center gap-2 mb-4">
    <h1 class="h3 fw-bold text-dark mb-0">
        <i class="bi bi-grid-fill me-2" aria-hidden="true"></i>Gestión de Categorías
    </h1>
    <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Nueva Categoría
    </a>
</div>

@if (session('status'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar alerta"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive" tabindex="0" role="region" aria-label="Listado de categorías">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Nombre de la Categoría</th>
                        <th>Productos Asociados</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $cat)
                        <tr>
                            <td class="ps-3 text-muted">{{ $cat->id }}</td>
                            <td class="fw-bold">{{ $cat->nombre }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $cat->productos_count }} productos</span>
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <a href="{{ route('public.productos', ['categoria_id' => $cat->id]) }}" target="_blank" class="btn btn-sm btn-outline-success me-1" aria-label="Ver productos de {{ $cat->nombre }} en el catálogo" title="Ver en catálogo">
                                    <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('admin.categorias.edit', $cat->id) }}" class="btn btn-sm btn-outline-secondary me-1" aria-label="Editar categoría {{ $cat->nombre }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.categorias.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar categoría {{ $cat->nombre }}">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No hay categorías registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categorias->hasPages())
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-center">
                {{ $categorias->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection