@extends('layouts.admin') 

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Gestión de Contenidos (Sitio Público)</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.configuraciones.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Sección INICIO -->
        <div class="card border-0 shadow-sm rounded-0 mb-4">
            <div class="card-header bg-dark text-white rounded-0">
                <h5 class="mb-0">Página de Inicio (Hero Banner)</h5>
            </div>
            <div class="card-body p-4">
                
                <div class="mb-4 bg-light p-3 border">
                    <label class="form-label fw-bold">Imagen de Fondo Oscurecida</label>
                    @if(isset($config['home_banner_image']))
                        <div class="mb-3">
                            <span class="d-block small text-muted mb-1">Imagen actual:</span>
                            <img src="{{ asset('storage/' . $config['home_banner_image']) }}" alt="Fondo Actual" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" name="home_banner_image" class="form-control rounded-0" accept="image/jpeg, image/png, image/webp">
                    <div class="form-text">Se aplicará un filtro oscuro automáticamente para que las letras blancas resalten. (Recomendado: 1920x1080px).</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Subtítulo Pequeño</label>
                    <input type="text" name="home_subtitulo" class="form-control rounded-0" value="{{ $config['home_subtitulo'] ?? 'Descubre el arte de nuestra tierra' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Título Principal</label>
                    <input type="text" name="home_titulo" class="form-control rounded-0" value="{{ $config['home_titulo'] ?? 'Artesanías de la Huasteca Potosina' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción / Mensaje de Bienvenida</label>
                    <textarea name="home_descripcion" rows="3" class="form-control rounded-0">{{ $config['home_descripcion'] ?? 'Apoya a los artesanos locales y lleva un pedazo de nuestra cultura a tu hogar.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sección QUIENES SOMOS -->
        <div class="card border-0 shadow-sm rounded-0 mb-4">
            <div class="card-header bg-dark text-white rounded-0">
                <h5 class="mb-0">Quiénes Somos</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Historia / Descripción Principal</label>
                    <textarea name="quienes_historia" rows="5" class="form-control rounded-0">{{ $config['quienes_historia'] ?? 'Somos un colectivo dedicado a preservar y promover el arte...' }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Texto Misión</label>
                        <textarea name="quienes_mision" rows="3" class="form-control rounded-0">{{ $config['quienes_mision'] ?? 'Impulsar el desarrollo económico local.' }}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Texto Visión</label>
                        <textarea name="quienes_vision" rows="3" class="form-control rounded-0">{{ $config['quienes_vision'] ?? 'Ser el referente número uno en la región.' }}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Texto Valores</label>
                        <textarea name="quienes_valores" rows="3" class="form-control rounded-0">{{ $config['quienes_valores'] ?? 'Comercio justo, identidad y respeto.' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón Guardar -->
        <div class="text-end sticky-bottom bg-white p-3 border-top shadow">
            <button type="submit" class="btn btn-primary px-5 rounded-0 fw-bold">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection