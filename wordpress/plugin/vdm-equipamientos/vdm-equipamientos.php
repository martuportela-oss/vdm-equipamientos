<?php
/**
 * Plugin Name: VDM Equipamientos
 * Description: Catálogo liviano de equipamientos para VDM Insumos, sin WooCommerce y compatible con Elementor mediante shortcodes.
 * Version: 1.0.0
 * Author: VDM Insumos
 * Text Domain: vdm-equipamientos
 * Domain Path: /languages
 * Requires at least: 6.7
 * Requires PHP: 8.2
 *
 * @package VDM_Equipamientos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VDM_EQUIPAMIENTOS_VERSION', '1.0.0' );
define( 'VDM_EQUIPAMIENTOS_FILE', __FILE__ );
define( 'VDM_EQUIPAMIENTOS_DIR', plugin_dir_path( __FILE__ ) );
define( 'VDM_EQUIPAMIENTOS_URL', plugin_dir_url( __FILE__ ) );
define( 'VDM_EQUIPAMIENTOS_POST_TYPE', 'equipamiento' );
define( 'VDM_EQUIPAMIENTOS_TAXONOMY', 'categoria_equipamiento' );

/**
 * Loads the plugin text domain.
 */
function vdm_equipamientos_load_textdomain() {
	load_plugin_textdomain(
		'vdm-equipamientos',
		false,
		dirname( plugin_basename( VDM_EQUIPAMIENTOS_FILE ) ) . '/languages'
	);
}
add_action( 'plugins_loaded', 'vdm_equipamientos_load_textdomain' );

/**
 * Registers the Equipamiento custom post type.
 */
function vdm_equipamientos_register_post_type() {
	$labels = array(
		'name'               => __( 'Equipamientos', 'vdm-equipamientos' ),
		'singular_name'      => __( 'Equipamiento', 'vdm-equipamientos' ),
		'menu_name'          => __( 'Equipamientos', 'vdm-equipamientos' ),
		'name_admin_bar'     => __( 'Equipamiento', 'vdm-equipamientos' ),
		'add_new'            => __( 'Agregar nuevo', 'vdm-equipamientos' ),
		'add_new_item'       => __( 'Agregar equipamiento', 'vdm-equipamientos' ),
		'new_item'           => __( 'Nuevo equipamiento', 'vdm-equipamientos' ),
		'edit_item'          => __( 'Editar equipamiento', 'vdm-equipamientos' ),
		'view_item'          => __( 'Ver equipamiento', 'vdm-equipamientos' ),
		'all_items'          => __( 'Todos los equipamientos', 'vdm-equipamientos' ),
		'search_items'       => __( 'Buscar equipamientos', 'vdm-equipamientos' ),
		'not_found'          => __( 'No se encontraron equipamientos', 'vdm-equipamientos' ),
		'not_found_in_trash' => __( 'No hay equipamientos en la papelera', 'vdm-equipamientos' ),
	);

	register_post_type(
		VDM_EQUIPAMIENTOS_POST_TYPE,
		array(
			'labels'          => $labels,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-hammer',
			'has_archive'     => 'equipamientos',
			'rewrite'         => array(
				'slug'       => 'equipamiento',
				'with_front' => false,
			),
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'taxonomies'      => array( VDM_EQUIPAMIENTOS_TAXONOMY ),
			'capability_type' => 'post',
		)
	);
}
add_action( 'init', 'vdm_equipamientos_register_post_type' );

/**
 * Registers the Equipamientos category taxonomy.
 */
