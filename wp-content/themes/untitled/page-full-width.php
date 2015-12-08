<?php
/**
 * Template Name: Full-Width Page
 *
 * The template for displaying full-width pages.
 *
 * @package untitled
 */

get_header();

	if ( '' != get_the_post_thumbnail() ) : ?>
		<div class="singleimg"><?php the_post_thumbnail( 'slider-img' ); ?></div>
	<?php endif; ?>

	<div id="main" class="site-main">
		<div id="primary" class="content-area full-width-page">
			<div id="content" class="site-content" role="main">

				<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile;
				?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php
get_footer();