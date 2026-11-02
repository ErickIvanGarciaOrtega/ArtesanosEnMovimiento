@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Editar Producto</h2>
        <a href="{{ route('emprendedor.productos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger rounded-0">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-0">
        <div class="card-body p-4">
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

                <div class="mb-3">
                    <label for="descripcion" class="form-label fw-bold">Descripción del Producto</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="form-control rounded-0">{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>

                <!-- CAMPO DE IMÁGENES MÚLTIPLES -->
                <div class="mb-4 bg-light p-3 border rounded-0">
                    <label class="form-label fw-bold">Fotografías del Producto (Máximo 10)</label>
                    
                    @if($producto->imagenes->count() > 0)
                        <div class="d-flex flex-wrap gap-3 mb-3 p-3 bg-white border">
                            @foreach($producto->imagenes as $img)
                                <div class="position-relative border shadow-sm" style="width: 90px; height: 90px;">
                                    <img src="{{ asset('storage/' . $img->ruta) }}" alt="Imagen del producto" style="width: 100%; height: 100%; object-fit: cover;">
                                    
                                    <!-- BOTÓN PARA ELIMINAR IMAGEN INDIVIDUAL (CORREGIDO A TYPE="SUBMIT") -->
                                    <button type="submit" form="form-eliminar-{{ $img->id }}" class="btn btn-danger btn-sm position-absolute top-0 end-0 translate-middle rounded-circle shadow" style="width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Eliminar esta foto">
                                        <i class="bi bi-x-lg" style="font-size: 12px;"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-text mb-3">Actualmente tienes {{ $producto->imagenes->count() }} imágenes vinculadas.</div>
                    @else
                        <div class="alert alert-warning py-2 small">Este producto aún no tiene fotografías.</div>
                    @endif
                    
                    <input type="file" name="imagenes[]" id="imagenes" class="form-control rounded-0" accept="image/jpeg, image/png, image/webp" multiple>
                    <div class="form-text">Si seleccionas nuevas imágenes, se sumarán a las actuales. Tamaño máximo: 2MB por imagen.</div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-dark rounded-0 px-4">Actualizar Producto</button>
                </div>
            </form>

            <!-- FORMULARIOS OCULTOS PARA ELIMINAR IMÁGENES -->
            @foreach($producto->imagenes as $img)
                <form id="form-eliminar-{{ $img->id }}" action="{{ route('emprendedor.productos.imagen.eliminar', $img->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta imagen de forma permanente?');">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach

        </div>
    </div>
</div>
@endsection