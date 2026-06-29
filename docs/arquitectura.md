# Arquitectura del proyecto VDM Equipamientos

## Objetivo

El proyecto implementa una sección de equipamientos para la web de VDM Insumos sobre WordPress, sin WooCommerce y sin dependencia de Elementor Pro.

La solución funciona como catálogo editable: permite crear categorías, cargar productos y renderizar listados desde páginas hechas con Elementor o desde el editor nativo mediante shortcodes.

## Estructura del repositorio

```text
wordpress/
  plugin/
    vdm-equipamientos/
      vdm-equipamientos.php
      assets/
        css/
          vdm-equipamientos.css
        js/
          vdm-equipamientos.js

docs/
  arquitectura.md
```

El plugin es autocontenido. Para instalarlo en WordPress se comprime la carpeta `wordpress/plugin/vdm-equipamientos/` como ZIP y se sube desde el administrador de plugins.

## Modelo de contenido

El plugin registra un Custom Post Type:

```text
equipamiento
```

Cada entrada de este tipo representa un producto o equipo del catálogo. Soporta título, editor, extracto, imagen destacada y revisiones.

También registra una taxonomía jerárquica:

```text
categoria_equipamiento
```

Esta taxonomía agrupa los productos por familia comercial. Las categorías iniciales son:

```text
Mobiliario
Línea Sanitaria
Transporte Sanitario
Equipamiento de Espuma - Limpieza
```

## Datos iniciales

Al activar el plugin se crean las categorías y productos de ejemplo si no existen previamente. La carga es idempotente: si un producto con el mismo slug ya existe, no se duplica y solo se asegura su relación con la categoría correspondiente.

El plugin no borra contenido al desactivarse. Esto es intencional: los equipamientos son contenido editorial del sitio y no deben perderse por una desactivación accidental.

## Shortcodes disponibles

```text
[vdm_equipamientos]
```

Muestra todos los equipamientos publicados.

```text
[vdm_equipamientos_categoria slug="mobiliario"]
```

Muestra el encabezado y la grilla de productos de una categoría específica.

```text
[vdm_categorias_equipamientos]
```

Muestra una grilla de categorías con cantidad de productos y enlace al archivo de cada categoría.

Estos shortcodes permiten integración directa con Elementor Free usando el widget Shortcode, o con el editor nativo de WordPress.

## Frontend

Los estilos viven en:

```text
assets/css/vdm-equipamientos.css
```

El CSS define grillas responsive, cards de producto, cards de categoría, estados `hover`, estados `focus-visible` y placeholders cuando un producto no tiene imagen destacada.

El JavaScript vive en:

```text
assets/js/vdm-equipamientos.js
```

El JS es deliberadamente mínimo. Solo marca los bloques como listos para habilitar microinteracciones visuales. No maneja datos, formularios ni estado crítico.

## Seguridad

El plugin no crea formularios propios ni acciones de guardado personalizadas, por eso no requiere nonce en esta etapa. La edición de contenido queda delegada a las pantallas nativas de WordPress, protegidas por las capacidades y nonces del core.

Las entradas de shortcode se sanitizan con `sanitize_title()`. La salida HTML usa funciones de escape de WordPress como `esc_html()`, `esc_url()` y `wp_kses_post()` donde corresponde.

## Rendimiento

Los assets se cargan solo cuando se renderiza alguno de los shortcodes del plugin. Las consultas usan `no_found_rows` para evitar cálculos de paginación innecesarios en grillas sin paginado.

El catálogo inicial es pequeño y los shortcodes cargan todos los productos publicados. Si el catálogo crece mucho, el siguiente paso recomendado es agregar paginación, filtros por AJAX o atributos `limit` y `orderby` en los shortcodes.

## Compatibilidad

La cabecera del plugin declara compatibilidad objetivo con:

```text
WordPress 6.7+
PHP 8.2+
```

El código usa funciones del core estables y no depende de WooCommerce, Elementor Pro, ACF ni librerías externas.

## Convenciones

Todos los identificadores públicos del plugin usan el prefijo `vdm_equipamientos_` o constantes `VDM_EQUIPAMIENTOS_*` para evitar colisiones con themes u otros plugins.

Los slugs principales son:

```text
Post type: equipamiento
Taxonomía: categoria_equipamiento
Archivo CPT: /equipamientos/
Single CPT: /equipamiento/{producto}/
Archivo taxonomía: /equipamientos/{categoria}/
```

## Evolución recomendada

Para futuras etapas, conviene mantener esta división:

```text
Contenido: WordPress
Estructura de datos: plugin VDM Equipamientos
Diseño visual: Elementor o theme
Estilos específicos del catálogo: assets del plugin
```

Las próximas mejoras naturales son agregar campos técnicos por producto, botones de consulta por WhatsApp, galería de imágenes, filtros por categoría y templates dedicados para single y archivo si el theme actual no cubre bien esas vistas.
