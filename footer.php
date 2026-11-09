<?php
/**
 * Site footer.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_about   = get_theme_mod( 'fc_footer_about', '' );
$fc_address = get_theme_mod( 'fc_footer_address', '' );
$fc_phone   = get_theme_mod( 'fc_footer_phone', '' );
$fc_email   = get_theme_mod( 'fc_footer_email', '' );
?>
<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__about">
			<p class="site-footer__name"><?php bloginfo( 'name' ); ?></p>
			<?php if ( $fc_about ) : ?>
				<p><?php echo esc_html( $fc_about ); ?></p>
			<?php endif; ?>
			<?php if ( $fc_address || $fc_phone || $fc_email ) : ?>
				<address class="site-footer__contact">
					<?php if ( $fc_address ) : ?>
						<span><?php echo nl2br( esc_html( $fc_address ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
					<?php if ( $fc_phone && function_exists( 'fc_tel_href' ) ) : ?>
						<a href="<?php echo esc_url( fc_tel_href( $fc_phone ) ); ?>"><?php echo esc_html( $fc_phone ); ?></a>
					<?php elseif ( $fc_phone ) : ?>
						<span><?php echo esc_html( $fc_phone ); ?></span>
					<?php endif; ?>
					<?php if ( $fc_email ) : ?>
						<a href="mailto:<?php echo esc_attr( antispambot( $fc_email ) ); ?>"><?php echo esc_html( antispambot( $fc_email ) ); ?></a>
					<?php endif; ?>
				</address>
			<?php endif; ?>
		</div>

		<?php foreach ( array( 'footer_1', 'footer_2' ) as $fc_loc ) : ?>
			<?php if ( has_nav_menu( $fc_loc ) ) : ?>
				<?php $fc_locs = get_nav_menu_locations(); $fc_menu = wp_get_nav_menu_object( $fc_locs[ $fc_loc ] ); ?>
				<nav class="site-footer__col" aria-label="<?php echo esc_attr( $fc_menu ? $fc_menu->name : $fc_loc ); ?>">
					<?php if ( $fc_menu ) : ?>
						<p class="site-footer__heading"><?php echo esc_html( $fc_menu->name ); ?></p>
					<?php endif; ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => $fc_loc,
							'container'      => false,
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php if ( has_nav_menu( 'social' ) ) : ?>
			<nav class="site-footer__col" aria-label="<?php esc_attr_e( 'Social links', 'faridpur-city' ); ?>">
				<p class="site-footer__heading"><?php esc_html_e( 'Follow us', 'faridpur-city' ); ?></p>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'social',
						'container'      => false,
						'menu_class'     => 'social-menu',
						'depth'          => 1,
						'link_before'    => '',
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</div>
	<div class="site-footer__bottom">
		<div class="container">
			<?php
			$fc_copy = get_theme_mod( 'fc_footer_copyright', '' );
			echo esc_html( $fc_copy ? $fc_copy : '© ' . fc_digits( gmdate( 'Y' ) ) . ' ' . get_bloginfo( 'name' ) );
			?>
		</div>
	</div>
</footer>

<?php if ( has_nav_menu( 'mobile_bar' ) ) : ?>
	<nav class="mobile-bar" aria-label="<?php esc_attr_e( 'Quick links', 'faridpur-city' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'mobile_bar',
				'container'      => false,
				'menu_class'     => 'mobile-bar__menu',
				'depth'          => 1,
			)
		);
		?>
	</nav>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
