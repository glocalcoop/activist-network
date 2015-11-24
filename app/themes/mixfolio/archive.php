<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
						<?php
							if ( is_category() ) {
								printf( __( 'Category Archives: %s', 'mixfolio' ), '<span>' . single_cat_title( '', false ) . '</span>' );

							} elseif ( is_tag() ) {
								printf( __( 'Tag Archives: %s', 'mixfolio' ), '<span>' . single_tag_title( '', false ) . '</span>' );

							} elseif ( is_author() ) {
								/* Queue the first post, that way we know
								 * what author we're dealing with (if that is the case).
								*/
								the_post();
								printf( __( 'Author Archives: %s', 'mixfolio' ), '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
								/* Since we called the_post() above, we need to
								 * rewind the loop back to the beginning that way
								 * we can run the loop properly, in full.
								 */
								rewind_posts();

							} elseif ( is_day() ) {
								printf( __( 'Daily Archives: %s', 'mixfolio' ), '<span>' . get_the_date() . '</span>' );

							} elseif ( is_month() ) {
								printf( __( 'Monthly Archives: %s', 'mixfolio' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

							} elseif ( is_year() ) {
								printf( __( 'Yearly Archives: %s', 'mixfolio' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

							} else {
								_e( 'Archives', 'mixfolio' );

							}
						?>
					</h1><!-- .page-title -->
				</header><!-- .page-header -->

				<?php rewind_posts(); ?>

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
						<p>
							<?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'mixfolio' ); ?>
						</p>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_footer(); ?>