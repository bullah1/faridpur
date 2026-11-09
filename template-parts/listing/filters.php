<?php
/**
 * Area and 24-hour filters for directory and service archives.
 * Filtered URLs are noindex and canonicalise to the clean archive (see plugin seo.php).
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

if ( ! taxonomy_exists( 'fc_area' ) ) {
	return;
}
$fc_areas = get_terms(
	array(
		'taxonomy'   => 'fc_area',
		'hide_empty' => true,
	)
);
$fc_action = function_exists( 'fc_canonical_url' ) ? preg_replace( '#/page/\d+/?$#', '/', fc_canonical_url() ) : '';
$fc_cur    = sanitize_title( (string) get_query_var( 'area' ) );
$fc_24     = '1' === (string) get_query_var( 'fc24' );
?>
<aside class="filters" aria-labelledby="filters-title">
	<h2 class="filters__title" id="filters-title"><?php esc_html_e( 'Filter', 'faridpur-city' ); ?></h2>
	<form method="get" action="<?php echo esc_url( $fc_action ); ?>" class="filters__form">
		<?php if ( $fc_areas && ! is_wp_error( $fc_areas ) ) : ?>
			<div class="field">
				<label for="filter-area"><?php esc_html_e( 'Area', 'faridpur-city' ); ?></label>
				<select id="filter-area" name="area">
					<option value=""><?php esc_html_e( 'All areas', 'faridpur-city' ); ?></option>
					<?php foreach ( $fc_areas as $fc_a ) : ?>
						<option value="<?php echo esc_attr( $fc_a->slug ); ?>" <?php selected( $fc_cur, $fc_a->slug ); ?>><?php echo esc_html( $fc_a->parent ? '— ' . $fc_a->name : $fc_a->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>
		<div class="field field--check">
			<input type="checkbox" id="filter-24" name="fc24" value="1" <?php checked( $fc_24 ); ?>>
			<label for="filter-24"><?php esc_html_e( 'Open 24 hours', 'faridpur-city' ); ?></label>
		</div>
		<div class="filters__actions">
			<button type="submit" class="btn btn--primary btn--sm"><?php esc_html_e( 'Apply', 'faridpur-city' ); ?></button>
			<?php if ( $fc_cur || $fc_24 ) : ?>
				<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( $fc_action ); ?>"><?php esc_html_e( 'Clear', 'faridpur-city' ); ?></a>
			<?php endif; ?>
		</div>
	</form>
	<?php
	if ( is_tax() ) :
		$fc_term     = get_queried_object();
		$fc_children = get_terms(
			array(
				'taxonomy'   => $fc_term->taxonomy,
				'parent'     => $fc_term->term_id,
				'hide_empty' => true,
			)
		);
		if ( $fc_children && ! is_wp_error( $fc_children ) ) :
			?>
			<h2 class="filters__title"><?php esc_html_e( 'Subcategories', 'faridpur-city' ); ?></h2>
			<ul class="filters__links">
				<?php foreach ( $fc_children as $fc_c ) : ?>
					<li><a href="<?php echo esc_url( get_term_link( $fc_c ) ); ?>"><?php echo esc_html( $fc_c->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php
		endif;
	endif;
	?>
</aside>
