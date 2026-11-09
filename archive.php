<?php
/**
 * Archives: news, notices, services, directory, events, places and their categories/areas.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

get_header();

$fc_text = fc_current_archive_text();
$fc_pt   = fc_archive_post_type();
$fc_listing = in_array( $fc_pt, array( 'fc_business', 'fc_service' ), true );
?>
<main id="main" class="site-main">
	<header class="page-hero">
		<div class="container">
			<?php fc_breadcrumbs(); ?>
			<h1 class="page-hero__title"><?php echo esc_html( $fc_text['title'] ); ?></h1>
			<?php if ( '' !== $fc_text['intro'] ) : ?>
				<p class="page-hero__intro"><?php echo esc_html( $fc_text['intro'] ); ?></p>
			<?php endif; ?>
			<?php if ( $GLOBALS['wp_query']->found_posts ) : ?>
				<p class="page-hero__count">
					<?php
					/* translators: %s: number of items. */
					echo esc_html( sprintf( _n( '%s item', '%s items', (int) $GLOBALS['wp_query']->found_posts, 'faridpur-city' ), fc_digits( (int) $GLOBALS['wp_query']->found_posts ) ) );
					?>
				</p>
			<?php endif; ?>
		</div>
	</header>

	<div class="container archive-body<?php echo $fc_listing ? ' archive-body--filters' : ''; ?>">
		<?php if ( $fc_listing ) : ?>
			<?php get_template_part( 'template-parts/listing/filters' ); ?>
		<?php endif; ?>

		<div class="archive-body__main">
			<?php if ( is_post_type_archive( 'fc_event' ) ) : ?>
				<nav class="tabs" aria-label="<?php esc_attr_e( 'Event time', 'faridpur-city' ); ?>">
					<?php $fc_past = 'past' === get_query_var( 'when' ); ?>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'fc_event' ) ); ?>" <?php echo $fc_past ? '' : 'aria-current="page"'; ?>><?php esc_html_e( 'Upcoming', 'faridpur-city' ); ?></a>
					<a href="<?php echo esc_url( add_query_arg( 'when', 'past', get_post_type_archive_link( 'fc_event' ) ) ); ?>" <?php echo $fc_past ? 'aria-current="page"' : ''; ?>><?php esc_html_e( 'Past', 'faridpur-city' ); ?></a>
				</nav>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>
				<?php
				$fc_layout = array(
					'post'        => 'card-grid card-grid--news',
					'fc_business' => 'card-grid card-grid--listings',
					'fc_service'  => 'card-grid card-grid--listings',
					'fc_place'    => 'card-grid card-grid--places',
					'fc_event'    => 'event-list event-list--page',
					'fc_notice'   => 'notice-list',
				);
				$fc_class  = isset( $fc_layout[ $fc_pt ] ) ? $fc_layout[ $fc_pt ] : 'card-grid card-grid--listings';
				?>
				<ul class="<?php echo esc_attr( $fc_class ); ?>" role="list">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<li>
							<?php
							get_template_part(
								'template-parts/cards/' . fc_card_for( get_post_type() ),
								null,
								array(
									'post'    => get_the_ID(),
									'heading' => 'h2',
								)
							);
							?>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php fc_pagination(); ?>
			<?php else : ?>
				<div class="empty-state">
					<p><?php esc_html_e( 'Nothing has been published here yet.', 'faridpur-city' ); ?></p>
					<?php if ( function_exists( 'fc_is_filtered_view' ) && fc_is_filtered_view() ) : ?>
						<p><a href="<?php echo esc_url( function_exists( 'fc_canonical_url' ) ? fc_canonical_url() : home_url( '/' ) ); ?>"><?php esc_html_e( 'Clear filters', 'faridpur-city' ); ?></a></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			// Sibling categories for internal linking.
			if ( is_tax( array( 'fc_business_cat', 'fc_service_cat', 'fc_place_type' ) ) ) :
				$fc_term     = get_queried_object();
				$fc_siblings = get_terms(
					array(
						'taxonomy'   => $fc_term->taxonomy,
						'parent'     => $fc_term->parent,
						'exclude'    => array( $fc_term->term_id ),
						'hide_empty' => true,
						'number'     => 12,
					)
				);
				if ( $fc_siblings && ! is_wp_error( $fc_siblings ) ) :
					?>
					<section class="related" aria-labelledby="related-cats">
						<h2 id="related-cats" class="related__title"><?php esc_html_e( 'Related categories', 'faridpur-city' ); ?></h2>
						<ul class="chips" role="list">
							<?php foreach ( $fc_siblings as $fc_s ) : ?>
								<li><a class="chip" href="<?php echo esc_url( get_term_link( $fc_s ) ); ?>"><?php echo fc_term_icon( $fc_s, 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $fc_s->name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</section>
					<?php
				endif;
			endif;
			?>
		</div>
	</div>
</main>
<?php
get_footer();
