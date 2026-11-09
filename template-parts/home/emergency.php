<?php
/**
 * Homepage: emergency services with one-tap call buttons.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'fc_get_emergency_services' ) ) {
	fc_home_setup_hint( 'emergency' );
	return;
}
$fc_items = fc_get_emergency_services( fc_mod_int( 'fc_count_emergency', 6 ) );
if ( ! $fc_items ) {
	fc_home_setup_hint( 'emergency' );
	return;
}
$fc_page = get_pages(
	array(
		'meta_key'   => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'meta_value' => 'page-templates/emergency.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		'number'     => 1,
	)
);
?>
<section class="home-section container" id="emergency" aria-labelledby="home-emergency">
	<div class="emergency">
		<?php
		fc_section_head(
			array(
				'id'         => 'home-emergency',
				'title'      => fc_mod( 'fc_title_emergency', __( 'Emergency services', 'faridpur-city' ) ),
				'subtitle'   => fc_mod( 'fc_sub_emergency', __( 'Tap a number to call.', 'faridpur-city' ) ),
				'link'       => $fc_page ? get_permalink( $fc_page[0] ) : '',
				'link_label' => __( 'All emergency numbers', 'faridpur-city' ),
			)
		);
		?>
		<ul class="emergency__grid" role="list">
			<?php foreach ( $fc_items as $fc_s ) : ?>
				<li><?php get_template_part( 'template-parts/cards/emergency', null, array( 'post' => $fc_s ) ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
