<?php
/**
 * Static page.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'article container' ); ?>>
			<?php fc_breadcrumbs(); ?>
			<header class="article__header content-narrow">
				<h1 class="article__title"><?php the_title(); ?></h1>
			</header>
			<div class="prose content-narrow">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
