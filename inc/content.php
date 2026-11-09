<?php
/**
 * Built-in content module: post types, taxonomies, fields and helper functions.
 *
 * This replaces the separate "Faridpur City Core" plugin, so the theme works on its own.
 * Nothing here loads if the plugin is active, so the two never clash.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

if ( defined( 'FC_CORE_VERSION' ) ) {
	return;
}

define( 'FC_BUILTIN', true );

/**
 * Bangla / English pick (follows the theme interface language).
 *
 * @param string $bn Bangla text.
 * @param string $en English text.
 * @return string
 */
function fc_t( $bn, $en ) {
	return 'en' === fc_theme_ui_lang() ? $en : $bn;
}

/* -------------------------------------------------------------------------
 * Icons
 * ---------------------------------------------------------------------- */

/**
 * SVG path data for every icon.
 *
 * @return array<string,string>
 */
function fc_icon_paths() {
	return array(
		'search'     => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'menu'       => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'      => '<path d="M6 6l12 12M18 6 6 18"/>',
		'chevron-l'  => '<path d="m15 6-6 6 6 6"/>',
		'chevron-r'  => '<path d="m9 6 6 6-6 6"/>',
		'arrow-r'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'phone'      => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
		'grid'       => '<rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/>',
		'calendar'   => '<rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10h16M8 3v4M16 3v4"/>',
		'map-pin'    => '<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
		'people'     => '<circle cx="9" cy="8" r="3"/><path d="M3 20a6 6 0 0 1 12 0"/><circle cx="17" cy="9" r="2.5"/><path d="M16 14.5a5 5 0 0 1 5 5"/>',
		'chat'       => '<path d="M4 5h16v11H9l-5 4z"/>',
		'directions' => '<path d="M12 3l9 9-9 9-9-9z"/><path d="M8 12h7M12 9l3 3-3 3"/>',
		'emergency'  => '<path d="M12 3l9 16H3z"/><path d="M12 9v4M12 16.5v.5"/>',
		'file'       => '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5M10 13h6M10 17h6"/>',
		'megaphone'  => '<path d="M4 10v4h3l8 4V6L7 10z"/><path d="M18 9a4 4 0 0 1 0 6"/>',
		'office'     => '<path d="M4 21V5l8-2v18M12 9h8v12M4 21h16M7 9h2M7 13h2M7 17h2"/>',
		'facebook'   => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/>',
		'youtube'    => '<rect x="3" y="6" width="18" height="12" rx="3"/><path d="m10 9.5 5 2.5-5 2.5z"/>',
		'link'       => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1M14 10a4 4 0 0 0-5.7 0l-3 3A4 4 0 0 0 11 18.7l1-1"/>',
		'clock'      => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'info'       => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8v.5"/>',
		'health'     => '<rect x="4" y="4" width="16" height="16" rx="3"/><path d="M12 8v8M8 12h8"/>',
		'education'  => '<path d="M3 9l9-5 9 5-9 5z"/><path d="M7 11.5V16c0 1.5 2.5 3 5 3s5-1.5 5-3v-4.5"/>',
		'transport'  => '<rect x="4" y="4" width="16" height="13" rx="2"/><path d="M4 11h16M8 17v3M16 17v3"/><circle cx="8" cy="14" r=".6"/><circle cx="16" cy="14" r=".6"/>',
		'shopping'   => '<path d="M5 8h14l-1 12H6z"/><path d="M9 8a3 3 0 0 1 6 0"/>',
		'food'       => '<path d="M7 3v8M5 3v5a2 2 0 0 0 4 0V3M7 11v10M17 3c-2 2-3 5-3 8h3v10"/>',
		'bank'       => '<path d="M3 9l9-5 9 5zM5 10v8M9.5 10v8M14.5 10v8M19 10v8M3 20h18"/>',
		'police'     => '<path d="M12 3l8 3v6c0 4.5-3.2 7.8-8 9-4.8-1.2-8-4.5-8-9V6z"/><path d="m9 12 2 2 4-4"/>',
		'fire'       => '<path d="M12 3c1 3 5 5 5 10a5 5 0 0 1-10 0c0-2 1-3 2-4 .2 1.5 1 2 2 2 0-3-1-5 1-8z"/>',
		'electricity' => '<path d="M13 3 5 13h6l-1 8 8-10h-6z"/>',
		'water'      => '<path d="M12 3s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11z"/>',
		'hotel'      => '<path d="M3 18V7M3 14h18v4M21 14v-2a3 3 0 0 0-3-3h-7v5"/><circle cx="7" cy="11" r="1.5"/>',
		'leaf'       => '<path d="M5 19c0-9 5-14 14-14 0 9-5 14-14 14z"/><path d="M5 19c3-4 6-6 9-8"/>',
	);
}

if ( ! function_exists( 'fc_icon' ) ) {
	/**
	 * Inline SVG icon.
	 *
	 * @param string $name Icon.
	 * @param array  $args Args: size, class, label.
	 * @return string
	 */
	function fc_icon( $name, $args = array() ) {
		$paths = fc_icon_paths();
		$args  = wp_parse_args(
			$args,
			array(
				'size'  => 24,
				'class' => '',
				'label' => '',
			)
		);
		$inner = isset( $paths[ $name ] ) ? $paths[ $name ] : $paths['grid'];
		$a11y  = '' !== $args['label'] ? ' role="img" aria-label="' . esc_attr( $args['label'] ) . '"' : ' aria-hidden="true" focusable="false"';
		return '<svg class="fc-icon ' . esc_attr( $args['class'] ) . '" width="' . (int) $args['size'] . '" height="' . (int) $args['size'] . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"' . $a11y . '>' . $inner . '</svg>';
	}
}

