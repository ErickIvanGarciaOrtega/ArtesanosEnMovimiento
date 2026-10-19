# Propuesta de mejora de flujo, navegación, seguridad e idempotencia

Estado: propuesta para revisión, sin implementación.
Fecha: 19 de octubre de 2026.
Versión revisada: [13255482d2062ae6639d75719b3166c7da03805a](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/commit/13255482d2062ae6639d75719b3166c7da03805a).

## 1. Objetivo y alcance de este PR

Completar el recorrido del visitante desde la búsqueda hasta el contacto con el artesano y facilitar que el emprendedor mantenga su catálogo. Cada mejora deberá incluir controles de acceso, reglas de publicación y pruebas de reintentos.

Este PR incorpora únicamente esta propuesta. No cambia rutas, controladores, vistas, migraciones, dependencias ni configuración. Las pruebas descritas son trabajo propuesto y no resultados de ejecución. La aprobación del documento acuerda un alcance; la implementación se presentará en PR posteriores.

La auditoría anterior tomó como referencia el commit 605dfbc. Los cambios recientes requieren revisar qué observaciones siguen vigentes. Este documento no declara corregidos todos los hallazgos de aquella versión.

## 2. Situación observada en el código actual

La siguiente evidencia procede de lectura de código, sin una nueva prueba de explotación ni revisión visual en navegador.

| Área | Observación | Referencia en la versión revisada |
| --- | --- | --- |
| Inicio | Hay llamadas a la acción y categorías con destino # y textos de ejemplo. | [public/home.blade.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/resources/views/public/home.blade.php) |
| Catálogo | Las tarjetas muestran información, pero no enlazan a una ficha de producto. Faltan paginación y una acción para limpiar filtros. | [public/productos.blade.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/resources/views/public/productos.blade.php) |
| Cuenta | El menú público no ofrece una entrada al área privada; el portal tampoco ofrece un acceso directo a la vista pública propia. | [layouts/public.blade.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/resources/views/layouts/public.blade.php), [layouts/emprendedor.blade.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/resources/views/layouts/emprendedor.blade.php) |
| Visibilidad | El catálogo filtra productos activos, pero el directorio no filtra estados y el perfil público carga todos los productos relacionados. | [PublicController.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/app/Http/Controllers/PublicController.php) |
| Roles | Los grupos privados exigen auth sin un control explícito de rol en esas declaraciones. | [routes/web.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/routes/web.php) |
| Relaciones | Editar o eliminar marcas y productos sí limita el registro al propietario; asignar una marca al producto solo comprueba que exista. | [EmprendedorController.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/app/Http/Controllers/EmprendedorController.php) |
| Redes sociales | Los campos se aceptan como texto y la nueva vista pública los utiliza como enlaces. Deben acordarse formato y esquemas permitidos. | [EmprendedorController.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/app/Http/Controllers/EmprendedorController.php), [public/emprendedor-detalle.blade.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/resources/views/public/emprendedor-detalle.blade.php) |
| Altas | Los controladores revisados no procesan una clave de idempotencia. Los productos nuevos se guardan con stock fijo en 1. | [EmprendedorController.php](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/13255482d2062ae6639d75719b3166c7da03805a/app/Http/Controllers/EmprendedorController.php) |

## 3. Recorridos propuestos

### Visitante

Inicio → categoría o búsqueda → catálogo filtrado → ficha de producto → perfil del artesano → contacto autorizado.

- Conectar los botones de inicio y categorías a rutas reales.
- Incorporar una ficha con descripción, precio, marca, categoría y enlace al creador. Mostrar imágenes cuando exista un flujo de medios implementado.
- Ofrecer búsqueda, cantidad de resultados, ordenamiento, filtros activos y “Limpiar filtros”.
- Paginar y conservar los filtros en la URL, al cambiar de página y al volver desde una ficha.
- Mostrar una salida útil cuando no haya resultados: limpiar filtros o volver al catálogo.
- Incorporar un botón de contacto usando exclusivamente los canales y datos que el artesano autorice publicar.
- Mostrar la sección actual y una ruta de regreso, por ejemplo “Inicio / Productos / Nombre del producto”.

### Emprendedor

Mi cuenta → perfil → marca opcional → producto → vista previa → publicación → edición o retirada.