function vdm_equipamientos_register_taxonomy() {
	$labels = array(
		'name'          => __( 'Categorías de equipamientos', 'vdm-equipamientos' ),
		'singular_name' => __( 'Categoría de equipamiento', 'vdm-equipamientos' ),
		'search_items'  => __( 'Buscar categorías', 'vdm-equipamientos' ),
		'all_items'     => __( 'Todas las categorías', 'vdm-equipamientos' ),
		'edit_item'     => __( 'Editar categoría', 'vdm-equipamientos' ),
		'update_item'   => __( 'Actualizar categoría', 'vdm-equipamientos' ),
		'add_new_item'  => __( 'Agregar categoría', 'vdm-equipamientos' ),
		'new_item_name' => __( 'Nueva categoría', 'vdm-equipamientos' ),
		'menu_name'     => __( 'Categorías', 'vdm-equipamientos' ),
	);

	register_taxonomy(
		VDM_EQUIPAMIENTOS_TAXONOMY,
		array( VDM_EQUIPAMIENTOS_POST_TYPE ),
		array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'equipamientos',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'vdm_equipamientos_register_taxonomy' );

/**
 * Returns the initial taxonomy terms.
 *
 * @return array<string,array{name:string,description:string}>
 */
function vdm_equipamientos_get_seed_terms() {
	return array(
		'mobiliario'                         => array(
			'name'        => 'Mobiliario',
			'description' => 'Mobiliario industrial en acero inoxidable para ordenar, proteger y optimizar áreas de trabajo.',
		),
		'linea-sanitaria'                    => array(
			'name'        => 'Línea Sanitaria',
			'description' => 'Soluciones sanitarias resistentes, fáciles de limpiar y preparadas para entornos de alta exigencia.',
		),
		'transporte-sanitario'               => array(
			'name'        => 'Transporte Sanitario',
			'description' => 'Equipos pensados para mover insumos y materiales con higiene, seguridad y continuidad operativa.',
		),
		'equipamiento-de-espuma-limpieza'    => array(
			'name'        => 'Equipamiento de Espuma - Limpieza',
			'description' => 'Equipamiento de apoyo para procesos de lavado, limpieza y sanitización en planta.',
		),
	);
}

/**
 * Returns the initial demo products grouped by taxonomy slug.
 *
 * @return array<string,array<string,string>>
 */
function vdm_equipamientos_get_seed_products() {
	return array(
		'mobiliario'                      => array(
			'Armario doble con estantes'            => 'Armario robusto para organizar utensilios, insumos y elementos de trabajo en sectores productivos.',
			'Estantería 012'                       => 'Estantería funcional para almacenamiento diario, con estructura práctica y fácil mantenimiento.',
			'Estantería 013'                       => 'Módulo de guardado resistente para optimizar espacios técnicos y áreas de preparación.',
			'Estantería 014'                       => 'Solución de apoyo para ordenar productos, recipientes y herramientas de uso frecuente.',
			'Estantería con ruedas 015'            => 'Estantería móvil para operaciones que requieren traslado interno y flexibilidad de uso.',
			'Estantería con plegado y corte 016'   => 'Estructura con terminaciones cuidadas, pensada para trabajo continuo y limpieza simple.',
			'Lockers'                              => 'Guardado individual para personal, ideal para separar pertenencias y mejorar el orden del sector.',
		),
		'linea-sanitaria'                 => array(
			'Cesto de basura portabolsa'          => 'Cesto sanitario preparado para cambio ágil de bolsa y uso intensivo en áreas limpias.',
			'Portabolsa'                         => 'Soporte práctico para residuos, diseñado para mantener el área ordenada y facilitar la higiene.',
			'Cesto de basura acero inoxidable'   => 'Cesto durable y lavable para espacios donde la limpieza visual y operativa es prioritaria.',
			'Dispenser acero inoxidable'          => 'Dispenser resistente para ambientes industriales, con terminación limpia y larga vida útil.',
			'Portarollo'                         => 'Accesorio sanitario simple y firme para mantener rollos disponibles en puntos de uso.',
			'Portaservilletas'                   => 'Soporte compacto para servilletas o papel, pensado para reposición rápida y uso cotidiano.',
			'Dispenser de jabón'                 => 'Equipo de dosificación para higiene de manos o superficies, apto para rutinas sanitarias frecuentes.',
		),
		'transporte-sanitario'            => array(
			'Línea transporte sanitario' => 'Equipamiento para traslado higiénico de materiales dentro de plantas, depósitos y sectores productivos.',
		),
		'equipamiento-de-espuma-limpieza' => array(
			'Lavadora de bandejas' => 'Equipo orientado al lavado eficiente de bandejas, reduciendo tiempos operativos y mejorando la sanitización.',
		),
	);
}

/**
 * Creates the initial terms and demo products.
 */
function vdm_equipamientos_seed_content() {
	foreach ( vdm_equipamientos_get_seed_terms() as $slug => $term_data ) {
		$term = get_term_by( 'slug', $slug, VDM_EQUIPAMIENTOS_TAXONOMY );

		if ( ! $term ) {
			wp_insert_term(
				$term_data['name'],
				VDM_EQUIPAMIENTOS_TAXONOMY,
				array(
					'slug'        => sanitize_title( $slug ),
					'description' => sanitize_text_field( $term_data['description'] ),
				)
			);
		}
	}

	foreach ( vdm_equipamientos_get_seed_products() as $term_slug => $products ) {
		foreach ( $products as $title => $description ) {
			$post_slug = sanitize_title( $title );
			$existing  = get_page_by_path( $post_slug, OBJECT, VDM_EQUIPAMIENTOS_POST_TYPE );

			if ( $existing instanceof WP_Post ) {
				wp_set_object_terms( $existing->ID, $term_slug, VDM_EQUIPAMIENTOS_TAXONOMY, false );
				continue;
			}

			$post_id = wp_insert_post(
				array(
					'post_type'    => VDM_EQUIPAMIENTOS_POST_TYPE,
					'post_status'  => 'publish',
					'post_title'   => wp_strip_all_tags( $title ),
					'post_name'    => $post_slug,
					'post_excerpt' => sanitize_text_field( $description ),
					'post_content' => wp_kses_post(
						$description . "\n\n" . 'Disponible para proyectos que requieren soluciones sanitarias, durables y fáciles de integrar a la operación diaria.'
					),
					'meta_input'   => array(
						'_vdm_equipamiento_seed' => '1',
					),
				),
				true
			);

			if ( ! is_wp_error( $post_id ) && $post_id ) {
				wp_set_object_terms( $post_id, $term_slug, VDM_EQUIPAMIENTOS_TAXONOMY, false );
			}
		}
	}
}

/**
 * Plugin activation callback.
 */
function vdm_equipamientos_activate() {
	vdm_equipamientos_register_post_type();
	vdm_equipamientos_register_taxonomy();
	vdm_equipamientos_seed_content();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'vdm_equipamientos_activate' );

/**
 * Plugin deactivation callback.
 */
function vdm_equipamientos_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'vdm_equipamientos_deactivate' );

