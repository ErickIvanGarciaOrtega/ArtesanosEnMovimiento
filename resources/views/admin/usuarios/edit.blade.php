@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">
        <i class="bi bi-pencil-square me-2" aria-hidden="true"></i>Editar Usuario
    </h1>
    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary" aria-label="Volver al listado de usuarios">
        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Volver al listado
    </a>
</div>

<!-- U03 FIX: Bloque de alertas para mostrar mensajes de estado o errores del sistema -->
@if (session('status'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
        <strong>¡Atención!</strong> {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar alerta"></button>
    </div>
@endif

<!-- U06 FIX: Contenedor con max-width para evitar desbordamientos en móviles -->
<div class="card border-0 shadow-sm" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Nombre Completo</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $usuario->name) }}" required aria-required="true">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $usuario->email) }}" required aria-required="true">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Nueva Contraseña (Opcional)</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Dejar en blanco para mantener la actual">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="role" class="form-label fw-bold">Rol de Usuario</label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required aria-required="true">
                        <option value="emprendedor" {{ old('role', $usuario->role) == 'emprendedor' ? 'selected' : '' }}>Emprendedor</option>
                        <option value="admin" {{ old('role', $usuario->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label fw-bold">Estado</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required aria-required="true">
                        <option value="activo" {{ old('status', $usuario->status) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('status', $usuario->status) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- U04 FIX: Uso de btn-brand (magenta) para garantizar un contraste alto -->
            <button type="submit" class="btn btn-brand px-4 py-2 fw-bold" aria-label="Guardar cambios del usuario">
                <i class="bi bi-save me-1" aria-hidden="true"></i> Actualizar Usuario
            </button>
        </form>
    </div>
</div>
@endsection