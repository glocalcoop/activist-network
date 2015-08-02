<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Sequential
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="wrapper">

				<section class="error-404 not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'sequential' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="page-content">
						<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'sequential' ); ?></p>

						<?php get_search_form(); ?>

						<div class="error-404-widgets">
							<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

							<?php
								/* translators: %1$s: smiley */
								$sequential_archive_content = '<p>' . sprintf( __( 'Try looking in the monthly archives. %1$s', 'sequential' ), convert_smilies( ':)' ) ) . '</p>';
								the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$sequential_archive_content" );
							?>

							<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
						</div><!-- .error-404-widgets -->

					</div><!-- .page-content -->
				</section><!-- .error-404 -->

			</div><!-- .wrapper -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>