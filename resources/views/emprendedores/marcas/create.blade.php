@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <!-- Encabezado con el estilo y color institucional -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--huasteca-verde);">
            <i class="bi bi-plus-circle me-2"></i> Registrar Marca
        </h2>
        <a href="{{ route('emprendedor.marcas.index') }}" class="btn btn-outline-secondary rounded-0">
            <i class="bi bi-arrow-left"></i> Volver a Mis Marcas
        </a>
    </div>

    <!-- Mostrar errores de validación generales -->
    @if ($errors->any())
        <div class="alert alert-danger rounded-0">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tarjeta del Formulario -->
    <div class="card border-0 shadow-sm rounded-0" style="border-top: 5px solid var(--huasteca-verde) !important;">
        <div class="card-body p-4">
            <form action="{{ route('emprendedor.marcas.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre de la Marca *</label>
                    <input type="text" name="nombre" id="nombre" class="form-control rounded-0" required value="{{ old('nombre') }}" placeholder="Ej. Artesanías Huastecas">
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="form-label fw-bold">Descripción (Opcional)</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="form-control rounded-0" placeholder="Describe brevemente la línea o concepto de esta marca...">{{ old('descripcion') }}</textarea>
                </div>

                <div class="text-end">
                    <!-- Botón con el Magenta Intenso institucional -->
                    <button type="submit" class="btn text-white rounded-0 px-4 fw-bold shadow-sm" style="background-color: var(--huasteca-magenta);">
                        <i class="bi bi-check-lg me-1"></i> Guardar Marca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection