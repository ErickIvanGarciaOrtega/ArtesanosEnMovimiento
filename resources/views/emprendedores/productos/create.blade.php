@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Agregar Nuevo Producto</h2>
        <a href="{{ route('emprendedor.productos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-0">
        <div class="card-body p-4">
            <!-- IMPORTANTE: enctype para permitir archivos -->
            <form action="{{ route('emprendedor.productos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label fw-bold">Nombre del Producto *</label>
                        <input type="text" name="nombre" id="nombre" class="form-control rounded-0" required value="{{ old('nombre') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="precio" class="form-label fw-bold">Precio (MXN) *</label>
                        <input type="number" step="0.01" name="precio" id="precio" class="form-control rounded-0" required value="{{ old('precio') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="categoria_id" class="form-label fw-bold">Categoría *</label>
                        <select name="categoria_id" id="categoria_id" class="form-select rounded-0" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label fw-bold">Marca (Opcional)</label>
                        <select name="marca_id" id="marca_id" class="form-select rounded-0">
                            <option value="">Sin Marca</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label fw-bold">Descripción del Producto</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="form-control rounded-0">{{ old('descripcion') }}</textarea>
                </div>

                <!-- CAMPO DE IMAGEN -->
                <div class="mb-4">
                    <label for="imagen" class="form-label fw-bold">Fotografía del Producto (Opcional)</label>
                    <input type="file" name="imagen" id="imagen" class="form-control rounded-0" accept="image/jpeg, image/png, image/webp">
                    <div class="form-text">Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 2MB.</div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-dark rounded-0 px-4">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection