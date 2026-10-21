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
        
        <!-- Mensaje de Éxito -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Mensajes de Error (NUEVO: Esto nos dirá por qué no guarda) -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- IMPORTANTE: Se agregó enctype="multipart/form-data" -->
        <form action="{{ route('emprendedor.perfil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- NUEVO: Campo para Subir Fotografía / Logotipo -->
            <div class="mb-4 bg-light p-3 border rounded">
                <label class="form-label fw-bold">Fotografía o Logotipo de Perfil</label>
                @if(isset($emprendedor->imagen) && $emprendedor->imagen)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $emprendedor->imagen) }}" alt="Foto de perfil" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                @endif
                <input type="file" name="imagen" class="form-control" accept="image/jpeg, image/png, image/webp">
                <div class="form-text">Sube una imagen cuadrada para que se ajuste perfectamente (Max: 2MB).</div>
            </div>

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
                <input type="text" name="instagram" id="instagram" class="form-control" placeholder="https://instagram.com/tuusuario" value="{{ old('instagram', $emprendedor->instagram ?? '') }}">
            </div>

            <div class="mb-4">
                <label for="tiktok" class="form-label">TikTok</label>
                <input type="text" name="tiktok" id="tiktok" class="form-control" placeholder="https://tiktok.com/@tuusuario" value="{{ old('tiktok', $emprendedor->tiktok ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i> Guardar Perfil
            </button>
        </form>
    </div>
</div>
@endsection