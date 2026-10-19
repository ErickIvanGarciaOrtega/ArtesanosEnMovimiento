@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Editar Producto</h2>
        <a href="{{ route('emprendedor.productos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-0">
        <div class="card-body p-4">
            <!-- IMPORTANTE: enctype para permitir archivos -->
            <form action="{{ route('emprendedor.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label fw-bold">Nombre del Producto *</label>
                        <input type="text" name="nombre" id="nombre" class="form-control rounded-0" required value="{{ old('nombre', $producto->nombre) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="precio" class="form-label fw-bold">Precio (MXN) *</label>
                        <input type="number" step="0.01" name="precio" id="precio" class="form-control rounded-0" required value="{{ old('precio', $producto->precio) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="categoria_id" class="form-label fw-bold">Categoría *</label>
                        <select name="categoria_id" id="categoria_id" class="form-select rounded-0" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ (old('categoria_id', $producto->categoria_id) == $categoria->id) ? 'selected' : '' }}>
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
                                <option value="{{ $marca->id }}" {{ (old('marca_id', $producto->marca_id) == $marca->id) ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="form-label fw-bold">Descripción del Producto</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="form-control rounded-0">{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>

                <!-- CAMPO DE IMAGEN CON PREVISUALIZACIÓN -->
                <div class="mb-4 p-3 bg-light border">
                    <label for="imagen" class="form-label fw-bold text-dark">Actualizar Fotografía del Producto</label>
                    
                    @if($producto->imagen)
                        <div class="mb-3 d-flex align-items-center">
                            <div class="me-3">
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen de {{ $producto->nombre }}" class="img-thumbnail" style="height: 100px; width: 100px; object-fit: cover;">
                            </div>
                            <div>
                                <span class="small text-muted d-block mb-1">Imagen actual vinculada.</span>
                                <span class="badge bg-success">Activa</span>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="imagen" id="imagen" class="form-control rounded-0" accept="image/jpeg, image/png, image/webp">
                    <div class="form-text">Si subes una nueva imagen, la anterior será reemplazada. Max: 2MB.</div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-dark rounded-0 px-4">Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection