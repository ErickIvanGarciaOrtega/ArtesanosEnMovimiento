@extends('layouts.emprendedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Producto</h3>
    <a href="{{ route('emprendedor.productos.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver a Mis Productos
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('emprendedor.productos.update', $producto->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre del Producto</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="precio" class="form-label fw-bold">Precio ($ MXN)</label>
                    <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="{{ old('precio', $producto->precio) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="marca_id" class="form-label fw-bold">Marca Asociada</label>
                    <select name="marca_id" id="marca_id" class="form-select">
                        <option value="">-- Seleccionar Marca --</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}" {{ $producto->marca_id == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="categoria_id" class="form-label fw-bold">Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="form-select">
                        <option value="">-- Seleccionar Categoría --</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="descripcion" class="form-label fw-bold">Descripción del Producto</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="form-control">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning px-4">
                <i class="bi bi-save me-1"></i> Actualizar Producto
            </button>
        </form>
    </div>
</div>
@endsection