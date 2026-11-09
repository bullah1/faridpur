<?php
/**
 * Homepage: business directory categories and featured listings.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

if ( ! post_type_exists( 'fc_business' ) ) {
	fc_home_setup_hint( 'directory' );
	return;
}
$fc_count    = fc_mod_int( 'fc_count_directory', 4 );
$fc_featured = get_posts(
	array(
		'post_type'      => 'fc_business',
		'posts_per_page' => $fc_count,
		'meta_key'       => '_fc_featured', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		'orderby'        => 'modified',
		'no_found_rows'  => true,
	)
);
if ( count( $fc_featured ) < $fc_count ) {
	$fc_featured = array_merge(
		$fc_featured,
		get_posts(
			array(
				'post_type'      => 'fc_business',
				'posts_per_page' => $fc_count - count( $fc_featured ),
				'post__not_in'   => wp_list_pluck( $fc_featured, 'ID' ),
				'orderby'        => 'modified',
				'no_found_rows'  => true,
			)
		)
	);
}
$fc_cats = function_exists( 'fc_get_home_terms' ) ? fc_get_home_terms( 'fc_business_cat', 10 ) : array();
if ( ! $fc_featured && ! $fc_cats ) {
	fc_home_setup_hint( 'directory' );
	return;
}
?>
<section class="home-section home-section--tint" aria-labelledby="home-directory">
	<div class="container">
		<?php
		fc_section_head(
			array(
				'id'         => 'home-directory',
				'title'      => fc_mod( 'fc_title_directory', __( 'Business directory', 'faridpur-city' ) ),
				'subtitle'   => fc_mod( 'fc_sub_directory' ),
				'link'       => get_post_type_archive_link( 'fc_business' ),
				'link_label' => __( 'Full directory', 'faridpur-city' ),
			)
		);
		?>
		<?php if ( $fc_cats ) : ?>
			<ul class="chips" role="list" aria-label="<?php esc_attr_e( 'Directory categories', 'faridpur-city' ); ?>">
				<?php foreach ( $fc_cats as $fc_cat ) : ?>
					<li><a class="chip" href="<?php echo esc_url( get_term_link( $fc_cat ) ); ?>"><?php echo fc_term_icon( $fc_cat, 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $fc_cat->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( $fc_featured ) : ?>
			<ul class="card-grid card-grid--listings" role="list">
				<?php foreach ( $fc_featured as $fc_b ) : ?>
					<li><?php get_template_part( 'template-parts/cards/listing', null, array( 'post' => $fc_b, 'heading' => 'h3' ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
