<?php
/**
 * Single business / public service.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

$fc_id      = get_the_ID();
$fc_tax     = 'fc_service' === get_post_type() ? 'fc_service_cat' : 'fc_business_cat';
$fc_cat     = fc_first_term( $fc_id, $fc_tax );
$fc_area    = fc_first_term( $fc_id, 'fc_area' );
$fc_phones  = function_exists( 'fc_phones' ) ? fc_phones( $fc_id ) : array();
$fc_wa      = fc_meta( $fc_id, 'whatsapp' );
$fc_map     = function_exists( 'fc_map_link' ) ? fc_map_link( $fc_id ) : '';
$fc_hours   = function_exists( 'fc_hours' ) ? fc_hours( $fc_id ) : array();
$fc_24      = '1' === (string) fc_meta( $fc_id, '24x7' );
$fc_checked = fc_meta( $fc_id, 'verified' );
$fc_report  = get_theme_mod( 'fc_report_url', '' );
$fc_source  = fc_meta( $fc_id, 'source_url' );
?>
<article class="listing container">
	<?php fc_breadcrumbs(); ?>
	<div class="listing__layout">
		<div class="listing__main">
			<header class="listing__header">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'fc-square', array( 'class' => 'listing__logo', 'loading' => 'eager' ) ); ?>
				<?php else : ?>
					<span class="listing__logo listing__logo--icon" aria-hidden="true"><?php echo $fc_cat ? fc_term_icon( $fc_cat, 36 ) : fc_icon( 'grid', array( 'size' => 36 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
				<div>
					<h1 class="listing__title"><?php the_title(); ?></h1>
					<?php if ( fc_meta( $fc_id, 'name_en' ) ) : ?>
						<p class="listing__alt" lang="en"><?php echo esc_html( fc_meta( $fc_id, 'name_en' ) ); ?></p>
					<?php endif; ?>
					<p class="listing__tags">
						<?php if ( $fc_cat ) : ?>
							<a class="tag" href="<?php echo esc_url( get_term_link( $fc_cat ) ); ?>"><?php echo esc_html( $fc_cat->name ); ?></a>
						<?php endif; ?>
						<?php if ( $fc_area ) : ?>
							<a class="tag tag--plain" href="<?php echo esc_url( get_term_link( $fc_area ) ); ?>"><?php echo esc_html( $fc_area->name ); ?></a>
						<?php endif; ?>
						<?php fc_open_badge( $fc_id ); ?>
					</p>
				</div>
			</header>

			<?php if ( get_the_content() ) : ?>
				<section class="prose" aria-labelledby="about-title">
					<h2 id="about-title" class="block-title"><?php esc_html_e( 'About', 'faridpur-city' ); ?></h2>
					<?php the_content(); ?>
				</section>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/listing/gallery', null, array( 'post_id' => $fc_id ) ); ?>

			<?php if ( $fc_24 || $fc_hours ) : ?>
				<section class="hours-block" aria-labelledby="hours-title">
					<h2 id="hours-title" class="block-title"><?php esc_html_e( 'Opening hours', 'faridpur-city' ); ?></h2>
					<?php if ( $fc_24 ) : ?>
						<p><?php esc_html_e( 'Open 24 hours, every day.', 'faridpur-city' ); ?></p>
					<?php else : ?>
						<table class="hours-table">
							<tbody>
								<?php foreach ( fc_days() as $fc_day => $fc_label ) : ?>
									<?php
									if ( ! isset( $fc_hours[ $fc_day ] ) ) {
										continue;
									}
									$fc_h = $fc_hours[ $fc_day ];
									?>
									<tr>
										<th scope="row"><?php echo esc_html( $fc_label ); ?></th>
										<td><?php echo $fc_h['closed'] ? esc_html__( 'Closed', 'faridpur-city' ) : esc_html( fc_time( $fc_h['open'] ) . ' – ' . fc_time( $fc_h['close'] ) ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</section>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/listing/map', null, array( 'post_id' => $fc_id ) ); ?>

			<footer class="listing__foot">
				<?php if ( $fc_checked ) : ?>
					<span>
						<?php
						/* translators: %s: date. */
						echo esc_html( sprintf( __( 'Information last checked: %s', 'faridpur-city' ), fc_date( $fc_checked ) ) );
						?>
					</span>
				<?php endif; ?>
				<?php if ( $fc_source ) : ?>
					<a href="<?php echo esc_url( $fc_source ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Official source', 'faridpur-city' ); ?></a>
				<?php endif; ?>
				<?php if ( $fc_report ) : ?>
					<a href="<?php echo esc_url( $fc_report ); ?>"><?php esc_html_e( 'Report wrong information', 'faridpur-city' ); ?></a>
				<?php endif; ?>
			</footer>
		</div>

		<aside class="listing__side" aria-label="<?php esc_attr_e( 'Contact', 'faridpur-city' ); ?>">
			<div class="contact-card">
				<?php if ( $fc_phones ) : ?>
					<a class="btn btn--primary btn--block" href="<?php echo esc_url( fc_tel_href( $fc_phones[0] ) ); ?>"><?php echo fc_icon( 'phone', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Call', 'faridpur-city' ); ?> <?php echo esc_html( $fc_phones[0] ); ?></a>
				<?php endif; ?>
				<?php if ( $fc_wa && function_exists( 'fc_whatsapp_href' ) ) : ?>
					<a class="btn btn--ghost btn--block" href="<?php echo esc_url( fc_whatsapp_href( $fc_wa ) ); ?>" target="_blank" rel="noopener"><?php echo fc_icon( 'chat', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>WhatsApp</a>
				<?php endif; ?>
				<dl class="contact-list">
					<?php if ( count( $fc_phones ) > 1 ) : ?>
						<dt><?php esc_html_e( 'Phone', 'faridpur-city' ); ?></dt>
						<dd><?php fc_phone_links( $fc_id ); ?></dd>
					<?php endif; ?>
					<?php if ( fc_meta( $fc_id, 'address' ) ) : ?>
						<dt><?php esc_html_e( 'Address', 'faridpur-city' ); ?></dt>
						<dd><?php echo nl2br( esc_html( fc_meta( $fc_id, 'address' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></dd>
					<?php endif; ?>
					<?php if ( fc_meta( $fc_id, 'email' ) ) : ?>
						<dt><?php esc_html_e( 'Email', 'faridpur-city' ); ?></dt>
						<dd><a href="mailto:<?php echo esc_attr( antispambot( fc_meta( $fc_id, 'email' ) ) ); ?>"><?php echo esc_html( antispambot( fc_meta( $fc_id, 'email' ) ) ); ?></a></dd>
					<?php endif; ?>
					<?php if ( fc_meta( $fc_id, 'website' ) ) : ?>
						<dt><?php esc_html_e( 'Website', 'faridpur-city' ); ?></dt>
						<dd><a href="<?php echo esc_url( fc_meta( $fc_id, 'website' ) ); ?>" rel="noopener" target="_blank"><?php echo esc_html( wp_parse_url( fc_meta( $fc_id, 'website' ), PHP_URL_HOST ) ); ?></a></dd>
					<?php endif; ?>
					<?php if ( fc_meta( $fc_id, 'facebook' ) ) : ?>
						<dt>Facebook</dt>
						<dd><a href="<?php echo esc_url( fc_meta( $fc_id, 'facebook' ) ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Facebook page', 'faridpur-city' ); ?></a></dd>
					<?php endif; ?>
				</dl>
			</div>

			<?php
			$fc_related_args = array(
				'post_type'      => get_post_type(),
				'posts_per_page' => 5,
				'post__not_in'   => array( $fc_id ),
				'orderby'        => 'title',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			);
			$fc_tq = array();
			if ( $fc_cat ) {
				$fc_tq[] = array(
					'taxonomy' => $fc_tax,
					'terms'    => $fc_cat->term_id,
				);
			}
			if ( $fc_area ) {
				$fc_tq[] = array(
					'taxonomy' => 'fc_area',
					'terms'    => $fc_area->term_id,
				);
			}
			$fc_related = array();
			if ( $fc_tq ) {
				$fc_related_args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $fc_tq ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$fc_related                   = get_posts( $fc_related_args );
				if ( ! $fc_related && $fc_cat ) {
					$fc_related_args['tax_query'] = array( $fc_tq[0] ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					$fc_related                   = get_posts( $fc_related_args );
				}
			}
			if ( $fc_related ) :
				?>
				<section class="side-list" aria-labelledby="related-title">
					<h2 id="related-title" class="block-title"><?php esc_html_e( 'Similar nearby', 'faridpur-city' ); ?></h2>
					<ul>
						<?php foreach ( $fc_related as $fc_r ) : ?>
							<?php $fc_ra = fc_first_term( $fc_r->ID, 'fc_area' ); ?>
							<li><a href="<?php echo esc_url( get_permalink( $fc_r ) ); ?>"><?php echo esc_html( get_the_title( $fc_r ) ); ?></a><?php echo $fc_ra ? ' <span>· ' . esc_html( $fc_ra->name ) . '</span>' : ''; ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>
		</aside>
	</div>

	<?php if ( $fc_phones || $fc_map ) : ?>
		<div class="action-bar">
			<?php if ( $fc_phones ) : ?>
				<a class="btn btn--primary" href="<?php echo esc_url( fc_tel_href( $fc_phones[0] ) ); ?>"><?php echo fc_icon( 'phone', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Call', 'faridpur-city' ); ?></a>
			<?php endif; ?>
			<?php if ( $fc_map ) : ?>
				<a class="btn btn--ghost" href="<?php echo esc_url( $fc_map ); ?>" target="_blank" rel="noopener"><?php echo fc_icon( 'directions', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Directions', 'faridpur-city' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</article>
