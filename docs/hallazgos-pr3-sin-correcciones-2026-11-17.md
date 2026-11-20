# Hallazgos del PR #3 antes de aplicar nuestras correcciones

Referencia revisada: `58c72f77a0e42cabcf8a280200aecf5d33b197e7`, rama `correcciones-auditoria`. Criterios: [auditoría del PR #2](informe-auditoria-2026-11-03.md). Fecha: 17 de noviembre de 2026.

Este documento registra el estado del PR #3 original, que ya contenía las correcciones de Erick. «Sin correcciones» significa antes de nuestras correcciones complementarias: no significa volver al estado anterior al PR #3. El primer commit de esta entrega agrega únicamente diagnóstico, pruebas de reproducción y evidencias; no cambia la aplicación ni sus dependencias.

## Qué se comprobó

Se instaló el composer.lock del PR en un checkout aislado y limpio. Se usó PHP 8.5.0, Laravel 13.42.0 y PHPUnit 12.5.43, con SQLite en memoria, almacenamiento simulado y correo en memoria. No se enviaron correos reales ni se modificó la base de datos habitual.

| Comprobación | Resultado sin nuestras correcciones |
| --- | --- |
| Suite histórica de auditoría | 35 casos: 24 aprobados, 11 fallidos; 77 aserciones. |
| Regresiones complementarias | 12 casos: 6 aprobados, 6 fallidos. |
| Autorización administrativa | Una sesión emprendedora obtuvo 200 en rutas que deben devolver 403. |
| Dependencias propuestas por el PR | Composer audit --locked no reportó avisos conocidos en esa ejecución. |

Los fallos de la suite histórica incluyen diferencias de contrato y de URL: una descripción y un nombre que ahora se exigen, rutas de imagen con prefijo duplicado y una expectativa antigua de error 500 que el PR cambia a redirección. No equivalen a once vulnerabilidades independientes. Las doce regresiones complementarias separan los fallos de integridad de esas diferencias.

## Hallazgos que impiden dar por cerrado el trabajo

| Caso | Qué se encontró | Evidencia o criterio |
| --- | --- | --- |
| R01 — autorización previa no incluida en el remoto | Las rutas administrativas solo exigen auth, sin rol; el login administrativo no restringe rol/estado. | Respuestas 200 para emprendedores; rutas y controladores del commit fijado. Es previo al PR #3. |
| S05 — recuperación | El enlace conserva un origen recibido de la petición. Los dominios de trustHosts son patrones amplios: localhost también coincide con localhost.ejemplo.test. | Correo en memoria, URL generada y fuentes de Laravel/Symfony de las versiones bloqueadas. |
| F08 — búsqueda | buscar[] se evita en la consulta de nombre, pero llega al escape HTML de la vista. | HTTP 500 y TypeError de htmlspecialchars. |
| F10 — imágenes | La transacción revierte filas, pero no elimina los archivos nuevos si falla insertar la foto. | Archivos huérfanos reproducidos al crear y editar con un fallo de persistencia inyectado. |
| F01 — descripción | Se vuelve obligatoria en backend, pero la pantalla la sigue ofreciendo opcional. | La creación sin descripción devuelve validación en lugar del alta esperada. Requiere alinear el contrato. |
| R06 — sitio web | sitio_web[] alcanza preg_match antes de validar el tipo. | TypeError reproducido. Ya aparece en la base del PR, no lo introduce este. |
| U02 — navegación | Solo se cubre parte del recorrido entre portal y sitio público. | Falta el acceso público al portal y acciones contextuales de ida/regreso. |
| U04 — accesibilidad | Quedan controles sin etiqueta/nombre y contrastes insuficientes. | Axe y selectores documentados en la auditoría; se repiten en la línea de base de la integración antes de corregir UI. |
| U06 — móvil | Se mantienen causas de desborde en marca, cabeceras y tarjetas. | Línea de base: portada 475 px a viewport 320; administración 375 px y emprendedor 333 px. |
| U08 — listados | Catálogo paginado, pero otros listados siguen con get() y algunas imágenes se cargan por elemento. | Revisión de consultas; no se midió carga concurrente. |

## Qué sí atiende el PR #3 en código

| Puntos del PR #2 | Resultado de lectura y comprobación |
| --- | --- |
| S01 / S02 | Listas explícitas de campos y validación de marca por propietario. Las regresiones de perfil con campos válidos y marca ajena confirman los límites revisados. |
| S03 | Validación de tipo/contenido/tamaño para las imágenes CMS. |
| S04 | Productos inactivos excluidos de detalle, perfil y relacionados. La cuenta inactiva sigue pública, pero bloquear su portal depende de la autenticación faltante. |
| V01 | Nuevas entradas sociales restringidas a HTTP/HTTPS; no se saneó la base existente. |
| F02 / F03 | Se guarda el nombre del perfil y se valida el total de imágenes antes de actualizar. |
| F04 / F05 | Se protege la pérdida de rol del último administrador y se detectan colisiones de slug. |
| F06 / F07 / F09 | Se corrigen nombres de tabla en down(), se rechaza precio negativo y se eliminan fotos al borrar usuario. |
| D01 / D02 | Versiones propuestas corregidas según los avisos de referencia; audit --locked actual sin avisos conocidos. |
| U01 / U03 / U05 / U07 | Hay menú móvil, aviso de bloqueo en edición de usuarios, mensaje de cero resultados y cierre de formulario corregido. Los recorridos posteriores y sus límites se detallan en la entrega aplicada. |

No se atribuye al PR #3 la ausencia de controles locales que nunca se publicaron. Tampoco se presentan las 74 pruebas anteriores a este PR como una validación del código remoto actual.

## Evidencias sin aplicar cambios

- [Suite histórica exacta](evidencias/revision-pr3-2026-11-17/sin-correcciones/AuditSecurityTest.php), conservada sin la adaptación posterior de la respuesta de error de foto.
- [Doce regresiones usadas para comparar](evidencias/revision-pr3-2026-11-17/sin-correcciones/Pr3IntegrationTest.php), fuera de la suite habitual para poder ejecutarlas explícitamente contra el checkout original.
- [Resultado estructurado](evidencias/revision-pr3-2026-11-17/sin-correcciones/resultados.json).
- [JUnit de los 35 casos](evidencias/revision-pr3-2026-11-17/sin-correcciones/original-auditoria.xml) y [JUnit de las 12 regresiones](evidencias/revision-pr3-2026-11-17/sin-correcciones/original-pruebas-nuevas.xml).
- [Auditoría de dependencias original](evidencias/revision-pr3-2026-11-17/sin-correcciones/composer-audit-original.json).

Para repetir, preparar un checkout aislado del commit fijado, instalar su lockfile y ejecutar cada archivo de pruebas de este directorio de manera explícita con su phpunit.xml. No ejecutar las suites original y adaptada en un mismo proceso: comparten el nombre de clase histórica. Configurar una clave de aplicación sintética y el entorno de pruebas; nunca usar una base real.

Las correcciones y los resultados posteriores se entregan en un commit separado, para que el diagnóstico pueda revisarse sin mezclarlo con la implementación.