/* -------------------------------------------------------------------------
 * Post types and taxonomies
 * ---------------------------------------------------------------------- */

/**
 * Register post types and taxonomies.
 */
function fc_builtin_register() {
	$types = array(
		'fc_notice'   => array(
			'slug'  => 'notices',
			'icon'  => 'dashicons-megaphone',
			'bn'    => array( 'বিজ্ঞপ্তি', 'বিজ্ঞপ্তি', 'সব বিজ্ঞপ্তি' ),
			'en'    => array( 'Notices', 'Notice', 'All notices' ),
			'thumb' => false,
		),
		'fc_service'  => array(
			'slug'  => 'services',
			'icon'  => 'dashicons-building',
			'bn'    => array( 'নাগরিক সেবা', 'সেবা', 'সব সেবা' ),
			'en'    => array( 'Public services', 'Service', 'All services' ),
			'thumb' => true,
		),
		'fc_business' => array(
			'slug'  => 'directory',
			'icon'  => 'dashicons-store',
			'bn'    => array( 'ব্যবসা ডিরেক্টরি', 'ব্যবসা', 'সব ব্যবসা' ),
			'en'    => array( 'Directory', 'Business', 'All businesses' ),
			'thumb' => true,
		),
		'fc_event'    => array(
			'slug'  => 'events',
			'icon'  => 'dashicons-calendar-alt',
			'bn'    => array( 'অনুষ্ঠান', 'অনুষ্ঠান', 'সব অনুষ্ঠান' ),
			'en'    => array( 'Events', 'Event', 'All events' ),
			'thumb' => true,
		),
		'fc_place'    => array(
			'slug'  => 'places',
			'icon'  => 'dashicons-location-alt',
			'bn'    => array( 'দর্শনীয় স্থান', 'স্থান', 'সব স্থান' ),
			'en'    => array( 'Places', 'Place', 'All places' ),
			'thumb' => true,
		),
	);
	$lang  = 'en' === fc_theme_ui_lang() ? 'en' : 'bn';
	foreach ( $types as $type => $t ) {
		list( $plural, $single, $all ) = $t[ $lang ];
		$supports = array( 'title', 'editor', 'excerpt', 'page-attributes' );
		if ( $t['thumb'] ) {
			$supports[] = 'thumbnail';
		}
		register_post_type(
			$type,
			array(
				'labels'        => array(
					'name'          => $plural,
					'singular_name' => $single,
					'menu_name'     => $plural,
					'all_items'     => $all,
					'add_new'       => fc_t( 'নতুন যোগ করুন', 'Add new' ),
					'add_new_item'  => fc_t( 'নতুন যোগ করুন', 'Add new' ),
					'edit_item'     => fc_t( 'সম্পাদনা', 'Edit' ),
					'search_items'  => fc_t( 'খুঁজুন', 'Search' ),
				),
				'public'        => true,
				'has_archive'   => $t['slug'],
				'rewrite'       => array(
					'slug'       => $t['slug'],
					'with_front' => false,
				),
				'menu_icon'     => $t['icon'],
				'menu_position' => 21,
				'supports'      => $supports,
				'show_in_rest'  => true,
			)
		);
	}

	register_post_type(
		'fc_banner',
		array(
			'labels'              => array(
				'name'          => fc_t( 'হিরো ব্যানার', 'Hero banners' ),
				'singular_name' => fc_t( 'ব্যানার', 'Banner' ),
				'add_new_item'  => fc_t( 'নতুন ব্যানার', 'Add new banner' ),
				'edit_item'     => fc_t( 'ব্যানার সম্পাদনা', 'Edit banner' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-images-alt2',
			'menu_position'       => 20,
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);

	$tax_labels = static function ( $bn, $en ) {
		$n = fc_t( $bn, $en );
		return array(
			'name'          => $n,
			'singular_name' => $n,
			'menu_name'     => $n,
			'all_items'     => $n,
			'add_new_item'  => fc_t( 'নতুন যোগ করুন', 'Add new' ),
			'edit_item'     => fc_t( 'সম্পাদনা', 'Edit' ),
		);
	};
	$taxes      = array(
		'fc_service_cat'  => array( array( 'fc_service' ), 'সেবার ধরন', 'Service categories', 'service-category' ),
		'fc_business_cat' => array( array( 'fc_business' ), 'ব্যবসার ধরন', 'Business categories', 'directory-category' ),
		'fc_place_type'   => array( array( 'fc_place' ), 'স্থানের ধরন', 'Place types', 'place-type' ),
		'fc_area'         => array( array( 'fc_service', 'fc_business', 'fc_place', 'fc_event' ), 'এলাকা', 'Areas', 'area' ),
	);
	foreach ( $taxes as $tax => $d ) {
		register_taxonomy(
			$tax,
			$d[0],
			array(
				'labels'            => $tax_labels( $d[1], $d[2] ),
				'hierarchical'      => true,
				'public'            => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array(
					'slug'       => $d[3],
					'with_front' => false,
				),
			)
		);
	}
}
add_action( 'init', 'fc_builtin_register', 5 );

// Flush permalinks once after the theme (or this module) changes.
add_action(
	'init',
	static function () {
		if ( get_option( 'fc_builtin_rewrite' ) !== FC_THEME_VERSION . '-1' ) {
			flush_rewrite_rules( false );
			update_option( 'fc_builtin_rewrite', FC_THEME_VERSION . '-1', false );
		}
	},
	99
);

add_filter(
	'query_vars',
	static function ( $vars ) {
		$vars[] = 'area';
		$vars[] = 'fc24';
		$vars[] = 'when';
		return $vars;
	}
);

/**
 * Archive ordering and the area / 24-hour / upcoming-past filters.
 */
add_action(
	'pre_get_posts',
	static function ( $q ) {
		if ( is_admin() || ! $q->is_main_query() ) {
			return;
		}
		$listing_tax = array( 'fc_service_cat', 'fc_business_cat', 'fc_place_type', 'fc_area' );
		$is_listing  = $q->is_post_type_archive( array( 'fc_service', 'fc_business', 'fc_place', 'fc_event', 'fc_notice' ) ) || $q->is_tax( $listing_tax );
		if ( ! $is_listing ) {
			return;
		}
		$q->set( 'posts_per_page', 12 );

		if ( $q->is_post_type_archive( 'fc_event' ) ) {
			$now  = wp_date( 'Y-m-d\TH:i' );
			$past = 'past' === $q->get( 'when' );
			$q->set( 'meta_key', '_fc_start' );
			$q->set( 'orderby', 'meta_value' );
			$q->set( 'order', $past ? 'DESC' : 'ASC' );
			$q->set(
				'meta_query',
				array(
					array(
						'key'     => '_fc_start',
						'value'   => $past ? $now : wp_date( 'Y-m-d' ) . 'T00:00',
						'compare' => $past ? '<' : '>=',
						'type'    => 'CHAR',
					),
				)
			);
		} elseif ( $q->is_post_type_archive( array( 'fc_service', 'fc_business', 'fc_place' ) ) || $q->is_tax( $listing_tax ) ) {
			$q->set(
				'orderby',
				array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				)
			);
		}

		$area = sanitize_title( (string) $q->get( 'area' ) );
		if ( '' !== $area ) {
			$tax   = (array) $q->get( 'tax_query' );
			$tax[] = array(
				'taxonomy' => 'fc_area',
				'field'    => 'slug',
				'terms'    => array( $area ),
			);
			$q->set( 'tax_query', $tax );
		}
		if ( '1' === (string) $q->get( 'fc24' ) ) {
			$meta   = (array) $q->get( 'meta_query' );
			$meta[] = array(
				'key'   => '_fc_24x7',
				'value' => '1',
			);
			$q->set( 'meta_query', $meta );
		}
	}
);

/* -------------------------------------------------------------------------
 * Data helpers used by the templates
 * ---------------------------------------------------------------------- */

if ( ! function_exists( 'fc_meta' ) ) {
	/**
	 * Read a field.
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

/**
 * Read a category field (icon, home).
 *
 * @param int    $term_id Term.
 * @param string $key     Key.
 * @return string
 */
function fc_term_meta( $term_id, $key ) {
	return (string) get_term_meta( (int) $term_id, 'fc_' . $key, true );
}

/**
 * Phone numbers of a listing (one per line in the editor).
 *
 * @param int $post_id Post.
 * @return string[]
 */
function fc_phones( $post_id ) {
	$raw = (string) fc_meta( $post_id, 'phone' );
	if ( '' === $raw ) {
		return array();
	}
	return array_values( array_filter( array_map( 'trim', preg_split( '/[\r\n,]+/', $raw ) ) ) );
}

/**
 * tel: link for a number.
 *
 * @param string $number Number.
 * @return string
 */
function fc_tel_href( $number ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', (string) $number );
}

/**
 * WhatsApp link (Bangladesh numbers starting with 0 become +880).
 *
 * @param string $number Number.
 * @return string
 */
function fc_whatsapp_href( $number ) {
	$d = preg_replace( '/\D/', '', (string) $number );
	if ( 0 === strpos( $d, '0' ) ) {
		$d = '880' . substr( $d, 1 );
	}
	return 'https://wa.me/' . $d;
}

/**
 * Google Maps link from coordinates or address.
 *
 * @param int $post_id Post.
 * @return string
 */
function fc_map_link( $post_id ) {
	$lat = fc_meta( $post_id, 'lat' );
	$lng = fc_meta( $post_id, 'lng' );
	if ( '' !== $lat && '' !== $lng ) {
		return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $lat . ',' . $lng );
	}
	$addr = fc_meta( $post_id, 'address' );
	return '' !== $addr ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $addr . ', Faridpur' ) : '';
}

/**
 * Weekday keys and labels, week starting Saturday.
 *
 * @return array<string,string>
 */
function fc_days() {
	return array(
		'sat' => fc_t( 'শনিবার', 'Saturday' ),
		'sun' => fc_t( 'রবিবার', 'Sunday' ),
		'mon' => fc_t( 'সোমবার', 'Monday' ),
		'tue' => fc_t( 'মঙ্গলবার', 'Tuesday' ),
		'wed' => fc_t( 'বুধবার', 'Wednesday' ),
		'thu' => fc_t( 'বৃহস্পতিবার', 'Thursday' ),
		'fri' => fc_t( 'শুক্রবার', 'Friday' ),
	);
}

/**
 * Opening hours: day => array( closed, open, close ).
 *
 * @param int $post_id Post.
 * @return array
 */
function fc_hours( $post_id ) {
	$h = get_post_meta( (int) $post_id, '_fc_hours', true );
	return is_array( $h ) ? $h : array();
}

/**
 * Is a listing open right now? Null when no hours are set.
 *
 * @param int $post_id Post.
 * @return bool|null
 */
function fc_is_open_now( $post_id ) {
	$hours = fc_hours( $post_id );
	if ( ! $hours ) {
		return null;
	}
	$now = new DateTimeImmutable( 'now', wp_timezone() );
	$day = strtolower( substr( $now->format( 'D' ), 0, 3 ) );
	if ( ! isset( $hours[ $day ] ) ) {
		return null;
	}
	$h = $hours[ $day ];
	if ( ! empty( $h['closed'] ) ) {
		return false;
	}
	if ( empty( $h['open'] ) || empty( $h['close'] ) ) {
		return null;
	}
	$t = $now->format( 'H:i' );
	return $t >= $h['open'] && $t <= $h['close'];
}

/**
 * Has an event finished?
 *
 * @param int $post_id Event.
 * @return bool
 */
function fc_event_is_past( $post_id ) {
	$end = fc_meta( $post_id, 'end' );
	$ref = $end ? $end : fc_meta( $post_id, 'start' );
	if ( ! $ref ) {
		return false;
	}
	if ( strlen( $ref ) <= 10 ) {
		$ref .= 'T23:59';
	}
	return $ref < wp_date( 'Y-m-d\TH:i' );
}

/**
 * Hero banners in order.
 *
 * @param int $n Max.
 * @return WP_Post[]
 */
function fc_get_banners( $n = 5 ) {
	return get_posts(
		array(
			'post_type'      => 'fc_banner',
			'posts_per_page' => (int) $n,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'no_found_rows'  => true,
		)
	);
}

/**
 * Notices to show on the homepage (urgent first, expired ones hidden).
 *
 * @param int $n Max.
 * @return WP_Post[]
 */
function fc_get_active_notices( $n = 3 ) {
	$posts = get_posts(
		array(
			'post_type'      => 'fc_notice',
			'posts_per_page' => 30,
			'no_found_rows'  => true,
		)
	);
	$today = wp_date( 'Y-m-d' );
	$posts = array_values(
		array_filter(
			$posts,
			static function ( $p ) use ( $today ) {
				$until = fc_meta( $p->ID, 'hide_after' );
				return '' === $until || $until >= $today;
			}
		)
	);
	usort(
		$posts,
		static function ( $a, $b ) {
			$ua = 'urgent' === fc_meta( $a->ID, 'priority' ) ? 0 : 1;
			$ub = 'urgent' === fc_meta( $b->ID, 'priority' ) ? 0 : 1;
			return $ua <=> $ub;
		}
	);
	return array_slice( $posts, 0, (int) $n );
}

/**
 * Emergency services (tick "Emergency service" on a public service).
 *
 * @param int $n Max.
 * @return WP_Post[]
 */
function fc_get_emergency_services( $n = 6 ) {
	return get_posts(
		array(
			'post_type'      => 'fc_service',
			'posts_per_page' => (int) $n,
			'meta_key'       => '_fc_emergency', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'no_found_rows'  => true,
		)
	);
}

/**
 * Latest news for the homepage.
 *
 * @param int $n Max.
 * @return WP_Post[]
 */
function fc_get_home_news( $n = 5 ) {
	return get_posts(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => (int) $n,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

/**
 * Upcoming events (cancelled ones skipped).
 *
 * @param int $n Max.
 * @return WP_Post[]
 */
function fc_get_upcoming_events( $n = 3 ) {
	return get_posts(
		array(
			'post_type'      => 'fc_event',
			'posts_per_page' => (int) $n,
			'meta_key'       => '_fc_start', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'no_found_rows'  => true,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'AND',
				array(
					'key'     => '_fc_start',
					'value'   => wp_date( 'Y-m-d' ) . 'T00:00',
					'compare' => '>=',
					'type'    => 'CHAR',
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => '_fc_status',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => '_fc_status',
						'value'   => 'cancelled',
						'compare' => '!=',
					),
				),
			),
		)
	);
}

/**
 * Categories for the homepage: ones marked "Show on homepage", else the biggest top-level ones.
 *
 * @param string $taxonomy Taxonomy.
 * @param int    $n        Max.
 * @return WP_Term[]
 */
function fc_get_home_terms( $taxonomy, $n = 12 ) {
	$flagged = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'number'     => (int) $n,
			'meta_key'   => 'fc_home', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value' => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	if ( $flagged && ! is_wp_error( $flagged ) ) {
		return $flagged;
	}
	$any = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'parent'     => 0,
			'number'     => (int) $n,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);
	return ( $any && ! is_wp_error( $any ) ) ? $any : array();
}

/**
 * Clean URL of the current archive (used by filters and canonical tags).
 *
 * @return string
 */
function fc_canonical_url() {
	if ( is_tax() || is_category() || is_tag() ) {
		$link = get_term_link( get_queried_object() );
		return is_wp_error( $link ) ? home_url( '/' ) : $link;
	}
	if ( is_post_type_archive() ) {
		$pt = get_query_var( 'post_type' );
		$pt = is_array( $pt ) ? reset( $pt ) : $pt;
		return (string) get_post_type_archive_link( $pt );
	}
	return home_url( '/' );
}

/**
 * Is an archive narrowed by area / 24h / past events?
 *
 * @return bool
 */
function fc_is_filtered_view() {
	return '' !== (string) get_query_var( 'area' ) || '1' === (string) get_query_var( 'fc24' ) || 'past' === get_query_var( 'when' );
}

/**
 * Breadcrumb trail.
 */
function fc_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	$items = array( array( fc_t( 'হোম', 'Home' ), home_url( '/' ) ) );

	if ( is_singular() ) {
		$pt = get_post_type();
		if ( 'post' === $pt ) {
			$bid = (int) get_option( 'page_for_posts' );
			$cat = fc_first_term( get_the_ID(), 'category' );
			if ( $bid ) {
				$items[] = array( get_the_title( $bid ), get_permalink( $bid ) );
			} elseif ( $cat ) {
				$items[] = array( $cat->name, get_category_link( $cat ) );
			}
		} elseif ( 'page' !== $pt && get_post_type_archive_link( $pt ) ) {
			$po      = get_post_type_object( $pt );
			$items[] = array( $po->labels->name, get_post_type_archive_link( $pt ) );
		}
		$items[] = array( get_the_title(), '' );
	} elseif ( is_tax() || is_category() ) {
		$term = get_queried_object();
		$tobj = get_taxonomy( $term->taxonomy );
		if ( $tobj && ! empty( $tobj->object_type[0] ) && get_post_type_archive_link( $tobj->object_type[0] ) && 'fc_area' !== $term->taxonomy ) {
			$po      = get_post_type_object( $tobj->object_type[0] );
			$items[] = array( $po->labels->name, get_post_type_archive_link( $tobj->object_type[0] ) );
		}
		$items[] = array( $term->name, '' );
	} elseif ( is_post_type_archive() ) {
		$items[] = array( post_type_archive_title( '', false ), '' );
	} elseif ( is_home() ) {
		$items[] = array( fc_t( 'সংবাদ', 'News' ), '' );
	} elseif ( is_search() ) {
		$items[] = array( fc_t( 'অনুসন্ধান', 'Search' ), '' );
	} else {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr( fc_t( 'ব্রেডক্রাম্ব', 'Breadcrumb' ) ) . '"><ol>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $it ) {
		if ( $i === $last || '' === $it[1] ) {
			echo '<li><span aria-current="page">' . esc_html( $it[0] ) . '</span></li>';
		} else {
			echo '<li><a href="' . esc_url( $it[1] ) . '">' . esc_html( $it[0] ) . '</a></li>';
		}
	}
	echo '</ol></nav>';
}

/* -------------------------------------------------------------------------
 * Admin: field editor (meta boxes)
 * ---------------------------------------------------------------------- */

/**
 * Field definitions per post type.
 *
 * @param string $type Post type.
 * @return array[]
 */
function fc_builtin_fields( $type ) {
	$f = static function ( $key, $label_bn, $label_en, $kind = 'text', $extra = array() ) {
		return array_merge(
			array(
				'key'   => $key,
				'label' => fc_t( $label_bn, $label_en ),
				'type'  => $kind,
			),
			$extra
		);
	};

	$listing = array(
		$f( 'name_en', 'ইংরেজি নাম', 'Name in English' ),
		$f( 'phone', 'ফোন নম্বর', 'Phone numbers', 'textarea', array( 'help' => fc_t( 'প্রতি লাইনে একটি নম্বর।', 'One number per line.' ) ) ),
		$f( 'whatsapp', 'হোয়াটসঅ্যাপ নম্বর', 'WhatsApp number' ),
		$f( 'email', 'ইমেইল', 'Email', 'email' ),
		$f( 'website', 'ওয়েবসাইট', 'Website', 'url' ),
		$f( 'facebook', 'ফেসবুক পেজ', 'Facebook page', 'url' ),
		$f( 'address', 'ঠিকানা', 'Address', 'textarea' ),
		$f( 'directions', 'কীভাবে যাবেন', 'How to get there', 'textarea' ),
		$f( 'lat', 'অক্ষাংশ (lat)', 'Latitude', 'text' ),
		$f( 'lng', 'দ্রাঘিমাংশ (lng)', 'Longitude', 'text' ),
		$f( '24x7', '২৪ ঘণ্টা খোলা', 'Open 24 hours', 'checkbox' ),
		$f( 'hours', 'খোলার সময়', 'Opening hours', 'hours' ),
		$f( 'verified', 'তথ্য যাচাইয়ের তারিখ', 'Information last checked', 'date' ),
		$f( 'source_url', 'সরকারি সূত্রের লিংক', 'Official source link', 'url' ),
		$f( 'gallery', 'ছবির গ্যালারি', 'Photo gallery', 'gallery' ),
	);

	switch ( $type ) {
		case 'fc_business':
			return array_merge( array( $f( 'featured', 'হোমপেজে বিশেষ ব্যবসা', 'Featured on homepage', 'checkbox' ) ), $listing );
		case 'fc_service':
			return array_merge( array( $f( 'emergency', 'জরুরি সেবা', 'Emergency service', 'checkbox', array( 'help' => fc_t( 'টিক দিলে হোমপেজের জরুরি সেবায় দেখাবে।', 'Shown in the homepage emergency section.' ) ) ) ), $listing );
		case 'fc_notice':
			return array(
				$f(
					'priority',
					'গুরুত্ব',
					'Priority',
					'select',
					array(
						'options' => array(
							'normal' => fc_t( 'সাধারণ', 'Normal' ),
							'urgent' => fc_t( 'জরুরি', 'Urgent' ),
						),
					)
				),
				$f( 'issue_date', 'প্রকাশের তারিখ', 'Issue date', 'date' ),
				$f( 'office', 'দপ্তর', 'Issuing office' ),
				$f( 'pdf', 'পিডিএফ / সংযুক্তি', 'PDF attachment', 'media' ),
				$f( 'hide_after', 'এই তারিখের পর হোমপেজ থেকে সরান', 'Hide from homepage after', 'date' ),
			);
		case 'fc_event':
			return array(
				$f( 'start', 'শুরু', 'Starts', 'datetime' ),
				$f( 'end', 'শেষ', 'Ends', 'datetime' ),
				$f( 'venue_place', 'স্থান (তালিকা থেকে)', 'Venue (from Places)', 'place' ),
				$f( 'venue_text', 'স্থান (লিখে)', 'Venue (text)' ),
				$f( 'address', 'ঠিকানা', 'Address' ),
				$f( 'organizer', 'আয়োজক', 'Organiser' ),
				$f(
					'status',
					'অবস্থা',
					'Status',
					'select',
					array(
						'options' => array(
							'scheduled' => fc_t( 'নির্ধারিত', 'Scheduled' ),
							'cancelled' => fc_t( 'বাতিল', 'Cancelled' ),
							'postponed' => fc_t( 'স্থগিত', 'Postponed' ),
						),
					)
				),
				$f( 'reg_url', 'নিবন্ধন / টিকিটের লিংক', 'Registration / tickets link', 'url' ),
			);
		case 'fc_place':
			return array(
				$f( 'name_en', 'ইংরেজি নাম', 'Name in English' ),
				$f( 'visiting_hours', 'ভিজিটের সময়', 'Visiting hours' ),
				$f( 'entry_fee', 'প্রবেশ মূল্য', 'Entry fee' ),
				$f( 'address', 'ঠিকানা', 'Address', 'textarea' ),
				$f( 'directions', 'কীভাবে যাবেন', 'How to get there', 'textarea' ),
				$f( 'lat', 'অক্ষাংশ (lat)', 'Latitude' ),
				$f( 'lng', 'দ্রাঘিমাংশ (lng)', 'Longitude' ),
				$f( 'gallery', 'ছবির গ্যালারি', 'Photo gallery', 'gallery' ),
			);
		case 'fc_banner':
			return array(
				$f( 'label', 'ছোট লেবেল', 'Small label' ),
				$f( 'subtitle', 'উপশিরোনাম', 'Subtitle', 'textarea' ),
				$f( 'btn_label', 'বাটনের লেখা', 'Button label' ),
				$f( 'btn_url', 'বাটনের লিংক', 'Button link', 'url' ),
				$f( 'mobile_image', 'মোবাইলের ছবি', 'Mobile image', 'media', array( 'help' => fc_t( 'ডেস্কটপের ছবি হলো "ফিচার্ড ইমেজ"।', 'The desktop image is the Featured image.' ) ) ),
			);
		case 'post':
			return array(
				$f( 'source', 'সংবাদের সূত্র', 'News source' ),
			);
	}
	return array();
}

add_action(
	'add_meta_boxes',
	static function () {
		foreach ( array( 'fc_business', 'fc_service', 'fc_notice', 'fc_event', 'fc_place', 'fc_banner', 'post' ) as $type ) {
			add_meta_box( 'fc_details', fc_t( 'বিস্তারিত তথ্য', 'Details' ), 'fc_builtin_render_box', $type, 'normal', 'high' );
		}
	}
);

/**
 * Meta box output.
 *
 * @param WP_Post $post Post.
 */
function fc_builtin_render_box( $post ) {
	wp_nonce_field( 'fc_save_details', 'fc_details_nonce' );
	echo '<style>.fc-f{display:grid;grid-template-columns:200px 1fr;gap:6px 14px;align-items:start;margin:0 0 12px}.fc-f label{font-weight:600;padding-top:6px}.fc-f input[type=text],.fc-f input[type=url],.fc-f input[type=email],.fc-f textarea,.fc-f select{width:100%;max-width:560px}.fc-f .description{grid-column:2;margin:0}.fc-hours td{padding:3px 8px 3px 0}.fc-thumbs img{width:64px;height:64px;object-fit:cover;margin:0 6px 6px 0;border-radius:4px}@media(max-width:782px){.fc-f{grid-template-columns:1fr}.fc-f .description{grid-column:1}}</style>';
	foreach ( fc_builtin_fields( $post->post_type ) as $field ) {
		$key  = $field['key'];
		$val  = 'hours' === $field['type'] ? fc_hours( $post->ID ) : get_post_meta( $post->ID, '_fc_' . $key, true );
		$name = 'fc_f[' . $key . ']';
		$id   = 'fc_f_' . sanitize_key( $key );
		echo '<div class="fc-f"><label for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] ) . '</label><div>';
		switch ( $field['type'] ) {
			case 'textarea':
				echo '<textarea rows="3" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">' . esc_textarea( (string) $val ) . '</textarea>';
				break;
			case 'checkbox':
				echo '<input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="1" ' . checked( '1', (string) $val, false ) . '>';
				break;
			case 'select':
				echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">';
				foreach ( $field['options'] as $ov => $ol ) {
					echo '<option value="' . esc_attr( $ov ) . '" ' . selected( (string) $val, (string) $ov, false ) . '>' . esc_html( $ol ) . '</option>';
				}
				echo '</select>';
				break;
			case 'date':
				echo '<input type="date" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $val ) . '">';
				break;
			case 'datetime':
				echo '<input type="datetime-local" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $val ) . '">';
				break;
			case 'place':
				echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '"><option value="">—</option>';
				foreach ( get_posts( array( 'post_type' => 'fc_place', 'posts_per_page' => 200, 'orderby' => 'title', 'order' => 'ASC', 'no_found_rows' => true ) ) as $pl ) {
					echo '<option value="' . (int) $pl->ID . '" ' . selected( (int) $val, $pl->ID, false ) . '>' . esc_html( get_the_title( $pl ) ) . '</option>';
				}
				echo '</select>';
				break;
			case 'media':
				echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $val ) . '">';
				echo '<span class="fc-thumbs" data-for="' . esc_attr( $id ) . '">' . ( $val ? wp_get_attachment_image( (int) $val, array( 64, 64 ) ) : '' ) . '</span>';
				echo '<button type="button" class="button fc-pick" data-target="' . esc_attr( $id ) . '" data-multiple="0">' . esc_html( fc_t( 'ফাইল বাছাই', 'Choose file' ) ) . '</button> <button type="button" class="button-link fc-clear" data-target="' . esc_attr( $id ) . '">' . esc_html( fc_t( 'মুছুন', 'Remove' ) ) . '</button>';
				break;
			case 'gallery':
				echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $val ) . '">';
				echo '<span class="fc-thumbs" data-for="' . esc_attr( $id ) . '">';
				foreach ( array_filter( array_map( 'absint', explode( ',', (string) $val ) ) ) as $att ) {
					echo wp_get_attachment_image( $att, array( 64, 64 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				echo '</span><button type="button" class="button fc-pick" data-target="' . esc_attr( $id ) . '" data-multiple="1">' . esc_html( fc_t( 'ছবি বাছাই', 'Choose photos' ) ) . '</button> <button type="button" class="button-link fc-clear" data-target="' . esc_attr( $id ) . '">' . esc_html( fc_t( 'মুছুন', 'Remove' ) ) . '</button>';
				break;
			case 'hours':
				echo '<table class="fc-hours"><thead><tr><th></th><th>' . esc_html( fc_t( 'বন্ধ', 'Closed' ) ) . '</th><th>' . esc_html( fc_t( 'খোলে', 'Opens' ) ) . '</th><th>' . esc_html( fc_t( 'বন্ধ হয়', 'Closes' ) ) . '</th></tr></thead><tbody>';
				foreach ( fc_days() as $dk => $dl ) {
					$h = isset( $val[ $dk ] ) ? $val[ $dk ] : array();
					echo '<tr><td>' . esc_html( $dl ) . '</td>';
					echo '<td><input type="checkbox" name="' . esc_attr( $name . '[' . $dk . '][closed]' ) . '" value="1" ' . checked( ! empty( $h['closed'] ), true, false ) . '></td>';
					echo '<td><input type="time" name="' . esc_attr( $name . '[' . $dk . '][open]' ) . '" value="' . esc_attr( isset( $h['open'] ) ? $h['open'] : '' ) . '"></td>';
					echo '<td><input type="time" name="' . esc_attr( $name . '[' . $dk . '][close]' ) . '" value="' . esc_attr( isset( $h['close'] ) ? $h['close'] : '' ) . '"></td></tr>';
				}
				echo '</tbody></table>';
				break;
			default:
				$t = in_array( $field['type'], array( 'url', 'email' ), true ) ? $field['type'] : 'text';
				echo '<input type="' . esc_attr( $t ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $val ) . '">';
		}
		echo '</div>';
		if ( ! empty( $field['help'] ) ) {
			echo '<p class="description">' . esc_html( $field['help'] ) . '</p>';
		}
		echo '</div>';
	}
}

add_action(
	'save_post',
	static function ( $post_id, $post ) {
		if ( ! isset( $_POST['fc_details_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fc_details_nonce'] ) ), 'fc_save_details' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$in = isset( $_POST['fc_f'] ) && is_array( $_POST['fc_f'] ) ? wp_unslash( $_POST['fc_f'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised per field below.
		foreach ( fc_builtin_fields( $post->post_type ) as $field ) {
			$key = $field['key'];
			$raw = isset( $in[ $key ] ) ? $in[ $key ] : '';
			$val = '';
			switch ( $field['type'] ) {
				case 'textarea':
					$val = sanitize_textarea_field( (string) $raw );
					break;
				case 'url':
					$val = esc_url_raw( (string) $raw );
					break;
				case 'email':
					$val = sanitize_email( (string) $raw );
					break;
				case 'checkbox':
					$val = $raw ? '1' : '';
					break;
				case 'select':
					$val = isset( $field['options'][ $raw ] ) ? (string) $raw : '';
					break;
				case 'date':
					$val = preg_match( '/^\d{4}-\d{2}-\d{2}$/', (string) $raw ) ? (string) $raw : '';
					break;
				case 'datetime':
					$val = preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', (string) $raw ) ? (string) $raw : '';
					break;
				case 'place':
				case 'media':
					$val = $raw ? (string) absint( $raw ) : '';
					break;
				case 'gallery':
					$val = implode( ',', array_filter( array_map( 'absint', explode( ',', (string) $raw ) ) ) );
					break;
				case 'hours':
					$val = array();
					foreach ( array_keys( fc_days() ) as $dk ) {
						$h = isset( $raw[ $dk ] ) && is_array( $raw[ $dk ] ) ? $raw[ $dk ] : array();
						if ( ! empty( $h['closed'] ) ) {
							$val[ $dk ] = array( 'closed' => true, 'open' => '', 'close' => '' );
						} elseif ( ! empty( $h['open'] ) && ! empty( $h['close'] ) && preg_match( '/^\d{2}:\d{2}$/', $h['open'] ) && preg_match( '/^\d{2}:\d{2}$/', $h['close'] ) ) {
							$val[ $dk ] = array( 'closed' => false, 'open' => $h['open'], 'close' => $h['close'] );
						}
					}
					break;
				default:
					$val = sanitize_text_field( (string) $raw );
			}
			if ( '' === $val || array() === $val ) {
				delete_post_meta( $post_id, '_fc_' . $key );
			} else {
				update_post_meta( $post_id, '_fc_' . $key, $val );
			}
		}
	},
	10,
	2
);

// Media picker for file and gallery fields.
add_action(
	'admin_enqueue_scripts',
	static function ( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}
		wp_enqueue_media();
		wp_add_inline_script(
			'media-editor',
			"jQuery(function($){\n" .
			"$(document).on('click','.fc-pick',function(e){e.preventDefault();var b=$(this),t=$('#'+b.data('target')),multi=b.data('multiple')==1;" .
			"var f=wp.media({multiple:multi});f.on('select',function(){var s=f.state().get('selection'),ids=[],h='';" .
			"s.each(function(a){a=a.toJSON();ids.push(a.id);var u=(a.sizes&&a.sizes.thumbnail)?a.sizes.thumbnail.url:a.icon;h+='<img src=\"'+u+'\" alt=\"\">';});" .
			"t.val(ids.join(','));$('.fc-thumbs[data-for='+b.data('target')+']').html(h);});f.open();});\n" .
			"$(document).on('click','.fc-clear',function(){var id=$(this).data('target');$('#'+id).val('');$('.fc-thumbs[data-for='+id+']').empty();});\n" .
			'});'
		);
	}
);

/* -------------------------------------------------------------------------
 * Admin: category fields (icon, show on homepage)
 * ---------------------------------------------------------------------- */

foreach ( array( 'fc_service_cat', 'fc_business_cat', 'fc_place_type' ) as $fc_tax ) {
	add_action(
		$fc_tax . '_add_form_fields',
		static function () {
			fc_builtin_term_fields( 0 );
		}
	);
	add_action(
		$fc_tax . '_edit_form_fields',
		static function ( $term ) {
			fc_builtin_term_fields( $term->term_id );
		}
	);
	add_action( 'created_' . $fc_tax, 'fc_builtin_save_term' );
	add_action( 'edited_' . $fc_tax, 'fc_builtin_save_term' );
}
unset( $fc_tax );

/**
 * Icon and homepage fields on category forms.
 *
 * @param int $term_id Term (0 when adding).
 */
function fc_builtin_term_fields( $term_id ) {
	$icon = $term_id ? fc_term_meta( $term_id, 'icon' ) : '';
	$home = $term_id ? fc_term_meta( $term_id, 'home' ) : '';
	wp_nonce_field( 'fc_save_term', 'fc_term_nonce' );
	$wrap_open  = $term_id ? '<tr class="form-field"><th scope="row">' : '<div class="form-field"><label>';
	$wrap_mid   = $term_id ? '</th><td>' : '</label>';
	$wrap_close = $term_id ? '</td></tr>' : '</div>';

	echo $wrap_open . esc_html( fc_t( 'আইকন', 'Icon' ) ) . $wrap_mid; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<select name="fc_term_icon"><option value="">—</option>';
	foreach ( array_keys( fc_icon_paths() ) as $name ) {
		echo '<option value="' . esc_attr( $name ) . '" ' . selected( $icon, $name, false ) . '>' . esc_html( $name ) . '</option>';
	}
	echo '</select>' . $wrap_close; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	echo $wrap_open . esc_html( fc_t( 'হোমপেজে দেখান', 'Show on homepage' ) ) . $wrap_mid; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<input type="checkbox" name="fc_term_home" value="1" ' . checked( '1', $home, false ) . '>' . $wrap_close; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Save category fields.
 *
 * @param int $term_id Term.
 */
function fc_builtin_save_term( $term_id ) {
	if ( ! isset( $_POST['fc_term_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fc_term_nonce'] ) ), 'fc_save_term' ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	$icon = isset( $_POST['fc_term_icon'] ) ? sanitize_key( wp_unslash( $_POST['fc_term_icon'] ) ) : '';
	if ( '' !== $icon && isset( fc_icon_paths()[ $icon ] ) ) {
		update_term_meta( $term_id, 'fc_icon', $icon );
	} else {
		delete_term_meta( $term_id, 'fc_icon' );
	}
	if ( ! empty( $_POST['fc_term_home'] ) ) {
		update_term_meta( $term_id, 'fc_home', '1' );
	} else {
		delete_term_meta( $term_id, 'fc_home' );
	}
}

require_once get_template_directory() . '/inc/demo-content.php';
