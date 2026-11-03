@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--huasteca-verde);">Mis Marcas Registradas</h2>
        <div>
            <a href="{{ route('emprendedor.dashboard') }}" class="btn btn-outline-secondary rounded-0 me-2">
                <i class="bi bi-arrow-left"></i> Volver al Inicio
            </a>
            <a href="{{ route('emprendedor.marcas.create') }}" class="btn text-white rounded-0 fw-bold shadow-sm" style="background-color: var(--huasteca-magenta);">
                <i class="bi bi-plus-lg"></i> Nueva Marca
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($marcas as $marca)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-0 p-3 h-100" style="border-left: 4px solid var(--huasteca-verde) !important;">
                    <div class="card-body d-flex flex-column justify-content-between p-2">
                        <div>
                            <!-- CAMBIO: Se usa text-dark o color institucional en lugar del azul de enlaces -->
                            <h5 class="fw-bold mb-2" style="color: var(--huasteca-texto);">
                                {{ $marca->nombre }}
                            </h5>
                            <p class="text-muted small mb-3">
                                {{ $marca->descripcion ?? 'Sin descripción registrada.' }}
                            </p>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 border-top pt-2">
                            <a href="{{ route('emprendedor.marcas.edit', $marca->id) }}" class="btn btn-outline-warning btn-sm rounded-0 px-3 text-dark fw-bold">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form action="{{ route('emprendedor.marcas.destroy', $marca->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta marca?');" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-0 px-3 fw-bold">
                                    <i class="bi bi-trash"></i> Eliminar
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
</div>
@endsection