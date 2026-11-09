<?php
/**
 * Emergency tile: name and primary number as a call link. Args: post.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_post = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();
if ( ! $fc_post ) {
	return;
}
$fc_phones = function_exists( 'fc_phones' ) ? fc_phones( $fc_post->ID ) : array();
$fc_cat    = fc_first_term( $fc_post->ID, 'fc_service_cat' );
?>
<div class="em-tile">
	<span class="em-tile__icon" aria-hidden="true"><?php echo $fc_cat ? fc_term_icon( $fc_cat ) : fc_icon( 'emergency' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<div class="em-tile__body">
		<a class="em-tile__name" href="<?php echo esc_url( get_permalink( $fc_post ) ); ?>"><?php echo esc_html( get_the_title( $fc_post ) ); ?></a>
		<?php if ( $fc_phones ) : ?>
			<a class="em-tile__phone" href="<?php echo esc_url( fc_tel_href( $fc_phones[0] ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: service, 2: number. */ __( 'Call %1$s: %2$s', 'faridpur-city' ), get_the_title( $fc_post ), $fc_phones[0] ) ); ?>"><?php echo fc_icon( 'phone', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $fc_phones[0] ); ?></a>
		<?php endif; ?>
	</div>
</div>
