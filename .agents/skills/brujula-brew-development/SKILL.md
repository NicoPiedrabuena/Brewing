---
name: brujula-brew-development
description: Desarrollar, corregir y revisar Brújula Brew Log en este repositorio Laravel, respetando su dominio cervecero, su interfaz Blade y su PWA. Usar para cambios técnicos dentro del proyecto BREWING; no usar para asesoramiento cervecero general.
---

# Brújula Brew Log

Trabajar sobre la aplicación existente y conservar su enfoque simple: Laravel renderiza una única interfaz Blade, Eloquent persiste los datos y JavaScript mejora la navegación y los diálogos sin convertirla en una SPA.

Antes de un cambio sustancial, leer [references/project-map.md](references/project-map.md). Consultar solamente las secciones relevantes para la tarea.

## Invariantes del proyecto

- Mantener la interfaz y los mensajes visibles en español, con diseño responsive y prioridad para uso móvil.
- Conservar las relaciones `Recipe -> Batch -> Reading` y sus borrados en cascada. No borrar una receta o historial sin una acción explícita del usuario.
- Tratar los valores de densidad como gravedad específica: OG entre `1.000` y `1.200`, FG y lecturas entre `0.990` y `1.200`. Preservar tres decimales.
- Usar solamente estos estados persistidos de lote: `planned`, `brewing`, `fermenting`, `conditioning`, `packaged`, `finished`. Traducirlos en la presentación, no en la base de datos.
- Calcular el ABV real desde OG y FG mediante el accessor de `Batch`; no duplicar ese cálculo en controladores o vistas.
- Mantener validación de entrada en el servidor aunque también se agregue ayuda o validación en el navegador.
- Los assets específicos de la aplicación viven actualmente en `public/css/brew.css` y `public/js/brew.js`; no asumir que Vite los compila.
- Si cambia un asset cacheado o la estrategia offline, actualizar el identificador `CACHE` de `public/sw.js` para que instalaciones existentes reciban la nueva versión.

## Forma de implementar

- Seguir primero los patrones existentes; introducir servicios, componentes o dependencias nuevas sólo cuando reduzcan complejidad real.
- Para cambios de datos, crear una migración nueva. No reescribir una migración que pueda haber sido ejecutada salvo que la tarea sea reconstruir deliberadamente una base descartable.
- Sincronizar cada campo nuevo o modificado entre migración, `$fillable`, casts, validación, formulario y presentación. Revisar también el service worker si afecta assets.
- Evitar consultas N+1: cargar relaciones y conteos en `BrewController::index()` cuando las vistas los recorran.
- Mantener acciones HTTP con semántica REST y protección CSRF mediante las directivas Blade existentes.
- No copiar valores de `.env` a archivos versionados. La instalación local puede usar MySQL aunque `.env.example` conserve SQLite como configuración inicial.

## Verificación

Elegir verificaciones proporcionales al cambio:

1. Ejecutar `composer test` para comportamiento PHP y agregar pruebas Feature para rutas, validación, persistencia y cascadas que se modifiquen.
2. Ejecutar `npm run build` si se toca la configuración frontend o cualquier recurso administrado por Vite.
3. Para cambios en Blade, CSS, JavaScript o PWA, comprobar manualmente los flujos afectados en vista móvil y escritorio: navegación, apertura/cierre de diálogos, formularios, errores y estado offline cuando corresponda.
4. No ejecutar migraciones destructivas ni reemplazar datos locales como parte de una verificación ordinaria.

Al terminar, indicar qué se verificó y cualquier comprobación manual pendiente.
