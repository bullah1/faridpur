<?php
/**
 * Search form.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_id    = wp_unique_id( 'search-' );
$fc_label = ! empty( $args['aria_label'] ) ? $args['aria_label'] : __( 'Search', 'faridpur-city' );
$fc_class = ! empty( $args['class'] ) ? $args['class'] : '';
?>
<form role="search" method="get" class="search-form <?php echo esc_attr( $fc_class ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $fc_label ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $fc_id ); ?>"><?php esc_html_e( 'Search for', 'faridpur-city' ); ?></label>
	<?php echo fc_icon( 'search', array( 'size' => 18, 'class' => 'search-form__icon' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<input type="search" id="<?php echo esc_attr( $fc_id ); ?>" class="search-form__input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Services, organisations, news…', 'faridpur-city' ); ?>">
	<button type="submit" class="search-form__submit"><?php esc_html_e( 'Search', 'faridpur-city' ); ?></button>
</form>
