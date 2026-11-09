<?php
/**
 * Image gallery from the "gallery" field. Args: post_id.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_ids = array_filter( array_map( 'absint', explode( ',', (string) fc_meta( $args['post_id'], 'gallery' ) ) ) );
if ( ! $fc_ids ) {
	return;
}
?>
<section class="gallery-block" aria-labelledby="gallery-title">
	<h2 id="gallery-title" class="block-title"><?php esc_html_e( 'Photos', 'faridpur-city' ); ?></h2>
	<ul class="gallery-grid" role="list">
		<?php foreach ( $fc_ids as $fc_att ) : ?>
			<?php
			$fc_full = wp_get_attachment_image_url( $fc_att, 'large' );
			if ( ! $fc_full ) {
				continue;
			}
			?>
			<li><a href="<?php echo esc_url( $fc_full ); ?>"><?php echo wp_get_attachment_image( $fc_att, 'fc-card', false, array( 'loading' => 'lazy' ) ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</section>
