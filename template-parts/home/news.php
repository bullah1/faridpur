<?php
/**
 * Homepage: featured and latest news.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_news = function_exists( 'fc_get_home_news' ) ? fc_get_home_news( fc_mod_int( 'fc_count_news', 5 ) ) : get_posts( array( 'posts_per_page' => 5 ) );
if ( ! $fc_news ) {
	fc_home_setup_hint( 'news' );
	return;
}
$fc_lead = array_shift( $fc_news );
$fc_page = (int) get_option( 'page_for_posts' );
?>
<section class="home-section container" aria-labelledby="home-news">
	<?php
	fc_section_head(
		array(
			'id'         => 'home-news',
			'title'      => fc_mod( 'fc_title_news', __( 'Latest news', 'faridpur-city' ) ),
			'link'       => $fc_page ? get_permalink( $fc_page ) : '',
			'link_label' => __( 'All news', 'faridpur-city' ),
		)
	);
	?>
	<div class="news-layout">
		<?php
		get_template_part(
			'template-parts/cards/post',
			null,
			array(
				'post'    => $fc_lead,
				'variant' => 'lead',
			)
		);
		?>
		<?php if ( $fc_news ) : ?>
			<ul class="news-list" role="list">
				<?php foreach ( $fc_news as $fc_p ) : ?>
					<li>
						<?php
						get_template_part(
							'template-parts/cards/post',
							null,
							array(
								'post'    => $fc_p,
								'variant' => 'row',
							)
						);
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
