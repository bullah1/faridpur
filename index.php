<?php
/**
 * Fallback template.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

if ( is_archive() || is_home() ) {
	require get_template_directory() . '/archive.php';
	return;
}

get_header();
?>
<main id="main" class="site-main">
	<div class="container content-narrow">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/cards/generic', null, array( 'post' => get_the_ID() ) );
		endwhile;
		fc_pagination();
		?>
	</div>
</main>
<?php
get_footer();
