# Artesanos en Movimiento

Aplicación Laravel para administrar usuarios y perfiles de emprendedores, marcas y productos. Las vistas usan Blade y Bootstrap; Vite procesa los recursos de resources/css y resources/js.

## Instalación local en PowerShell

Requiere PHP y Composer 2, PDO SQLite y Node.js compatible con Vite 8. composer.json permite PHP 8.3+, pero hay que comprobar los requisitos de las versiones bloqueadas con composer check-platform-reqs.

Desde la carpeta del proyecto, para una base local nueva:

```powershell
composer install
if (-not (Test-Path -LiteralPath .env)) { Copy-Item .env.example .env }
php artisan key:generate
if (-not (Test-Path -LiteralPath database/database.sqlite)) {
    New-Item -ItemType File -Path database/database.sqlite | Out-Null
}
php artisan migrate
php artisan storage:link
npm ci --ignore-scripts
npm run build
php artisan admin:create
php artisan serve
```

Visitar http://localhost:8000 para la portada pública. El acceso administrativo está en /login y el portal de emprendedores tiene entrada en /emprendedor/login. Configurar APP_URL con el origen real del sitio: los enlaces de recuperación se generan desde ese valor.

Las instrucciones anteriores están destinadas a una base local nueva. Revisar la configuración y los respaldos antes de migrar una base existente. No versionar .env. Mantener APP_DEBUG=false en producción.

La recuperación de contraseñas requiere configurar correo: el valor local MAIL_MAILER=log escribe mensajes en el registro y no los entrega al destinatario.

## Desarrollo y validación

En terminales separadas, ejecutar php artisan serve y npm run dev. Para comprobar el proyecto:

```powershell
php artisan test
php artisan route:list
php artisan view:cache
php artisan view:clear
npm run build
composer audit
npm audit
```

Los tests usan SQLite en memoria conforme a phpunit.xml. La auditoría de dependencias no sustituye las pruebas de permisos y reglas de negocio.

Para crear la cuenta administrativa inicial, ejecutar `php artisan admin:create`. Solicita nombre, correo y una contraseña oculta con confirmación. Puede recibir nombre y correo mediante `--name` y `--email`; la contraseña siempre se introduce de forma interactiva. No sobrescribe cuentas existentes.

Los seeders no crean cuentas ni contraseñas predeterminadas. Las contraseñas nuevas y sus cambios requieren al menos 12 caracteres con mayúscula, minúscula, número y símbolo; se valida además el límite de 72 bytes de bcrypt y se rechazan caracteres nulos. Las contraseñas existentes siguen permitiendo iniciar sesión; estos cambios no modifican credenciales guardadas.

Ambos portales usan la sesión `web` sobre `users`: `/login` admite administradores activos y `/emprendedor/login` admite emprendedores activos con perfil activo. Una sesión solo puede representar una cuenta a la vez. El acceso a la otra área devuelve 403; al desactivar una cuenta se cierra su sesión en la siguiente petición protegida. Un cambio de contraseña invalida las sesiones anteriores cuando intenten volver a entrar a rutas protegidas.

La recuperación por correo corresponde a administradores activos. Los emprendedores mantienen el flujo existente de contactar al administrador, quien cambia la contraseña desde la edición de usuarios. La URL antigua `/admin/emprendedores` redirige al CRUD de usuarios, que incluye ambos roles. Se retiraron las acciones antiguas de emprendedores que apuntaban a métodos inexistentes.

Los intentos de login se limitan a 5 por minuto por combinación de correo/IP y 30 por minuto por IP, compartidos por ambos portales. La recuperación y el restablecimiento tienen un límite compartido de 5 solicitudes por minuto por IP.

## Organización

| Carpeta | Responsabilidad |
| --- | --- |
| app/Http/Controllers | Acciones administrativas, inicio de sesión y portal de emprendedores |
| app/Models | Usuarios, perfiles, marcas, categorías, productos y secciones web |
| routes | Rutas HTTP y comandos de consola |
| database | Migraciones, fábricas y seeders |
| resources/views | Vistas Blade y layouts |
| tests | Pruebas automatizadas |

Ver [el diagnóstico del PR #3 sin correcciones complementarias](docs/hallazgos-pr3-sin-correcciones-2026-11-17.md) y [el informe de correcciones y validación](docs/informe-revision-pr3-2026-11-17.md). Ambos distinguen el código original, los cambios aplicados, los resultados reproducidos y los puntos pendientes.
