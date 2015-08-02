<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package Mixfolio
 */

global $mixfolio_count;

$mixfolio_count = 1;

get_header(); ?>

	<section id="primary" class="full-width">
		<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php printf( __( 'Category Archives: %s', 'mixfolio' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?>
					</h1><!-- .page-title -->

					<?php
						$category_description = category_description();
						if ( ! empty( $category_description ) )
							echo apply_filters( 'category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>' );
					?>
				</header><!-- .page-header -->

				<ul class="grid">
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							/**
							* Thumbnail Grid
							*/
							get_template_part( 'content', 'grid' );

							$mixfolio_count++;
						?>

					<?php endwhile; ?>
				</ul><!-- .grid -->

				<?php mixfolio_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title">
							<?php _e( 'Nothing Found', 'mixfolio' ); ?>
						</h1><!-- .entry-title -->
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'mixfolio' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_footer(); ?>