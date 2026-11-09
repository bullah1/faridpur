<?php
/**
 * Faridpur City theme bootstrap.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

define( 'FC_THEME_VERSION', '1.0.0' );

// Built-in content module. Skipped when the Faridpur City Core plugin is active (avoids duplicate function errors).
if ( ! defined( 'FC_CORE_VERSION' ) ) {
	require get_template_directory() . '/inc/content.php';
}
require get_template_directory() . '/inc/compat.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer.php';

/**
 * Interface language for theme strings: "bn" (default) or "en".
 *
 * @return string
 */
function fc_theme_ui_lang() {
	$lang = get_theme_mod( 'fc_ui_lang', 'bn' );
	return 'en' === $lang ? 'en' : 'bn';
}

/**
 * Load Bangla interface strings when selected, independent of the WordPress site language,
 * so the front end is Bangla even if wp-admin stays in English.
 */
add_action(
	'after_setup_theme',
	static function () {
		if ( 'bn' === fc_theme_ui_lang() ) {
			load_textdomain( 'faridpur-city', get_template_directory() . '/languages/bn_BD.mo' );
			// Front-end strings printed by the companion plugin (breadcrumbs, weekdays, icon labels).
			if ( ! is_admin() && defined( 'FC_CORE_DIR' ) ) {
				load_textdomain( 'faridpur-city-core', FC_CORE_DIR . 'languages/faridpur-city-core-bn_BD.mo' );
			}
		} else {
			load_theme_textdomain( 'faridpur-city', get_template_directory() . '/languages' );
		}
	},
	1
);

add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 96,
				'width'       => 96,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor.css' );

		add_image_size( 'fc-card', 640, 400, true );
		add_image_size( 'fc-thumb', 240, 160, true );
		add_image_size( 'fc-hero', 1600, 600, true );
		add_image_size( 'fc-hero-mobile', 800, 800, true );
		add_image_size( 'fc-square', 160, 160, true );

		register_nav_menus(
			array(
				'primary'      => __( 'Primary menu', 'faridpur-city' ),
				'mobile_bar'   => __( 'Mobile bottom bar (up to 5 items)', 'faridpur-city' ),
				'footer_1'     => __( 'Footer column 1', 'faridpur-city' ),
				'footer_2'     => __( 'Footer column 2', 'faridpur-city' ),
				'useful_links' => __( 'Useful links (homepage)', 'faridpur-city' ),
				'social'       => __( 'Social links', 'faridpur-city' ),
			)
		);

		$GLOBALS['content_width'] = 760;
	}
);

/**
 * Front-end assets.
 */
add_action(
	'wp_enqueue_scripts',
	static function () {
		$uri = get_template_directory_uri();
		wp_enqueue_style( 'faridpur-city', $uri . '/assets/css/main.css', array(), FC_THEME_VERSION );
		wp_enqueue_script( 'faridpur-city', $uri . '/assets/js/main.js', array(), FC_THEME_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
		wp_localize_script(
			'faridpur-city',
			'fcTheme',
			array(
				'openMenu'  => __( 'Open menu', 'faridpur-city' ),
				'closeMenu' => __( 'Close menu', 'faridpur-city' ),
				'mapTitle'  => __( 'Map', 'faridpur-city' ),
				/* translators: 1: current slide number, 2: total slides. */
				'slideOf'   => __( 'Slide %1$s of %2$s', 'faridpur-city' ),
			)
		);
		// Block library CSS is only needed on pages with block content.
		if ( is_front_page() || is_post_type_archive() || is_tax() || is_category() || is_search() ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'global-styles' );
			wp_dequeue_style( 'classic-theme-styles' );
		}
	},
	20
);

// Preload the most used font file.
add_action(
	'wp_head',
	static function () {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_template_directory_uri() . '/assets/fonts/hind-siliguri-bengali-400-normal.woff2' )
		);
	},
	1
);

// Emoji detection script and styles are not used.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );

/**
 * Set the html lang attribute from the interface language when the site locale is English.
 */
add_filter(
	'language_attributes',
	static function ( $output ) {
		if ( is_admin() ) {
			return $output;
		}
		if ( 'bn' === fc_theme_ui_lang() && 0 !== strpos( get_locale(), 'bn' ) ) {
			$output = preg_replace( '/lang="[^"]*"/', 'lang="bn"', $output );
		}
		return $output;
	}
);

add_filter(
	'excerpt_length',
	static function () {
		return 24;
	}
);
add_filter(
	'excerpt_more',
	static function () {
		return '…';
	}
);

/**
 * Body classes.
 */
add_filter(
	'body_class',
	static function ( $classes ) {
		$classes[] = 'fc-lang-' . fc_theme_ui_lang();
		if ( has_nav_menu( 'mobile_bar' ) ) {
			$classes[] = 'has-mobile-bar';
		}
		return $classes;
	}
);

/**
 * Icons for the mobile bar and social menus.
 * Mobile bar: add a CSS class such as "icon-phone" to a menu item (Screen Options → CSS Classes).
 * Social: the icon is picked from the link's domain.
 */
add_filter(
	'nav_menu_item_title',
	static function ( $title, $item, $args ) {
		$loc = isset( $args->theme_location ) ? $args->theme_location : '';
		if ( 'mobile_bar' === $loc ) {
			$icon = 'grid';
			foreach ( (array) $item->classes as $class ) {
				if ( 0 === strpos( (string) $class, 'icon-' ) ) {
					$icon = substr( $class, 5 );
				}
			}
			return fc_icon( $icon, array( 'size' => 22 ) ) . '<span>' . $title . '</span>';
		}
		if ( 'social' === $loc ) {
			$host = (string) wp_parse_url( $item->url, PHP_URL_HOST );
			$icon = false !== strpos( $host, 'facebook' ) || false !== strpos( $host, 'fb.' ) ? 'facebook' : ( false !== strpos( $host, 'youtu' ) ? 'youtube' : 'link' );
			return fc_icon( $icon, array( 'size' => 20 ) ) . '<span>' . $title . '</span>';
		}
		return $title;
	},
	10,
	3
);

/**
 * Translated document titles for search and 404 (core strings follow the site locale).
 */
add_filter(
	'document_title_parts',
	static function ( $parts ) {
		if ( is_search() ) {
			/* translators: %s: search query. */
			$parts['title'] = sprintf( __( 'Results for “%s”', 'faridpur-city' ), get_search_query() );
		} elseif ( is_404() ) {
			$parts['title'] = __( 'This page could not be found', 'faridpur-city' );
		} elseif ( is_paged() ) {
			/* translators: %s: page number. */
			$parts['page'] = sprintf( __( 'Page %s', 'faridpur-city' ), fc_digits( (string) max( 1, (int) get_query_var( 'paged' ) ) ) );
		}
		return $parts;
	},
	20
);

// Structured data / Open Graph language follows the interface language.
add_filter(
	'fc_core_language',
	static function ( $lang ) {
		return ( 'bn' === fc_theme_ui_lang() && 0 !== strpos( $lang, 'bn' ) ) ? 'bn-BD' : $lang;
	}
);
