@extends('layouts.emprendedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0"><i class="bi bi-person-gear me-2"></i>Mi Perfil de Emprendedor</h3>
    <a href="{{ route('emprendedor.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver al Inicio
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('emprendedor.perfil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Nombre Completo / Comercial</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

<div class="mb-3">
    <label for="categoria_principal" class="form-label fw-bold">Categoría Principal</label>
    <select name="categoria_principal" id="categoria_principal" class="form-select">
        <option value="">-- Selecciona una categoría --</option>
        @foreach($categorias as $cat)
            <option value="{{ $cat->nombre }}" {{ (old('categoria_principal', $emprendedor->categoria_principal) == $cat->nombre) ? 'selected' : '' }}>
                {{ $cat->nombre }}
            </option>
        @endforeach
    </select>
</div>

            <div class="mb-3">
                <label for="biografia" class="form-label fw-bold">Biografía / Historia del Emprendimiento</label>
                <textarea name="biografia" id="biografia" rows="4" class="form-control" placeholder="Escribe brevemente sobre tus productos o técnica artesanal...">{{ old('biografia', $emprendedor->biografia ?? '') }}</textarea>
            </div>

            <h5 class="fw-bold mt-4 mb-3"><i class="bi bi-share me-2"></i>Redes Sociales (Opcional)</h5>

            <div class="mb-3">
                <label for="facebook" class="form-label">Facebook</label>
                <input type="text" name="facebook" id="facebook" class="form-control" placeholder="https://facebook.com/tupagina" value="{{ old('facebook', $emprendedor->facebook ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="instagram" class="form-label">Instagram</label>
                <input type="text" name="instagram" id="instagram" class="form-control" placeholder="@tuusuario" value="{{ old('instagram', $emprendedor->instagram ?? '') }}">
            </div>

            <div class="mb-4">
                <label for="tiktok" class="form-label">TikTok</label>
                <input type="text" name="tiktok" id="tiktok" class="form-control" placeholder="@tuusuario" value="{{ old('tiktok', $emprendedor->tiktok ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i> Guardar Perfil
            </button>
        </form>
    </div>
</div>
@endsection