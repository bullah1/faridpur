<?php
/**
 * Site header.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0F6B37">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'faridpur-city' ); ?></a>

<?php if ( get_theme_mod( 'fc_topbar', true ) ) : ?>
<aside class="topbar" aria-label="<?php esc_attr_e( 'Date and quick links', 'faridpur-city' ); ?>">
	<div class="container topbar__inner">
		<span class="topbar__date"><?php echo esc_html( fc_date( time(), 'l, j F Y' ) ); ?></span>
		<div class="topbar__links">
			<?php
			$fc_tb_url = get_theme_mod( 'fc_topbar_link_url', '' );
			if ( $fc_tb_url ) :
				?>
				<a class="topbar__link" href="<?php echo esc_url( $fc_tb_url ); ?>"><?php echo fc_icon( 'phone', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( fc_mod( 'fc_topbar_link_label', __( 'Emergency numbers', 'faridpur-city' ) ) ); ?></a>
			<?php endif; ?>
			<?php
			$fc_alt_url = get_theme_mod( 'fc_alt_lang_url', '' );
			if ( $fc_alt_url ) :
				?>
				<a class="topbar__lang" href="<?php echo esc_url( $fc_alt_url ); ?>" hreflang="<?php echo 'bn' === fc_theme_ui_lang() ? 'en' : 'bn'; ?>"><?php echo esc_html( fc_mod( 'fc_alt_lang_label', 'bn' === fc_theme_ui_lang() ? 'EN' : 'বাংলা' ) ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</aside>
<?php endif; ?>

<header class="site-header">
	<div class="container site-header__inner">
		<div class="brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php endif; ?>
			<a class="brand__text" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="brand__name"><?php bloginfo( 'name' ); ?></span>
				<?php if ( get_bloginfo( 'description' ) ) : ?>
					<span class="brand__tagline"><?php bloginfo( 'description' ); ?></span>
				<?php endif; ?>
			</a>
		</div>

		<nav class="primary-nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Main menu', 'faridpur-city' ); ?>">
			<div class="primary-nav__head">
				<span class="primary-nav__title"><?php esc_html_e( 'Menu', 'faridpur-city' ); ?></span>
				<button type="button" class="icon-btn nav-close" aria-label="<?php esc_attr_e( 'Close menu', 'faridpur-city' ); ?>"><?php echo fc_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
			</div>
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'menu',
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
			} elseif ( current_user_can( 'edit_theme_options' ) ) {
				echo '<p class="menu-hint"><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Assign a menu to “Primary menu”.', 'faridpur-city' ) . '</a></p>';
			}
			?>
		</nav>

		<div class="site-header__actions">
			<?php get_search_form( array( 'aria_label' => __( 'Site search', 'faridpur-city' ) ) ); ?>
			<button type="button" class="icon-btn nav-toggle" aria-controls="primary-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'faridpur-city' ); ?>"><?php echo fc_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		</div>
	</div>
</header>
<div class="nav-backdrop" hidden></div>
