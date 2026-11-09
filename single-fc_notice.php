<?php
/**
 * Single notice.
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
		$fc_id     = get_the_ID();
		$fc_issue  = fc_meta( $fc_id, 'issue_date' );
		$fc_office = fc_meta( $fc_id, 'office' );
		$fc_att    = (int) fc_meta( $fc_id, 'pdf' );
		?>
		<article <?php post_class( 'article container' ); ?>>
			<?php fc_breadcrumbs(); ?>
			<header class="article__header content-narrow">
				<p class="meta">
					<?php if ( 'urgent' === fc_meta( $fc_id, 'priority' ) ) : ?>
						<span class="badge badge--urgent"><?php esc_html_e( 'Urgent', 'faridpur-city' ); ?></span>
					<?php endif; ?>
					<time datetime="<?php echo esc_attr( $fc_issue ? $fc_issue : get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( $fc_issue ? fc_date( $fc_issue ) : fc_post_date() ); ?></time>
					<?php if ( $fc_office ) : ?>
						<span><?php echo esc_html( $fc_office ); ?></span>
					<?php endif; ?>
				</p>
				<h1 class="article__title"><?php the_title(); ?></h1>
			</header>
			<div class="prose content-narrow">
				<?php the_content(); ?>
				<?php if ( $fc_att ) : ?>
					<?php
					$fc_url  = wp_get_attachment_url( $fc_att );
					$fc_mime = get_post_mime_type( $fc_att );
					if ( $fc_url && 0 === strpos( (string) $fc_mime, 'image/' ) ) :
						echo wp_get_attachment_image( $fc_att, 'large', false, array( 'loading' => 'lazy' ) );
					endif;
					if ( $fc_url ) :
						?>
						<p><a class="btn btn--ghost" href="<?php echo esc_url( $fc_url ); ?>"><?php echo fc_icon( 'file', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Download attachment', 'faridpur-city' ); ?></a></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
