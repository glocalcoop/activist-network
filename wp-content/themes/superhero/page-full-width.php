<?php
/**
 * Template Name: Full-Width Page
 *
 * The template for displaying full-width pages.
 *
 * @package Superhero
 * @since Superhero 1.0
 */

get_header(); ?>

<div id="primary" class="content-area full-width-page">
	<div id="content" class="site-content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_footer(); ?>