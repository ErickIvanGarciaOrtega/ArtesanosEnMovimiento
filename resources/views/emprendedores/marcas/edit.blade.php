@extends('layouts.emprendedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Marca</h4>
    <a href="{{ route('emprendedor.marcas.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver a Mis Marcas
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('emprendedor.marcas.update', $marca->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre de la Marca</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $marca->nombre) }}" required>
            </div>

            <div class="mb-4">
                <label for="descripcion" class="form-label fw-bold">Descripción (Opcional)</label>
                <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion', $marca->descripcion) }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning px-4">
                <i class="bi bi-save me-1"></i> Actualizar Marca
            </button>
        </form>
    </div>
</div>
@endsection