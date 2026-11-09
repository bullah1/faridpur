<?php
/**
 * Homepage: renders sections in the order and visibility set in
 * Customizer → Faridpur City → Homepage sections.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main site-main--home">
	<h1 class="screen-reader-text"><?php bloginfo( 'name' ); ?></h1>
	<?php
	foreach ( fc_home_sections() as $fc_section => $fc_visible ) {
		if ( $fc_visible ) {
			get_template_part( 'template-parts/home/' . str_replace( '_', '-', $fc_section ) );
		}
	}
	?>
</main>
<?php
get_footer();
