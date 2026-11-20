# Revisión de las correcciones del PR #3 frente a la auditoría del PR #2

Fecha: 17 de noviembre de 2026. Proyecto: ArtesanosEnMovimiento.

## Dictamen

El PR #3 contiene correcciones útiles, pero **todavía no permite cerrar la auditoría del PR #2 ni afirmar que todo funciona sin regresiones**. Hay correcciones incompletas, un cambio de validación que no coincide con el formulario y pendientes de interfaz. Además, el PR remoto carece de los controles locales de autenticación que estaban presentes cuando se preparó el PR #2.

Después de resolver el acceso a PHP 8.5.0, se ejecutó la revisión dinámica. El PR original pasa 24 de los 35 casos históricos de auditoría y 6 de las 12 regresiones finales. Nuestra integración pasa 86 pruebas de aplicación/regresión (572 aserciones) y los 35 casos de auditoría adaptados (190 aserciones). Los resultados no convierten cada test fallido en una vulnerabilidad y se distinguen las diferencias de contrato descritas abajo.

Recomendación: mantener pendiente la aprobación del PR original hasta incorporar y probar las correcciones descritas. Las regresiones de nuestra integración sí se ejecutaron; los casos U04/U06 se corrigieron posteriormente en la integración; U02 y U08 conservan su alcance pendiente. Esto no significa que todas sus correcciones fallen: varias atienden directamente las causas originales, como limitar los campos modificables, comprobar la marca del propietario y corregir los nombres de tablas en las reversiones.

## Cómo se entrega el diagnóstico y la aplicación

La rama de publicación es `revision/pr3-hallazgos-y-correcciones`, basada en `correcciones-auditoria` porque el PR #3 sigue abierto. Contiene dos commits: primero el [diagnóstico sin correcciones complementarias](hallazgos-pr3-sin-correcciones-2026-11-17.md), con aplicación y dependencias intactas; después el código corregido, la capa local de autenticación que faltaba publicar, las regresiones y la evidencia posterior. El segundo estado incluye implementación, no solo ejecución de pruebas.

Las suites originales se conservan en `evidencias/revision-pr3-2026-11-17/sin-correcciones`. La suite adaptada y los tests de aplicación corresponden al estado con correcciones. Los campos de publicación de las evidencias JSON describen su ejecución local, anterior a abrir el PR; no indican que el código de esta entrega sea únicamente documental. Se solicita a `ErickIvanGarciaOrtega` revisión y responsabilidad de seguimiento.

## Qué se comparó

