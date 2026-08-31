# Mapa técnico

## Arquitectura

- Runtime: PHP 8.2+, Laravel 12 y Eloquent.
- Entrada web: `routes/web.php` dirige todos los flujos a `app/Http/Controllers/BrewController.php`.
- Dominio: `Recipe`, `Batch` y `Reading` en `app/Models/`.
- Esquema inicial del dominio: `database/migrations/2026_08_28_000001_create_brewing_tables.php`.
- Interfaz principal: `resources/views/brew.blade.php`, renderizada completamente en el servidor.
- Interacción progresiva: `public/js/brew.js`; estilos: `public/css/brew.css`.
- PWA: `public/manifest.webmanifest`, `public/sw.js` y `public/icon.svg`.
- Vite/Tailwind están instalados por el esqueleto Laravel, pero la interfaz Brew actual enlaza directamente los assets de `public/`.

## Modelo del dominio

`Recipe` tiene muchas `Batch`; `Batch` pertenece a una receta y tiene muchas `Reading`. Las claves foráneas eliminan en cascada hacia lotes y mediciones.

Una receta guarda objetivos reutilizables: volumen, OG, FG, ABV, IBU, color, ingredientes, proceso y notas. Un lote conserva los valores reales de una cocción. Una medición es una observación fechada de gravedad, temperatura, pH y notas.

Los lotes activos que aparecen en Inicio son exclusivamente `brewing`, `fermenting` y `conditioning`. `planned`, `packaged` y `finished` siguen visibles en la bitácora, pero no en esa selección.

## Flujos y rutas

- `GET /`: tablero, recetas, cocciones y diálogos.
- `POST /recetas`: crear receta.
- `PATCH /recetas/{recipe}`: editar receta.
- `DELETE /recetas/{recipe}`: borrar receta, sus lotes y sus mediciones.
- `POST /lotes`: crear una cocción desde una receta.
- `PATCH /lotes/{batch}`: actualizar seguimiento y cierre.
- `POST /lotes/{batch}/mediciones`: registrar una medición.

La navegación entre Inicio, Recetas y Cocciones ocurre en el navegador mostrando u ocultando secciones de la misma respuesta HTML. Los formularios hacen envíos tradicionales y regresan con mensajes flash o errores de validación.

## Convenciones visuales

- Paleta definida mediante variables CSS: papel cálido, tinta oscura, dorado y verde.
- Tipografías: DM Sans y DM Serif Display obtenidas desde Google Fonts.
- Punto de quiebre móvil principal: `700px`.
- En escritorio hay navegación superior; en móvil, barra inferior fija.
- Los formularios y detalles se presentan con elementos nativos `<dialog>`.

## Entorno y comandos

- Preparación estándar: `composer run setup`.
- Desarrollo integrado: `composer run dev`.
- Pruebas: `composer test`.
- Compilación frontend: `npm run build`.
- Formato PHP disponible: `vendor/bin/pint` (usar el equivalente apropiado en Windows).

La configuración real debe leerse desde el entorno sin revelar secretos. No asumir que `.env` coincide con `.env.example`: la instalación local conocida usa MySQL y la plantilla del repositorio propone SQLite.
