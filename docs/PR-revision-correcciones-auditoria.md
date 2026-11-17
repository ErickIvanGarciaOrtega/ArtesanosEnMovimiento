# Completar las correcciones del PR #3 con diagnóstico y evidencia antes/después

## Qué se hizo

Esta entrega **incluye correcciones de aplicación**, además de las pruebas y el informe. Se revisó el [PR #3](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/pull/3), commit `58c72f7`, contra los 26 puntos de la [auditoría del PR #2](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/pull/2); se reprodujeron los casos pendientes, se aplicaron correcciones y se volvió a comprobar el resultado.

El PR está dividido en dos commits para poder revisar ambos estados:

| Parte | Contenido |
| --- | --- |
| **1. Descubierto sin nuestras correcciones** | Diagnóstico, suites y resultados del PR #3 original. Este commit solo añade documentación y evidencia; no modifica la aplicación ni dependencias. |
| **2. Descubierto con las correcciones aplicadas** | Código corregido, capa local de autenticación publicada, pruebas de regresión, ajustes de interfaz y evidencias posteriores. |

La base es `correcciones-auditoria`: el PR #3 continúa abierto. Así se revisan los cambios complementarios sin repetir como nuevos los cambios que ya hizo Erick. No se fusiona automáticamente el PR #3 ni se modifica `main`.

## 1. Qué se descubrió antes de aplicar nuestras correcciones

El [diagnóstico sin correcciones complementarias](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/hallazgos-pr3-sin-correcciones-2026-11-17.md) corresponde a un checkout aislado y limpio del commit original. Se instalaron sus versiones bloqueadas y se usaron base, archivos y correo de prueba.

| Caso | Resultado encontrado |
| --- | --- |
| **Autorización administrativa** | Una sesión emprendedora accede a acciones administrativas con 200 en lugar de 403. Los controles locales usados en la auditoría anterior faltaban en el remoto; el problema es previo al PR #3. |
| **S05, recuperación** | El enlace conserva un origen recibido en la petición. `trustHosts` usa patrones amplios; `localhost` también coincide con `localhost.ejemplo.test`. |
| **F08, búsqueda** | `buscar[]` deja de entrar a la consulta de nombre, pero llega a la vista y produce 500 en `htmlspecialchars`. |
| **F10, imágenes** | El rollback revierte filas, pero quedan archivos nuevos al fallar la inserción de una foto, en alta y edición. |
| **F01, descripción** | Pasa a ser obligatoria solo en backend mientras el formulario la sigue ofreciendo opcional. |
| **Sitio web** | `sitio_web[]` produce un error de tipos antes de validar. Ya estaba en la base del PR, no lo introduce este. |
| **U04 y U06** | En la línea de base de la integración anterior al ajuste de UI se confirmaron controles sin nombre, contraste insuficiente y desbordamientos móviles. |
| **U02 y U08** | Siguen incompletos el recorrido entre portal/sitio público y la paginación/carga anticipada de los demás listados. |

El original pasa **24/35** casos históricos y **6/12** regresiones finales. Los once fallos históricos incluyen diferencias de contrato y URL; **no son once vulnerabilidades independientes**. La tabla completa de los 26 puntos distingue lo corregido por Erick de lo que seguía pendiente en el [informe](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/informe-revision-pr3-2026-11-17.md).

## 2. Qué se aplicó y se volvió a probar

| Corrección incluida | Comportamiento después |
| --- | --- |
| **Roles, estado y sesiones** | Se incorpora la capa local que faltaba en el remoto: guard compartido, middleware de rol/estado, invalidación de sesiones, límites de intentos y recuperación de administradores activos. |
| **Contraseñas y alta inicial** | Se conservan las reglas locales de contraseña, se retiran credenciales predeterminadas del seeder y se incluye `admin:create` para aprovisionar la cuenta inicial. El README documenta el uso. |
| **F10** | Se revierten las filas y se limpian las rutas nuevas cuando falla crear o editar fotos. Se detecta `store=false`, se registra la excepción y se devuelve error de formulario. |
| **S05** | El origen del enlace se genera desde `APP_URL`; el host recibido no decide el destino del reset. Esto no sustituye la configuración global del hosting. |
| **F08** | Se normalizan buscar, search, categoría y marca antes de consultar y renderizar. |
| **F01 y sitio web** | La descripción sigue opcional y compatible con el esquema. Se comprueba el tipo antes de normalizar el sitio web y se restringen sus esquemas. |
| **U04** | Se asocian 34 etiquetas del CMS, filtros y archivo de perfil; se nombran acciones y enlaces sociales; se corrigen contraste, foco, salto al contenido y estado de menús. Se retiran superposiciones de la barra de guardar y de una etiqueta lateral. |
| **U06** | Las cabeceras, marcas y tarjetas se adaptan al ancho disponible. La tabla conserva su desplazamiento dentro del contenedor; se comprueba el ancho real con el recorte de raíz desactivado. |
| **Dependencias** | Se resuelve el lock del PR con Boost, se instala y se valida con Composer; los cambios adicionales de Guzzle, PSR-7 y polyfill se detallan en el informe. |

Los controles de autenticación y algunas pruebas ya existían localmente antes de esta revisión: se publican aquí porque no estaban en la base remota y son necesarios para que la entrega tenga los límites verificados. No se atribuyen como un hallazgo nuevo causado por el PR #3.

## Cómo se probó

| Comprobación | Sin nuestras correcciones | Con las correcciones aplicadas |
| --- | --- | --- |
| Auditoría, 35 casos | 24 aprobados, 11 fallidos; 77 aserciones | **35 aprobados; 190 aserciones** |
| Regresiones complementarias, 12 casos | 6 aprobados, 6 fallidos | Aprobadas dentro de la suite completa |
| Aplicación y regresiones | El resultado local antiguo no se atribuye al PR original | **86 aprobadas; 572 aserciones**, repetidas después de los ajustes de UI |
| Composer audit --locked | Sin avisos conocidos en esa ejecución | Sin avisos conocidos en esa ejecución |
| Móvil, después de corregir UI | Antes: documento de 475/375/333 px a viewport 320 para portada/admin/emprendedor | **37 estados sin desbordamiento**: 28 combinaciones de siete páginas y cuatro anchos, más nueve menús abiertos |
| Accesibilidad y teclado | La línea de base conservaba controles sin nombre y contrastes | **119 comprobaciones de navegador aprobadas**; axe sin infracciones detectadas en siete páginas; tres selectores revisados por colores calculados, 15.43:1 |
| Otros controles | — | Pint, build, `composer validate --strict` y `git diff --check` correctos; migraciones en SQLite desechable y down/up de sitio_web comprobados |

Se usaron PHP 8.5.0, Laravel 13.42.0, PHPUnit 12.5.43, Edge/Playwright y axe-core. Se mantuvieron las exigencias de integridad al adaptar la expectativa histórica de un 500 a una redirección con error de imágenes. El ejemplo genérico de arranque se sustituye por pruebas que comprueban la portada y los accesos con una base aislada.

Los recorridos comprobados incluyen login/logout de ambos roles, altas de categoría/emprendedor/marca/producto, imagen, descripción vacía, perfil público, búsqueda sin coincidencias y malformada, aviso de autodesactivación, borrado, menú con Enter, foco y guardado de CMS/perfil. Las cuentas y el contenido son sintéticos. No se probó producción ni concurrencia o carga.

## Evidencias

- [Original sin aplicar correcciones: suites, resultados y JUnit](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/tree/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/sin-correcciones).
- [Resultados comparados](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/resultados.json), [suite de aplicación](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/integracion-suite.xml) y [auditoría aplicada](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/integracion-auditoria.xml).
- [Validación final de accesibilidad/móvil](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/accesibilidad-movil/resumen.json), [medidas y recorridos](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/accesibilidad-movil/verificacion.json), [contraste revisado](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/accesibilidad-movil/contraste-revisado.json) y [JUnit después de UI](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/accesibilidad-movil/suite-aplicacion.xml).
- [Informe completo, causas, reproducción y criterios de cierre](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/informe-revision-pr3-2026-11-17.md).

Portada y panel administrativo después de las correcciones, a 375 px (base de prueba con contenido sintético):

![Portada corregida a 375 px](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/accesibilidad-movil/public-375.png)

![Panel administrativo corregido a 375 px](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/accesibilidad-movil/admin-375.png)

También se repitieron instalación, validación y pruebas sobre el checkout limpio preparado para publicar: [86 pruebas y auditoría de 35 casos aprobadas](https://github.com/ErickIvanGarciaOrtega/ArtesanosEnMovimiento/blob/revision/pr3-hallazgos-y-correcciones/docs/evidencias/revision-pr3-2026-11-17/publicacion/validacion-checkout-limpio.json).

## Qué sigue pendiente

- **U02:** completar los accesos contextuales y el recorrido de ida/regreso entre portal y sitio público.
- **U08:** paginar los demás listados y anticipar relaciones; medir rendimiento antes de declarar cerrado ese punto.
- Confirmar las decisiones de negocio documentadas y comprobar la configuración de despliegue: `APP_URL`, correo y almacenamiento. En local faltaba `public/storage`; se resolvió con `artisan storage:link` y se verificó la imagen con HTTP 200. Ese enlace del entorno no es un cambio de código del PR.

No se afirma que todo el PR #2 quede cerrado. U04/U06 quedan corregidos y comprobados en el alcance descrito; el análisis automatizado no sustituye una sesión exhaustiva con lector de pantalla.

## Revisión y supervisión

Se solicita a **@ErickIvanGarciaOrtega** la revisión y la responsabilidad de seguimiento de este PR. La separación por commits permite revisar primero los hallazgos del original y después el código y las evidencias de las correcciones.
