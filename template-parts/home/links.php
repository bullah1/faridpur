<?php
/**
 * Homepage: useful links (menu location "Useful links").
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

if ( ! has_nav_menu( 'useful_links' ) ) {
	fc_home_setup_hint( 'links' );
	return;
}
?>
<section class="home-section container" aria-labelledby="home-links">
	<?php
	fc_section_head(
		array(
			'id'    => 'home-links',
			'title' => fc_mod( 'fc_title_links', __( 'Useful links', 'faridpur-city' ) ),
		)
	);
	wp_nav_menu(
		array(
			'theme_location' => 'useful_links',
			'container'      => false,
			'menu_class'     => 'link-grid',
			'depth'          => 1,
		)
	);
	?>
</section>
