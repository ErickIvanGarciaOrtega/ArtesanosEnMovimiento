@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap align-items-center gap-2 mb-4">
        <h1 class="h2 fw-bold mb-0" style="color: var(--huasteca-verde);">
            <i class="bi bi-tags me-2" aria-hidden="true"></i>Mis Marcas Registradas
        </h1>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('emprendedor.dashboard') }}" class="btn btn-outline-secondary rounded-0">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Volver al Inicio
            </a>
            <a href="{{ route('emprendedor.marcas.create') }}" class="btn text-white rounded-0 fw-bold shadow-sm" style="background-color: var(--huasteca-magenta);">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Nueva Marca
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar alerta"></button>
        </div>
    @endif

    <div class="row g-4 mb-5">
        @forelse($marcas as $marca)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-0 p-3 h-100" style="border-left: 4px solid var(--huasteca-verde) !important;">
                    <div class="card-body d-flex flex-column justify-content-between p-2">
                        <div>
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <h2 class="h5 fw-bold mb-0" style="color: var(--huasteca-texto);">
                                    {{ $marca->nombre }}
                                </h2>
                                <span class="badge bg-light text-dark border fw-normal">
                                    {{ $marca->productos_count ?? 0 }} {{ ($marca->productos_count ?? 0) === 1 ? 'producto' : 'productos' }}
                                </span>
                            </div>
                            <p class="text-muted small mb-3">
                                {{ $marca->descripcion ?? 'Sin descripción registrada.' }}
                            </p>
                        </div>
                        
                        <div class="d-flex justify-content-end flex-wrap gap-2 border-top pt-2">
                            <a href="{{ route('public.productos', ['marca_id' => $marca->id]) }}" target="_blank" class="btn btn-outline-success btn-sm rounded-0 px-3 fw-bold" aria-label="Ver productos de la marca {{ $marca->nombre }} en el catálogo">
                                <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i> Catálogo
                            </a>
                            <a href="{{ route('emprendedor.marcas.edit', $marca->id) }}" class="btn btn-outline-dark btn-sm rounded-0 px-3 fw-bold" aria-label="Editar marca {{ $marca->nombre }}">
                                <i class="bi bi-pencil me-1" aria-hidden="true"></i> Editar
                            </a>
                            <form action="{{ route('emprendedor.marcas.destroy', $marca->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta marca?');" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-0 px-3 fw-bold" aria-label="Eliminar marca {{ $marca->nombre }}">
                                    <i class="bi bi-trash me-1" aria-hidden="true"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="card border-0 shadow-sm rounded-0 p-5 bg-white">
                    <p class="text-muted mb-3">Aún no has registrado ninguna marca para tus productos.</p>
                    <div>
                        <a href="{{ route('emprendedor.marcas.create') }}" class="btn text-white rounded-0 px-4 fw-bold" style="background-color: var(--huasteca-magenta);">
                            Registrar mi primera Marca
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- U08 FIX: Paginación del listado de marcas -->
    @if($marcas->hasPages())
        <div class="d-flex justify-content-center">
            {{ $marcas->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection