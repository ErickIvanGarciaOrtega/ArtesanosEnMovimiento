@extends('layouts.emprendedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0"><i class="bi bi-tags me-2"></i>Mis Marcas Registradas</h3>
    <div>
        <a href="{{ route('emprendedor.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al Inicio
        </a>
        <a href="{{ route('emprendedor.marcas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nueva Marca
        </a>
    </div>
</div>

<div class="row g-3">
    @forelse($marcas as $marca)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <h5 class="fw-bold text-primary mb-2">{{ $marca->nombre }}</h5>
                    <p class="text-muted small mb-4 flex-grow-1">{{ $marca->descripcion ?? 'Sin descripción registrada.' }}</p>
                    
                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <a href="{{ route('emprendedor.marcas.edit', $marca->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                        <form action="{{ route('emprendedor.marcas.destroy', $marca->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta marca?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash me-1"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 text-center">
                <p class="text-muted mb-0">Aún no has registrado ninguna marca. Haz clic en "Nueva Marca" para agregar la primera.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection