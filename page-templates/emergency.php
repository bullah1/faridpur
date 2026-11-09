<?php
/**
 * Template Name: Emergency services
 *
 * Lists every public service marked "Emergency", grouped by category. The page's own
 * content is shown above the list.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

get_header();
$fc_items = function_exists( 'fc_get_emergency_services' ) ? fc_get_emergency_services( -1 ) : array();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<header class="page-hero">
			<div class="container">
				<?php fc_breadcrumbs(); ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<?php if ( get_the_content() ) : ?>
					<div class="page-hero__intro prose"><?php the_content(); ?></div>
				<?php endif; ?>
			</div>
		</header>
	<?php endwhile; ?>
	<div class="container home-section">
		<?php if ( $fc_items ) : ?>
			<div class="emergency emergency--page">
				<ul class="emergency__grid" role="list">
					<?php foreach ( $fc_items as $fc_s ) : ?>
						<li><?php get_template_part( 'template-parts/cards/emergency', null, array( 'post' => $fc_s ) ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php else : ?>
			<div class="empty-state"><p><?php esc_html_e( 'No emergency services have been added yet.', 'faridpur-city' ); ?></p></div>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
