<?php
/**
 * Single place.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$fc_id   = get_the_ID();
		$fc_type = fc_first_term( $fc_id, 'fc_place_type' );
		$fc_area = fc_first_term( $fc_id, 'fc_area' );
		?>
		<article <?php post_class( 'article container' ); ?>>
			<?php fc_breadcrumbs(); ?>
			<header class="article__header content-narrow">
				<p class="listing__tags">
					<?php if ( $fc_type ) : ?>
						<a class="tag" href="<?php echo esc_url( get_term_link( $fc_type ) ); ?>"><?php echo esc_html( $fc_type->name ); ?></a>
					<?php endif; ?>
					<?php if ( $fc_area ) : ?>
						<a class="tag tag--plain" href="<?php echo esc_url( get_term_link( $fc_area ) ); ?>"><?php echo esc_html( $fc_area->name ); ?></a>
					<?php endif; ?>
				</p>
				<h1 class="article__title"><?php the_title(); ?></h1>
				<?php if ( fc_meta( $fc_id, 'name_en' ) ) : ?>
					<p class="listing__alt" lang="en"><?php echo esc_html( fc_meta( $fc_id, 'name_en' ) ); ?></p>
				<?php endif; ?>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="article__figure"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></figure>
			<?php endif; ?>
			<div class="content-narrow">
				<?php
				$fc_facts = array(
					'visiting_hours' => array( 'clock', __( 'Visiting hours', 'faridpur-city' ) ),
					'entry_fee'      => array( 'info', __( 'Entry fee', 'faridpur-city' ) ),
					'address'        => array( 'map-pin', __( 'Address', 'faridpur-city' ) ),
				);
				$fc_has   = array_filter(
					array_keys( $fc_facts ),
					static function ( $k ) use ( $fc_id ) {
						return '' !== fc_meta( $fc_id, $k );
					}
				);
				if ( $fc_has ) :
					?>
					<dl class="facts">
						<?php foreach ( $fc_has as $fc_k ) : ?>
							<dt><?php echo fc_icon( $fc_facts[ $fc_k ][0], array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $fc_facts[ $fc_k ][1] ); ?></dt>
							<dd><?php echo esc_html( fc_meta( $fc_id, $fc_k ) ); ?></dd>
						<?php endforeach; ?>
					</dl>
				<?php endif; ?>
				<div class="prose"><?php the_content(); ?></div>
				<?php if ( fc_meta( $fc_id, 'directions' ) ) : ?>
					<section aria-labelledby="directions-title">
						<h2 id="directions-title" class="block-title"><?php esc_html_e( 'How to get there', 'faridpur-city' ); ?></h2>
						<p><?php echo nl2br( esc_html( fc_meta( $fc_id, 'directions' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					</section>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/listing/gallery', null, array( 'post_id' => $fc_id ) ); ?>
				<?php get_template_part( 'template-parts/listing/map', null, array( 'post_id' => $fc_id ) ); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
