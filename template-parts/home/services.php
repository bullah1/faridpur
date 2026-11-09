<?php
/**
 * Homepage: service categories as icon cards.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'fc_get_home_terms' ) ) {
	fc_home_setup_hint( 'services' );
	return;
}
$fc_terms = fc_get_home_terms( 'fc_service_cat', fc_mod_int( 'fc_count_services', 12 ) );
// No categories yet: show the latest services themselves so the section is not empty.
$fc_items = array();
if ( ! $fc_terms ) {
	foreach ( get_posts( array( 'post_type' => 'fc_service', 'posts_per_page' => fc_mod_int( 'fc_count_services', 12 ), 'no_found_rows' => true ) ) as $fc_sp ) {
		$fc_items[] = array(
			'url'   => get_permalink( $fc_sp ),
			'name'  => get_the_title( $fc_sp ),
			'icon'  => '1' === (string) fc_meta( $fc_sp->ID, 'emergency' ) ? fc_icon( 'emergency' ) : fc_icon( 'office' ),
			'count' => '',
		);
	}
} else {
	foreach ( $fc_terms as $fc_term ) {
		$fc_items[] = array(
			'url'   => get_term_link( $fc_term ),
			'name'  => $fc_term->name,
			'icon'  => fc_term_icon( $fc_term ),
			/* translators: %s: number of listings. */
			'count' => $fc_term->count ? sprintf( _n( '%s listing', '%s listings', $fc_term->count, 'faridpur-city' ), fc_digits( $fc_term->count ) ) : '',
		);
	}
}
if ( ! $fc_items ) {
	fc_home_setup_hint( 'services' );
	return;
}
?>
<section class="home-section container" aria-labelledby="home-services">
	<?php
	fc_section_head(
		array(
			'id'       => 'home-services',
			'title'    => fc_mod( 'fc_title_services', __( 'City services', 'faridpur-city' ) ),
			'subtitle' => fc_mod( 'fc_sub_services' ),
			'link'     => get_post_type_archive_link( 'fc_service' ),
			'link_label' => __( 'All services', 'faridpur-city' ),
		)
	);
	?>
	<ul class="cat-grid" role="list">
		<?php foreach ( $fc_items as $fc_it ) : ?>
			<li>
				<a class="cat-card" href="<?php echo esc_url( $fc_it['url'] ); ?>">
					<span class="cat-card__icon"><?php echo $fc_it['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- registry SVG. ?></span>
					<span class="cat-card__name"><?php echo esc_html( $fc_it['name'] ); ?></span>
					<?php if ( '' !== $fc_it['count'] ) : ?>
						<span class="cat-card__count"><?php echo esc_html( $fc_it['count'] ); ?></span>
					<?php endif; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
