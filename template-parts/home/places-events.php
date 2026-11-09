<?php
/**
 * Homepage: featured places and upcoming events.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_places = post_type_exists( 'fc_place' ) ? get_posts(
	array(
		'post_type'      => 'fc_place',
		'posts_per_page' => fc_mod_int( 'fc_count_places', 3 ),
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
	)
) : array();
$fc_events = function_exists( 'fc_get_upcoming_events' ) ? fc_get_upcoming_events( fc_mod_int( 'fc_count_events', 3 ) ) : array();
if ( ! $fc_places && ! $fc_events ) {
	fc_home_setup_hint( 'places_events' );
	return;
}
?>
<section class="home-section container split" aria-label="<?php esc_attr_e( 'Places and events', 'faridpur-city' ); ?>">
	<?php if ( $fc_places ) : ?>
		<div class="split__main">
			<?php
			fc_section_head(
				array(
					'id'         => 'home-places',
					'title'      => fc_mod( 'fc_title_places', __( 'Places to visit', 'faridpur-city' ) ),
					'link'       => get_post_type_archive_link( 'fc_place' ),
					'link_label' => __( 'All places', 'faridpur-city' ),
				)
			);
			?>
			<ul class="card-grid card-grid--places" role="list">
				<?php foreach ( $fc_places as $fc_pl ) : ?>
					<li><?php get_template_part( 'template-parts/cards/place', null, array( 'post' => $fc_pl, 'heading' => 'h3' ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
	<?php if ( $fc_events ) : ?>
		<div class="split__side">
			<?php
			fc_section_head(
				array(
					'id'         => 'home-events',
					'title'      => fc_mod( 'fc_title_events', __( 'Upcoming events', 'faridpur-city' ) ),
					'link'       => get_post_type_archive_link( 'fc_event' ),
					'link_label' => __( 'All events', 'faridpur-city' ),
				)
			);
			?>
			<ul class="event-list" role="list">
				<?php foreach ( $fc_events as $fc_ev ) : ?>
					<li><?php get_template_part( 'template-parts/cards/event', null, array( 'post' => $fc_ev, 'heading' => 'h3' ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</section>
