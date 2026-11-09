<?php
/**
 * Single event.
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
		$fc_start  = fc_meta( $fc_id, 'start' );
		$fc_end    = fc_meta( $fc_id, 'end' );
		$fc_place  = (int) fc_meta( $fc_id, 'venue_place' );
		$fc_venue  = $fc_place ? get_the_title( $fc_place ) : fc_meta( $fc_id, 'venue_text' );
		$fc_addr   = $fc_place ? fc_meta( $fc_place, 'address' ) : fc_meta( $fc_id, 'address' );
		$fc_status = fc_meta( $fc_id, 'status', 'scheduled' );
		$fc_past   = function_exists( 'fc_event_is_past' ) && fc_event_is_past( $fc_id );
		$fc_reg    = fc_meta( $fc_id, 'reg_url' );
		?>
		<article <?php post_class( 'article container' ); ?>>
			<?php fc_breadcrumbs(); ?>
			<header class="article__header content-narrow">
				<p class="meta">
					<?php if ( 'cancelled' === $fc_status ) : ?>
						<span class="badge badge--closed"><?php esc_html_e( 'Cancelled', 'faridpur-city' ); ?></span>
					<?php elseif ( 'postponed' === $fc_status ) : ?>
						<span class="badge badge--urgent"><?php esc_html_e( 'Postponed', 'faridpur-city' ); ?></span>
					<?php elseif ( $fc_past ) : ?>
						<span class="badge"><?php esc_html_e( 'This event has ended', 'faridpur-city' ); ?></span>
					<?php endif; ?>
				</p>
				<h1 class="article__title"><?php the_title(); ?></h1>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="article__figure"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?></figure>
			<?php endif; ?>
			<div class="content-narrow">
				<dl class="facts">
					<?php if ( $fc_start ) : ?>
						<dt><?php echo fc_icon( 'calendar', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'When', 'faridpur-city' ); ?></dt>
						<dd>
							<time datetime="<?php echo esc_attr( $fc_start ); ?>"><?php echo esc_html( fc_date( $fc_start, 'l, j F Y' ) . ', ' . fc_time( substr( $fc_start, 11, 5 ) ) ); ?></time>
							<?php if ( $fc_end ) : ?>
								– <time datetime="<?php echo esc_attr( $fc_end ); ?>"><?php echo esc_html( substr( $fc_end, 0, 10 ) === substr( $fc_start, 0, 10 ) ? fc_time( substr( $fc_end, 11, 5 ) ) : fc_date( $fc_end, 'j F Y' ) . ', ' . fc_time( substr( $fc_end, 11, 5 ) ) ); ?></time>
							<?php endif; ?>
						</dd>
					<?php endif; ?>
					<?php if ( $fc_venue ) : ?>
						<dt><?php echo fc_icon( 'map-pin', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Where', 'faridpur-city' ); ?></dt>
						<dd>
							<?php if ( $fc_place ) : ?>
								<a href="<?php echo esc_url( get_permalink( $fc_place ) ); ?>"><?php echo esc_html( $fc_venue ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $fc_venue ); ?>
							<?php endif; ?>
							<?php if ( $fc_addr ) : ?>
								<br><?php echo esc_html( $fc_addr ); ?>
							<?php endif; ?>
						</dd>
					<?php endif; ?>
					<?php if ( fc_meta( $fc_id, 'organizer' ) ) : ?>
						<dt><?php echo fc_icon( 'people', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Organiser', 'faridpur-city' ); ?></dt>
						<dd><?php echo esc_html( fc_meta( $fc_id, 'organizer' ) ); ?></dd>
					<?php endif; ?>
				</dl>
				<?php if ( $fc_reg && ! $fc_past && 'cancelled' !== $fc_status ) : ?>
					<p><a class="btn btn--primary" href="<?php echo esc_url( $fc_reg ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Register / tickets', 'faridpur-city' ); ?></a></p>
				<?php endif; ?>
				<div class="prose"><?php the_content(); ?></div>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
