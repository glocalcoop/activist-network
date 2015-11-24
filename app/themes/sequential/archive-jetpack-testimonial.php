<?php
/**
 * The template for displaying the Testimonials archive page.
 *
 * @package Sequential
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php $jetpack_options = get_theme_mod( 'jetpack_testimonials' ); ?>

			<div class="wrapper">
				<article class="hentry">
					<header class="entry-header">
						<h1 class="entry-title">
							<?php
								if ( '' != $jetpack_options['page-title'] ) {
									echo esc_html( $jetpack_options['page-title'] );
								} else {
									_e( 'Testimonials', 'sequential' );
								}
							?>
						</h1><!-- .entry-title -->
					</header><!-- .entry-header -->

					<?php if ( isset( $jetpack_options['page-content'] ) && '' != $jetpack_options['page-content'] ) : // only display if content not empty ?>
					<div class="entry-content">
						<?php echo convert_chars( convert_smilies( wptexturize( stripslashes( wp_filter_post_kses( addslashes( $jetpack_options['page-content'] ) ) ) ) ) ); ?>
					</div><!-- .entry-content -->
					<?php endif; ?>
				</article><!-- .hentry -->
			</div><!-- .wrapper -->

			<?php if ( have_posts() ) : ?>

				<div class="grid-area">
					<div class="wrapper clear">

						<?php while ( have_posts() ) : the_post(); ?>

							<?php get_template_part( 'content', 'testimonial' ); ?>

						<?php endwhile; ?>

					</div><!-- .wrapper -->
				</div><!-- .grid-area -->

			<?php
				sequential_paging_nav();
				endif;
				wp_reset_postdata();
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>