- Añadir “Mi cuenta” en el sitio público y redirigir al área correspondiente al rol después del acceso.
- Mostrar “Ver mi perfil público” y el regreso al sitio público en el portal.
- Conservar un menú consistente al crear y editar marcas y productos.
- Ofrecer un estado inicial de borrador y una vista previa privada antes de publicar, sujeto a la decisión de negocio.
- Explicar los campos requeridos y conservar todos los datos y selecciones cuando haya errores.
- Mostrar estados de guardado, impedir dobles clics accidentales y recuperar el formulario tras un fallo.
- Advertir cambios sin guardar y explicar los efectos de eliminar una marca o producto.
- Permitir retirar una publicación sin exigir su borrado definitivo.
- Definir si el stock representa inventario o solo disponibilidad antes de cambiar su comportamiento.

### Administración

Mi cuenta → usuarios o categorías → edición → validación → confirmación del resultado.

- Comprobar el rol en el servidor y reflejar en el menú solo las acciones permitidas.
- Diferenciar visualmente éxito, advertencia, validación y error.
- Explicar por qué una categoría con productos no puede eliminarse.
- Proteger la conservación del último administrador en desactivación, cambio de rol, borrado y operaciones simultáneas.
- Presentar confirmaciones de borrado con el nombre del registro y su alcance.
- Añadir nombres accesibles a las acciones con iconos y una navegación que funcione con teclado.

## 4. Reglas comunes a acordar

| Tema | Propuesta inicial | Decisión necesaria |
| --- | --- | --- |
| Publicación | Mostrar productos publicados únicamente cuando la cuenta y el perfil del propietario estén activos. Aplicar la regla en inicio, catálogo, directorio, fichas y acceso directo por ID. | Confirmar qué significan activo, publicado, borrador e inactivo y el tratamiento de datos históricos. |
| Contacto | Mostrar canales autorizados y validados; no utilizar automáticamente el correo de acceso como contacto público. | Confirmar canal, datos publicados y consentimiento del artesano. |
| Stock | Mantener una regla explícita y uniforme en alta, edición y catálogo. | Definir inventario por unidades o disponibilidad. |
| Marca | Conservarla como opcional; si se selecciona, debe pertenecer al propietario del producto. | Confirmar si se permiten productos sin marca y si la marca puede tener otros usos compartidos. |
| Categorías | Mantener referencias coherentes al renombrar y eliminar; controlar colisiones del slug. | Decidir cómo migrar el nombre de categoría que hoy guarda el perfil como texto. |
| Redes sociales | Aceptar URL http/https validada o convertir un usuario al formato autorizado antes de publicar el enlace. | Acordar el formato por plataforma y los destinos permitidos. |
| Paginación | Propuesta inicial: 24 productos públicos por página y 20 filas en tablas privadas, con un máximo de tamaño validado por el servidor. | Confirmar cantidades y criterios de orden. |
| Idempotencia | Una clave por operación intencional, con resultado recuperable durante una ventana documentada. | Acordar retención, tratamiento de claves vencidas y respuesta al reintento en curso. |

Las decisiones pendientes se resolverán en la revisión de esta propuesta, antes de implementar los flujos afectados.

## 5. Contrato propuesto de idempotencia

La primera entrega cubriría las altas de productos, marcas y categorías. La creación de perfiles deberá ser atómica y respetar la relación de un perfil por usuario. Las ediciones y bajas tendrán pruebas de repetición de sus efectos.

### Identidad y alcance de la operación

- Generar una clave impredecible para cada nuevo formulario intencional. Blade puede enviarla en un campo oculto, separado del token CSRF.
- Conservar la clave durante un reintento del mismo envío. Una operación nueva recibe otra clave.
- Asociarla al usuario autenticado y a la operación; incluir el recurso de destino cuando corresponda.
- Calcular una huella de los campos de negocio normalizados. Excluir CSRF y otros valores transitorios; no guardar contraseñas o tokens de sesión como parte del registro.
- Verificar sesión, rol y propiedad en cada petición, incluidos los reintentos de una operación completada.
- Reservar la clave, guardar el cambio y registrar su resultado dentro de una operación atómica respaldada por una restricción única en base de datos. La desactivación visual del botón es un apoyo de interfaz.

### Comportamiento esperado

| Situación | Resultado propuesto |
| --- | --- |
| Primera petición válida | Crear un registro y conservar la referencia al resultado. |
| Misma clave y mismos datos, ya completada | Recuperar el resultado sin repetir la escritura. En formularios, puede devolver la misma redirección de éxito. |
| Misma clave completada con otros datos | Rechazar el conflicto, propuesta HTTP 409 con mensaje comprensible, sin modificar el registro. |
| Misma clave todavía en proceso | Esperar de forma acotada o devolver un estado temporal documentado, sin iniciar otra creación. El contrato HTTP exacto queda por acordar. |
| Datos inválidos | Permitir corregir y reenviar sin dejar una reserva que bloquee el formulario. |
| Fallo antes de confirmar la transacción | Revertir el cambio y la reserva; permitir el reintento. |
| Guardado confirmado, respuesta perdida | Recuperar el resultado anterior cuando llegue el reintento. |
| Dos claves nuevas con datos iguales | Considerarlas operaciones distintas y aplicar las reglas normales del negocio; no deduplicar productos solo por nombre o contenido. |
| Mismo texto de clave en cuentas diferentes | Mantener ámbitos independientes y no entregar resultados de otra cuenta. |
| Clave vencida | Aplicar una política explícita y probada; no prometer deduplicación más allá de la retención acordada. |
| Registro eliminado después de crearlo | El reintento no debe recrearlo silenciosamente. |

