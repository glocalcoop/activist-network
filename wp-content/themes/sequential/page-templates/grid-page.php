<?php
/**
 * Template Name: Grid Page
 *
 * @package Sequential
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php if ( '' != $post->post_content ) : // only display if content not empty ?>

				<div class="wrapper">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', 'page' ); ?>

					<?php endwhile; // end of the loop. ?>

				</div><!-- .wrapper -->

			<?php endif; ?>

			<?php
				$sequential_child_pages = new WP_Query( array(
					'post_type'      => 'page',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'post_parent'    => $post->ID,
					'posts_per_page' => 999,
					'no_found_rows'  => true,
				) );
			?>

			<?php if ( $sequential_child_pages->have_posts() ) : ?>

				<div class="grid-area">
					<div class="wrapper clear">

						<?php while ( $sequential_child_pages->have_posts() ) : $sequential_child_pages->the_post(); ?>

							<?php get_template_part( 'content', 'grid' ); ?>

						<?php endwhile; ?>

					</div><!-- .wrapper -->
				</div><!-- .grid-area -->

			<?php
				endif;
				wp_reset_postdata();
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>