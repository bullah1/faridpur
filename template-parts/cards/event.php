<?php
/**
 * Event row with date block. Args: post, heading.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_post = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();
$fc_h    = isset( $args['heading'] ) ? $args['heading'] : 'h2';
if ( ! $fc_post ) {
	return;
}
$fc_id     = $fc_post->ID;
$fc_start  = fc_meta( $fc_id, 'start' );
$fc_place  = (int) fc_meta( $fc_id, 'venue_place' );
$fc_venue  = $fc_place ? get_the_title( $fc_place ) : fc_meta( $fc_id, 'venue_text' );
$fc_status = fc_meta( $fc_id, 'status', 'scheduled' );
$fc_past   = function_exists( 'fc_event_is_past' ) && fc_event_is_past( $fc_id );
?>
<article class="event-row<?php echo $fc_past ? ' is-past' : ''; ?>">
	<?php if ( $fc_start ) : ?>
		<time class="date-block" datetime="<?php echo esc_attr( $fc_start ); ?>">
			<span class="date-block__day"><?php echo esc_html( fc_date( $fc_start, 'j' ) ); ?></span>
			<span class="date-block__month"><?php echo esc_html( fc_date( $fc_start, 'M' ) ); ?></span>
		</time>
	<?php endif; ?>
	<div class="event-row__body">
		<<?php echo esc_attr( $fc_h ); ?> class="event-row__title"><a href="<?php echo esc_url( get_permalink( $fc_post ) ); ?>"><?php echo esc_html( get_the_title( $fc_post ) ); ?></a></<?php echo esc_attr( $fc_h ); ?>>
		<p class="event-row__meta">
			<?php
			$fc_bits = array_filter( array( $fc_venue, $fc_start ? fc_time( substr( $fc_start, 11, 5 ) ) : '' ) );
			echo esc_html( implode( ' · ', $fc_bits ) );
			?>
			<?php if ( 'cancelled' === $fc_status ) : ?>
				<span class="badge badge--closed"><?php esc_html_e( 'Cancelled', 'faridpur-city' ); ?></span>
			<?php elseif ( 'postponed' === $fc_status ) : ?>
				<span class="badge badge--urgent"><?php esc_html_e( 'Postponed', 'faridpur-city' ); ?></span>
			<?php elseif ( $fc_past ) : ?>
				<span class="badge"><?php esc_html_e( 'Ended', 'faridpur-city' ); ?></span>
			<?php endif; ?>
		</p>
	</div>
</article>