/**
 * Enqueues frontend assets for shortcode output.
 */
function vdm_equipamientos_enqueue_assets() {
	wp_enqueue_style(
		'vdm-equipamientos',
		VDM_EQUIPAMIENTOS_URL . 'assets/css/vdm-equipamientos.css',
		array(),
		VDM_EQUIPAMIENTOS_VERSION
	);

	wp_enqueue_script(
		'vdm-equipamientos',
		VDM_EQUIPAMIENTOS_URL . 'assets/js/vdm-equipamientos.js',
		array(),
		VDM_EQUIPAMIENTOS_VERSION,
		true
	);
}

/**
 * Renders a reusable notice.
 *
 * @param string $message Notice text.
 * @return string
 */
function vdm_equipamientos_render_notice( $message ) {
	vdm_equipamientos_enqueue_assets();

	return sprintf(
		'<p class="vdm-equipamientos__empty">%s</p>',
		esc_html( $message )
	);
}

/**
 * Returns the placeholder markup used when a product has no thumbnail.
 *
 * @return string
 */
function vdm_equipamientos_get_placeholder() {
	return '<div class="vdm-equipamiento-card__media" aria-hidden="true"><span>VDM</span></div>';
}

/**
 * Renders one product card.
 *
 * @param WP_Post $post Product post.
 * @return string
 */
function vdm_equipamientos_render_card( WP_Post $post ) {
	$title     = get_the_title( $post );
	$excerpt   = get_the_excerpt( $post );
	$permalink = get_permalink( $post );
	$terms     = get_the_terms( $post, VDM_EQUIPAMIENTOS_TAXONOMY );
	$term_name = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : __( 'Equipamiento', 'vdm-equipamientos' );
	$thumbnail = get_the_post_thumbnail(
		$post,
		'medium_large',
		array(
			'class'   => 'vdm-equipamiento-card__image',
			'loading' => 'lazy',
		)
	);

	ob_start();
	?>
	<article class="vdm-equipamiento-card">
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Thumbnail is sanitized with wp_kses_post; placeholder is fixed plugin markup. ?>
		<?php echo $thumbnail ? wp_kses_post( $thumbnail ) : vdm_equipamientos_get_placeholder(); ?>
		<div class="vdm-equipamiento-card__body">
			<p class="vdm-equipamiento-card__category"><?php echo esc_html( $term_name ); ?></p>
			<h3 class="vdm-equipamiento-card__title">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
			</h3>
			<?php if ( $excerpt ) : ?>
				<p class="vdm-equipamiento-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
			<?php endif; ?>
			<a class="vdm-equipamiento-card__link" href="<?php echo esc_url( $permalink ); ?>"><?php esc_html_e( 'Ver detalle', 'vdm-equipamientos' ); ?></a>
		</div>
	</article>
	<?php
	return trim( ob_get_clean() );
}

/**
 * Returns the base query args for product grids.
 *
 * @return array<string,mixed>
 */
function vdm_equipamientos_get_base_query_args() {
	return array(
		'post_type'              => VDM_EQUIPAMIENTOS_POST_TYPE,
		'post_status'            => 'publish',
		'posts_per_page'         => -1,
		'orderby'                => 'title',
		'order'                  => 'ASC',
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => true,
	);
}

/**
 * Renders a products grid.
 *
 * @param array<string,mixed> $query_args Query overrides.
 * @param string             $empty_message Message shown when no products exist.
 * @return string
 */
