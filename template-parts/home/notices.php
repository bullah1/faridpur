<?php
/**
 * Homepage notices strip.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'fc_get_active_notices' ) ) {
	fc_home_setup_hint( 'notices' );
	return;
}
$fc_notices = fc_get_active_notices( 3 );
if ( ! $fc_notices ) {
	fc_home_setup_hint( 'notices' );
	return;
}
?>
<section class="notices container" aria-labelledby="home-notices">
	<div class="notices__box">
		<h2 class="notices__badge" id="home-notices"><?php echo fc_icon( 'megaphone', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Notices', 'faridpur-city' ); ?></h2>
		<ul class="notices__list">
			<?php foreach ( $fc_notices as $fc_n ) : ?>
				<li>
					<?php if ( 'urgent' === fc_meta( $fc_n->ID, 'priority' ) ) : ?>
						<span class="badge badge--urgent"><?php esc_html_e( 'Urgent', 'faridpur-city' ); ?></span>
					<?php endif; ?>
					<a href="<?php echo esc_url( get_permalink( $fc_n ) ); ?>"><?php echo esc_html( get_the_title( $fc_n ) ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<a class="notices__all" href="<?php echo esc_url( get_post_type_archive_link( 'fc_notice' ) ); ?>"><?php esc_html_e( 'All notices', 'faridpur-city' ); ?> <?php echo fc_icon( 'arrow-r', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	</div>
</section>
