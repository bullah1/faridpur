<?php
/**
 * Single news article.
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
		$fc_cat    = fc_first_term( get_the_ID(), 'category' );
		$fc_source = fc_meta( get_the_ID(), 'source' );
		?>
		<article <?php post_class( 'article container' ); ?>>
			<?php fc_breadcrumbs(); ?>
			<header class="article__header content-narrow">
				<p class="meta">
					<?php if ( $fc_cat ) : ?>
						<a class="meta__cat" href="<?php echo esc_url( get_category_link( $fc_cat ) ); ?>"><?php echo esc_html( $fc_cat->name ); ?></a>
					<?php endif; ?>
					<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( fc_post_date() ); ?></time>
					<?php if ( get_the_modified_date( 'Y-m-d' ) !== get_the_date( 'Y-m-d' ) ) : ?>
						<span>
							<?php
							/* translators: %s: date. */
							echo esc_html( sprintf( __( 'Updated %s', 'faridpur-city' ), fc_date( get_post_modified_time( 'U', true ) ) ) );
							?>
						</span>
					<?php endif; ?>
				</p>
				<h1 class="article__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="article__lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="article__figure">
					<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					<?php if ( get_the_post_thumbnail_caption() ) : ?>
						<figcaption><?php the_post_thumbnail_caption(); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endif; ?>
			<div class="prose content-narrow">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
			<?php if ( $fc_source ) : ?>
				<p class="article__source content-narrow">
					<?php
					/* translators: %s: source name. */
					echo esc_html( sprintf( __( 'Source: %s', 'faridpur-city' ), $fc_source ) );
					?>
				</p>
			<?php endif; ?>
		</article>

		<?php
		$fc_related = $fc_cat ? get_posts(
			array(
				'posts_per_page' => 3,
				'cat'            => $fc_cat->term_id,
				'post__not_in'   => array( get_the_ID() ),
				'no_found_rows'  => true,
			)
		) : array();
		if ( $fc_related ) :
			?>
			<section class="container home-section" aria-labelledby="more-news">
				<?php
				fc_section_head(
					array(
						'id'         => 'more-news',
						'title'      => __( 'More news', 'faridpur-city' ),
						'link'       => get_category_link( $fc_cat ),
						'link_label' => $fc_cat->name,
					)
				);
				?>
				<ul class="card-grid card-grid--news" role="list">
					<?php foreach ( $fc_related as $fc_r ) : ?>
						<li><?php get_template_part( 'template-parts/cards/post', null, array( 'post' => $fc_r, 'heading' => 'h3' ) ); ?></li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
