<?php
/**
 * Generic result card (pages and anything without a dedicated card). Args: post, heading.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_post = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();
$fc_h    = isset( $args['heading'] ) ? $args['heading'] : 'h2';
if ( ! $fc_post ) {
	return;
}
?>
<article class="generic-card">
	<<?php echo esc_attr( $fc_h ); ?> class="generic-card__title"><a href="<?php echo esc_url( get_permalink( $fc_post ) ); ?>"><?php echo esc_html( get_the_title( $fc_post ) ); ?></a></<?php echo esc_attr( $fc_h ); ?>>
	<p><?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $fc_post ) ) ); ?></p>
</article>
