<?php
/**
 * Not found.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main">
	<div class="container content-narrow notfound">
		<p class="notfound__code"><?php echo esc_html( fc_digits( '404' ) ); ?></p>
		<h1 class="article__title"><?php esc_html_e( 'This page could not be found', 'faridpur-city' ); ?></h1>
		<p><?php esc_html_e( 'The link may be old or mistyped. Search the portal or go to the homepage.', 'faridpur-city' ); ?></p>
		<?php get_search_form( array( 'aria_label' => __( 'Search the portal', 'faridpur-city' ), 'class' => 'search-form--large' ) ); ?>
		<p><a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to homepage', 'faridpur-city' ); ?></a></p>
	</div>
</main>
<?php
get_footer();
