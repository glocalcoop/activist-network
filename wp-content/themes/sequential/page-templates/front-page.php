<?php
/**
 * Template Name: Front Page
 *
 * @package Sequential
 */

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

	<div class="hero">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="wrapper">
				<?php sequential_post_thumbnail(); ?>

				<div class="entry-written-content">
					<?php
						if ( 1 == get_theme_mod( 'sequential_title_front_page' ) ) {
							the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header>' );
						}
					?>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
					<?php edit_post_link( __( 'Edit', 'sequential' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' ); ?>
				</div><!-- .entry-written-content -->
			</div><!-- .wrapper -->
		</article><!-- .hentry -->
	</div><!-- .hero -->

	<?php endwhile; // end of the loop. ?>

	<div id="primary" class="content-area full-width">
		<div id="content" class="site-content" role="main">

			<?php
				rewind_posts();
				sequential_featured_pages();
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>