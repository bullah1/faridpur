<?php
/**
 * News card. Args: post, variant (card|lead|row), heading (h2|h3).
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_post    = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();
$fc_variant = isset( $args['variant'] ) ? $args['variant'] : 'card';
$fc_h       = isset( $args['heading'] ) ? $args['heading'] : 'h3';
if ( ! $fc_post ) {
	return;
}
$fc_cat  = fc_first_term( $fc_post->ID, 'category' );
$fc_size = 'row' === $fc_variant ? 'fc-thumb' : ( 'lead' === $fc_variant ? 'large' : 'fc-card' );
?>
<article class="news-card news-card--<?php echo esc_attr( $fc_variant ); ?>">
	<?php fc_post_image( $fc_post->ID, $fc_size, 'news-card__media media', 'lead' === $fc_variant ); ?>
	<div class="news-card__body">
		<p class="meta">
			<?php if ( $fc_cat ) : ?>
				<span class="meta__cat"><?php echo esc_html( $fc_cat->name ); ?></span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $fc_post ) ); ?>"><?php echo esc_html( fc_post_date( $fc_post ) ); ?></time>
		</p>
		<<?php echo esc_attr( $fc_h ); ?> class="news-card__title"><a class="stretched" href="<?php echo esc_url( get_permalink( $fc_post ) ); ?>"><?php echo esc_html( get_the_title( $fc_post ) ); ?></a></<?php echo esc_attr( $fc_h ); ?>>
		<?php if ( 'row' !== $fc_variant ) : ?>
			<p class="news-card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $fc_post ) ) ); ?></p>
		<?php endif; ?>
	</div>
</article>
