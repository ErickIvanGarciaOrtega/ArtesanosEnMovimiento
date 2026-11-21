@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">
        <i class="bi bi-people me-2" aria-hidden="true"></i>Gestión de Usuarios y Emprendedores
    </h1>
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Nuevo Usuario
    </a>
</div>

@if (session('status'))
    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar alerta"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive" tabindex="0" role="region" aria-label="Listado de usuarios y emprendedores">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Registro</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @if($usuario->role === 'admin')
                                    <span class="badge bg-danger">Administrador</span>
                                @else
                                    <span class="badge bg-primary">Emprendedor</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->status === 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-3 text-nowrap">
                                @if($usuario->role === 'emprendedor' && $usuario->emprendedor)
                                    <a href="{{ route('public.emprendedor.detalle', $usuario->emprendedor->id) }}" target="_blank" class="btn btn-sm btn-outline-success me-1" aria-label="Ver perfil público de {{ $usuario->name }}" title="Ver perfil público">
                                        <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-outline-secondary me-1" aria-label="Editar usuario {{ $usuario->name }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar usuario {{ $usuario->name }}" {{ auth()->id() == $usuario->id ? 'disabled' : '' }}>
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($usuarios->hasPages())
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-center">
                {{ $usuarios->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection