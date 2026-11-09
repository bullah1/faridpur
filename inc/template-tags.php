<?php
/**
 * Template helpers.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

/**
 * Convert ASCII digits to Bangla digits when the setting is on.
 *
 * @param string|int $s Input.
 * @return string
 */
function fc_digits( $s ) {
	$s = (string) $s;
	if ( ! get_theme_mod( 'fc_bangla_digits', true ) || 'bn' !== fc_theme_ui_lang() ) {
		return $s;
	}
	return strtr( $s, array( '0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯' ) );
}

/**
 * Format a date for display. Month and weekday names follow the interface language.
 *
 * @param string|int $when   Timestamp, "Y-m-d", or "Y-m-d\TH:i".
 * @param string     $format PHP date format.
 * @return string
 */
function fc_date( $when, $format = '' ) {
	if ( '' === $when || null === $when ) {
		return '';
	}
	$format = $format ? $format : 'j F Y';
	try {
		$dt = is_numeric( $when ) ? ( new DateTimeImmutable( '@' . (int) $when ) )->setTimezone( wp_timezone() ) : new DateTimeImmutable( (string) $when, wp_timezone() );
	} catch ( Exception $e ) {
		return '';
	}
	$out = $dt->format( $format );
	if ( 'bn' === fc_theme_ui_lang() ) {
		$map = array(
			'January'   => 'জানুয়ারি',
			'February'  => 'ফেব্রুয়ারি',
			'March'     => 'মার্চ',
			'April'     => 'এপ্রিল',
			'May'       => 'মে',
			'June'      => 'জুন',
			'July'      => 'জুলাই',
			'August'    => 'আগস্ট',
			'September' => 'সেপ্টেম্বর',
			'October'   => 'অক্টোবর',
			'November'  => 'নভেম্বর',
			'December'  => 'ডিসেম্বর',
			'Saturday'  => 'শনিবার',
			'Sunday'    => 'রবিবার',
			'Monday'    => 'সোমবার',
			'Tuesday'   => 'মঙ্গলবার',
			'Wednesday' => 'বুধবার',
			'Thursday'  => 'বৃহস্পতিবার',
			'Friday'    => 'শুক্রবার',
			'Jan'       => 'জানু',
			'Feb'       => 'ফেব্রু',
			'Mar'       => 'মার্চ',
			'Apr'       => 'এপ্রি',
			'Jun'       => 'জুন',
			'Jul'       => 'জুলা',
			'Aug'       => 'আগ',
			'Sep'       => 'সেপ্ট',
			'Oct'       => 'অক্টো',
			'Nov'       => 'নভে',
			'Dec'       => 'ডিসে',
			'am'        => 'পূর্বাহ্ণ',
			'pm'        => 'অপরাহ্ণ',
			'AM'        => 'পূর্বাহ্ণ',
			'PM'        => 'অপরাহ্ণ',
		);
		// Replace whole words only, longest first, so "May"/"Mar" do not collide with full names.
		$out = preg_replace_callback(
			'/\b(' . implode( '|', array_map( 'preg_quote', array_keys( $map ) ) ) . ')\b/',
			static function ( $m ) use ( $map ) {
				return $map[ $m[1] ];
			},
			$out
		);
	}
	return fc_digits( $out );
}

/**
 * Format a stored time (H:i) for display.
 *
 * @param string $hm Time.
 * @return string
 */
function fc_time( $hm ) {
	if ( ! preg_match( '/^\d{2}:\d{2}$/', (string) $hm ) ) {
		return '';
	}
	return fc_date( '2000-01-01T' . $hm, get_option( 'time_format', 'g:i a' ) );
}

/**
 * The date of a post for display.
 *
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function fc_post_date( $post = null ) {
	return fc_date( get_post_timestamp( $post ) );
}

/**
 * Homepage sections in saved order with visibility.
 *
 * @return array<string,bool> key => visible.
 */
function fc_home_sections() {
	$defaults = fc_home_section_labels();
	$saved    = (string) get_theme_mod( 'fc_home_sections', '' );
	$out      = array();
	foreach ( array_filter( explode( ',', $saved ) ) as $pair ) {
		list( $key, $on ) = array_pad( explode( ':', $pair ), 2, '1' );
		if ( isset( $defaults[ $key ] ) && ! isset( $out[ $key ] ) ) {
			$out[ $key ] = '1' === $on;
		}
	}
	foreach ( array_keys( $defaults ) as $key ) {
		if ( ! isset( $out[ $key ] ) ) {
			$out[ $key ] = true;
		}
	}
	return $out;
}

/**
 * Homepage section keys and admin labels (default order).
 *
 * @return array<string,string>
 */
function fc_home_section_labels() {
	return array(
		'hero'          => __( 'Hero banner', 'faridpur-city' ),
		'notices'       => __( 'Notices', 'faridpur-city' ),
		'services'      => __( 'City services', 'faridpur-city' ),
		'news'          => __( 'Latest news', 'faridpur-city' ),
		'directory'     => __( 'Business directory', 'faridpur-city' ),
		'emergency'     => __( 'Emergency services', 'faridpur-city' ),
		'places_events' => __( 'Places & events', 'faridpur-city' ),
		'links'         => __( 'Useful links', 'faridpur-city' ),
	);
}

/**
 * A text theme mod with a translatable default.
 *
 * @param string $key     Mod name.
 * @param string $default Translated default.
 * @return string
 */
function fc_mod( $key, $default = '' ) {
	$v = get_theme_mod( $key, '' );
	return '' !== trim( (string) $v ) ? (string) $v : $default;
}

/**
 * An integer theme mod, clamped.
 *
 * @param string $key     Mod name.
 * @param int    $default Default.
 * @return int
 */
function fc_mod_int( $key, $default ) {
	$v = absint( get_theme_mod( $key, $default ) );
	return max( 1, min( 24, $v ? $v : $default ) );
}

/**
 * Section heading row with optional "see all" link.
 *
 * @param array $args { id, title, subtitle, link, link_label, tag }.
 */
function fc_section_head( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'id'         => '',
			'title'      => '',
			'subtitle'   => '',
			'link'       => '',
			'link_label' => __( 'See all', 'faridpur-city' ),
			'tag'        => 'h2',
		)
	);
	$tag  = in_array( $args['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $args['tag'] : 'h2';
	echo '<div class="section-head"><div class="section-head__text">';
	printf( '<%1$s class="section-head__title" id="%2$s">%3$s</%1$s>', esc_attr( $tag ), esc_attr( $args['id'] ), esc_html( $args['title'] ) );
	if ( '' !== $args['subtitle'] ) {
		echo '<p class="section-head__sub">' . esc_html( $args['subtitle'] ) . '</p>';
	}
	echo '</div>';
	if ( '' !== $args['link'] ) {
		printf(
			'<a class="section-head__link" href="%1$s">%2$s %3$s<span class="screen-reader-text">: %4$s</span></a>',
			esc_url( $args['link'] ),
			esc_html( $args['link_label'] ),
			fc_icon( 'arrow-r', array( 'size' => 18 ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $args['title'] )
		);
	}
	echo '</div>';
}

/**
 * Responsive image for a post or a neutral placeholder block.
 *
 * @param int    $post_id Post.
 * @param string $size    Image size.
 * @param string $class   Wrapper class.
 * @param bool   $eager   Load eagerly (first visible image).
 */
function fc_post_image( $post_id, $size = 'fc-card', $class = 'media', $eager = false ) {
	echo '<div class="' . esc_attr( $class ) . '">';
	if ( has_post_thumbnail( $post_id ) ) {
		$attrs = array(
			'loading' => $eager ? 'eager' : 'lazy',
			'class'   => 'media__img',
		);
		if ( $eager ) {
			$attrs['fetchpriority'] = 'high';
		}
		echo get_the_post_thumbnail( $post_id, $size, $attrs );
	} else {
		echo '<span class="media__placeholder" aria-hidden="true">' . fc_icon( 'grid', array( 'size' => 28 ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</div>';
}

/**
 * Icon for a category term (falls back to "grid").
 *
 * @param WP_Term $term Term.
 * @param int     $size Size.
 * @return string
 */
function fc_term_icon( $term, $size = 24 ) {
	$slug = function_exists( 'fc_term_meta' ) ? fc_term_meta( $term->term_id, 'icon' ) : '';
	return fc_icon( $slug ? $slug : 'grid', array( 'size' => $size ) );
}

/**
 * First term name of a taxonomy for a post.
 *
 * @param int    $post_id  Post.
 * @param string $taxonomy Taxonomy.
 * @return WP_Term|null
 */
function fc_first_term( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
}

/**
 * "Open now / Closed / Open 24 hours" badge.
 *
 * @param int $post_id Listing.
 */
function fc_open_badge( $post_id ) {
	if ( ! function_exists( 'fc_is_open_now' ) ) {
		return;
	}
	if ( '1' === (string) fc_meta( $post_id, '24x7' ) ) {
		echo '<span class="badge badge--open">' . esc_html__( 'Open 24 hours', 'faridpur-city' ) . '</span>';
		return;
	}
	$open = fc_is_open_now( $post_id );
	if ( null === $open ) {
		return;
	}
	echo $open
		? '<span class="badge badge--open">' . esc_html__( 'Open now', 'faridpur-city' ) . '</span>'
		: '<span class="badge badge--closed">' . esc_html__( 'Closed now', 'faridpur-city' ) . '</span>';
}

/**
 * Pagination for the main query.
 */
function fc_pagination() {
	global $wp_query;
	if ( (int) $wp_query->max_num_pages < 2 ) {
		return;
	}
	$links = paginate_links(
		array(
			'type'      => 'array',
			'mid_size'  => 1,
			'prev_text' => fc_icon( 'chevron-l', array( 'size' => 18 ) ) . '<span>' . esc_html__( 'Previous', 'faridpur-city' ) . '</span>',
			'next_text' => '<span>' . esc_html__( 'Next', 'faridpur-city' ) . '</span>' . fc_icon( 'chevron-r', array( 'size' => 18 ) ),
			'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'faridpur-city' ) . ' </span>',
		)
	);
	if ( ! $links ) {
		return;
	}
	echo '<nav class="pagination" aria-label="' . esc_attr__( 'Pages', 'faridpur-city' ) . '"><ul>';
	foreach ( $links as $link ) {
		// Convert page numbers to Bangla digits inside link text only.
		$link = preg_replace_callback(
			'/>(\d+)</',
			static function ( $m ) {
				return '>' . fc_digits( $m[1] ) . '<';
			},
			$link
		);
		echo '<li>' . $link . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core markup.
	}
	echo '</ul></nav>';
}

/**
 * Human label for a post type (used on search results).
 *
 * @param string $post_type Post type.
 * @return string
 */
function fc_type_label( $post_type ) {
	$labels = array(
		'post'        => __( 'News', 'faridpur-city' ),
		'page'        => __( 'Page', 'faridpur-city' ),
		'fc_notice'   => __( 'Notice', 'faridpur-city' ),
		'fc_service'  => __( 'Public service', 'faridpur-city' ),
		'fc_business' => __( 'Directory', 'faridpur-city' ),
		'fc_event'    => __( 'Event', 'faridpur-city' ),
		'fc_place'    => __( 'Place', 'faridpur-city' ),
	);
	return isset( $labels[ $post_type ] ) ? $labels[ $post_type ] : '';
}

/**
 * Card template part for a post type.
 *
 * @param string $post_type Post type.
 * @return string Slug under template-parts/cards/.
 */
function fc_card_for( $post_type ) {
	$map = array(
		'post'        => 'post',
		'fc_notice'   => 'notice',
		'fc_service'  => 'listing',
		'fc_business' => 'listing',
		'fc_event'    => 'event',
		'fc_place'    => 'place',
	);
	return isset( $map[ $post_type ] ) ? $map[ $post_type ] : 'generic';
}

/**
 * Archive heading and intro for the current request.
 *
 * @return array{title:string,intro:string,key:string}
 */
function fc_current_archive_text() {
	$title = '';
	$intro = '';
	$key   = '';
	if ( is_home() ) {
		$key   = 'news';
		$t     = fc_archive_text( 'news' );
		$id    = (int) get_option( 'page_for_posts' );
		$title = '' !== $t['title'] ? $t['title'] : ( $id ? get_the_title( $id ) : __( 'News', 'faridpur-city' ) );
		$intro = $t['intro'];
	} elseif ( is_post_type_archive() ) {
		$key   = (string) get_query_var( 'post_type' );
		$t     = fc_archive_text( $key );
		$title = '' !== $t['title'] ? $t['title'] : post_type_archive_title( '', false );
		$intro = $t['intro'];
	} elseif ( is_category() || is_tax() ) {
		$term  = get_queried_object();
		$key   = $term->taxonomy;
		$title = single_term_title( '', false );
		$intro = wp_strip_all_tags( term_description() );
	}
	return array(
		'title' => $title,
		'intro' => $intro,
		'key'   => $key,
	);
}

/**
 * Post type that the current archive lists.
 *
 * @return string
 */
function fc_archive_post_type() {
	if ( is_home() || is_category() ) {
		return 'post';
	}
	if ( is_post_type_archive() ) {
		$pt = get_query_var( 'post_type' );
		return is_array( $pt ) ? (string) reset( $pt ) : (string) $pt;
	}
	if ( is_tax() ) {
		$map = array(
			'fc_service_cat'  => 'fc_service',
			'fc_business_cat' => 'fc_business',
			'fc_place_type'   => 'fc_place',
			'fc_area'         => 'mixed',
		);
		$tax = get_queried_object()->taxonomy;
		return isset( $map[ $tax ] ) ? $map[ $tax ] : 'mixed';
	}
	return 'mixed';
}

/**
 * Print a tel: link list for a listing.
 *
 * @param int    $post_id Listing.
 * @param string $class   Class.
 */
function fc_phone_links( $post_id, $class = 'phone-list' ) {
	if ( ! function_exists( 'fc_phones' ) ) {
		return;
	}
	$phones = fc_phones( $post_id );
	if ( ! $phones ) {
		return;
	}
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $phones as $p ) {
		echo '<li><a href="' . esc_url( fc_tel_href( $p ) ) . '">' . esc_html( $p ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Shown only to logged-in editors when a homepage section is empty, explaining
 * what to add. Visitors never see it, so an empty section simply stays hidden for them.
 *
 * @param string $key Section key.
 */
function fc_home_setup_hint( $key ) {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	if ( ! fc_core_active() && 'links' !== $key ) {
		$title = fc_home_section_labels()[ $key ] ?? $key;
		$text  = __( 'The “Faridpur City Core” plugin is not active. Activate it under Plugins to use this section.', 'faridpur-city' );
		$link  = admin_url( 'plugins.php' );
		$label = __( 'Open Plugins', 'faridpur-city' );
	} else {
		$hints = array(
			'notices'       => array( __( 'Add a notice under Notices → Add new. Notices past their “Hide from homepage after” date are not shown.', 'faridpur-city' ), 'post-new.php?post_type=fc_notice' ),
			'services'      => array( __( 'Add a service under Public services → Add new, or create categories under Public services → Service categories. Tick the category on the right side of each service.', 'faridpur-city' ), 'edit-tags.php?taxonomy=fc_service_cat&post_type=fc_service' ),
			'news'          => array( __( 'Publish a post under Posts → Add New.', 'faridpur-city' ), 'post-new.php' ),
			'directory'     => array( __( 'Publish a business under Directory → Add new. Tick “Show on homepage” on business categories to show them as chips.', 'faridpur-city' ), 'post-new.php?post_type=fc_business' ),
			'emergency'     => array( __( 'Open a public service, tick “Emergency service” and add a phone number.', 'faridpur-city' ), 'edit.php?post_type=fc_service' ),
			'places_events' => array( __( 'Publish a place (Places → Add new) or an upcoming event with a start date (Events → Add new).', 'faridpur-city' ), 'post-new.php?post_type=fc_place' ),
			'links'         => array( __( 'Create a menu and assign it to the “Useful links (homepage)” location.', 'faridpur-city' ), 'nav-menus.php' ),
		);
		if ( ! isset( $hints[ $key ] ) ) {
			return;
		}
		$title = fc_home_section_labels()[ $key ] ?? $key;
		$text  = $hints[ $key ][0];
		$link  = admin_url( $hints[ $key ][1] );
		$label = __( 'Add content', 'faridpur-city' );
	}
	echo '<section class="container home-section"><div class="setup-hint" role="note"><p class="setup-hint__title">';
	/* translators: %s: section name. */
	echo esc_html( sprintf( __( '“%s” section is empty', 'faridpur-city' ), $title ) );
	echo '</p><p>' . esc_html( $text ) . '</p><p class="setup-hint__meta"><a class="btn btn--ghost btn--sm" href="' . esc_url( $link ) . '">' . esc_html( $label ) . '</a> <span>' . esc_html__( 'Only logged-in editors see this box.', 'faridpur-city' ) . '</span></p></div></section>';
}
