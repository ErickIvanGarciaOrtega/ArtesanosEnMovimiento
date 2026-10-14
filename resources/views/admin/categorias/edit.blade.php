@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Categoría</h3>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver a Categorías
    </a>
</div>

<div class="card border-0 shadow-sm max-w-lg">
    <div class="card-body p-4">
        <form action="{{ route('admin.categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="form-label fw-bold">Nombre de la Categoría</label>
                <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $categoria->nombre) }}" required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-warning px-4">
                <i class="bi bi-save me-1"></i> Actualizar Categoría
            </button>
        </form>
    </div>
</div>
@endsection