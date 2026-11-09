<?php
/**
 * Fallbacks so the theme renders safely if the Faridpur City Core plugin is inactive.
 * Plugins load before themes, so these are only defined when the plugin is missing.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'fc_icon' ) ) {
	/**
	 * Minimal UI icon set.
	 *
	 * @param string $name Icon.
	 * @param array  $args Args.
	 * @return string
	 */
	function fc_icon( $name, $args = array() ) {
		$paths = array(
			'search'    => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
			'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
			'close'     => '<path d="M6 6l12 12M18 6 6 18"/>',
			'chevron-l' => '<path d="m15 6-6 6 6 6"/>',
			'chevron-r' => '<path d="m9 6 6 6-6 6"/>',
			'arrow-r'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
			'phone'     => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
			'grid'      => '<rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/>',
		);
		$args  = wp_parse_args( $args, array( 'size' => 24, 'class' => '', 'label' => '' ) );
		$inner = isset( $paths[ $name ] ) ? $paths[ $name ] : $paths['grid'];
		$a11y  = '' !== $args['label'] ? ' role="img" aria-label="' . esc_attr( $args['label'] ) . '"' : ' aria-hidden="true" focusable="false"';
		return '<svg class="fc-icon ' . esc_attr( $args['class'] ) . '" width="' . (int) $args['size'] . '" height="' . (int) $args['size'] . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"' . $a11y . '>' . $inner . '</svg>';
	}
}

if ( ! function_exists( 'fc_meta' ) ) {
	/**
	 * Read a meta field.
	 *
	 * @param int    $post_id Post.
	 * @param string $key     Key.
	 * @param mixed  $default Default.
	 * @return mixed
	 */
	function fc_meta( $post_id, $key, $default = '' ) {
		$v = get_post_meta( (int) $post_id, '_fc_' . $key, true );
		return ( '' === $v || null === $v ) ? $default : $v;
	}
}

if ( ! function_exists( 'fc_breadcrumbs' ) ) {
	/**
	 * No breadcrumbs without the plugin.
	 */
	function fc_breadcrumbs() {}
}

if ( ! function_exists( 'fc_archive_text' ) ) {
	/**
	 * Empty archive text without the plugin.
	 *
	 * @return array
	 */
	function fc_archive_text() {
		return array(
			'title' => '',
			'intro' => '',
		);
	}
}

/**
 * Whether the companion plugin is active.
 *
 * @return bool
 */
function fc_core_active() {
	return defined( 'FC_CORE_VERSION' );
}
