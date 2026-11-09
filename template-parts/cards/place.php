<?php
/**
 * Place card. Args: post, heading.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_post = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();
$fc_h    = isset( $args['heading'] ) ? $args['heading'] : 'h2';
if ( ! $fc_post ) {
	return;
}
$fc_area = fc_first_term( $fc_post->ID, 'fc_area' );
$fc_type = fc_first_term( $fc_post->ID, 'fc_place_type' );
?>
<article class="place-card">
	<?php fc_post_image( $fc_post->ID, 'fc-card', 'place-card__media media' ); ?>
	<<?php echo esc_attr( $fc_h ); ?> class="place-card__title"><a class="stretched" href="<?php echo esc_url( get_permalink( $fc_post ) ); ?>"><?php echo esc_html( get_the_title( $fc_post ) ); ?></a></<?php echo esc_attr( $fc_h ); ?>>
	<p class="place-card__meta"><?php echo esc_html( implode( ' · ', array_filter( array( $fc_type ? $fc_type->name : '', $fc_area ? $fc_area->name : '' ) ) ) ); ?></p>
</article>
