<?php
/**
 * The Template for displaying all single posts.
 *
 * @package untitled
 */

$mini_query = new WP_Query( array(
	'posts_per_page' => 18,
	'post__not_in'	 =>	array( get_the_ID() ),
	'meta_query'     => array( array( 'key' => '_thumbnail_id' ) ),
) );


get_header();

if ( '' != get_the_post_thumbnail() ) : ?>
	<div class="singleimg"><?php the_post_thumbnail( 'slider-img' ); ?></div>
	<div class="minislides">
		<div class="carousel">
			<ul class="slides">
				<?php
					while ( $mini_query->have_posts() ) :
						$mini_query->the_post();
				?>
				<li>
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'thumbnail-img' ); ?></a>
				</li>
				<?php
					endwhile;

					// Reset the post data
					wp_reset_postdata();
				?>
			</ul>
		</div>
	</div>

<?php endif; ?>

	<div id="single-main" class="site-main">
		<div id="single-primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<?php
					while ( have_posts() ) :
						the_post();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<div class="entry-meta">
							<?php untitled_posted_on(); ?>
						</div><!-- .entry-meta -->
						<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php
							the_content();
							wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'untitled' ),
								'after'  => '</div>',
							) );
						?>
					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<?php
							/* translators: used between list items, there is a space after the comma */
							$category_list = get_the_category_list( __( ', ', 'untitled' ) );

							/* translators: used between list items, there is a space after the comma */
							$tag_list = get_the_tag_list( '', __( ', ', 'untitled' ) );

							if ( ! untitled_categorized_blog() ) :
								// This blog only has 1 category so we just need to worry about tags in the meta text
								if ( '' != $tag_list ) :
									$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'untitled' );
								else :
									$meta_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'untitled' );
								endif;

							else :
								// But this blog has loads of categories so we should probably display them here
								if ( '' != $tag_list ) :
									$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'untitled' );
								else :
									$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'untitled' );
								endif;

							endif; // end check for categories on this blog

							printf(
								$meta_text,
								$category_list,
								$tag_list,
								get_permalink(),
								the_title_attribute( 'echo=0' )
							);

							edit_post_link( __( 'Edit', 'untitled' ), '<span class="edit-link">', '</span>' );
						?>
					</footer><!-- .entry-meta -->
				</article><!-- #post-## -->

				<?php
						untitled_content_nav( 'nav-below' );

						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() ) :
							comments_template();
						endif;
					endwhile;
				?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