Una baja repetida puede devolver primero una redirección y después un 404: el criterio es que el registro siga eliminado y no se repitan efectos del borrado. En una edición se comparará el estado de negocio; un cambio de fecha técnica no demuestra por sí mismo un fallo de idempotencia.

Las notificaciones o integraciones futuras necesitan su propio control de reintentos. No se promete entrega exactamente una vez en servicios externos solo por tener una clave local.

La conservación del último administrador y la unicidad del perfil también necesitan protección frente a operaciones distintas que se solapen. Se comprobarán además de los reintentos con la misma clave.

## 6. Matriz de pruebas automatizadas propuesta

Todas las pruebas de esta matriz están pendientes de implementación y ejecución.

### Seguridad y reglas de negocio

| ID | Escenario | Criterio de aceptación |
| --- | --- | --- |
| SEG-01 | Visitante, emprendedor A, emprendedor B y administrador acceden a rutas privadas por GET, POST, PUT y DELETE. | Solo el rol y la identidad autorizados pueden consultar o modificar; un rechazo no cambia datos. |
| SEG-02 | A intenta editar o eliminar una marca o producto de B mediante su ID. | Rechazo 403/404 según el contrato, sin cambios en registros ajenos. |
| SEG-03 | A intenta asignar una marca de B al crear o editar su producto. | Error de validación y ninguna asociación ajena guardada. |
| SEG-04 | Borrador, producto retirado, cuenta inactiva y perfil inactivo se consultan por todos los recorridos públicos. | La regla acordada de publicación se mantiene incluso accediendo directamente por ID. |
| SEG-05 | Nombres y descripciones contienen etiquetas HTML; redes sociales reciben esquemas peligrosos o formatos no admitidos. | Contenido escapado y enlaces rechazados o normalizados según la política acordada. |
| SEG-06 | Búsqueda o IDs se envían como listas, con tipos incorrectos, valores inexistentes o tamaños excesivos. | Validación controlada y sin errores 500; límites de tamaño y ordenamiento aplicados en servidor. |
| SEG-07 | Una petición modifica datos sin CSRF válido o desde un origen no permitido. | Rechazo con CSRF activo y sin escrituras; esta prueba no usa la omisión habitual del modo testing. |
| SEG-08 | Logout, desactivación, cambio de rol o contraseña ocurren con sesiones abiertas. | La siguiente petición aplica los permisos y la revocación definidos. |
| SEG-09 | Login y recuperación reciben intentos repetidos, tokens vencidos/reutilizados y un fallo de correo. | Límites de intentos, respuestas que no enumeren cuentas y manejo controlado de fallos. |
| NEG-01 | Dos nombres de categoría producen el mismo slug; se renombra una categoría usada por perfiles o productos. | Resultado controlado y referencias coherentes, sin errores inesperados. |
| NEG-02 | Se elimina una marca o categoría con relaciones. | Se aplica la regla de conservación acordada y se comunica su alcance. |

### Idempotencia, atomicidad y concurrencia

| ID | Escenario | Criterio de aceptación |
| --- | --- | --- |
| IDE-01 | Repetir secuencialmente el alta con misma clave y datos. | Un registro y recuperación de su resultado. |
| IDE-02 | Repetir la clave completada con otro contenido. | Conflicto controlado y sin cambios. |
| IDE-03 | Simular pérdida de respuesta después de confirmar el guardado. | El reintento encuentra el registro previo. |
| IDE-04 | Forzar fallo antes de confirmar la transacción y reintentar. | Sin registros parciales ni reserva bloqueada; el reintento válido crea uno. |
| IDE-05 | Corregir un formulario rechazado por validación. | El envío corregido puede completarse una sola vez. |
| IDE-06 | Usar la misma clave desde dos cuentas. | Resultados aislados y sin exposición entre propietarios. |
| IDE-07 | Enviar varias peticiones simultáneas con la misma clave. | Una creación; las demás recuperan el resultado o reciben el estado temporal acordado. |
| IDE-08 | Vencer la ventana de retención o eliminar el recurso entre intentos. | Se aplica la política documentada sin recreación silenciosa ni garantías falsas. |
| IDE-09 | Repetir edición y borrado. | Estado final coherente, sin perfiles duplicados ni efectos comerciales repetidos. |
| CON-01 | Dos administradores intentan desactivarse entre sí; también se prueban cambio de rol y borrado. | Se mantiene al menos un administrador activo conforme a la regla acordada. |
| CON-02 | Dos ediciones intentan crear el perfil faltante del mismo usuario. | Un perfil por usuario y ninguna escritura parcial. |