function vdm_equipamientos_render_grid( $query_args = array(), $empty_message = '' ) {
	vdm_equipamientos_enqueue_assets();

	$query_args = is_array( $query_args ) ? $query_args : array();
	$query      = new WP_Query( array_merge( vdm_equipamientos_get_base_query_args(), $query_args ) );

	ob_start();
	?>
	<div class="vdm-equipamientos" data-vdm-equipamientos>
		<?php if ( $query->have_posts() ) : ?>
			<div class="vdm-equipamientos__grid">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					$current_post = get_post();

					if ( $current_post instanceof WP_Post ) {
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Card renderer escapes all dynamic values.
						echo vdm_equipamientos_render_card( $current_post );
					}
				endwhile;
				?>
			</div>
		<?php else : ?>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Notice renderer escapes the message.
			echo vdm_equipamientos_render_notice(
				$empty_message ? $empty_message : __( 'No hay equipamientos disponibles.', 'vdm-equipamientos' )
			);
			?>
		<?php endif; ?>
	</div>
	<?php

	wp_reset_postdata();

	return trim( ob_get_clean() );
}

/**
 * Shortcode: [vdm_equipamientos].
 *
 * @return string
 */
function vdm_equipamientos_shortcode() {
	return vdm_equipamientos_render_grid();
}
add_shortcode( 'vdm_equipamientos', 'vdm_equipamientos_shortcode' );

/**
 * Shortcode: [vdm_equipamientos_categoria slug="mobiliario"].
 *
 * @param array<string,string>|string $atts Shortcode attributes.
 * @return string
 */
function vdm_equipamientos_categoria_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'slug' => '',
		),
		is_array( $atts ) ? $atts : array(),
		'vdm_equipamientos_categoria'
	);

	$slug = sanitize_title( $atts['slug'] );

	if ( ! $slug ) {
		return vdm_equipamientos_render_notice( __( 'Indica una categoría con el atributo slug.', 'vdm-equipamientos' ) );
	}

	$term = get_term_by( 'slug', $slug, VDM_EQUIPAMIENTOS_TAXONOMY );

	if ( ! $term || is_wp_error( $term ) ) {
		return vdm_equipamientos_render_notice( __( 'La categoría solicitada no existe.', 'vdm-equipamientos' ) );
	}

	ob_start();
	?>
	<section class="vdm-equipamientos vdm-equipamientos--categoria" data-vdm-equipamientos>
		<header class="vdm-equipamientos__header">
			<p class="vdm-equipamientos__eyebrow"><?php esc_html_e( 'Equipamientos', 'vdm-equipamientos' ); ?></p>
			<h2 class="vdm-equipamientos__heading"><?php echo esc_html( $term->name ); ?></h2>
			<?php if ( ! empty( $term->description ) ) : ?>
				<p class="vdm-equipamientos__description"><?php echo esc_html( $term->description ); ?></p>
			<?php endif; ?>
		</header>
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Grid renderer escapes all dynamic values.
		echo vdm_equipamientos_render_grid(
			array(
				'tax_query' => array(
					array(
						'taxonomy' => VDM_EQUIPAMIENTOS_TAXONOMY,
						'field'    => 'slug',
						'terms'    => $slug,
					),
				),
			),
			__( 'No hay equipamientos cargados en esta categoría.', 'vdm-equipamientos' )
		);
		?>
	</section>
	<?php

	return trim( ob_get_clean() );
}
add_shortcode( 'vdm_equipamientos_categoria', 'vdm_equipamientos_categoria_shortcode' );

/**
 * Shortcode: [vdm_categorias_equipamientos].
 *
 * @return string
 */
function vdm_categorias_equipamientos_shortcode() {
	vdm_equipamientos_enqueue_assets();

	$terms = get_terms(
		array(
			'taxonomy'   => VDM_EQUIPAMIENTOS_TAXONOMY,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return vdm_equipamientos_render_notice( __( 'No hay categorías de equipamientos cargadas.', 'vdm-equipamientos' ) );
	}

	ob_start();
	?>
	<div class="vdm-categorias-equipamientos" data-vdm-categorias>
		<?php foreach ( $terms as $term ) : ?>
			<?php
			$term_link = get_term_link( $term );

			if ( is_wp_error( $term_link ) ) {
				continue;
			}
			?>
			<article class="vdm-categoria-card">
				<a class="vdm-categoria-card__link" href="<?php echo esc_url( $term_link ); ?>">
					<span class="vdm-categoria-card__count"><?php echo esc_html( (string) $term->count ); ?></span>
					<h3 class="vdm-categoria-card__title"><?php echo esc_html( $term->name ); ?></h3>
					<?php if ( ! empty( $term->description ) ) : ?>
						<p class="vdm-categoria-card__description"><?php echo esc_html( $term->description ); ?></p>
					<?php endif; ?>
					<span class="vdm-categoria-card__action"><?php esc_html_e( 'Ver categoría', 'vdm-equipamientos' ); ?></span>
				</a>
			</article>
		<?php endforeach; ?>
	</div>
	<?php

	return trim( ob_get_clean() );
}
add_shortcode( 'vdm_categorias_equipamientos', 'vdm_categorias_equipamientos_shortcode' );
