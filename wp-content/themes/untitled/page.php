<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package untitled
 */

get_header();

	if ( '' != get_the_post_thumbnail() ) : ?>
		<div class="singleimg"><?php the_post_thumbnail( 'slider-img' ); ?></div>
	<?php endif; ?>

	<div id="main" class="site-main">
		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile;
				?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php
get_sidebar();
get_footer();