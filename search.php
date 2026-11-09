<?php
/**
 * Search results across all content types.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

get_header();
global $wp_query;
?>
<main id="main" class="site-main">
	<header class="page-hero">
		<div class="container">
			<?php fc_breadcrumbs(); ?>
			<h1 class="page-hero__title">
				<?php
				/* translators: %s: search query. */
				echo esc_html( sprintf( __( 'Results for “%s”', 'faridpur-city' ), get_search_query() ) );
				?>
			</h1>
			<?php get_search_form( array( 'aria_label' => __( 'Search again', 'faridpur-city' ), 'class' => 'search-form--large' ) ); ?>
		</div>
	</header>
	<div class="container archive-body">
		<div class="archive-body__main">
			<?php if ( have_posts() ) : ?>
				<p class="page-hero__count">
					<?php
					/* translators: %s: number of results. */
					echo esc_html( sprintf( _n( '%s result', '%s results', (int) $wp_query->found_posts, 'faridpur-city' ), fc_digits( (int) $wp_query->found_posts ) ) );
					?>
				</p>
				<ul class="search-list" role="list">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<li>
							<span class="search-list__type"><?php echo esc_html( fc_type_label( get_post_type() ) ); ?></span>
							<?php get_template_part( 'template-parts/cards/' . fc_card_for( get_post_type() ), null, array( 'post' => get_the_ID(), 'heading' => 'h2' ) ); ?>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php fc_pagination(); ?>
			<?php else : ?>
				<div class="empty-state">
					<p><?php esc_html_e( 'Nothing matched your search. Try a different word, or browse the sections below.', 'faridpur-city' ); ?></p>
					<?php if ( has_nav_menu( 'primary' ) ) : ?>
						<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'link-grid', 'depth' => 1 ) ); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