### Flujo, navegador y volumen

| ID | Escenario | Criterio de aceptación |
| --- | --- | --- |
| FLU-01 | Inicio → categoría → catálogo → producto → artesano → contacto. | Destinos reales, regreso disponible y solo datos autorizados publicados. |
| FLU-02 | Buscar, filtrar, ordenar, paginar, abrir ficha y volver. | Se conservan filtros; limpiar filtros recupera el catálogo esperado. |
| FLU-03 | Entrar por Mi cuenta y navegar entre portal y sitio público. | Redirección por rol y menús consistentes, sin acceso cruzado. |
| FLU-04 | Guardar con errores y con conexión lenta; doble clic y reenvío. | Mensajes comprensibles, campos conservados y un solo efecto por envío. |
| FLU-05 | Guardar borrador, previsualizar, publicar, editar y retirar. | Estados y visibilidad coherentes en todas las páginas. |
| ACC-01 | Navegar con teclado y revisar anchos de 320, 375, 414 y 768 píxeles. | Foco visible, controles identificables y contenido utilizable sin desbordamientos que impidan la tarea. |
| VOL-01 | Aumentar el catálogo y solicitar distintos tamaños de página. | Cantidad de registros acotada, filtros conservados y máximo validado. |
| VOL-02 | Medir consultas, tamaño de respuesta y latencia en entorno representativo. | Resultados registrados y umbrales acordados con datos del cliente, sin inventar capacidad garantizada. |

### Entornos y evidencia de las pruebas

- Usar PHPUnit/Laravel para permisos, validación, reglas e idempotencia, con una base exclusiva de pruebas y datos sintéticos.
- Usar pruebas en navegador para formularios, doble clic, regreso a resultados, menús móviles y accesibilidad.
- Simular correo y almacenamiento en pruebas aisladas; registrar explícitamente cuándo el fallo fue inducido.
- Probar concurrencia con procesos distintos contra una base compartida de pruebas y el motor acordado para despliegue.
- Ejecutar la suite en paralelo con una base independiente por proceso no reproduce carreras sobre el mismo registro.
- Separar en el reporte los resultados de pruebas aisladas, concurrencia real, navegador y verificaciones del servidor.
- Adjuntar a cada PR de implementación casos ejecutados, resultado y límites. Si un caso no se ejecutó, debe figurar como pendiente.

## 7. Entregas posteriores sugeridas

| Orden | Entrega | Evidencia requerida para cerrar |
| --- | --- | --- |
| 1 | Acordar publicación, contacto, stock, categorías y contrato de reintento. | Decisiones registradas y matriz ajustada. |
| 2 | Permisos, visibilidad y propiedad de registros. | Pruebas de acceso y publicación por todas las rutas. |
| 3 | Idempotencia de altas y protección de invariantes de datos. | Reintentos, fallos y concurrencia compartida comprobados. |
| 4 | Recorridos públicos, ficha de producto, filtros y acceso a cuenta. | Navegación completa y preservación de contexto en navegador. |
| 5 | Formularios, publicación/retirada, confirmaciones y accesibilidad. | Datos recuperables, estados claros y pruebas móviles/teclado. |
| 6 | Volumen y revisión del despliegue. | Métricas representativas y comprobaciones del servidor documentadas. |

Cada entrega se revisará por separado. No se proponen cambios de dependencias o de tecnología como requisito previo para aprobar este documento.

## 8. Criterios para aprobar esta propuesta documental

- [ ] El recorrido público termina en un canal de contacto acordado.
- [ ] Se acordaron las reglas de publicación y disponibilidad.
- [ ] Se definieron propiedad de marcas, categorías y conservación de datos.
- [ ] Se acordó el contrato de idempotencia, incluida retención y petición en curso.
- [ ] La matriz incluye seguridad, reintentos, atomicidad y concurrencia compartida.
- [ ] Se aceptó el orden de entregas y los límites de cada entorno de prueba.

La aprobación de este PR no significa que esas pruebas hayan pasado ni que el sistema actual cumpla los criterios. Autoriza conservar y discutir la propuesta; el trabajo de implementación deberá revisarse por separado.
