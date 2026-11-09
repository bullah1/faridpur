<?php
/**
 * Single listing.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

get_header();
echo '<main id="main" class="site-main">';
while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/listing/single' );
}
echo '</main>';
get_footer();
