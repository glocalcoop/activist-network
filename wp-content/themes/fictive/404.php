<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Fictive
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found hentry">
				<header class="page-header">
					<h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'fictive' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'fictive' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>