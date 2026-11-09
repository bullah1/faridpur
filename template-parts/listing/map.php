<?php
/**
 * Click-to-load map (no third-party request until the visitor asks). Args: post_id.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_id   = (int) $args['post_id'];
$fc_lat  = fc_meta( $fc_id, 'lat' );
$fc_lng  = fc_meta( $fc_id, 'lng' );
$fc_link = function_exists( 'fc_map_link' ) ? fc_map_link( $fc_id ) : '';
if ( '' === $fc_lat && '' === $fc_link ) {
	return;
}
?>
<section class="map-block" aria-labelledby="map-title">
	<h2 id="map-title" class="block-title"><?php esc_html_e( 'Location', 'faridpur-city' ); ?></h2>
	<?php if ( '' !== $fc_lat && '' !== $fc_lng ) : ?>
		<div class="map-embed" data-map-src="<?php echo esc_url( 'https://maps.google.com/maps?q=' . rawurlencode( $fc_lat . ',' . $fc_lng ) . '&z=16&output=embed' ); ?>">
			<button type="button" class="btn btn--ghost map-embed__load"><?php echo fc_icon( 'map-pin', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Show map', 'faridpur-city' ); ?></button>
			<p class="map-embed__note"><?php esc_html_e( 'The map loads from Google Maps when you click.', 'faridpur-city' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( $fc_link ) : ?>
		<p><a href="<?php echo esc_url( $fc_link ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open in Google Maps', 'faridpur-city' ); ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'faridpur-city' ); ?></span></a></p>
	<?php endif; ?>
</section>
