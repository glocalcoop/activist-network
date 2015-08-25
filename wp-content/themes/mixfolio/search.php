<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Mixfolio
 */

get_header(); ?>

		<section id="primary">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php printf( __( 'Search Results for: %s', 'mixfolio' ), '<span>' . get_search_query() . '</span>' ); ?>
					</h1><!-- .page-title -->
				</header><!-- .page-header -->

				<?php mixfolio_content_nav( 'nav-above' ); ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'search' ); ?>

				<?php endwhile; ?>

				<?php mixfolio_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title">
							<?php _e( 'Nothing Found', 'mixfolio' ); ?>
						</h1><!-- .entry-title -->
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p>
							<?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'mixfolio' ); ?>
						</p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>