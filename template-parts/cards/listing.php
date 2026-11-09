<?php
/**
 * Directory / public service card. Args: post, heading.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_post = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();
$fc_h    = isset( $args['heading'] ) ? $args['heading'] : 'h2';
if ( ! $fc_post ) {
	return;
}
$fc_id      = $fc_post->ID;
$fc_tax     = 'fc_service' === $fc_post->post_type ? 'fc_service_cat' : 'fc_business_cat';
$fc_cat     = fc_first_term( $fc_id, $fc_tax );
$fc_area    = fc_first_term( $fc_id, 'fc_area' );
$fc_phones  = function_exists( 'fc_phones' ) ? fc_phones( $fc_id ) : array();
$fc_map     = function_exists( 'fc_map_link' ) ? fc_map_link( $fc_id ) : '';
$fc_name_en = fc_meta( $fc_id, 'name_en' );
$fc_address = fc_meta( $fc_id, 'address' );
?>
<article class="listing-card">
	<div class="listing-card__head">
		<?php if ( has_post_thumbnail( $fc_id ) ) : ?>
			<?php echo get_the_post_thumbnail( $fc_id, 'fc-square', array( 'class' => 'listing-card__logo', 'loading' => 'lazy', 'alt' => '' ) ); ?>
		<?php else : ?>
			<span class="listing-card__logo listing-card__logo--icon" aria-hidden="true"><?php echo $fc_cat ? fc_term_icon( $fc_cat ) : fc_icon( 'grid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php endif; ?>
		<div>
			<<?php echo esc_attr( $fc_h ); ?> class="listing-card__title"><a href="<?php echo esc_url( get_permalink( $fc_post ) ); ?>"><?php echo esc_html( get_the_title( $fc_post ) ); ?></a></<?php echo esc_attr( $fc_h ); ?>>
			<p class="listing-card__meta">
				<?php
				$fc_bits = array_filter( array( $fc_name_en, $fc_cat ? $fc_cat->name : '', $fc_area ? $fc_area->name : '' ) );
				echo esc_html( implode( ' · ', $fc_bits ) );
				?>
			</p>
		</div>
	</div>
	<?php if ( $fc_address ) : ?>
		<p class="listing-card__address"><?php echo fc_icon( 'map-pin', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $fc_address ); ?></span></p>
	<?php endif; ?>
	<div class="listing-card__badges"><?php fc_open_badge( $fc_id ); ?></div>
	<div class="listing-card__actions">
		<?php if ( $fc_phones ) : ?>
			<a class="btn btn--primary btn--sm" href="<?php echo esc_url( fc_tel_href( $fc_phones[0] ) ); ?>"><?php echo fc_icon( 'phone', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Call', 'faridpur-city' ); ?><span class="screen-reader-text"> <?php echo esc_html( get_the_title( $fc_post ) ); ?></span></a>
		<?php endif; ?>
		<?php if ( $fc_map ) : ?>
			<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( $fc_map ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Map', 'faridpur-city' ); ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'faridpur-city' ); ?></span></a>
		<?php endif; ?>
		<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( get_permalink( $fc_post ) ); ?>" aria-hidden="true" tabindex="-1"><?php esc_html_e( 'Details', 'faridpur-city' ); ?></a>
	</div>
</article>
