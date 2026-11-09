<?php
/**
 * Homepage hero: admin-managed banners (Hero banners menu). Falls back to a search panel.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_banners = function_exists( 'fc_get_banners' ) ? fc_get_banners( 5 ) : array();
?>
<section class="hero container" aria-roledescription="<?php echo $fc_banners && count( $fc_banners ) > 1 ? esc_attr__( 'carousel', 'faridpur-city' ) : ''; ?>" aria-label="<?php esc_attr_e( 'Featured', 'faridpur-city' ); ?>">
	<?php if ( $fc_banners ) : ?>
		<div class="hero__track" data-hero>
			<?php
			foreach ( $fc_banners as $fc_i => $fc_banner ) :
				$fc_bid    = $fc_banner->ID;
				$fc_label  = fc_meta( $fc_bid, 'label' );
				$fc_sub    = fc_meta( $fc_bid, 'subtitle' );
				$fc_btn    = fc_meta( $fc_bid, 'btn_label' );
				$fc_url    = fc_meta( $fc_bid, 'btn_url' );
				$fc_mobile = (int) fc_meta( $fc_bid, 'mobile_image' );
				$fc_desk   = get_post_thumbnail_id( $fc_bid );
				?>
				<div class="hero__slide<?php echo 0 === $fc_i ? ' is-active' : ''; ?>" role="group" aria-roledescription="<?php esc_attr_e( 'slide', 'faridpur-city' ); ?>" aria-label="<?php echo esc_attr( fc_digits( ( $fc_i + 1 ) . ' / ' . count( $fc_banners ) ) ); ?>" <?php echo 0 === $fc_i ? '' : 'hidden'; ?>>
					<?php if ( $fc_desk || $fc_mobile ) : ?>
						<picture class="hero__media">
							<?php if ( $fc_mobile ) : ?>
								<source media="(max-width: 640px)" srcset="<?php echo esc_attr( (string) wp_get_attachment_image_srcset( $fc_mobile, 'fc-hero-mobile' ) ?: (string) wp_get_attachment_image_url( $fc_mobile, 'fc-hero-mobile' ) ); ?>" sizes="100vw">
							<?php endif; ?>
							<?php
							echo wp_get_attachment_image(
								$fc_desk ? $fc_desk : $fc_mobile,
								'fc-hero',
								false,
								array(
									'class'         => 'hero__img',
									'loading'       => 0 === $fc_i ? 'eager' : 'lazy',
									'fetchpriority' => 0 === $fc_i ? 'high' : 'auto',
									'sizes'         => '(max-width: 1280px) 100vw, 1240px',
								)
							);
							?>
						</picture>
					<?php endif; ?>
					<div class="hero__card">
						<?php if ( $fc_label ) : ?>
							<p class="hero__label"><?php echo esc_html( $fc_label ); ?></p>
						<?php endif; ?>
						<h2 class="hero__title"><?php echo esc_html( get_the_title( $fc_banner ) ); ?></h2>
						<?php if ( $fc_sub ) : ?>
							<p class="hero__text"><?php echo esc_html( $fc_sub ); ?></p>
						<?php endif; ?>
						<?php if ( $fc_btn && $fc_url ) : ?>
							<a class="btn btn--primary" href="<?php echo esc_url( $fc_url ); ?>"><?php echo esc_html( $fc_btn ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( count( $fc_banners ) > 1 ) : ?>
			<div class="hero__controls">
				<button type="button" class="icon-btn hero__prev" aria-label="<?php esc_attr_e( 'Previous slide', 'faridpur-city' ); ?>"><?php echo fc_icon( 'chevron-l' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				<span class="hero__status" aria-live="polite"></span>
				<button type="button" class="icon-btn hero__next" aria-label="<?php esc_attr_e( 'Next slide', 'faridpur-city' ); ?>"><?php echo fc_icon( 'chevron-r' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<div class="hero__fallback">
			<p class="hero__title"><?php bloginfo( 'name' ); ?></p>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="hero__text"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
			<?php get_search_form( array( 'aria_label' => __( 'Search the portal', 'faridpur-city' ), 'class' => 'search-form--large' ) ); ?>
		</div>
	<?php endif; ?>
</section>