| Referencia | Estado revisado |
| --- | --- |
| [PR #2](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/pull/2) | Informe de auditoría y 35 casos de reproducción; referencia `7dbb0be`, junto con cambios locales previos de autenticación. |
| [PR #3](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/pull/3) | Cabeza `58c72f77a0e42cabcf8a280200aecf5d33b197e7`, base `f56ecbaa7b98e1eec595c2603dfc85812bb97d08`: ocho commits, doce archivos. |
| Copia local integrada | Rama `integracion/pr3-2026-11-17`: cambios de aplicación del PR #3, cambios locales previos de autenticación y cinco ajustes complementarios descritos abajo. |

La cabeza remota se comprobó de nuevo y seguía siendo `58c72f7`. El conector no devolvió ejecuciones de workflows de PR ni estados de CI para ese commit. Esa consulta no aporta evidencia de pruebas aprobadas y no demuestra que no existan otros sistemas externos de validación.

Al comparar con `7dbb0be`, aparecen dieciséis archivos: además de los doce del PR, hay cambios previos de sitio web en modelo, migración y vistas. Se incluyeron en la integración local porque el controlador los necesita.

## La diferencia de autenticación cambia el resultado de la revisión

El informe del PR #2 declara que la auditoría se hizo con cambios locales de autenticación. Esos cambios **no forman parte del PR #3 remoto**. Comprobar solo nuestra copia integrada y dar ese resultado como si fuera del PR original ocultaría esta diferencia.

### R01. Las rutas administrativas remotas no comprueban el rol

Prioridad alta. Encontrado por revisión y confirmado en ejecución: las pruebas de acceso administrativo desde una sesión emprendedora recibieron 200 en lugar de 403. Ya estaba en la base; no lo introdujo el PR #3.

Evidencia: [routes/web.php en `58c72f7`, línea 83](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/58c72f77a0e42cabcf8a280200aecf5d33b197e7/routes/web.php#L83) protege el grupo administrativo solo con `auth`. El login administrativo, en la línea 51, llama a `Auth::attempt($credentials)` sin restringir rol ni estado. El login de emprendedores también inicia una sesión del guard por defecto. El controlador administrativo no añade una autorización de rol. La comparación con la base local confirma que el archivo de rutas no cambió en el PR.

Recorrido y criterio de validación en una base aislada:

1. Crear un usuario sintético de rol emprendedor e iniciar sesión por `/emprendedor/login`.
2. Pedir `GET /admin/usuarios`. El control actual solo exige que la sesión esté autenticada.
3. Comprobar que toda escritura administrativa devuelve 403 para ese rol. Un caso relevante es `PUT /admin/usuarios/{id-propio}` con nombre y correo válidos, `role=admin` y `status=activo`.
4. Verificar en la base que la cuenta no se convirtió en administradora.

Por el recorrido de código, las funciones administrativas quedan accesibles a una sesión autenticada sin autorización de rol. El control del último administrador no sustituye esa autorización. Esto afecta quince rutas del grupo administrativo; la frontera de roles del portal también requiere los controles locales existentes. Se confirmó acceso administrativo indebido; no se ejecutó una elevación de privilegios sobre una cuenta real. Todas las cuentas de prueba son sintéticas.

En la copia integrada se conservaron `auth:web`, `auth.session`, `role:admin` / `role:emprendedor`, la comprobación de cuenta activa y sus pruebas. **Son trabajo previo conservado, no una corrección nueva del PR #3 ni de esta segunda revisión.** Hay que incluir ese conjunto en la entrega final o asegurar controles equivalentes.

## Hallazgos sobre las correcciones del PR #3

### R02. S05 sigue abierto: la lista de hosts es un conjunto de patrones demasiado amplios

Prioridad alta condicionada al despliegue. El PR añade una protección que no cierra la causa original.

Evidencia: [bootstrap/app.php, línea 18](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/58c72f77a0e42cabcf8a280200aecf5d33b197e7/bootstrap/app.php#L18) pasa `artesanosyemprendedores.com`, `localhost` y `127.0.0.1` a `trustHosts`. Esos valores se interpretan como patrones, sin límites de inicio/final y con puntos que tienen significado de expresión regular.

Se contrastó con las versiones que propone el lockfile del PR: [TrustHosts en Laravel 13.42.0](https://github.com/laravel/framework/blob/v13.42.0/src/Illuminate/Http/Middleware/TrustHosts.php) pasa los patrones a Request; [Request en Symfony HttpFoundation 7.4.23](https://github.com/symfony/http-foundation/blob/v7.4.23/Request.php) los combina y usa `preg_match` contra el host. La combinación tampoco agrega anclas a cada patrón. Por ejemplo, `localhost.ejemplo.test` contiene `localhost` y coincide con ese patrón. Además, Laravel omite esta comprobación en entorno local y durante pruebas unitarias.

El controlador remoto de recuperación y la notificación por defecto siguen generando el enlace a partir del contexto de URL. Por ello, la protección del PR no basta para declarar resuelto S05. No se probó el proxy ni el servidor de producción y no se envió correo real.

Criterio de cierre: comprobar el enlace exacto con correo en memoria y un host ajeno; verificar también los hosts permitidos en una prueba que active el comportamiento de producción. Si se usa una lista de hosts, debe representar los dominios configurados con patrones anclados y escapados. En la copia local se eligió generar el origen del enlace desde APP_URL, descrito en C02.

### R03. F08 sigue abierto: se protege la consulta, pero no el campo mostrado

Prioridad media. Corrección incompleta de un fallo previo.

Evidencia: [PublicController.php, línea 30](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/58c72f77a0e42cabcf8a280200aecf5d33b197e7/app/Http/Controllers/PublicController.php#L30) comprueba si el término es string antes de usarlo en SQL. Sin embargo, [public/productos.blade.php, línea 21](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/58c72f77a0e42cabcf8a280200aecf5d33b197e7/resources/views/public/productos.blade.php#L21) sigue imprimiendo `request('buscar')` dentro del atributo value.

Reproducción ejecutada: `/productos?buscar[]=pieza` devuelve 500 en el PR original, con TypeError de htmlspecialchars al recibir un arreglo. El arreglo ya no pasa a la consulta de nombre, pero sigue llegando al escape HTML de Blade. Revisar también `categoria_id[]` y `marca_id[]`, que se usan en consultas y selectores. El criterio de cierre es una respuesta controlada sin error 500 y un formulario que pueda renderizarse; las cuatro variantes pasan en la integración. La copia local normaliza esos filtros antes de consultar y mostrar la vista.

### R04. F10 revierte las filas, pero deja archivos si falla la inserción de una foto

Prioridad media. La transacción mejora el comportamiento previo, pero no incluye el almacenamiento de archivos.

Evidencia: [EmprendedorController.php, línea 164](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/58c72f77a0e42cabcf8a280200aecf5d33b197e7/app/Http/Controllers/EmprendedorController.php#L164) crea el producto y luego guarda cada archivo antes de insertar su fila. El bloque de edición repite el patrón desde la línea 227. El catch devuelve un error de formulario, sin eliminar rutas ya escritas ni registrar la excepción.

Reproducción ejecutada: Storage::fake y una excepción al crear ProductoImagen confirmaron archivos huérfanos tanto en alta como en edición del PR original. Exigir que no queden ni un producto parcial ni archivos nuevos. En edición, exigir que sobrevivan los datos y archivos anteriores. Si `store()` devuelve false, tampoco debe presentarse la operación como correcta.

La copia local agrega compensación de las rutas nuevas y conserva la transacción. Las pruebas nuevas de creación y actualización fallan por archivos huérfanos en el PR original y pasan en la integración. Fallos persistentes del disco durante la propia limpieza y concurrencia quedan fuera de esta comprobación.

### R05. F01 cambia el requisito de descripción sin ajustar la pantalla

Prioridad media de flujo. Este cambio sí lo introduce el PR #3.

Evidencia: [EmprendedorController.php, línea 142](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/58c72f77a0e42cabcf8a280200aecf5d33b197e7/app/Http/Controllers/EmprendedorController.php#L142) y la línea 197 cambian descripción a `required|string`. Los formularios de alta y edición no se modifican para indicar el requisito ni exigirlo en el textarea.

Recorrido: abrir el alta, completar nombre, precio y categoría y dejar la descripción vacía, como permite la pantalla. El servidor devuelve un error de validación. Se evita el 500 original, pero ya no se satisface la expectativa del test que permitía crear sin descripción y cambia el contrato del formulario sin explicarlo.

Hay dos soluciones de producto válidas: hacerla obligatoria y alinear pantalla y pruebas, o conservarla opcional con un valor compatible con la columna no nullable. En la integración local se preservó el comportamiento opcional existente y se guarda una cadena vacía; no se impuso una nueva decisión de negocio.

### R06. sitio_web puede fallar antes de llegar a la validación

Prioridad baja de robustez. Ya aparece en el cambio de sitio web de la base `f56ecba`; no es una regresión introducida por el PR #3.

Evidencia: [perfilUpdate, línea 35](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/58c72f77a0e42cabcf8a280200aecf5d33b197e7/app/Http/Controllers/EmprendedorController.php#L35) usa `filled()` y luego `preg_match` / `trim` antes de validar el tipo de sitio_web. Enviar un arreglo permite alcanzar ese procesamiento con un tipo incorrecto. Se comprobó que la misma lógica ya estaba en la base del PR.

Cierre: enviar `sitio_web[]=ejemplo.test` y obtener error de validación, sin error de tipos. Comprobar también que un dominio sin protocolo se normaliza a HTTPS y que javascript: no se guarda. Se añadió la comprobación de tipo en la copia local.

## Estado de cada punto del PR #2

Los estados de esta tabla describen **el código del PR #3 original**. La sección de resultados ejecutados distingue ese código de la integración y señala los criterios históricos que necesitan adaptación. Una implementación visible en fuente no prueba por sí sola el cierre completo.

| ID | Resultado de revisión del PR #3 |
| --- | --- |
| S01 | Implementado en código: listas explícitas evitan editar propietario desde perfil, marca y producto. |
| S02 | Implementado en código: la validación de marca comprueba propietario tanto al crear como al editar. |
| S03 | Implementado en código: CMS exige imágenes reales, formatos permitidos y tamaño máximo. |
| S04 | Implementado en código: detalle y perfil restringen productos activos, como catálogo y relacionados. La cuenta inactiva sigue pública; bloquear su portal depende de los controles de autenticación que faltan en el remoto. |
| S05 | Incompleto: patrones de host demasiado amplios y comportamiento distinto en local/tests; R02. |
| V01 | Implementado en código para nuevas entradas: enlaces sociales y sitio web restringidos a HTTP/HTTPS. No se sanearon valores peligrosos ya guardados. |
| F01 | Incompleto: se evita el error de base haciendo obligatoria la descripción, sin alinear el formulario; R05. |
| F02 | Implementado en código: se actualiza name en users. |
| F03 | Implementado en código para el flujo secuencial: se comprueba el total de imágenes antes de modificar el producto. No se midió concurrencia. |
| F04 | Implementado en código: se protege también la pérdida de rol del último administrador. Esto no corrige R01. |
| F05 | Implementado en código: se detecta colisión de slug en alta y edición. No se probó la carrera entre peticiones. |
| F06 | Implementado en código: down() apunta a emprendedores y configuraciones. Ciclo aislado de la nueva migración y altas de esquema comprobados en la integración. |
| F07 | Implementado en código: min:0 evita precio negativo. Máximo y precisión siguen siendo decisiones aparte. |
| F08 | Incompleto: persiste la entrada no escalar en renderizado y otros filtros; R03. |
| F09 | Implementado en código para fotos de productos y perfil al eliminar usuario. Falta probar archivos y cascadas; no cubre una política de retención. |
| F10 | Incompleto: transacciones de filas sin compensación de archivos; R04. |
| D01 | El lock propone HttpFoundation 7.4.23, posterior a las correcciones identificadas en el PR #2. Instalado en el checkout original y auditado sin avisos conocidos en esta ejecución. |
| D02 | El lock propone Mime 7.4.22, versión de corrección indicada en el PR #2. Instalado en el checkout original y auditado sin avisos conocidos en esta ejecución. |
| U01 | Implementación estructural: botón móvil conectado al collapse y sidebar. La integración permite abrir el menú a 375 px, alcanzarlo con teclado y navegar al CMS; se midieron los cuatro anchos. |
| U02 | Parcial: los portales enlazan a home. El layout público sigue sin acceso visible al portal; faltan acciones contextuales Ver mi perfil / Ver producto y regreso. |
| U03 | Implementado en código: la edición de usuarios muestra session('status') con role=alert. El navegador confirmó el aviso de bloqueo y que la cuenta conserva estado activo. |
| U04 | Parcial: se añaden atributos en edición de usuarios y el botón móvil, pero siguen sin atenderse los selects de catálogo, acciones del listado y etiquetas del CMS señalados en el PR #2. Axe repetido en cinco páginas confirma que esos casos siguen presentes; ver axe.json. |
| U05 | Implementado en código para los filtros del formulario: distingue sin coincidencias y ofrece Limpiar filtros. El alias search no aparece entre las claves usadas para decidir ese mensaje. |
| U06 | Sin cierre: max-width en edición de usuarios no resuelve por sí mismo los casos medidos en portada y dashboards. No se modificó el layout público; las medidas repetidas coinciden con el desborde histórico. |
| U07 | Implementado en código: se elimina el cierre duplicado de form en logout. Se completó logout de administrador y emprendedor desde la interfaz; no se ejecutó un validador HTML exhaustivo. |
| U08 | Parcial: catálogo paginado a doce productos y conserva query string. Directorio y listados administrativos siguen con get(); imagenPrincipal se usa en el bucle sin carga anticipada en esa consulta. |

La tabla toma los criterios del [informe original](informe-auditoria-2026-11-03.md). No se contaron las 20 pruebas históricamente fallidas como 20 vulnerabilidades diferentes y no se trató una propuesta de negocio como si fuera un fallo de seguridad.

## Correcciones complementarias ya hechas localmente

Estos cambios **no están publicados en el PR #3 remoto**. Se hicieron durante la integración anterior y se mantienen identificados por separado.

| Cambio | Antes en el PR #3 | Después en la copia integrada | Archivo y prueba |
| --- | --- | --- | --- |
| C01 — F10 | Rollback de filas deja rutas nuevas en disco. | Guarda las rutas escritas, las limpia si falla crear/editar y detecta store=false; registra la excepción y devuelve error visible conservando entrada. | EmprendedorController; test_photo_creation_failure_removes_the_product_and_written_files y test_photo_update_failure_restores_product_and_keeps_previous_files. |
| C02 — S05 | Enlace depende del contexto de petición; hosts escritos como patrones amplios. | El origen del enlace sale de APP_URL y la ruta relativa de Laravel. No equivale a una política global de hosts. | AppServiceProvider; test_password_reset_url_uses_the_configured_origin_even_with_a_foreign_host. |
| C03 — F08 | Se ignora un arreglo para buscar en SQL, pero sigue llegando a la vista u otros filtros. | Normaliza buscar, search, categoria_id y marca_id antes de consultar y renderizar. | PublicController; cuatro entradas de test_array_filters_do_not_break_the_catalog_or_its_form. |
| C04 — F01 | Descripción requerida en backend, opcional en formulario. | Conserva descripción opcional con valor compatible con la columna existente. | EmprendedorController; test_optional_description_is_saved_without_database_errors. |
| C05 — R06 | sitio_web se normaliza antes de validar su tipo. | Solo normaliza strings sin esquema, mantiene validación HTTP/HTTPS y permite que un arreglo llegue a validación. | EmprendedorController; test_array_website_is_rejected_and_a_bare_domain_can_be_saved. |

También se agregó una prueba de perfil con campos válidos y propietario inyectado, y otra que intenta usar una marca ajena durante edición. Las reglas de contraseñas de AdminController, el guard y middleware locales se conservaron al resolver conflictos; no se atribuyen como nuevas correcciones.

La suite de auditoría cambia únicamente la respuesta esperada del fallo simulado de foto: ahora exige redirección y error de imágenes, además de producto revertido y cero archivos. No se eliminó la exigencia de integridad. La salida histórica de noviembre 3 se conserva y no se presenta como resultado nuevo.

## Resultados ejecutados

PHP 8.5.0 está accesible. El bloqueo inicial pertenecía a los permisos de la sesión y quedó resuelto. Se creó un worktree separado y limpio de `58c72f7`, se instaló su composer.lock y se ejecutaron pruebas sin cambiar su aplicación. La integración utiliza Laravel 13.42.0 y PHPUnit 12.5.43, con Boost conservado mediante resolución de Composer.

| Comprobación | PR original | Integración con correcciones |
| --- | --- | --- |
| Auditoría histórica, 35 casos | 24 aprobados / 11 fallidos, 77 aserciones | 35 aprobados, 190 aserciones |
| Regresiones finales, 12 casos | 6 aprobados / 6 fallidos | Incluidas y aprobadas dentro de la suite de 86 |
| Suite habitual más regresiones | No se atribuye la suite local al PR original | 86 aprobadas, 572 aserciones |
| Composer audit --locked | Sin avisos conocidos ni paquetes abandonados | Sin avisos conocidos ni paquetes abandonados |
| Pint / composer validate --strict / git diff --check | No se formateó el checkout original | Correctos |
| Migraciones | RefreshDatabase ejecuta su esquema durante los tests | Alta completa en SQLite desechable y down/up de sitio_web aprobados |
| Build | No se presenta el build local como ejecución del checkout original | npm run build correcto; advertencia opcional de fontaine |

Los once fallos históricos del original no son once vulnerabilidades independientes. Incluyen el 500 de buscar[], la cuenta inactiva que aún entra al portal, el Host del enlace de recuperación, ausencia de middleware de rol, dos URLs de eliminación de fotos distintas por el prefijo duplicado, el nuevo requisito de nombre y descripción, y la expectativa antigua de un 500 ante un fallo de foto. Esta última respuesta 302 puede ser una mejora; las regresiones nuevas demuestran aparte que sí quedan archivos huérfanos. No se atribuyen las diferencias de ruta o contrato a una regresión nueva sin esa evidencia.

Los seis fallos de las doce regresiones finales del original son: buscar[] al renderizar, descripción opcional, sitio_web[] antes de validar, origen de recuperación y archivos huérfanos al crear y al editar. Las doce pasan en la integración. La duodécima comprueba down/up de la migración de sitio_web. Se corrigió la preparación del caso de fallo de foto para enviar descripción válida y llegar realmente a la escritura; sus aserciones finales se conservaron.

### Navegador, móvil y accesibilidad: línea de base anterior a la corrección de UI

Se usó Edge con Playwright y una base SQLite desechable, correo array y cuentas sintéticas. El primer recorrido completó 41 comprobaciones antes de omitir la categoría obligatoria al guardar perfil: esa parada fue un error de preparación del recorrido, no un fallo del servidor. La continuación con la categoría completa verificó guardado, normalización del sitio, perfil público, imagen cargada, borrado, 404 del producto eliminado y logout.

Se comprobó: login y logout de ambos roles, menú administrativo móvil y navegación por teclado al CMS, bloqueo visible de autodesactivación, altas de categoría/emprendedor/marca/producto, imagen real, descripción vacía, búsqueda sin resultados, búsqueda malformada controlada y publicación. No es una prueba exhaustiva de todos los recorridos ni una campaña de concurrencia.

Faltaba public/storage: el archivo recién subido existía pero la URL devolvía 403. Se ejecutó artisan storage:link; la imagen pasó a 200 y cargó en navegador. El producto y su archivo sintético se eliminaron desde la interfaz al terminar. Este ajuste local de configuración no es una corrección de código atribuible al PR.

| Rol/página | Anchos con desbordamiento confirmados |
| --- | --- |
| Portada | 320, 375 y 414 px: documento de 475 px |
| Administración | 320 px: documento de 375 px; 375 px: documento de 378 px |
| Emprendedor | 320 px: documento de 333 px |

Se midieron los tres roles a 320, 375, 414 y 768 px. A 768 px no hubo desborde en esas páginas. U06 sigue abierto.

Axe se repitió a 1280 px en cinco páginas: portada sin infracciones detectadas por las reglas ejecutadas; catálogo con selectores sin nombre y contraste; usuarios con botones/enlaces sin nombre y contraste; CMS con 31 controles sin etiqueta asociada y contraste; perfil con un archivo sin etiqueta y contraste. Son nueve combinaciones regla/página; las cantidades dependen de las filas sintéticas. U04 sigue abierto. No se realizó una sesión exhaustiva con lector de pantalla.

### Dependencias y evidencia

Composer resolvió el lock del PR junto con Boost. Respecto al lock remoto, Guzzle pasó de 8.3.1 a 8.5.2, PSR-7 de 3.2.0 a 3.4.0 y polyfill-php85 de 1.43.0 a 1.44.0, además de las dependencias de Boost. No se mezcló el lockfile a mano. La instalación terminó, validate --strict pasó y la suite completa se ejecutó después. Un intento lento se detuvo durante autoload y se completó con install desde el lock ya resuelto; no se dejó una instalación a medias.

Evidencia reproducible en [resultados.json](evidencias/revision-pr3-2026-11-17/resultados.json), los cuatro JUnit XML del mismo directorio, [recorridos.json](evidencias/revision-pr3-2026-11-17/recorridos.json), [continuacion.json](evidencias/revision-pr3-2026-11-17/continuacion.json), [axe.json](evidencias/revision-pr3-2026-11-17/axe.json) y los dos composer-audit-*.json. Los registros preliminares del recorrido indican la omisión de categoría; la continuación documenta la resolución. Las capturas public-375.png, admin-375.png y emprendedor-375.png conservan la medición visual.

Para repetir backend desde la raíz: php -d opcache.enable_cli=0 vendor/bin/phpunit --configuration phpunit.xml --do-not-cache-result; después ejecutar la suite de auditoría explícita de docs/evidencias/auditoria-2026-11-03/AuditSecurityTest.php con el mismo runner. Las bases de PHPUnit son SQLite en memoria. La opción de opcache se limita al proceso de comprobación; no se cambió php.ini.

## Correcciones locales de accesibilidad y móvil (U04 y U06)

Tras la petición de comprobar estos pendientes, se corrigieron sus causas en la integración local. El PR #3 remoto sigue siendo el mismo; estos cambios no se le atribuyen como si ya estuvieran publicados.

### Qué se cambió

- Se asociaron las 34 etiquetas del CMS a sus controles, incluidos los tres enlaces sociales, sin cambiar names, rutas ni valores. Los filtros de catálogo y el archivo del perfil también tienen etiquetas asociadas.
- Las acciones de editar y eliminar usuarios incluyen el nombre de la cuenta en su nombre accesible. Los iconos decorativos de esas acciones se ocultan al lector de pantalla. Los enlaces sociales del footer también tienen nombre.
- Se ajustaron los contrastes de navegación, estados activos y botones de contorno. El estado activo se distingue además con subrayado y aria-current. Se agregó nombre y estado expandido a los menús, salto al contenido y foco visible.
- Las marcas usan una denominación breve en móvil; el enlace público conserva el nombre completo accesible. Se corrigen mínimos de flexbox y saltos de línea de cabeceras, tarjetas y distintivos. El portal colapsa su menú antes de que sus enlaces se compriman.
- Se acortó la opción lateral a Usuarios porque la etiqueta anterior quedaba parcialmente tapada en escritorio. El botón de guardar del CMS vuelve al flujo al final del formulario: su barra flotante podía cubrir un campo.
- Las reglas compartidas están en resources/views/layouts/accessibility.blade.php, incluidas desde los tres layouts. No se añadieron dependencias del proyecto.

### Cómo se comprobó

119 comprobaciones aprobadas en navegador: 28 combinaciones de siete páginas y cuatro anchos, más nueve estados con el menú abierto. Se midió scrollWidth con overflow-x:clip desactivado temporalmente en html/body, para evitar que el recorte ocultase un fallo de distribución. Los 37 estados caben en su viewport; las tablas mantienen su desplazamiento dentro del contenedor y la región de usuarios admite foco por teclado.

| Página a 320 px | Antes: ancho del documento | Después: ancho del documento |
| --- | ---: | ---: |
| Portada | 475 px | 320 px |
| Panel administrativo | 375 px | 320 px |
| Panel de emprendedor | 333 px | 320 px |

Axe no detectó infracciones de las reglas WCAG 2 A/AA y 2.1 A/AA ejecutadas en siete páginas: portada, catálogo, dashboard/usuarios/CMS de administración y dashboard/perfil de emprendedor. Los tres casos incompletos restantes son selectores con la flecha SVG de Bootstrap; no se ocultaron ni se desactivó la regla. El par de colores calculados es #212529 sobre #ffffff, con contraste 15.43:1, superior al mínimo 4.5:1 usado para ese texto. Los casos previos de elementos superpuestos desaparecieron después de corregir el menú y la barra de guardar.

El recorrido comprueba activación de menús con Enter, foco dentro del menú, salto al contenido, región de tabla enfocable, nombre de acciones y archivo, 34 asociaciones CMS y guardado de CMS/perfil con sesiones reales sobre la base desechable. Las 86 pruebas de aplicación/regresión vuelven a pasar con 572 aserciones; Pint y git diff --check pasan. La compilación vuelve a terminar correctamente.

Se inspeccionaron las capturas a 375 px de los tres roles. Los datos visibles son sintéticos de la base de prueba. Esta comprobación cierra los casos U04/U06 reproducidos en ese alcance local; no es una certificación general ni sustituye una sesión completa con lector de pantalla.

Evidencias posteriores: [resumen](evidencias/revision-pr3-2026-11-17/accesibilidad-movil/resumen.json), [recorrido y medidas](evidencias/revision-pr3-2026-11-17/accesibilidad-movil/verificacion.json), [contraste revisado](evidencias/revision-pr3-2026-11-17/accesibilidad-movil/contraste-revisado.json) y [JUnit](evidencias/revision-pr3-2026-11-17/accesibilidad-movil/suite-aplicacion.xml). Capturas: [portada](evidencias/revision-pr3-2026-11-17/accesibilidad-movil/public-375.png), [administración](evidencias/revision-pr3-2026-11-17/accesibilidad-movil/admin-375.png) y [emprendedor](evidencias/revision-pr3-2026-11-17/accesibilidad-movil/emprendedor-375.png). La evidencia anterior se conserva como línea de base.

## Condiciones para cerrar

1. Acordar e incluir la capa de autenticación y autorización que faltaba en el remoto.
2. Corregir S05, F08, F10 y el contrato de descripción; incorporar los ajustes locales después de probarlos.
3. Dependencias resueltas y suites ejecutadas en la integración: conservar las salidas y repetirlas si cambian las correcciones antes de publicar.
4. Recorridos principales y recuperación en pruebas aisladas comprobados; ampliar el recorrido cuando se corrija la interfaz.
5. U04/U06 corregidos y verificados localmente según la sección anterior. U02 y U08 siguen requiriendo el trabajo descrito en su alcance.

El PR original requiere correcciones por los fallos reproducidos. La integración pasa las comprobaciones de backend y los recorridos descritos, y ahora corrige los casos de accesibilidad y desbordamiento reproducidos. U02 y U08 mantienen sus pendientes específicos. Esto permite valorar lo que sí mejoró sin certificar ausencia total de errores ni cerrar por completo el PR #2.
