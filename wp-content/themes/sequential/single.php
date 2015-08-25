<?php
/**
 * The template for displaying all single posts.
 *
 * @package Sequential
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					if ( 'jetpack-testimonial' === get_post_type() ) {
						get_template_part( 'content', 'testimonial' );
					} else {
						get_template_part( 'content', 'single' );
					}
					?>

				<?php sequential_post_nav(); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( ( comments_open() || '0' != get_comments_number() ) && 'jetpack-testimonial' != get_post_type() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>