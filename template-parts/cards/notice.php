<?php
/**
 * Notice row. Args: post, heading.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_post = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();
$fc_h    = isset( $args['heading'] ) ? $args['heading'] : 'h2';
if ( ! $fc_post ) {
	return;
}
$fc_issue  = fc_meta( $fc_post->ID, 'issue_date' );
$fc_office = fc_meta( $fc_post->ID, 'office' );
$fc_pdf    = (int) fc_meta( $fc_post->ID, 'pdf' );
?>
<article class="notice-row">
	<span class="notice-row__icon" aria-hidden="true"><?php echo fc_icon( $fc_pdf ? 'file' : 'megaphone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<div>
		<<?php echo esc_attr( $fc_h ); ?> class="notice-row__title">
			<?php if ( 'urgent' === fc_meta( $fc_post->ID, 'priority' ) ) : ?>
				<span class="badge badge--urgent"><?php esc_html_e( 'Urgent', 'faridpur-city' ); ?></span>
			<?php endif; ?>
			<a href="<?php echo esc_url( get_permalink( $fc_post ) ); ?>"><?php echo esc_html( get_the_title( $fc_post ) ); ?></a>
		</<?php echo esc_attr( $fc_h ); ?>>
		<p class="meta">
			<time datetime="<?php echo esc_attr( $fc_issue ? $fc_issue : get_the_date( 'Y-m-d', $fc_post ) ); ?>"><?php echo esc_html( $fc_issue ? fc_date( $fc_issue ) : fc_post_date( $fc_post ) ); ?></time>
			<?php if ( $fc_office ) : ?>
				<span><?php echo esc_html( $fc_office ); ?></span>
			<?php endif; ?>
		</p>
	</div>
</article>
