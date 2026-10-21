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
                <h5 class="mb-0">Página de Inicio</h5>
            </div>
            <div class="card-body p-4">
                
                <h6 class="fw-bold text-dark mb-3">Hero Banner Principal</h6>

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

                <!-- BANNER PROMOCIONAL -->
                <hr class="my-4">
                <h6 class="fw-bold text-dark mb-3">Banner Promocional (Directorio)</h6>

                <div class="mb-4 bg-light p-3 border">
                    <label class="form-label fw-bold">Imagen Promocional</label>
                    @if(isset($config['home_promo_image']))
                        <div class="mb-3">
                            <span class="d-block small text-muted mb-1">Imagen actual:</span>
                            <img src="{{ asset('storage/' . $config['home_promo_image']) }}" alt="Promocional Actual" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" name="home_promo_image" class="form-control rounded-0" accept="image/jpeg, image/png, image/webp">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Título Promocional</label>
                    <input type="text" name="home_promo_titulo" class="form-control rounded-0" value="{{ $config['home_promo_titulo'] ?? 'Descubre el talento de nuestra región' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="home_promo_descripcion" rows="2" class="form-control rounded-0">{{ $config['home_promo_descripcion'] ?? 'Conoce a los creadores detrás de cada pieza y apoya el comercio local.' }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Texto del Botón</label>
                    <input type="text" name="home_promo_boton" class="form-control rounded-0" value="{{ $config['home_promo_boton'] ?? 'Directorio de Emprendedores' }}">
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

        <!-- Sección EMPRENDEDORES -->
        <div class="card border-0 shadow-sm rounded-0 mb-4">
            <div class="card-header bg-dark text-white rounded-0">
                <h5 class="mb-0">Página de Emprendedores</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Subtítulo Pequeño</label>
                    <input type="text" name="emprendedores_subtitulo" class="form-control rounded-0" value="{{ $config['emprendedores_subtitulo'] ?? 'Nuestros Creadores' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Título Principal</label>
                    <input type="text" name="emprendedores_titulo" class="form-control rounded-0" value="{{ $config['emprendedores_titulo'] ?? 'Directorio de Emprendedores' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="emprendedores_descripcion" rows="3" class="form-control rounded-0">{{ $config['emprendedores_descripcion'] ?? 'Conoce a las manos talentosas detrás de cada producto.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sección PRODUCTOS -->
        <div class="card border-0 shadow-sm rounded-0 mb-4">
            <div class="card-header bg-dark text-white rounded-0">
                <h5 class="mb-0">Página de Productos (Catálogo)</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Subtítulo Pequeño</label>
                    <input type="text" name="productos_subtitulo" class="form-control rounded-0" value="{{ $config['productos_subtitulo'] ?? 'Catálogo' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Título Principal</label>
                    <input type="text" name="productos_titulo" class="form-control rounded-0" value="{{ $config['productos_titulo'] ?? 'Nuestros Productos' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="productos_descripcion" rows="2" class="form-control rounded-0">{{ $config['productos_descripcion'] ?? 'Explora las piezas artesanales elaboradas por nuestros creadores.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sección CONTACTO -->
        <div class="card border-0 shadow-sm rounded-0 mb-4">
            <div class="card-header bg-dark text-white rounded-0">
                <h5 class="mb-0">Página de Contacto</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Subtítulo Pequeño</label>
                    <input type="text" name="contacto_subtitulo" class="form-control rounded-0" value="{{ $config['contacto_subtitulo'] ?? 'Contáctanos' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Título Principal</label>
                    <input type="text" name="contacto_titulo" class="form-control rounded-0" value="{{ $config['contacto_titulo'] ?? 'Ponte en contacto con nosotros' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="contacto_descripcion" rows="2" class="form-control rounded-0">{{ $config['contacto_descripcion'] ?? 'Estamos aquí para resolver tus dudas y escuchar tus propuestas.' }}</textarea>
                </div>
                
                <hr class="my-4">
                <h6 class="fw-bold text-dark mb-3">Información de Contacto y Ubicación</h6>

                <div class="mb-3">
                    <label class="form-label fw-bold">Título del Bloque de Información</label>
                    <input type="text" name="contacto_info_titulo" class="form-control rounded-0" value="{{ $config['contacto_info_titulo'] ?? 'Información de Contacto' }}">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Etiqueta Dirección</label>
                        <input type="text" name="contacto_dir_label" class="form-control rounded-0" value="{{ $config['contacto_dir_label'] ?? 'Ubicación' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Texto Dirección</label>
                        <input type="text" name="contacto_dir_texto" class="form-control rounded-0" value="{{ $config['contacto_dir_texto'] ?? 'Ciudad Valles, San Luis Potosí' }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Etiqueta Correo</label>
                        <input type="text" name="contacto_email_label" class="form-control rounded-0" value="{{ $config['contacto_email_label'] ?? 'Correo Electrónico' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Texto Correo</label>
                        <input type="text" name="contacto_email_texto" class="form-control rounded-0" value="{{ $config['contacto_email_texto'] ?? 'contacto@artesanos.com' }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Etiqueta Teléfono</label>
                        <input type="text" name="contacto_tel_label" class="form-control rounded-0" value="{{ $config['contacto_tel_label'] ?? 'Teléfono' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Texto Teléfono</label>
                        <input type="text" name="contacto_tel_texto" class="form-control rounded-0" value="{{ $config['contacto_tel_texto'] ?? '+52 (481) 000 0000' }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Título o Mensaje Adicional (Pie de Tarjeta)</label>
                    <input type="text" name="contacto_extra_titulo" class="form-control rounded-0" value="{{ $config['contacto_extra_titulo'] ?? 'Horarios de atención' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción Adicional</label>
                    <textarea name="contacto_extra_desc" rows="2" class="form-control rounded-0">{{ $config['contacto_extra_desc'] ?? 'Lunes a Viernes de 9:00 AM a 6:00 PM.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sección PIE DE PÁGINA (FOOTER) -->
        <div class="card border-0 shadow-sm rounded-0 mb-4">
            <div class="card-header bg-dark text-white rounded-0">
                <h5 class="mb-0">Pie de Página (Footer)</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción Corta</label>
                    <textarea name="footer_descripcion" rows="2" class="form-control rounded-0">{{ $config['footer_descripcion'] ?? 'Plataforma dedicada a la promoción de artesanos y emprendedores locales.' }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Enlace de Instagram</label>
                        <input type="url" name="footer_instagram" class="form-control rounded-0" placeholder="https://instagram.com/..." value="{{ $config['footer_instagram'] ?? '' }}">
                        <div class="form-text">Déjalo en blanco para ocultar el ícono.</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Enlace de Facebook</label>
                        <input type="url" name="footer_facebook" class="form-control rounded-0" placeholder="https://facebook.com/..." value="{{ $config['footer_facebook'] ?? '' }}">
                        <div class="form-text">Déjalo en blanco para ocultar el ícono.</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Enlace de TikTok</label>
                        <input type="url" name="footer_tiktok" class="form-control rounded-0" placeholder="https://tiktok.com/..." value="{{ $config['footer_tiktok'] ?? '' }}">
                        <div class="form-text">Déjalo en blanco para ocultar el ícono.</div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Texto de Copyright (Derechos Reservados)</label>
                    <input type="text" name="footer_copyright" class="form-control rounded-0" value="{{ $config['footer_copyright'] ?? 'Artesanos y Emprendedores en Movimiento. Todos los derechos reservados.' }}">
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