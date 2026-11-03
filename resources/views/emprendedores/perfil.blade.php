@extends('layouts.emprendedor')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0" style="color: var(--huasteca-verde);"><i class="bi bi-person-gear me-2"></i>Mi Perfil de Emprendedor</h3>
                <a href="{{ route('emprendedor.dashboard') }}" class="btn btn-outline-secondary rounded-0 btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Inicio
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-0" style="border-top: 5px solid var(--huasteca-verde) !important;">
                <div class="card-body p-4 p-md-5">
                    
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

                    <form action="{{ route('emprendedor.perfil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Fotografía -->
                        <div class="mb-4 bg-light p-4 border rounded-0 text-center">
                            <label class="form-label fw-bold d-block text-start">Fotografía o Logotipo de Perfil</label>
                            <div class="d-flex flex-column align-items-start gap-3">
                                @if(isset($emprendedor->imagen) && $emprendedor->imagen)
                                    <img src="{{ asset('storage/' . $emprendedor->imagen) }}" alt="Foto de perfil" class="rounded-circle border shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px;">
                                        <i class="bi bi-person text-muted fs-1"></i>
                                    </div>
                                @endif
                                <div class="w-100 text-start">
                                    <input type="file" name="imagen" class="form-control rounded-0" accept="image/jpeg, image/png, image/webp">
                                    <small class="text-muted">Sube una imagen cuadrada para que se ajuste perfectamente (Max: 2MB).</small>
                                </div>
                            </div>
                        </div>

                        <!-- Nombre Comercial -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Nombre Completo / Comercial *</label>
                            <input type="text" name="name" id="name" class="form-control rounded-0" value="{{ old('name', $user->name ?? '') }}" required>
                        </div>

                        <!-- Menú Desplegable de Categoría (Intacto) -->
                        <div class="mb-4">
                            <label for="categoria_principal" class="form-label fw-bold">Categoría Principal *</label>
                            <select name="categoria_principal" id="categoria_principal" class="form-select rounded-0" required>
                                <option value="">-- Selecciona una categoría --</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->nombre }}" {{ (old('categoria_principal', $emprendedor->categoria_principal ?? '') == $cat->nombre) ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Biografía -->
                        <div class="mb-4">
                            <label for="biografia" class="form-label fw-bold">Biografía / Historia del Emprendimiento</label>
                            <textarea name="biografia" id="biografia" rows="4" class="form-control rounded-0" placeholder="Escribe brevemente sobre tus productos o técnica artesanal...">{{ old('biografia', $emprendedor->biografia ?? '') }}</textarea>
                        </div>

                        <hr class="mb-4 border-light">

                        <!-- Redes Sociales -->
                        <h6 class="fw-bold mb-3" style="color: var(--huasteca-texto);"><i class="bi bi-share me-2"></i>Redes Sociales (Opcional)</h6>

                        <div class="mb-3">
                            <label for="facebook" class="form-label small fw-bold">Facebook</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-0 bg-light"><i class="bi bi-facebook" style="color: #1877F2;"></i></span>
                                <input type="url" name="facebook" id="facebook" class="form-control rounded-0" placeholder="https://facebook.com/tupagina" value="{{ old('facebook', $emprendedor->facebook ?? '') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="instagram" class="form-label small fw-bold">Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-0 bg-light"><i class="bi bi-instagram" style="color: #E4405F;"></i></span>
                                <input type="url" name="instagram" id="instagram" class="form-control rounded-0" placeholder="https://instagram.com/tuusuario" value="{{ old('instagram', $emprendedor->instagram ?? '') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="tiktok" class="form-label small fw-bold">TikTok</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-0 bg-light"><i class="bi bi-tiktok text-dark"></i></span>
                                <input type="url" name="tiktok" id="tiktok" class="form-control rounded-0" placeholder="https://tiktok.com/@tuusuario" value="{{ old('tiktok', $emprendedor->tiktok ?? '') }}">
                            </div>
                        </div>

                        <!-- Botón Guardar -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn text-white px-4 fw-bold rounded-0 shadow-sm" style="background-color: var(--huasteca-magenta);">
                                <i class="bi bi-save me-1"></i> Guardar Perfil
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection